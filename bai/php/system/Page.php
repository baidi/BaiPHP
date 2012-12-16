<?php
/**
 * <b>BaiPHP（简单PHP）开发框架</b>
 * @author		白晓阳
 * @copyright	Copyright (c) 2011 - 2012, 白晓阳
 * @link		http://dacbe.com
 * @version     V1.0.0 2012/03/31 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 */

/**
 * <b>BaiPHP（简单PHP）开发框架</b><br/>
 * <b>页面输出流程：</b>
 * <p>
 * 处理页面内容并输出最终页面
 * ·format：页面内容处理与缓存方法，如果需要对页面内容做特殊处理，应重写该方法。
 * ·assign：页面内容输出方法，无法重写该方法。
 * </p>
 * @author 白晓阳
 * @see Flow
 */
class Page extends Flow
{
	/**
	 * <b>处理页面内容并输出最终页面</b><br/>
	 *
	 * @param string $event 事件
	 *
	 * @return void
	 */
	final public function entrust(Event $event = null)
	{
		$page = cFlow($event, __CLASS__);
		Log::logf(__CLASS__, substr($page, strlen(_LOCAL)), 'Event');

		### 清理缓存
		if (! _DEBUG) {
			ob_clean();
		}
		### 输出页面
		ob_start();
		include($page);
		$this->content = ob_get_clean();
		$this->content = $this->format($event, $this->content);
		$this->rside = '&nbsp;';
		$layout = c('Layout', _DEFAULT);
		include(_LOCAL._PAGE.$layout);
	}

	/**
	 * 页面内容处理与缓存
	 * @param string $event 事件
	 * @param string $page 页面内容
	 */
	protected function format($event, $page)
	{
		### 页面压缩
		#$search = array(
		#'/^\s+/m',       #行首空白
		#'/^<!--.*-->$/', #页面注释
		#);
		#$page = preg_replace($search, '', $page);

		### 页面缓存
		if (defined('_CACHE'))
		{
			$cache = Cache::access();
			$cache->entrust($event, $page);
		}
		return $page;
	}
	
	protected function js()
	{
		$js = file_get_contents(_LOCAL.'bai/js/bai.js');
		$js = str_replace('$config$', json_encode(c('JS')), $js);
		$this->js = $js;
	}
	
	protected function css()
	{
		$css = file_get_contents(_LOCAL.'bai/css/bai.css');
		foreach (c('CSS') as $item => $value)
		{
			$css = str_replace($item, $value, $css);
		}
		$this->css = $css;
	}
}
?>
