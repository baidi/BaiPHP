<?php

/**
 * <h2>化简PHP（BaiPHP）开发框架</h2>
 *
 * @link http://www.baiphp.net
 * @copyright Copyright (c) 2011 - 2012, 白晓阳
 * @author 白晓阳
 * @version 1.0.0 2012/03/31 首版
 *          2.0.0 2012/07/01 首版
 *          <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 *          <p>欢迎提供各种形式的捐助。任何捐助者自动获得仅限于捐助者自身的商业使用（不包括再发行和再授权）授权。</p>
 */

/**
 * <h2>化简PHP（BaiPHP）开发框架</h2>
 * <h3>模板工场</h3>
 * <p>
 * 解析模板中的嵌入参数并生成输出片段。
 * 模板参数有三种形式：
 * <ol>
 * <li>即时参数：{$参数名}</li>
 * <li>判断参数：{$参数名 ? 表达式真 : 表达式假}</li>
 * <li>循环参数：{$参数名 ! 表达式}</li>
 * </ol>
 * 表达式中只能使用当前参数。
 * </p>
 *
 * @author 白晓阳
 */
class Template extends Work
{
	/**
	 * 模板标识：解析参数
	 */
	const ITEM = 'ITEM';
	/**
	 * 模板标识：解析器
	 */
	const HANDLE = 'HANDLE';
	/**
	 * 模板标识：首选项
	 */
	const PRIMARY = 'PRIMARY';
	/**
	 * 模板标识：次选项
	 */
	const SECORDARY = 'SECORDARY';

	/**
	 * 参数占位符
	 */
	protected $peg = '$';
	/**
	 * 参数匹配式
	 */
	protected $mode = null;
	/**
	 * 模板解析器
	 */
	protected $handler = array(
		null => 'param',
		### 判断处理式
		'?' => 'choose',
		### 循环处理式
		'!' => 'loop',
	);
	/**
	 * 循环体变量
	 */
	protected $looper = array(
		'$value',
		'$key',
	);
	/**
	 * 模板词典
	 */
	protected $dic = null;

	/**
	 * 模板工场静态入口
	 */
	private static $ACCESS = null;

	/**
	 * <h4>获取模板工场静态入口</h4>
	 *
	 * @param array $setting 即时配置
	 * @return Template 模板工场
	 */
	public static function access ($setting = null)
	{
		if ($setting != null || self::$ACCESS == null) {
			self::$ACCESS = new Template($setting);
		}
		return self::$ACCESS;
	}

	/**
	 * <h4>获取模板输出</h4>
	 * <p>
	 * 根据模板项选择相应模板，并解析嵌入参数生成最终输出。
	 * </p>
	 *
	 * @param string $item 模板项
	 * @param array $setting 嵌入参数
	 * @return string 输出片段
	 */
	public static function fetch ($item = null, $setting = null)
	{
		$template = Template::access();
		return $template->entrust($item, $setting);
	}

	/**
	 * <h4>获取模板文件输出</h4>
	 * <p>
	 * 根据模板文件，解析嵌入参数生成最终输出。
	 * </p>
	 *
	 * @param string $item 模板文件
	 * @param array $setting 嵌入参数
	 * @return string 输出片段
	 */
	public static function file ($item = null, $setting = null)
	{
		$template = Template::access();
		return $template->entrust($this->load($item), $setting);
	}

	/**
	 * <h4>生成模板输出</h4>
	 * <p>
	 * 根据模板项选择相应模板，并解析嵌入参数生成最终输出。
	 * </p>
	 *
	 * @param string $item 模板项
	 * @param array $setting 嵌入参数
	 * @return string 输出片段
	 */
	public function entrust ($item = null, $setting = null)
	{
		if ($item == null) {
			return null;
		}
		### 读取模板
		$template = $this->pick("$item", $this->dic);
		if ($template == null) {
			$template = $item;
		}
		### 匹配参数
		if ($this->mode == null ||
				 ! preg_match_all($this->mode, $template, $params, PREG_SET_ORDER)) {
			$this->result = $template;
			return $this->result;
		}
		### 非数组参数默认作为$content
		if (! is_array($setting)) {
			$setting = array('content' => "$setting");
		}
		krsort($setting);
		### 执行数据
		$this['template'] = $template;
		$this['setting'] = $setting;
		$this['params'] = $params;
		### 解析模板
		$this->result = $this->format();
		return $this->result;
	}

	/**
	 * <h4>解析模板参数</h4>
	 * <p>
	 * 解析模板参数并生成输出片段。
	 * </p>
	 *
	 * @return string 输出片段
	 */
	protected function format ()
	{
		### 执行数据
		$template = $this['template'];
		$setting = $this['setting'];
		$params = $this['params'];
		foreach ($params as $param) {
			### 解析参数
			$item = $this->pick(self::ITEM, $param);
			$value = $this->pick($item, $setting);
			$handle = $this->pick(self::HANDLE, $param);
			$handler = $this->pick($handle, $this->handler);
			if ($handler == null) {
				### 参数直接置换
				$template = str_replace($param[0], '', $template);
				continue;
			}
			### 调用解析器
			$this['content'] = $param[0];
			$this['value'] = $value;
			$this[self::ITEM] = $item;
			$this[self::PRIMARY] = $this->pick(self::PRIMARY, $param);
			$this[self::SECORDARY] = $this->pick(self::SECORDARY, $param);
			### 参数处理式
			$template = str_replace($param[0], $this->$handler(), $template);
		}
		return $template;
	}

	/**
	 * <h4>模板解析器：参数</h4>
	 *
	 * @return string 参数输出片段
	 */
	protected function param ()
	{
		### 执行数据
		$item = $this[self::ITEM];
		$value = $this['value'];
		$content = $this['content'];
		### 表达式
		return str_replace($this->peg . $item, $value, substr($content, 1, -1));
	}

	/**
	 * <h4>模板解析器：判断</h4>
	 *
	 * @return string 参数输出片段
	 */
	protected function choose ()
	{
		### 执行数据
		$item = $this[self::ITEM];
		$value = $this['value'];
		### 表达式
		$content = $value ? $this[self::PRIMARY] : $this[self::SECORDARY];
		if ($content == null) {
			return null;
		}
		return str_replace($this->peg . $item, $value, $content);
	}

	/**
	 * <h4>模板解析器：循环</h4>
	 *
	 * @return string 参数输出片段
	 */
	protected function loop ()
	{
		### 执行数据
		$item = $this[self::ITEM];
		$value = $this['value'];
		$content = $this[self::PRIMARY];
		### 参数非数组
		if (! is_array($value)) {
			$value = array($value);
		}
		### 表达式为空
		if ($content == null) {
			return implode('', $value);
		}
		### 循环解析
		$result = '';
		foreach ($value as $key => $val) {
			$result .= str_replace($this->looper, array($val,$key), $content);
		}
		return $result;
	}
}
