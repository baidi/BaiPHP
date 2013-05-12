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
 * <h3>模板工场</h3>
 * <p>
 * 根据预置模板和模板参数生成页面片段。
 * 模板参数有三种解析形式：
 * <ol>
 * <li>即时参数：{$参数名}</li>
 * <li>判断参数：{$参数名 ? 表达式真 : 表达式假}</li>
 * <li>循环参数：{$参数名 ! 表达式}</li>
 * </ol>
 * 表达式中只能使用当前参数。
 * </p>
 * @author 白晓阳
 */
class Template extends Work
{
	/** 参数表达式 */
	protected $param = '#\{\$(?<name>[a-zA-Z0-9_\x7f-\xff]+)\s*(?:(?<handle>[?!])\s*(?<first>[^|}]+)\s*(?:\|\s*(?<last>[^|}]+)\s*)?)?\}#';
	/** 参数处理式 */
	protected $handler = array(
		### 判断处理式
		'?' => 'choose',
		### 循环处理式
		'!' => 'loop',
	);
	/** 参数占位符 */
	protected $peg = '$';
	/** 循环体变量 */
	protected $looper = array('$value', '$key');
	/** 预置模板 */
	protected $templates = null;

	/** 模板工场静态入口 */
	static private $ACCESS = null;

	/**
	 * <h4>获取模板工场入口</h4>
	 * @param array $setting 即时配置
	 * @return Template 模板工场
	 */
	static public function access($setting = null)
	{
		if ($setting != null || self::$ACCESS == null)
		{
			self::$ACCESS = new Template($setting);
		}
		return self::$ACCESS;
	}

	/**
	 * <h4>生成页面片段</h4>
	 * <p>
	 * 根据生成项选择相应模板，并解析模板参数生成页面片段。
	 * </p>
	 * @param string $item 生成项
	 * @param array $setting 模板参数
	 * @return string 页面片段
	 */
	public function entrust($item = null, $setting = null)
	{
		if ($item == null)
		{
			return null;
		}
		### 读取模板
		$template = $this->pick("$item", $this->templates);
		if ($template == null || ! is_string($template))
		{
			return null;
		}
		### 参数匹配
		if ($this->param == null || ! preg_match_all($this->param, $template, $params, PREG_SET_ORDER))
		{
			$this->result = $template;
			return $this->result;
		}
		if (is_array($setting))
		{
			krsort($setting);
		}
		### 执行数据
		$this->runtime['template'] = $template;
		$this->runtime['setting']  = $setting;
		$this->runtime['params']   = $params;
		### 解析模板
		$this->result = $this->format();
		return $this->result;
	}

	/**
	 * <h4>解析模板</h4>
	 * <p>
	 * 解析模板参数并生成页面片段。
	 * </p>
	 * @return string 页面片段
	 */
	protected function format()
	{
		### 执行数据
		$template = $this->pick('template', $this->runtime);
		$setting  = $this->pick('setting',  $this->runtime);
		$params   = $this->pick('params',   $this->runtime);
		foreach ($params as $param)
		{
			### 解析参数
			$name    = $this->pick('name',   $param);
			$value   = $this->pick($name,    $setting);
			$handle  = $this->pick('handle', $param);
			$handler = $this->pick($handle,  $this->handler);
			if ($handler == null)
			{
				### 参数直接置换
				$template = str_replace($param[0], $this->pick($name, $setting), $template);
				continue;
			}
			$this->runtime['name']  = $name;
			$this->runtime['value'] = $value;
			$this->runtime['first'] = $this->pick('first', $param);
			$this->runtime['last']  = $this->pick('last',  $param);
			### 参数处理式
			$template = str_replace($param[0], $this->$handler(), $template);
		}
		return $template;
	}

	/**
	 * <h4>解析参数判断式</h4>
	 * @return string 参数解析片段
	 */
	protected function choose()
	{
		### 执行数据
		$name    = $this->pick('name',  $this->runtime);
		$value   = $this->pick('value', $this->runtime);
		### 获取表达式
		$content = $this->pick('first', $this->runtime);
		if (! $value)
		{
			$content = $this->pick('last', $this->runtime);
		}
		if ($content == null)
		{
			return null;
		}
		return str_replace($this->peg.$name, $value, $content);
	}

	/**
	 * <h4>解析参数循环式</h4>
	 * @return string 参数解析片段
	 */
	protected function loop()
	{
		### 执行数据
		$name    = $this->pick('name',  $this->runtime);
		$value   = $this->pick('value', $this->runtime);
		$content = $this->pick('first', $this->runtime);
		### 参数非数组
		if (! is_array($value))
		{
			if ($content == null)
			{
				return $value;
			}
			return str_replace($this->peg.$name, $value, $content);
		}
		### 表达式为空
		if ($content == null)
		{
			return implode('', $value);
		}
		### 循环解析
		$result = '';
		foreach ($value as $key => $val)
		{
			$result .= str_replace($this->looper, array($val, $key), $content);
		}
		return $result;
	}
}
