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
 * <h3>Template work</h3>
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
 * @copyright Copyright (C) 2011-2014 Xiao Yang, Bai
 * @author Xiao Yang, Bai
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
		'.' => 'call'
	);
	/**
	 * 循环体变量
	 */
	protected $looper = array(
		'$value',
		'$key'
	);
	/**
	 * 模板词典
	 */
	protected $dic = null;

	/**
	 * Static entrance
	 */
	protected static $ENTRANCE = false;

	/**
	 * <h4>获取模板输出</h4>
	 * <p>
	 * 根据模板项选择相应模板，并解析嵌入参数生成最终输出。
	 * </p>
	 *
	 * @param string $item
	 *        模板项
	 * @param array $settings
	 *        嵌入参数
	 * @return string 输出片段
	 */
	public static function cut($item = null, $settings = null)
	{
		$template = Template::access();
		return $template->entrust($item, $settings);
	}

	/**
	 * <h4>获取模板文件输出</h4>
	 * <p>
	 * 根据模板文件，解析嵌入参数生成最终输出。
	 * </p>
	 *
	 * @param string $item
	 *        模板文件
	 * @param array $settings
	 *        嵌入参数
	 * @return string 输出片段
	 */
	public static function file($item = null, $settings = null)
	{
		$template = Template::access();
		### 路径
		$path = $this->locate($item, __CLASS__);
		$app = self::pick(self::APP, $path);
		$base = self::pick(self::BASE, $path);
		### 加载文件
		$content = '';
		if ($base != null)
		{
			$content = file_get_contents(_LOCAL . $base);
		} else if ($app != null)
		{
			$content = file_get_contents(_LOCAL . $app);
		}
		return $template->entrust($content, $settings);
	}

	/**
	 * <h4>生成模板输出</h4>
	 * <p>
	 * 根据模板项选择相应模板，并解析嵌入参数生成最终输出。
	 * </p>
	 *
	 * @param string $item
	 *        模板项
	 * @param array $settings
	 *        嵌入参数
	 * @return string 输出片段
	 */
	public function entrust($item = null, $settings = null)
	{
		if ($item == null)
		{
			return null;
		}

		### 读取模板
		$template = self::pick("$item", $this->dic);
		if ($template == null)
		{
			$template = $item;
		}

		### 匹配参数
		if ($this->mode == null || !preg_match_all($this->mode, $template, $params, PREG_SET_ORDER))
		{
			$this->result = $template;
			return $this->result;
		}

		### 非数组参数默认作为$content
		if (!is_array($settings))
		{
			$settings = array(
				'content' => "$settings"
			);
		}
		krsort($settings);
		### 执行数据
		$this['template'] = $template;
		$this['setting'] = $settings;
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
	protected function format()
	{
		### 执行数据
		$template = $this['template'];
		$settings = $this['setting'];
		$params = $this['params'];
		foreach ($params as $param)
		{
			### 解析参数
			$item = self::pick(self::ITEM, $param);
			$value = self::pick($item, $settings);
			$handle = self::pick(self::HANDLE, $param);
			$handler = self::pick($handle, $this->handler);
			if ($handler == null)
			{
				### 参数直接置换
				$template = str_replace($param[0], '', $template);
				continue;
			}
			### 调用解析器
			$this['content'] = $param[0];
			$this['value'] = $value;
			$this[self::ITEM] = $item;
			$this[self::PRIMARY] = self::pick(self::PRIMARY, $param);
			$this[self::SECORDARY] = self::pick(self::SECORDARY, $param);
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
	protected function param()
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
	protected function choose()
	{
		### 执行数据
		$item = $this[self::ITEM];
		$value = $this['value'];
		### 表达式
		$content = $value ? $this[self::PRIMARY] : $this[self::SECORDARY];
		if ($content == null)
		{
			return null;
		}
		return str_replace($this->peg . $item, $value, $content);
	}

	/**
	 * <h4>模板解析器：循环</h4>
	 *
	 * @return string 参数输出片段
	 */
	protected function loop()
	{
		### 执行数据
		$item = $this[self::ITEM];
		$value = $this['value'];
		$content = $this[self::PRIMARY];
		### 参数非数组
		if (!is_array($value))
		{
			$value = array(
				$value
			);
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
			$result .= str_replace($this->looper, array(
				$val,
				$key
			), $content);
		}
		return $result;
	}

	/**
	 * <h4>模板解析器：调用</h4>
	 *
	 * @return string 参数输出片段
	 */
	protected function call()
	{
		### 执行数据
		$item = $this[self::ITEM];
		$value = $this['value'];
		### 表达式
		$content = $this[self::PRIMARY];
		$result = '';
		if (is_callable($content))
		{
			$result = call_user_func_array($content, array(
				$value
			));
		}
		return $result;
	}
}
