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
	 * @param array $setting 自定义配置
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
 	static public function css($items = null)
 	{
 		$style = Style::access();
 		return $style->entrust($items, '.'.__FUNCTION__);
 	}

 	/**
 	 * <h4>导入js文件</h4>
 	 * <p>
 	 * 导入样式js文件并格式化。
 	 * </p>
 	 * @return string 样式内容
 	 */
 	static public function js($items = null)
 	{
 		$style = Style::access();
 		return $style->entrust($items, '.'.__FUNCTION__);
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
 		return $style->entrust($items, '.'.__FUNCTION__);
 	}

	/**
	 * <h4>导入样式文件</h4>
	 * <p>
	 * 导入样式（css、js）文件并格式化。
	 * </p>
	 * @param array $items 导入文件
	 * @param string $ext 扩展名
	 * @return string 样式内容
	 */
 	public function entrust($items = null, $ext = null)
 	{
 		if ($items == null)
 		{
 			return null;
 		}
 		if (! is_array($items))
 		{
 			$items = array($items);
 		}
 		//$exts = $this->pick(__FUNCTION__, $this->preset);
 		$this->result = '';
 		foreach ($items as $item)
 		{
 			if ($item == null || ! is_string($item))
 			{
 				continue;
 			}
 			### 判断扩展名
	 		if ($ext == null)
	 		{
	 			$ext = strrchr($item, '.');
	 		}
	 		if ($ext == null || $ext === $item || $ext === '.')
	 		{
	 			continue;
	 		}
	 		if (substr($item, - strlen($ext)) !== $ext)
	 		{
	 			$item .= $ext;
	 		}
	 		### 导入样式文件
	 		$this->runtime['item'] = $item;
	 		$this->runtime['ext']  = $ext;
 			$this->result .= $this->fetch();
 		}
 		return $this->result;
 	}

	/**
	 * <h4>导入样式文件</h4>
	 * <p>
	 * 导入样式（css、js）文件并格式化。
	 * </p>
	 * @return string 样式内容
	 */
	protected function fetch()
	{
		### 执行数据
		$item = $this->pick('item', $this->runtime);
		$ext  = $this->pick('ext',  $this->runtime);
 		### 加载路径
 		$bai     = $this->pick(self::BAI,     $this->target);
 		$service = $this->pick(self::SERVICE, $this->target);
 		$branch  = substr($ext, 1)._DIR;
 		### 加载文件
 		ob_start();
 		if (is_file(_LOCAL.$bai.$branch.$item))
 		{
 			include _LOCAL.$bai.$branch.$item;
 		}
 		if (is_file(_LOCAL.$service.$branch.$item))
 		{
 			include _LOCAL.$service.$branch.$item;
 		}
 		$content = ob_get_clean();
 		### 应用配置
 		$preset = $this->config(substr($ext, 1));
 		if ($preset == null || ! is_array($preset))
 		{
 			return $content;
 		}
 		$items = array_keys($preset);
 		$values = array_values($preset);
 		foreach ($values as &$value)
 		{
 			if (is_array($value) || is_object($value))
 			{
 				$value = json_encode($value);
 			}
 		}
 		$content = str_replace($items, $values, $content);
 		return $content;
	}

	/**
	 * <h4>解析网络文件</h4>
	 * <p>
	 * 文件文件名和分支名，解析文件的网络路径。
	 * 总是优先使用服务路径。
	 * </p>
	 * @param string $file 文件名
	 * @param string $branch 分支名
	 * @return string 文件路径
	 */
	protected function request($file = null, $branch = null) {
		if ($file == null) {
			return null;
		}
		if ($branch == null) {
			$branch  = strrchr($item, '.')._DIR;
		}
		### 寻址路径
		$bai     = $this->pick(self::BAI,     $this->target);
		$service = $this->pick(self::SERVICE, $this->target);
		if (is_file(_LOCAL.$service.$branch.$file)) {
			return _WEB.$service.$branch.$file;
		}
		if (is_file(_LOCAL.$bai.$branch.$file)) {
			return _WEB.$bai.$branch.$file;
		}
		return null;
	}
 }
 