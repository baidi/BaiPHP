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
 * <h3>输入工场</h3>
 * <p>
 * 根据模板生成相应输入项（input、textarea等）。
 * </p>
 *
 * @author 白晓阳
 */
class Input extends Work
{
	/**
	 * 检验匹配式
	 */
	protected $check = null;
	/**
	 * 输入类型
	 */
	protected $types = null;
	/**
	 * 输入验证
	 */
	protected $checks = null;
	/**
	 * 输入提示
	 */
	protected $hints = null;
	/**
	 * 输入值
	 */
	protected $values = null;
	/**
	 * 输入模板
	 */
	protected $templates = null;

	/**
	 * 输入工场静态入口
	 */
	private static $ACCESS = null;

	/**
	 * <h4>获取输入工场入口</h4>
	 *
	 * @param array $setting 即时配置
	 * @return Style 输入工场
	 */
	public static function access ($setting = null)
	{
		if ($setting != null || self::$ACCESS == null) {
			self::$ACCESS = new Input($setting);
		}
		return self::$ACCESS;
	}

	/**
	 * <h4>生成输入项</h4>
	 *
	 * @param string $item 输入项
	 * @param array $setting 模板参数
	 * @param string $template 模板
	 * @return string 输入项（HTML）
	 */
	public static function fetch ($item = null, $setting = null, $template = 'text')
	{
		$input = Input::access();
		return $input->entrust($item, $setting, $template);
	}

	/**
	 * <h4>生成输入项</h4>
	 * <p>
	 * 生成输入项。
	 * </p>
	 *
	 * @param string $item 输入项
	 * @param array $setting 模板参数
	 * @param string $template 模板
	 * @return string 输入项（HTML）
	 */
	public function entrust ($item = null, $setting = null, $template = 'text')
	{
		if ($item == null) {
			return null;
		}
		$template = $this->pick($template, $this->templates);
		if ($template == null) {
			return null;
		}
		### 非数组参数默认作为$content
		if (! is_array($setting)) {
			$setting = array(
				'content' => "$setting"
			);
		}
		### 匹配检验项
		$event = "$this->target";
		$check = $this->config(self::CHECK, self::EVENT, $event, $item);
		$mode = $this->config(self::CHECK, 'mode');
		if (preg_match_all($mode, $check, $cases, PREG_SET_ORDER)) {
			$cases[] = array(
				Check::ITEM => _DEF,
				Check::PARAM => $check
			);
		}
		$this['cases'] = $cases;
		### 填充模板参数
		if ($cases != null) {
			$setting['type'] = $this->type();
			$setting['check'] = $this->check();
			$setting['hint'] = $this->hint();
		}
		$setting['event'] = $event;
		$setting['item'] = $item;
		$setting['value'] = $this->value($this->pick('type', $setting), $this->target[$item]);
		### 解析模板
		$this->result = Template::fetch($template, $setting);
		return $this->result;
	}

	/**
	 * <h4>解析输入值</h4>
	 *
	 * @param string $type 输入类型
	 * @param string $value 原始输入值
	 * @return string 输入值
	 */
	protected function value ($type, $value)
	{
		$result = $this->pick($type, $this->values);
		if ($result == null) {
			$result = $this->pick($result, $this->values);
		}
		if ($result != null) {
			$result = sprintf($result, $value);
		}
		return $result;
	}

	/**
	 * <h4>解析输入类型</h4>
	 * <p>
	 * 根据检验条件（type=***）解析输入类型。
	 * </p>
	 *
	 * @return string 输入类型
	 */
	protected function type ()
	{
		$cases = $this['cases'];
		### 获取输入类型
		$result = null;
		foreach ($cases as $case) {
			$item = $this->pick(Check::ITEM, $case);
			if (strcasecmp($item, __FUNCTION__) === 0) {
				$result = $this->pick(Check::PARAM, $case);
				break;
			}
		}
		### 转换输入类型
		$alt = $this->pick($result, $this->types);
		return ($alt == null) ? $result : $alt;
	}

	/**
	 * <h4>解析输入验证</h4>
	 * <p>
	 * 根据检验条件（required、max=***）解析输入验证。
	 * required => required="required"
	 * max=9 => maxlength="9"
	 * 全部 => data-check="***"
	 * </p>
	 *
	 * @return string 输入验证
	 */
	protected function check ()
	{
		$cases = $this['cases'];
		$result = array();
		foreach ($cases as $case) {
			$item = $this->pick(Check::ITEM, $case);
			$param = $this->pick(Check::PARAM, $case);
			$check = $this->pick($item, $this->checks);
			if ($check != null) {
				$result[] = sprintf($check, $param);
			}
		}
		return implode(' ', $result);
	}

	/**
	 * <h4>解析输入提示</h4>
	 * <p>
	 * 根据检验条件解析输入提示。
	 * </p>
	 *
	 * @return string 输入提示
	 */
	protected function hint ()
	{
		$cases = $this['cases'];
		$result = array();
		foreach ($cases as $case) {
			$item = $this->pick(Check::ITEM, $case);
			$param = $this->pick(Check::PARAM, $case);
			$hint = $this->pick($item, $this->hints);
			if ($hint != null) {
				$result[] = sprintf($hint, $param);
				continue;
			}
			$hint = $this->pick($param, $this->hints);
			if ($hint != null) {
				$result[] = $hint;
			}
		}
		return implode(', ', $result);
	}

	/**
	 * <h4>构建输入工场</h4>
	 *
	 * @param array $setting 即时配置
	 */
	protected function __construct ($setting = null)
	{
		parent::__construct($setting);
	}
}
