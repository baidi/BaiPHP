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
 * <h3>App event</h3>
 * <p>
 * 目标主体，收集并提供目标数据，以描述目标。
 * </p>
 *
 * @copyright Copyright (C) 2011-2014 Xiao Yang, Bai
 * @author Xiao Yang, Bai
 */
class Event extends Bai
{
	/**
	 * Event name
	 */
	protected $name = 'index';
	/**
	 * Event base
	 */
	protected $base = null;
	/**
	 * Anchor class
	 */
	protected $anchor = null;
	/**
	 * Data filters
	 */
	protected $filters = null;

	/**
	 * <h4>Entrust event</h4>
	 *
	 * @param array $settings
	 *        runtime settings
	 * @return mixed response
	 */
	public function entrust($settings = null)
	{
		$event = $this->base . $this->name;
		Log::logf(__FUNCTION__, $event, __CLASS__);
		Log::logf('start', date('Y-m-d H:m:s.B', _START), __CLASS__);
		$this->result = $this->run($settings);
		Log::logf('close', microtime(true) - _START, __CLASS__);
		Log::logf('deliver', $event, __CLASS__);
		return $this->result;
	}

	/**
	 * <h4>Filter client datas</h4>
	 *
	 * @param string $datas
	 *        client datas
	 * @return mixed filtered datas
	 */
	protected function filter($datas = null)
	{
		if ($datas == null || $this->filters == null || !is_array($this->filters))
		{
			return $datas;
		}
		return preg_replace(array_keys($this->filters), array_values($this->filters), $datas);
	}

	public static function session($item = null, $value = null)
	{
		if ($item == null)
		{
			return false;
		}

		if (session_id() == null)
		{
			session_id($_SERVER['SERVER_NAME']);
			session_start();
		}
		$_SESSION[$item] = $value;
		session_commit();
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
		if (!$this->offsetExists($item))
		{
			$this->runtime[$item] = isset($this->$item) ? $this->$item : '';
		}
		return $this->runtime[$item];
	}

	public function __toString()
	{
		return $this->name;
	}

	/**
	 * <h4>Build event</h4>
	 * <p>
	 * 根据$_SESSION、$_GET、$_POST、预置数据、自定义数据构建当前目标。
	 * 数据优先级依次提升，但预置数据中的当前事项和服务路径会被提交的数据覆盖。
	 * </p>
	 *
	 * @param array $settings
	 *        runtime settingss
	 */
	public function __construct($settings = 'config.php')
	{
		### load configs
		$ext = self::config(_APP, Bai::ROOT);
		foreach ((array) $settings as $item)
		{
			$this->load($item, $ext);
		}
		$this->fit(self::config(__CLASS__), $this, 1);

		### start session
		if (session_id() == null)
		{
			session_id($_SERVER['SERVER_NAME']);
			session_start();
		}
		### apply client datas
		$this->fit($_SESSION, $this->runtime);
		$this->fit($this->filter($_COOKIE), $this->runtime);
		$this->fit($this->filter($_GET), $this->runtime);
		$this->fit($this->filter($_POST), $this->runtime);
		session_commit();

		### apply event name
		$name = $this['event'];
		if ($name == null)
		{
			$name = self::config(_APP, Bai::EVENT);
		}
		if ($name != null)
		{
			$this->name = $name;
		}

		### apply custom base
		$base = $this['base'];
		if ($base == null)
		{
			$base = self::config(_APP, Bai::BASE);
			$this->base = $base;
		}
		if ($base != null)
		{
			$this->base = $base;
		}

		$this->event = $this;
	}
}
