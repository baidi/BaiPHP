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
 * <h3>Input work</h3>
 * <p>
 * 根据模板生成相应输入项（input、textarea等）。
 * </p>
 *
 * @copyright Copyright (C) 2011-2014 Xiao Yang, Bai
 * @author Xiao Yang, Bai
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
	 * Static entrance
	 */
	protected static $ENTRANCE = false;

	/**
	 * <h4>生成输入项</h4>
	 *
	 * @param string $item
	 *        输入项
	 * @param array $setting
	 *        模板参数
	 * @param string $template
	 *        模板
	 * @return string 输入项（HTML）
	 */
	public static function cut($item = null, $setting = null, $template = 'text')
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
	 * @param string $item
	 *        输入项
	 * @param array $setting
	 *        模板参数
	 * @param string $template
	 *        模板
	 * @return string 输入项（HTML）
	 */
	public function entrust($item = null, $setting = null, $template = 'text')
	{
		if ($item == null)
		{
			return null;
		}
		$template = self::pick($template, $this->templates);
		if ($template == null)
		{
			return null;
		}
		### 非数组参数默认作为$content
		if (!is_array($setting))
		{
			$setting = array(
				'content' => "$setting"
			);
		}
		### 匹配检验项
		$event = self::pick('event', $setting);
		if ($event == null)
		{
			$event = "$this->event";
		}
		$check = self::config('Check', 'Event', $event, $item);
		$mode = self::config('Check', 'mode');
		if (preg_match_all($mode, $check, $cases, PREG_SET_ORDER))
		{
			$cases[] = array(
				Check::ITEM => _DEF,
				Check::PARAM => $check
			);
		}
		$this['cases'] = $cases;
		### 填充模板参数
		if ($cases != null)
		{
			$setting['type'] = $this->type();
			$setting['check'] = $this->check();
			$setting['hint'] = $this->hint();
		}
		$setting['event'] = $event;
		$setting['item'] = $item;
		$setting['value'] = $this->value(self::pick('type', $setting), $this->event[$item]);
		$setting['class'] = empty($setting['class']) ? 'input' : $setting['class'] . ' input';
		### 解析模板
		$this->result = Template::cut($template, $setting);
		return $this->result;
	}

	/**
	 * <h4>解析输入值</h4>
	 *
	 * @param string $type
	 *        输入类型
	 * @param string $value
	 *        原始输入值
	 * @return string 输入值
	 */
	protected function value($type, $value)
	{
		$result = self::pick($type, $this->values);
		if ($result == null)
		{
			$result = self::pick($result, $this->values);
		}
		if ($result != null)
		{
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
	protected function type()
	{
		$cases = $this['cases'];
		### 获取输入类型
		$result = null;
		foreach ($cases as $case)
		{
			$item = self::pick(Check::ITEM, $case);
			if (strcasecmp($item, __FUNCTION__) === 0)
			{
				$result = self::pick(Check::PARAM, $case);
				break;
			}
		}
		### 转换输入类型
		$alt = self::pick($result, $this->types);
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
	protected function check()
	{
		$cases = $this['cases'];
		$result = array();
		foreach ($cases as $case)
		{
			$item = self::pick(Check::ITEM, $case);
			$param = self::pick(Check::PARAM, $case);
			$check = self::pick($item, $this->checks);
			if ($check != null)
			{
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
	protected function hint()
	{
		$cases = $this['cases'];
		$result = array();
		foreach ($cases as $case)
		{
			$item = self::pick(Check::ITEM, $case);
			$param = self::pick(Check::PARAM, $case);
			$hint = self::pick($item, $this->hints);
			if ($hint != null)
			{
				$result[] = sprintf($hint, $param);
				continue;
			}
			$hint = self::pick($param, $this->hints);
			if ($hint != null)
			{
				$result[] = $hint;
			}
		}
		return implode(', ', $result);
	}
}
