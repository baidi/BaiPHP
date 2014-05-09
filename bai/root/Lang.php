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
 * <h3>Language work</h3>
 * <p>
 * 转换语言项目到当前语言。
 * </p>
 *
 * @copyright Copyright (C) 2011-2014 Xiao Yang, Bai
 * @author Xiao Yang, Bai
 */
class Lang extends Work
{
	/**
	 * ID: Chinese(simple)
	 */
	const ZH = 'zh_CN';
	/**
	 * ID: English(US)
	 */
	const EN = 'en_US';

	/**
	 * Primary language
	 */
	protected $primary = self::EN;

	/**
	 * Static entrance
	 */
	protected static $ENTRANCE = false;

	/**
	 * <h4>Fetch language item</h4>
	 * <p>
	 * 获取语言项目的当前内容，并即时输出。
	 * </p>
	 *
	 * @param string $item
	 *        item name
	 * @param boolean $print
	 *        whether to print
	 * @return string
	 */
	public static function cut($item = null, $print = true)
	{
		$lang = Lang::access();
		$result = $lang->entrust($item);
		if ($print)
		{
			echo $result;
		}
		return $result;
	}

	/**
	 * <h4>Fetch language item</h4>
	 * <p>
	 * 根据语言项目，获取当前语言的对应内容。
	 * </p>
	 *
	 * @param string $item
	 *        item name
	 * @return string
	 */
	public function entrust($item = null)
	{
		if ($item == null || !is_string($item))
		{
			return '';
		}

		$dic = self::config(__CLASS__, $this->primary);
		$event = self::config(__CLASS__, $this->primary, Bai::EVENT, "$this->event");

		$this->result = self::pick($item, $event);
		if ($this->result === null)
		{
			$this->result = self::pick($item, $dic);
		}
		$this[$item] = $this->result;
		return $this->result;
	}

	/**
	 * <h3>Read runtime item</h3>
	 *
	 * @param string $name
	 *        item name
	 * @return mixed item value
	 */
	public function offsetGet($item)
	{
		if (!isset($this->runtime[$item]))
		{
			$this->runtime[$item] = $this->entrust($item);
		}
		return $this->runtime[$item];
	}

	/**
	 * <h4>Build lang work and load lang</h4>
	 *
	 * @param array $settings
	 *        runtime settings
	 */
	protected function __construct($settings = null)
	{
		parent::__construct($settings);
		$this->load($this->primary);
	}
}
