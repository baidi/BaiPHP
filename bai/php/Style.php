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
	/** 内嵌模板 */
	protected $insets = null;
	/** 外链模板 */
	protected $links = null;

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
	 * <h4>导入图片文件</h4>
	 * <p>
	 * 导入样式图片文件并格式化。
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
	 * @param string $items 项目名
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
		if ($branch == null)
		{
			$branch == get_class($this)._DIR;
		}
		$this->result = '';
		foreach ((array)$items as $item)
		{
			if ($item == null || ! is_string($item))
			{
				continue;
			}
			$this->runtime['item']   = $item;
			$this->runtime['branch'] = $branch;
			if (! $inset)
			{
				$this->result = $this->link();
				break;
			}
			$this->result .= $this->inset();
		}
		return $this->result;
	}

	/**
	 * <h4>内嵌样式文件</h4>
	 * @return string
	 */
	protected function inset()
	{
		### 执行数据
		$item   = $this->pick('item',   $this->runtime);
		$branch = $this->pick('branch', $this->runtime);
		$template = $this->pick($branch, $this->insets);
		if (! $template)
		{
			return null;
		}
		### 加载文件
		$bai     = _LOCAL.$this->target[self::BAI].$branch._DIR;
		$service = _LOCAL.$this->target[self::SERVICE].$branch._DIR;
		$content = '';
		if (is_file($bai.$item))
		{
		    Log::logf(__FUNCTION__, $bai.$item, __CLASS__);
			$content .= file_get_contents($bai.$item);
		}
		if (is_file($service.$item))
		{
		    Log::logf(__FUNCTION__, $service.$item, __CLASS__);
			$content .= file_get_contents($service.$item);
		}
		$content = sprintf($template, $content);
		return $content;
	}

	/**
	 * <h4>外链样式文件</h4>
	 * @return string
	 */
	protected function link()
	{
		### 执行数据
		$item   = $this->pick('item',   $this->runtime);
		$branch = $this->pick('branch', $this->runtime);
		$template = $this->pick($branch, $this->links);
		### 外链文件
		$bai     = $this->target[self::BAI].$branch._DIR;
		$service = $this->target[self::SERVICE].$branch._DIR;
		$content = '';
		if (is_file(_LOCAL.$service.$item))
		{
		    Log::logf(__FUNCTION__, _WEB.$service.$item, __CLASS__);
			if ($template == null)
			{
				return _WEB.$service.$item;
			}
			$content .= sprintf($template, _WEB.$service.$item);
		}
		if (is_file(_LOCAL.$bai.$item))
		{
		    Log::logf(__FUNCTION__, _WEB.$bai.$item, __CLASS__);
			if ($template == null)
			{
				return _WEB.$bai.$item;
			}
			$content = sprintf($template, _WEB.$bai.$item).$content;
		}
		return $content;
	}
}
