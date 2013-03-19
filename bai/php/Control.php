<?php
/**
 * <h2>BaiPHP（简单PHP）开发框架</h2>
 * @link      http://www.baiphp.com
 * @copyright Copyright (c) 2011 - 2012, 白晓阳
 * @author    白晓阳
 * @version   1.0.0 2012/03/31 首版
 *            2.0.0 2012/07/01 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 * <p>欢迎提供捐助。任何捐助者自动获得仅限于捐助者自身的商业使用（不包括再发行和再授权）授权。</p>
 */

/**
 * <h2>BaiPHP（简单PHP）开发框架</h2>
 * <h3>调度流程</h3>
 * <p>
 * 对目标进行登记、过滤、识别，并对内委托。
 * </p>
 * @author 白晓阳
 */
class Control extends Flow
{
	/**
	 * <h4>访问登记</h4>
	 * <p>
	 * 记录访问来源、访问目标和实际响应。
	 * </p>
	 * @return void
	 */
	protected function checkin()
	{
		### 访问来源
		$client = array(
		$_SERVER['REMOTE_HOST'],               ### 客户主机名
		$_SERVER['REMOTE_ADDR'],               ### 客户IP地址
		$_SERVER['REMOTE_PORT'],               ### 客户端口
		$this->pick('HTTP_REFERER', $_SERVER), ### 访问起始页（空为URL直连）
		$_SERVER['HTTP_USER_AGENT'],           ### 客户环境
		);
		$this->target->client = Log::logf('client', $client, __CLASS__);
		### 访问目标
		$server = array(
		$_SERVER['SERVER_NAME'],     ### 服务器主机名
		$_SERVER['SERVER_ADDR'],     ### 服务器IP地址
		$_SERVER['SERVER_PORT'],     ### 服务器端口
		$_SERVER['REQUEST_METHOD'],  ### 访问方式
		$_SERVER['REQUEST_URI'],     ### 访问地址
		$_SERVER['SERVER_SOFTWARE'], ### 服务器环境
		);
		$this->target->server = Log::logf('server', $server, __CLASS__);
		### 响应文件
		$script = array(
		$_SERVER['SCRIPT_FILENAME'], ### 响应文件
		$_SERVER['QUERY_STRING'],    ### 访问参数
		);
		$this->target->script = Log::logf('script', $script, __CLASS__);
		return true;
	}

	/**
	 * <h4>访客识别</h4>
	 * <p>
	 * 用户及权限识别。
	 * </p>
	 * @return void
	 */
	protected function identify()
	{
		$preset = $this->pick(__FUNCTION__, $this->preset);
		if ($preset == null || ! is_array($preset))
		{
			return true;
		}
		return true;
	}
}
?>
