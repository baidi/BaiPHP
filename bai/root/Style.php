<?php
################################################################################
# BaiPHP Mobile Framework
# http://www.baiphp.com
# Copyright (C) 2011-2014 Xiao Yang, Bai
#
# Anyone obtaining a copy of BaiPHP gets permission to use, copy, modify, merge,
# publish, distribute, and/or sell it for non-profit purpose.
# Any contributor to BaiPHP gets for-profit permission for itself only, which
# can't be transferred or rent.
# Authors or copyright holders don't take any for all the consequences arising
# therefrom.
# By using BaiPHP, you are unconditionally agree to this notice and must keep it
# in the copy.
################################################################################


/**
 * <h2>BaiPHP Mobile Framework</h2>
 * <h3>样式工场</h3>
 * <p>
 * 导入样式（css、js）文件，生成样式信息。
 * </p>
 *
 * @copyright Copyright (C) 2011-2014 Xiao Yang, Bai
 * @author Xiao Yang, Bai
 */
class Style extends Work
{
	/**
	 * 默认图片（原图无效时使用）
	 */
	protected $img = '_blank.png';
	/**
	 * 内嵌模板
	 */
	protected $insets = null;
	/**
	 * 外链模板
	 */
	protected $links = null;

	/**
	 * Static entrance
	 */
	protected static $ENTRANCE = false;

	/**
	 * <h4>导入css文件</h4>
	 * <p>
	 * 导入样式css文件并格式化。
	 * </p>
	 *
	 * @return string 样式内容
	 */
	public static function css($item = null, $inset = false)
	{
		$style = Style::access();
		return $style->entrust($item, __FUNCTION__, $inset);
	}

	/**
	 * <h4>导入js文件</h4>
	 * <p>
	 * 导入样式js文件并格式化。
	 * </p>
	 *
	 * @return string 样式内容
	 */
	public static function js($item = null, $inset = false)
	{
		$style = Style::access();
		return $style->entrust($item, __FUNCTION__, $inset);
	}

	/**
	 * <h4>导入图片文件</h4>
	 * <p>
	 * 导入样式图片文件并格式化。
	 * </p>
	 *
	 * @return string 样式内容
	 */
	public static function img($item = null)
	{
		$style = Style::access();
		return $style->entrust($item, __FUNCTION__);
	}

	/**
	 * <h4>导入js文件</h4>
	 * <p>
	 * 导入样式js文件并格式化。
	 * </p>
	 *
	 * @return string 样式内容
	 */
	public static function file($item = null, $branch = null)
	{
		$style = Style::access();
		return $style->entrust($item, $branch);
	}

	/**
	 * <h4>导入样式文件</h4>
	 * <p>
	 * 导入样式（css、js）文件并格式化。
	 * </p>
	 *
	 * @param string $items
	 *        项目名
	 * @param string $ext
	 *        分支名
	 * @param bool $inset
	 *        是否嵌入
	 * @return string 样式内容
	 */
	public function entrust($items = null, $ext = null, $inset = false)
	{
		if ($items == null)
		{
			return null;
		}

		$this->result = '';
		foreach ((array) $items as $item)
		{
			if ($item == null || !is_string($item))
			{
				continue;
			}
			$this['item'] = $item;
			$this['ext'] = $ext;
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
	 * <h4>内嵌样式文件</h4>
	 *
	 * @return string
	 */
	protected function inset()
	{
		### 执行数据
		$item = $this['item'];
		$ext = $this['ext'];
		$template = self::pick($ext, $this->insets);
		if (!$template)
		{
			return null;
		}
		### 路径
		$path = $this->locate($item, get_class($this) . _DIR . $ext);
		$app = self::pick(Bai::APP, $path);
		$base = self::pick(Bai::BASE, $path);
		### 加载文件
		$content = '';
		if ($app != null)
		{
			Log::logf(__FUNCTION__, $app, __CLASS__);
			$content .= file_get_contents(_LOCAL . $app);
		}
		if ($base != null)
		{
			Log::logf(__FUNCTION__, $base, __CLASS__);
			$content .= file_get_contents(_LOCAL . $base);
		}
		if ($content != null)
		{
			$content = sprintf($template, $content);
		}
		return $content;
	}

	/**
	 * <h4>外链样式文件</h4>
	 *
	 * @return string
	 */
	protected function link()
	{
		### 执行数据
		$item = $this['item'];
		$ext = $this['ext'];
		$template = self::pick($ext, $this->links);
		### 路径
		$path = $this->locate($item, get_class($this) . _DIR . $ext);
		$app = self::pick(Bai::APP, $path);
		$base = self::pick(Bai::BASE, $path);
		### 外链文件
		if ($template != null)
		{
			$content = '';
			if ($base != null)
			{
				Log::logf(__FUNCTION__, $base, __CLASS__);
				$content .= sprintf($template, _WEB . $base);
			}
			if ($app != null)
			{
				Log::logf(__FUNCTION__, $app, __CLASS__);
				$content .= sprintf($template, _WEB . $app);
			}
			return $content;
		}
		### 外链文件名
		if ($base != null)
		{
			Log::logf(__FUNCTION__, $base, __CLASS__);
			return _WEB . $base;
		}
		if ($app != null)
		{
			Log::logf(__FUNCTION__, $app, __CLASS__);
			return _WEB . $app;
		}
		return null;
	}
}
