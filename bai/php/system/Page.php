<?php

### 权限检查
if (! defined('_ISSUE'))
	exit('对不起！请走正门……');

/**
 * <b>Bai页面输出流程：</b>
 * <p>格式化信息并输出最终页面</p>
 *
 * @author    白晓阳
 * @copyright Copyright (c) 2011 - 2012, 白晓阳
 * @link       http://www.dacbe.com
 * @version    V1.0.0 2012/03/21 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 */
class Page extends Issue
{
	/**
	 * 格式化信息并输出最终页面
	 * @param 请求事件： $event
	 */
	public function assign($event)
	{
		$page = cRequest($event, __CLASS__);
		$message = $this->log->messagef(__CLASS__, $page);
		$this->log->assign($message, Log::L_DEBUG);

		//ob_clean();
		ob_start();
		include($page);
		$page = ob_get_clean();
		$page = $this->format($event, $page);
		print($page);
	}

	/**
	 * 页面格式化与缓存处理
	 * @param 请求事件： $event
	 * @param 页面内容： $page
	 */
	protected function format($event, $page)
	{
		#$search = array(
				#'/^\s+/m',       #行首空白
				#'/^<!--.*-->$/', #页面注释
		#);
		#$page = preg_replace($search, '', $page);

		if (defined('_CACHE'))
		{
			$cache = Cache::access();
			$cache->assign($event, $page);
		}
		return $page;
	}
}
?>
