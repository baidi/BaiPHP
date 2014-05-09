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
 * <h3>Check work</h3>
 * <p>
 * Check inputs.
 * </p>
 *
 * @copyright Copyright (C) 2011-2014 Xiao Yang, Bai
 * @author Xiao Yang, Bai
 */
class Check extends Work
{
	/**
	 * ID: check item
	 */
	const ITEM = 1;
	/**
	 * ID: check param
	 */
	const PARAM = 2;

	/**
	 * Charset
	 */
	protected $charset = 'utf-8';
	/**
	 * Gap of params
	 */
	protected $gap = ',';
	/**
	 * Rule mode
	 */
	protected $mode = null;
	/**
	 * Type modes
	 */
	protected $types = null;

	/**
	 * Static entrance
	 */
	protected static $ENTRANCE = null;

	/**
	 * <h4>Check inputs</h4>
	 * <p>
	 * Check cases: required min=9 max=9 type=N function=P
	 * <ul>
	 * <li>required: not empty</li>
	 * <li>min=9: min size</li>
	 * <li>max=9 : max size</li>
	 * <li>type=N : value type</li>
	 * <li>call=P : call function</li>
	 * </ul>
	 * 各规则可以任意组合，规则之间以空格分隔
	 * </p>
	 *
	 * @param array $settings
	 *        runtime settings
	 * @param array $source
	 *        inputs source
	 * @return mixed
	 *         false: pass
	 *         string: error message
	 */
	public function entrust ($settings = null, $source = null)
	{
		if ($this->mode == null || ! is_string($this->mode)) {
			Log::logs('mode', __CLASS__, Log::NOTICE);
			return true;
		}

		### read check form for current event
		$form = self::config(__CLASS__, Bai::EVENT, "$this->event");
		$this->fit($settings, $form);
		if ($form == null || ! is_array($form)) {
			return true;
		}

		if ($source == null) {
			$source = $this->event;
		}

		### check source items
		foreach ($form as $item => $mode) {
			if ($item == null || ! is_string($item) || $mode == null || ! is_string($mode)) {
				continue;
			}
			$this['item'] = $item;
			$this['mode'] = $mode;
			$this['value'] = $source[$item];
			$this->message = $this->checkItem();
			if ($this->message) {
				return false;
			}
		}
		return true;
	}

	/**
	 * <h4>Check one item</h4>
	 *
	 * @return mixed
	 *         false: pass
	 *         string: error message
	 */
	protected function checkItem ()
	{
		$item = $this['item'];
		$mode = $this['mode'];
		$value = $this['value'];

		Log::logf(__FUNCTION__, array($item, $mode), __CLASS__);

		### parse check cases
		if (! preg_match_all($this->mode, $mode, $cases, PREG_SET_ORDER)) {
			return false;
		}

		foreach ($cases as $case) {
			$check = self::pick(self::ITEM, $case);
			if ($check !== 'required' && $value == null) {
				continue;
			}

			$param = self::pick(self::PARAM, $case);
			if ($param === null) {
				$message = $this->$check();
			} else {
				$param = explode($this->gap, $param);
				$message = call_user_func_array(array($this, $check), $param);
			}
			if ($message) {
				return $message;
			}
		}
		return false;
	}

	/**
	 * <h4>Check risk of characters</h4>
	 *
	 * @return mixed
	 *         false: pass
	 *         string: error message
	 */
	protected function risk ()
	{
		$value = $this['value'];
		$mode = self::pick(__FUNCTION__, $this->types);
		if ($value != null && preg_match($mode, $value)) {
			return Log::logs(__FUNCTION__, __CLASS__);
		}
		return false;
	}

	/**
	 * <h4>Non-empty check</h4>
	 *
	 * @return mixed
	 *         false: pass
	 *         string: error message
	 */
	protected function required ()
	{
		$value = $this['value'];
		if ($value == null) {
			return Log::logs(__FUNCTION__, __CLASS__);
		}
		return false;
	}

	/**
	 * <h4>Min size check</h4>
	 *
	 * @param int $size min size
	 * @return mixed
	 *         false: pass
	 *         string: error message
	 */
	protected function min ($size = 0)
	{
		if ($size === null || ! is_numeric($$size) || $size <= 0) {
			Log::logs('config', __CLASS__, Log::NOTICE);
			return false;
		}

		$value = $this['value'];
		if (mb_strlen($value, $this->charset) >= $size) {
			return false;
		}
		return Log::logf(__FUNCTION__, $size, __CLASS__);
	}

	/**
	 * <h4>Max size check</h4>
	 *
	 * @param int $size max size
	 * @param int $decimal float size
	 * @return mixed
	 *         false: pass
	 *         string: error message
	 */
	protected function max ($size = null, $decimal = 0)
	{
		if ($size === null || ! is_numeric($size) || $size <= 0) {
			Log::logs('config', __CLASS__, Log::NOTICE);
			return false;
		}

		$value = $this['value'];
		if (is_numeric($decimal) && $decimal > 0) {
			### check float
			$numbers = explode('.', $value);
			$length = count($numbers);

			if ($length == 1 &&
					 mb_strlen($numbers[0], $this->charset) <= $size - $decimal) {
				return false;
			}

			if ($length == 2 && mb_strlen($numbers[1], $this->charset) <= $decimal
					&& mb_strlen($numbers[0], $this->charset) <= $size - $decimal) {
				return false;
			}

			return Log::logf(__FUNCTION__, $size, __CLASS__);
		}

		if (mb_strlen($value, $this->charset) <= $size) {
			return false;
		}
		return Log::logf(__FUNCTION__, $size, __CLASS__);
	}

	/**
	 * <h4>Type check</h4>
	 * <p>
	 * 根据正则表达式检验输入内容。
	 * </p>
	 *
	 * @param string $type
	 *        data type
	 * @return mixed
	 *         false: pass
	 *         string: error message
	 */
	protected function type ($type = null)
	{
		$value = $this['value'];
		$mode = self::pick($type, $this->types);
		if ($mode == null || preg_match($mode, $value)) {
			$this['type'] = $type;
			return false;
		}
		return Log::logs(__FUNCTION__, __CLASS__);
	}

	/**
	 * <h4>Number range check</h4>
	 *
	 * @param number $min min limit
	 * @param number $max max limit
	 * @return mixed
	 *         false: pass
	 *         string: error message
	 */
	protected function range ($min = null, $max = null)
	{
		if ($min === null || $max === null || ! is_numeric($min) || ! is_numeric($max)) {
			Log::logs('config', __CLASS__, Log::NOTICE);
			return false;
		}

		$value = $this['value'];
		if ($value >= $min && $value <= $max) {
			return false;
		}
		return Log::logf(__FUNCTION__, array($min, $max), __CLASS__);
	}

	/**
	 * <h4>Enum item check</h4>
	 *
	 * @return mixed
	 *         false: pass
	 *         string: error message
	 */
	protected function enum ()
	{
		$options = func_get_args();
		if ($options == null) {
			Log::logs('config', __CLASS__, Log::NOTICE);
			return false;
		}

		$value = $this['value'];
		if (in_array($value, $options) || in_array("'$value'", $options) ||
				 in_array("\"$value\"", $options)) {
			return false;
		}
		return Log::logs(__FUNCTION__, __CLASS__);
	}

	/**
	 * <h4>Set item check</h4>
	 *
	 * @return mixed
	 *         false: pass
	 *         string: error message
	 */
	protected function set ()
	{
		$options = func_get_args();
		if ($options == null) {
			Log::logs('config', __CLASS__, Log::NOTICE);
			return false;
		}

		$values = explode($this->gap, $this['value']);
		foreach ($values as $value) {
			if (in_array($value, $options) || in_array("'$value'", $options) ||
					 in_array("\"$value\"", $options)) {
				continue;
			}
			return Log::logs(__FUNCTION__, __CLASS__);
		}
		return false;
	}

	public function __call ($name, $params)
	{
		if (is_callable($name)) {
			return call_user_func_array($name, $params);
		}
		return Log::logs(__FUNCTION__, __CLASS__);
	}
}
?>
