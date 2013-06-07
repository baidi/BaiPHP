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
	/** 页面布局 */
	protected $layout  = '_page.php';
	/** 页面样式 */
	protected $css     = null;
	/** 页面脚本 */
	protected $js      = null;
	/** 页面版式 */
	protected $formats = null;
	/** 页面修整 */
	protected $trims   = null;

	/**
	 * <h4>生成页面HTML</h4>
	 * @return void
	 */
	protected function html()
	{
		$this['js']    = $this->js;
		$this['css']   = $this->css;
		$this['lside'] = null;
		$this['rside'] = null;
		return $this->load($this->layout);
	}

	/**
	 * <h4>页面内容处理</h4>
	 * <p>
	 * 应用页面版式，页面修整，并缓存页面
	 * </p>
	 * @param string $event 事件
	 * @param string $page 页面内容
	 */
	protected function format()
	{
		$page = $this->result;
		### 应用页面版式
		if ($this->formats && is_array($this->formats)) {
			$page = str_replace(array_keys($this->formats), array_values($this->formats), $page);
		}
		### 应用页面修整
		if ($this->trims && is_array($this->trims)) {
			$page = preg_replace(array_keys($this->trims), array_values($this->trims), $page);
		}
		### 应用页面缓存
		#$cache = Cache::access();
		#$cache->entrust($event, $page);
		return $page;
	}

	/**
	 * <h3>读取项目</h3>
	 * @param string $item 项目名
	 * @return mixed 项目值
	 */
	public function offsetGet($item)
	{
		if (! isset($this->runtime[$item])) {
			### 从语言工场取值
			$this->runtime[$item] = Lang::fetch($item, false);
		}
		return $this->runtime[$item];
	}
}
