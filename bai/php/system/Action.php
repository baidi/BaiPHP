<?php

### 权限检查
if (! defined('_ISSUE'))
	exit('对不起！请走正门……');

/**
 * <b>Bai事件处理流程：</b>
 * <p>检验输入、访问数据并分派至相应页面</p>
 *
 * @author    白晓阳
 * @copyright Copyright (c) 2011 - 2012, 白晓阳
 * @link       http://www.dacbe.com
 * @version    V1.0.0 2012/03/21 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 */
class Action extends Issue
{
	/**
	 * 检验输入、访问数据库并分派相应页面
	 * @param 请求事件： $event
	 */
	public function assign($event)
	{
		$message = $this->log->message(__CLASS__);
		$this->log->assign($message, Log::L_DEBUG);

		if ($this->cache($event))
			return;
		if (! $this->check($event))
			$this->data($event);
		$this->page($event);
	}

	/**
	 * 提取缓存页面
	 * @param 请求事件： $event
	 */
	protected function cache($event)
	{
		if (! defined('_CACHE'))
			return false;
		$cache = Cache::access();
		return $cache->assign($event);
	}

	/**
	 * 检验输入内容
	 * @param 请求事件： $event
	 */
	protected function check($event)
	{
		if (isset($_REQUEST[__FUNCTION__]))
			return false;
		$check = Check::access();
		$message = $check->assign($event);
		if ($message)
			$_REQUEST['error'] = $message;
		return $message;
	}

	/**
	 * 读取数据
	 * @param 请求事件： $event
	 */
	protected function data($event)
	{
		return;
	}

	/**
	 * 分派相应页面
	 * @param 请求事件： $event
	 */
	protected function page($event)
	{
		$page = cRequest($event, __CLASS__);
		$page = new $page();
		$page->assign($event);
	}
}
?>
