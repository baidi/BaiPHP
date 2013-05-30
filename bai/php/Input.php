<?php
/**
 * <h2>化简PHP（BaiPHP）开发框架</h2>
 * @link      http://www.baiphp.net
 * @copyright Copyright (c) 2011 - 2012, 白晓阳
 * @author    白晓阳
 * @version   1.0.0 2012/03/31 首版
 *            2.0.0 2012/07/01 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 * <p>欢迎提供各种形式的捐助。任何捐助者自动获得仅限于捐助者自身的商业使用（不包括再发行和再授权）授权。</p>
 */

/**
 * <h2>化简PHP（BaiPHP）开发框架</h2>
 * <h3>输入工场</h3>
 * <p>
 * 生成输入项（input、textarea）。
 * </p>
 * @author 白晓阳
 */
class Input extends Template
{
	/** 首选项 */
	protected $primary = 'input';
	/** 检验匹配式 */
	protected $check = null;
	/** 输入类型 */
	protected $types   = null;
	/** 输入验证 */
	protected $checks  = null;
	/** 输入提示 */
	protected $hints   = null;

	/** 输入工场静态入口 */
	private static $ACCESS = null;

	/**
	 * <h4>获取输入工场入口</h4>
	 * @param array $setting 即时配置
	 * @return Style 输入工场
	 */
	public static function access($setting = null)
	{
		if ($setting != null || self::$ACCESS == null)
		{
			self::$ACCESS = new Input($setting);
		}
		return self::$ACCESS;
	}

	/**
	 * <h4>生成输入项</h4>
	 * <p>
	 * 生成输入项。
	 * </p>
	 * @param string $item 生成项
	 * @param array $setting 模板参数
	 * @return string 输入项（HTML）
	 */
	public function entrust($item = null, $setting = null)
	{
		### 生成项默认为input
		if ($item == null)
		{
			$item = $this->primary;
		}
		### 字符串默认作为$value
		if ($setting != null && is_string($setting))
		{
			$setting = array('value' => $setting);
		}
		if (! is_array($setting))
		{
			$setting = array();
		}
		### 匹配检验项
		$event = $this->target[self::EVENT];
		$check = $this->config(self::CHECK, $event, $item);
		if (preg_match_all($this->check, $check, $cases, PREG_SET_ORDER))
		{
			$cases[_DEF] = $check;
		}
		$this->runtime['cases'] = $cases;
		### 填充模板参数
		$setting['event'] = $event;
		$setting['item']  = $item;
		$setting['value'] = $this->pick($item, $this->target[$event]);
		if ($cases != null)
		{
			$setting['type']  = $this->type();
			$setting['check'] = $this->check();
			$setting['hint']  = $this->hint();
		}
		### 解析模板
		$this->result = parent::entrust($item, $setting);
		return $this->result;
	}

	/**
	 * <h4>解析输入类型</h4>
	 * <p>
	 * 根据检验条件（type=***）解析输入类型。
	 * </p>
	 * @return string 输入类型
	 */
	protected function type()
	{
		$cases = $this->pick('cases', $this->runtime);
		### 获取输入类型
		$result = null;
		foreach ($cases as $case)
		{
			$item  = $this->pick('item',  $case);
			if ($item === __FUNCTION__)
			{
				$result = $this->pick('value', $case);
				break;
			}
		}
		### 转换输入类型
		$alt = $this->pick($result, $this->types);
		return ($alt === null) ? $result : $alt;
	}

	/**
	 * <h4>解析输入验证</h4>
	 * <p>
	 * 根据检验条件（required、max=***）解析输入验证。
	 * required => required="required"
	 * max=9 => maxlength="9"
	 * 全部 => data-check="***"
	 * </p>
	 * @return string 输入验证
	 */
	protected function check()
	{
		$cases = $this->pick('cases', $this->runtime);
		$result = array();
		foreach ($cases as $case)
		{
			$item  = $this->pick('item',  $case);
			$params = $this->pick('params', $case);
			$check = $this->pick($item, $this->checks);
			if ($check != null)
			{
				$result[] = sprintf($check, $params);
			}
		}
		return implode(' ', $result);
	}

	/**
	 * <h4>解析输入提示</h4>
	 * <p>
	 * 根据检验条件解析输入提示。
	 * </p>
	 * @return string 输入提示
	 */
	protected function hint()
	{
		$cases = $this->pick('cases', $this->runtime);
		$result = array();
		foreach ($cases as $case)
		{
			$item  = $this->pick('item',  $case);
			$params = $this->pick('params', $case);
			$hint = $this->pick($item, $this->hints);
			if ($hint != null)
			{
				$result[] = sprintf($hint, $params);
				continue;
			}
			$hint = $this->pick($params, $this->hints);
			if ($hint != null)
			{
				$result[] = $case;
			}
		}
		return implode(', ', $hints);
	}
}
