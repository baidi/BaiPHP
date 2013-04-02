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
 * <h3>页面流程</h3>
 * <p>
 * 处理页面内容并输出最终页面
 * ·format：页面内容处理与缓存方法，如果需要对页面内容做特殊处理，应重写该方法。
 * ·assign：页面内容输出方法，无法重写该方法。
 * </p>
 * @author 白晓阳
 */
class Page extends Flow
{

	/**
	 * <h4>生成页面HTML</h4>
	 * @return void
	 */
	protected function html()
	{
		$this->css = Style::css($this->pick('css', $this->preset), true);
		$this->js = Style::js($this->pick('js', $this->preset), true);
		$this->lang = Lang::access();
		$this->lside = false;
		$this->rside = false;
		return $this->load('_layout.php');
	}

	/**
	 * <h4>页面内容处理与缓存</h4>
	 * @param string $event 事件
	 * @param string $page 页面内容
	 */
	protected function format()
	{
		$page = $this->result;
		### 页面压缩
		#$search = array(
		#'/^\s+/m',       #行首空白
		#'/^<!--.*-->$/', #页面注释
		#);
		#$page = preg_replace($search, '', $page);

		### 应用配置
		$preset = $this->pick(__FUNCTION__, $this->preset);
 		if ($preset && is_array($preset))
 		{
	 		$items = array_keys($preset);
	 		$values = array_values($preset);
	 		$page = str_replace($items, $values, $page);
 		}

		### 页面缓存
// 		if (defined('_CACHE'))
// 		{
// 			$cache = Cache::access();
// 			$cache->entrust($event, $page);
// 		}
		return $page;
	}

	/**
	 * 属性未知时，返回对应语言项或预置内容。
	 */
	public function __get($item)
	{
		$result = $this->lang->entrust($item);
		if ($result === null)
		{
			$result = $this->pick($item, $this->preset);
		}
		$this->$item = $result;
		return $result;
	}
}
