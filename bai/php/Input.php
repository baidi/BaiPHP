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
 * <h3>输入工场</h3>
 * <p>
 * 生成输入项（input、textarea）。
 * </p>
 * @author 白晓阳
 */
class Input extends Work
{
	/** 输入工场静态入口 */
	static private $ACCESS = null;

	/** 首选项 */
	protected $primary = 'text';

	/**
	 * <h4>获取输入工场入口</h4>
	 * @param array $setting 自定义配置
	 * @return Style 输入工场
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
	 * <h4>生成输入项</h4>
	 * <p>
	 * 生成输入项。
	 * </p>
	 * @param array $item 输入项
	 * @return string 输入项（HTML）
	 */
	public function entrust($item = null)
	{
		if ($item == null || ! is_string($item))
		{
			return null;
		}
		$event = $this->target[self::EVENT];
		$this->runtime['event'] = $event;
		$this->runtime['item']  = $item;
		$this->runtime['type']  = $this->type();
		$this->runtime['value'] = $this->value();
		$this->runtime['check'] = $this->check();
		$this->runtime['hint']  = $this->hint();
		$this->result = $this->format();
		return $this->result;
	}

	protected function type()
	{
		$event = $this->pick('event', $this->runtime);
		$item  = $this->pick('item',  $this->runtime);
		$check = $this->config(self::CHECK, $event, $item);
		$preset = $this->pick(__FUNCTION__, $this->preset);
		$pattern = $this->pick(_DEFAULT, $preset);
		if (preg_match($pattern, $check, $type))
		{
			$type = $type[1];
		}
		if ($type == null)
		{
			$type = $this->primary;
		}
		$alt = $this->pick($type, $preset);
		return ($alt == null) ? $type : $alt;
	}

	protected function value()
	{
		$event = $this->pick('event', $this->runtime);
		$item  = $this->pick('item',  $this->runtime);
		$result = $this->pick($item, $this->target[$event]);
		if ($result)
		{
				
		}
	}

	protected function check()
	{
		$preset = $this->pick(__FUNCTION__, $this->preset);
		$check = $this->pick('check', $this->runtime);
		preg_match_all($preset, $check, $cases);
		$this->runtime['cases'] = $cases;
	}

	protected function hint()
	{
		$check = $this->pick('check', $this->runtime);
		$preset = $this->pick(__FUNCTION__, $this->preset);
		$mode = $this->pick(_DEFAULT, $this->preset);
		preg_match_all($mode, $check, $cases);
		$hints = array();
		foreach ($cases as $case)
		{
			if (isset($preset[$case['item']]))
			{
				$hints[] = $preset[$case['item']];
				continue;
			}
			if (isset($preset[$case['value']]))
			{
				$hints[] = $preset[$case['value']];
			}
		}
		$this->runtime[__FUNCTION__] = implode(', ', $hints);
	}

	protected function format()
	{
		$preset = $this->pick(__FUNCTION__, $this->preset);
		if ($preset == null || ! is_string($preset))
		{
			return null;
		}
		foreach ($this->runtime as $item => $value)
		{
			$preset = str_replace('{$'.$item.'}', $value, $preset);
		}
		$preset = preg_replace('/\{\$[^\}]+\}/', '', $preset);
		return $preset;
	}
}
