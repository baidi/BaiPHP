<?php
/**
 * <h2>BaiPHP（简单PHP）开发框架</h2>
 * @link      http://www.baiphp.com
 * @copyright Copyright (c) 2011 - 2012, 白晓阳
 * @author    白晓阳
 * @version   1.0.0 2012/03/31 首版
 *            2.0.0 2012/07/01 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 * <p>欢迎提供各种形式的捐助。任何捐助者自动获得仅限于捐助者自身的商业使用（不包括再发行和再授权）授权。</p>
 */

/**
 * <h2>BaiPHP（简单PHP）开发框架</h2>
 * <h3>样式工场</h3>
 * <p>
 * 导入样式（css、js）文件，生成样式信息。
 * </p>
 * @author 白晓阳
 */
 class Style extends Work
 {
	/** 样式工场静态入口 */
	static private $ACCESS = null;

	/**
	 * <h4>获取样式工场入口</h4>
	 * @param array $setting 即时配置
	 * @return Style 样式工场
	 */
	static public function access($setting = null)
	{
		if ($setting != null || self::$ACCESS == null)
		{
			self::$ACCESS = new Style($setting);
		}
		return self::$ACCESS;
	}

 	/**
 	 * <h4>导入css文件</h4>
 	 * <p>
 	 * 导入样式css文件并格式化。
 	 * </p>
 	 * @return string 样式内容
 	 */
 	static public function css($items = null, $inset = false)
 	{
 		$style = Style::access();
 		return $style->entrust($items, __FUNCTION__, $inset);
 	}

 	/**
 	 * <h4>导入js文件</h4>
 	 * <p>
 	 * 导入样式js文件并格式化。
 	 * </p>
 	 * @return string 样式内容
 	 */
 	static public function js($items = null, $inset = false)
 	{
 		$style = Style::access();
 		return $style->entrust($items, __FUNCTION__, $inset);
 	}

 	/**
 	 * <h4>导入js文件</h4>
 	 * <p>
 	 * 导入样式js文件并格式化。
 	 * </p>
 	 * @return string 样式内容
 	 */
 	static public function img($items = null)
 	{
 		$style = Style::access();
 		return $style->entrust($items, __FUNCTION__);
 	}

 	/**
 	 * <h4>导入js文件</h4>
 	 * <p>
 	 * 导入样式js文件并格式化。
 	 * </p>
 	 * @return string 样式内容
 	 */
 	static public function file($items = null, $branch = null)
 	{
 		$style = Style::access();
 		return $style->entrust($items, $branch);
 	}

	/**
	 * <h4>导入样式文件</h4>
	 * <p>
	 * 导入样式（css、js）文件并格式化。
	 * </p>
	 * @param array $items 文件名
	 * @param string $branch 分支名
	 * @param bool $inset 是否嵌入
	 * @return string 样式内容
	 */
 	public function entrust($items = null, $branch = null, $inset = false)
 	{
 		if ($items == null)
 		{
 			return null;
 		}
 		if (! is_array($items))
 		{
 			$items = array($items);
 		}
 		if ($branch == null)
 		{
 			$branch == get_class($this);
 		}
 		$this->result = '';
 		foreach ($items as $item)
 		{
 			if ($item == null || ! is_string($item))
 			{
 				continue;
 			}
	 		$this->runtime['item']   = $item;
	 		$this->runtime['branch'] = $branch;
	 		if ($inset)
	 		{
	 			$this->result .= $this->inset();
	 			continue;
	 		}
 			$this->result .= $this->link();
 		}
 		return $this->result;
 	}

 	/**
 	 * <h4>嵌入样式文件</h4>
 	 * @return string
 	 */
	protected function inset()
	{
		### 执行数据
		$item   = $this->pick('item', $this->runtime);
		$branch = $this->pick('branch', $this->runtime);
		$preset = $this->pick(__FUNCTION__, $this->preset);
		$preset = $this->pick($branch, $preset);
		if (! $preset)
		{
			return null;
		}
 		### 加载文件
 		$bai     = $this->pick(self::BAI,     $this->target);
 		$service = $this->pick(self::SERVICE, $this->target);
 		$content = '';
 		if (is_file(_LOCAL.$bai.$branch._DIR.$item))
 		{
 			$content .= file_get_contents(_LOCAL.$bai.$branch._DIR.$item);
 		}
 		if (is_file(_LOCAL.$service.$branch._DIR.$item))
 		{
 			$content .= file_get_contents(_LOCAL.$service.$branch._DIR.$item);
 		}
 		return $content;
	}

	/**
	 * <h4>外连样式文件</h4>
	 * @return string
	 */
	protected function link()
	{
		### 执行数据
		$item = $this->pick('item', $this->runtime);
		$branch = $this->pick('branch',  $this->runtime);
		$preset = $this->pick(__FUNCTION__, $this->preset);
		$preset = $this->pick($branch, $preset);
 		### 外连文件
 		$bai     = $this->pick(self::BAI,     $this->target);
 		$service = $this->pick(self::SERVICE, $this->target);
 		$content = '';
 		if (is_file(_LOCAL.$service.$branch._DIR.$item))
 		{
 			if (! $preset)
 			{
 				return _WEB.$service.$branch._DIR.$item;
 			}
 			$content .= sprintf($preset, _WEB.$service.$branch._DIR.$item);
 		}
 		if (is_file(_LOCAL.$bai.$branch._DIR.$item))
 		{
 			if (! $preset)
 			{
 				return _WEB.$bai.$branch._DIR.$item;
 			}
 			$content = sprintf($preset, _WEB.$bai.$branch._DIR.$item).$content;
 		}
 		return $content;
	}
 }
 