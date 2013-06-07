<?php
/**
 * <h2>化简PHP（BaiPHP）开发框架</h2>
 * @link      http://www.baiphp.net
 * @copyright Copyright (c) 2011 - 2012, 白晓阳
 * @author    白晓阳
 * @version   1.0.0 2012/03/31 首版
 *            2.0.0 2012/07/01 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 * <p>欢迎提供捐助。任何捐助者自动获得仅限于捐助者自身的商业使用（不包括再发行和再授权）授权。</p>
 */

/**
 * <h2>化简PHP（BaiPHP）开发框架</h2>
 * <h3>调度流程</h3>
 * <p>
 * 对目标进行登记、过滤、识别，并对内委托。
 * </p>
 * @author 白晓阳
 */
class Control extends Flow
{
	/** 访问地址过滤 */
	protected $filters    = null;
	/** 每秒最大访问频度 */
	protected $visitLimit = 10;
	/** 访问次数键值（用于$_SESSION） */
	protected $visitCount = '_visit_count';
	/** 访问时间键值（用于$_SESSION） */
	protected $visitTime  = '_visit_time';

	/**
	 * <h4>访问登记</h4>
	 * <p>
	 * 记录访问来源、访问目标和实际响应。
	 * </p>
	 * @return boolean
	 */
	protected function checkin()
	{
		### 访问来源
		$client = array(
			### 客户主机名
			$_SERVER['REMOTE_HOST'],
			### 客户IP地址
			$_SERVER['REMOTE_ADDR'],
			### 客户端口
			$_SERVER['REMOTE_PORT'],
			### 访问起始页（空为URL直连）
			$this->pick('HTTP_REFERER', $_SERVER),
			### 客户环境
			$_SERVER['HTTP_USER_AGENT'],
		);
		Log::logf('client', $client, __CLASS__);
			### 访问目标
			$server = array(
			### 服务器主机名
			$_SERVER['SERVER_NAME'],
			### 服务器IP地址
			$_SERVER['SERVER_ADDR'],
			### 服务器端口
			$_SERVER['SERVER_PORT'],
			### 访问方式
			$_SERVER['REQUEST_METHOD'],
			### 访问地址
			$_SERVER['REQUEST_URI'],
			### 服务器环境
			$_SERVER['SERVER_SOFTWARE'],
		);
		Log::logf('server', $server, __CLASS__);
		### 响应文件
		$script = array(
			### 响应文件
			$_SERVER['SCRIPT_FILENAME'],
			### 访问参数
			$_SERVER['QUERY_STRING'],
		);
		Log::logf('script', $script, __CLASS__);
		return true;
	}

	/**
	 * <h4>访问地址过滤</h4>
	 * @return boolean
	 */
	protected function filter()
	{
		if ($this->filters == null || ! is_array($this->filters)) {
			return true;
		}
		### 访问地址过滤
		$result = true;
		foreach ($this->filters as $item => $mode) {
			if (preg_match($item, $_SERVER['REMOTE_ADDR'])) {
				$result = $mode;
			}
		}
		if (! $result) {
			$this->notice = Log::logs(__FUNCTION__, __CLASS__, Log::ERROR);
		}
		return $result;
	}

	/**
	 * <h4>访问频度限制</h4>
	 * <p>利用会话实现简单的访问频度限制。</p>
	 * @return boolean
	 */
	protected function limit()
	{
		if ($this->visitLimit <= 0) {
			return true;
		}
		### 当前访问次数
		$count = (int)$this->pick($this->visitCount, $_SESSION);
		if ($count >= $this->visitLimit) {
			$this->notice = Log::logs(__FUNCTION__, __CLASS__, Log::ERROR);
			return false;
		}
		### 访问时间
		$time  = $this->pick($this->visitTime,  $_SESSION);
		if ($time != $_SERVER['REQUEST_TIME']) {
			### 重新统计
			$_SESSION[$this->visitTime]  = $_SERVER['REQUEST_TIME'];
			$_SESSION[$this->visitCount] = 1;
			return true;
		}
		### 更新访问次数
		$_SESSION[$this->visitCount] = $count + 1;
		return true;
	}
}
?>
