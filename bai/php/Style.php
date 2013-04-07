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
	/** 默认图片（原图无效时使用） */
	protected $img = '_blank.png';

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
	static public function css($item = null, $inset = false)
	{
		$style = Style::access();
		return $style->entrust($item, __FUNCTION__, $inset);
	}

	/**
	 * <h4>导入js文件</h4>
	 * <p>
	 * 导入样式js文件并格式化。
	 * </p>
	 * @return string 样式内容
	 */
	static public function js($item = null, $inset = false)
	{
		$style = Style::access();
		return $style->entrust($item, __FUNCTION__, $inset);
	}

	/**
	 * <h4>导入js文件</h4>
	 * <p>
	 * 导入样式js文件并格式化。
	 * </p>
	 * @return string 样式内容
	 */
	static public function img($item = null)
	{
		$style = Style::access();
		return $style->entrust($item, __FUNCTION__);
	}

	/**
	 * <h4>导入js文件</h4>
	 * <p>
	 * 导入样式js文件并格式化。
	 * </p>
	 * @return string 样式内容
	 */
	static public function file($item = null, $branch = null)
	{
		$style = Style::access();
		return $style->entrust($item, $branch);
	}

	/**
	 * <h4>导入样式文件</h4>
	 * <p>
	 * 导入样式（css、js）文件并格式化。
	 * </p>
	 * @param string $item 项目名
	 * @param string $branch 分支名
	 * @param bool $inset 是否嵌入
	 * @return string 样式内容
	 */
	public function entrust($item = null, $branch = null, $inset = false)
	{
		if ($item == null || ! is_string($item) || $branch == null)
		{
			return null;
		}
		$this->runtime['item']   = $item;
		$this->runtime['branch'] = $branch._DIR;
		$this->result = $inset ? $this->inset() : $this->link();
		return $this->result;
	}

	/**
	 * <h4>嵌入样式文件</h4>
	 * @return string
	 */
	protected function inset()
	{
		### 执行数据
		$item   = $this->pick('item',   $this->runtime);
		$branch = $this->pick('branch', $this->runtime);
		if (! $this->pick($item, $this->insets))
		{
			return null;
		}
		### 加载文件
		$bai     = _LOCAL.$this->target[self::BAI];
		$service = _LOCAL.$this->target[self::SERVICE];
		$content = '';
		if (is_file($bai.$branch.$item))
		{
			$content .= file_get_contents($bai.$branch.$item);
		}
		if (is_file($service.$branch.$item))
		{
			$content .= file_get_contents($service.$branch.$item);
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
		$item   = $this->pick('item',   $this->runtime);
		$branch = $this->pick('branch', $this->runtime);
		$template = $this->pick($item, $this->links);
		### 外连文件
		$bai     = $this->target[self::BAI];
		$service = $this->target[self::SERVICE];
		$content = '';
		if (is_file(_LOCAL.$service.$branch.$item))
		{
			if ($template == null)
			{
				return _WEB.$service.$branch.$item;
			}
			$content .= sprintf($template, _WEB.$service.$branch.$item);
		}
		if (is_file(_LOCAL.$bai.$branch.$item))
		{
			if ($template == null)
			{
				return _WEB.$bai.$branch.$item;
			}
			$content .= sprintf($template, _WEB.$bai.$branch.$item).$content;
		}
		return $content;
	}
}
