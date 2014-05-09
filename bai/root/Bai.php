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
 * <h3>App base class</h3>
 * <p>
 * Define global basic ids, single entrusting entrance and common methods.
 * All others must entend from it.
 * </p>
 *
 * @copyright Copyright (C) 2011-2014 Xiao Yang, Bai
 * @author Xiao Yang, Bai
 */
abstract class Bai implements ArrayAccess
{
	/**
	 * ID: App base
	 */
	const APP = 'App';
	/**
	 * ID: Custom base
	 */
	const BASE = 'Base';
	/**
	 * ID: Runtime base
	 */
	const RUNTIME = 'Runtime';
	/**
	 * ID: Root folder, under base
	 */
	const ROOT = 'Root';
	/**
	 * ID: Ext folder, under base
	 */
	const EXT = 'Ext';

	/**
	 * ID: Bai
	 */
	const BAI = 'Bai';
	/**
	 * ID: App event
	 */
	const EVENT = 'Event';
	/**
	 * ID: App service
	 */
	const SERVICE = 'Service';
	/**
	 * ID: App process
	 */
	const PROCESS = 'Process';
	/**
	 * ID: App work
	 */
	const WORK = 'Work';

	/**
	 * ID: Debug mode
	 */
	const DEBUG = 'Debug';
	/**
	 * ID: Nil
	 */
	const NIL = '_NIL';
	/**
	 * ID: End
	 */
	const END = '_END';
	/**
	 * ID: Flush
	 */
	const FLUSH = '_FLUSH';

	/**
	 * Current event
	 */
	protected $event = null;
	/**
	 * Class specified config
	 */
	protected $config = array();
	/**
	 * Runtime datas
	 */
	protected $runtime = array();
	/**
	 * Process message
	 */
	protected $message = null;
	/**
	 * Process result
	 */
	protected $result = null;

	/**
	 * <h4>Read global config</h4>
	 * <p>
	 * According to arguements, read global config item by item.
	 * If no arguements or any invalid item, return null as default.
	 * </p>
	 *
	 * @param string $item...
	 *        item name...
	 * @return mixed last item value
	 */
	public static function config()
	{
		$config = $GLOBALS[__FUNCTION__];
		foreach (func_get_args() as $item)
		{
			$item = "$item";
			if (!is_array($config) || !isset($config[$item]))
			{
				return null;
			}
			$config = $config[$item];
		}
		return $config;
	}

	/**
	 * <h4>Pick out an item</h4>
	 * <p>
	 * Pick out an item from source array/object.
	 * If invalid source or item name, return null as default.
	 * </p>
	 *
	 * @param string $item
	 *        item name
	 * @param array $source
	 *        source array/object
	 * @param bool $print
	 *        print or not
	 * @return mixed item value
	 */
	public static function pick($item = null, $source = null, $print = false)
	{
		if ($item == null || $source == null)
		{
			return null;
		}
		$item = "$item";

		### pick out specified item from source array/object
		if (is_array($source) && isset($source[$item]))
		{
			if ($print)
			{
				echo $source[$item];
			}
			return $source[$item];
		}
		if (is_object($source) && isset($source->$item))
		{
			if ($print)
			{
				echo $source->$item;
			}
			return $source->$item;
		}

		return null;
	}

	/**
	 * <h4>Get static entrance</h4>
	 *
	 * @param array $settings
	 *        runtime settings
	 * @return Bai
	 */
	public static function access($settings = null)
	{
		if (!isset(static::$ENTRANCE))
		{
			return new static($settings);
		}

		if ($settings != null || static::$ENTRANCE == null)
		{
			static::$ENTRANCE = new static($settings);
		}
		return static::$ENTRANCE;
	}

	/**
	 * <h4>Build class instance</h4>
	 * <p>
	 * Check class name and build it.
	 * </p>
	 *
	 * @param string $class
	 *        class name
	 * @param array $settings
	 *        runtime settings
	 * @return mixed class instance or null
	 */
	public static function build($class = null, $settings = null)
	{
		if ($class == null || !is_string($class))
		{
			return null;
		}

		### check whether class exists(prefixed by event name)
		$event = ucfirst((string) $GLOBALS['event']);
		if (class_exists($event . $class))
		{
			$class = $event . $class;
		} else if (!class_exists($class))
		{
			$message = Log::logf(__FUNCTION__, $class, __CLASS__, Log::ERROR);
			trigger_error($message, E_USER_ERROR);
			return null;
		}

		### build instance
		return $class::access($settings);
	}

	/**
	 * <h4>Build url</h4>
	 * <p>
	 * Build url according to event name and custom base.
	 * </p>
	 *
	 * @param string $event
	 *        event name
	 * @param string $base
	 *        custom base
	 * @param string $settings
	 *        runtime settings
	 * @return string URL
	 */
	public static function url($event = null, $base = null, $settings = null)
	{
		### generate url params
		$params = array();
		if ($event != null)
		{
			$params[] = lcfirst(self::EVENT) . '=' . $event;
		}
		if ($base != null)
		{
			$params[] = lcfirst(self::BASE) . '=' . $base;
		}
		if ($settings != null)
		{
			foreach ((array) $settings as $item => $value)
			{
				if (is_string($item))
				{
					$params[] = $item . '=' . $value;
				}
				if (is_int($item) && $value != null)
				{
					$params[] = $value;
				}
			}
		}

		### build url
		$url = _WEB;
		if ($params)
		{
			$url .= '?' . implode('&', $params);
		}
		return $url;
	}

	/**
	 * <h4>Entrust event</h4>
	 * <p>
	 * Entrust event to this and expect a response.
	 * Normally, it's the only non-static entrance method,
	 * all other non-static methods is under this one and non-public.
	 * </p>
	 *
	 * @param array $settings
	 *        runtime settings
	 * @return mixed response
	 */
	public function entrust($settings = null)
	{
		return $this->run($settings);
	}

	/**
	 * <h4>Process event</h4>
	 * <p>
	 * Deal with event according to preseted process, which is defined under
	 * $config[self::PROCESS], and deliver response.
	 * If no process preseted, find out one parent by parent.
	 * </p>
	 *
	 * @param array $settings
	 *        runtime settings
	 * @return mixed response
	 */
	protected function run($settings = null)
	{
		### read process preseted
		$parent = $class = get_class($this);
		do
		{
			$process = self::config(self::PROCESS, $parent);
		} while (($process == null || !is_array($process)) && ($parent = get_parent_class($parent)));

		### fit runtime process
		$this->fit($settings, $process);
		if ($process == null || !is_array($process))
		{
			return true;
		}

		### run process
		$jump = null;
		foreach ($process as $item => $mode)
		{
			if ($mode === null || ($jump != null && $jump !== $item))
			{
				continue;
			}

			if (method_exists($this, $item))
			{
				### run self method
				Log::logf(__FUNCTION__, array($class,$item), __CLASS__);
				$this->result = $this->$item();
			} else
			{
				### entrust to others
				$entrustee = self::build($item);
				Log::logf('entrust', "$entrustee", __CLASS__);
				$this->result = $entrustee->entrust();
				$this->message = $entrustee->message;
			}

			### if no error
			if ($this->message == null)
			{
				if ($mode === false)
				{
					break;
				}
				$jump = null;
				continue;
			}

			if ($this->message == self::END)
			{
				return $this->result;
			}

			### if error occurs
			$this->event->message = $this->message;
			$this->event->anchor = $this;
			if (is_string($mode) && $mode)
			{
				$jump = $mode;
				continue;
			}

			### default error handler
			$jump = 'error';
		}

		return $this->result;
	}

	/**
	 * <h4>Deal with error</h4>
	 * <p>
	 * Deal with error.
	 * </p>
	 *
	 * @return string error response
	 */
	protected function error()
	{
		$this->message = null;
		return json_encode(array(
			'status' => false,
			'message' => $this->event->message
		));
	}

	/**
	 * <h4>Fit source array</h4>
	 * <p>
	 * Fit source array into master array/object.
	 * If no master spectified, deal this as master.
	 * There are 3 fitting modes:
	 * 0: fit non-null items from source or non-setted items in master(default)
	 * 1: fit all items
	 * -1: fit non-setted items in master
	 * </p>
	 *
	 * @param array $source
	 *        source array
	 * @param array $master
	 *        master array/object
	 * @param int $mode
	 *        0: non-null/non-setted items(default)
	 *        1: all items
	 *        -1: non-setted items
	 * @return bool fitted or not
	 */
	protected function fit($source = null, &$master = self::NIL, $mode = 0)
	{
		if ($source == null || !is_array($source))
		{
			return false;
		}
		if ($master === self::NIL)
		{
			$master = $this;
		}

		### fit source items into master array
		if (is_array($master))
		{
			foreach ($source as $item => $value)
			{
				if (isset($master[$item]) && is_array($master[$item]))
				{
					$this->fit($value, $master[$item]);
					continue;
				}
				if ($mode === 0 && ($value !== null || !isset($master[$item])) || $mode > 0 ||
						 $mode < 0 && !isset($master[$item]))
				{
					$master[$item] = $value;
				}
			}
			return true;
		}

		### fit source items into master object
		if (is_object($master))
		{
			foreach ($source as $item => $value)
			{
				if ($mode === 0 && ($value !== null || !isset($master->$item)) || $mode > 0 ||
						 $mode < 0 && !isset($master->$item))
				{
					$master->$item = $value;
				}
			}
			return true;
		}

		$master = $source;
		return false;
	}

	/**
	 * <h4>Locate item path</h4>
	 * <p>
	 * Locate item path relative to local path.
	 * By default, ext path is class name.
	 * </p>
	 *
	 * @param string $item
	 *        item name
	 * @param string $ext
	 *        ext path
	 * @return array item pathes, two at most
	 *         Bai::APP: path under app base
	 *         Bai::BASE: path under custom base
	 */
	protected function locate($item = null, $ext = null)
	{
		if ($item == null)
		{
			return null;
		}
		$item = "$item";

		### by default, ext path is class name.
		if ($ext == null)
		{
			$ext = get_class($this) . _DIR;
		}
		$ext = "$ext";
		if (substr($ext, -1) !== _DIR)
		{
			$ext .= _DIR;
		}

		### base path
		$config = self::config(_APP);
		$app = $config[self::APP] . $ext . $item;
		$base = $config[self::BASE] . $ext . $item;

		$location = array();
		if (is_file(_LOCAL . $app))
		{
			$location[self::APP] = $app;
		}
		if (is_file(_LOCAL . $base))
		{
			$location[self::BASE] = $base;
		}
		return $location;
	}

	/**
	 * <h4>Load item</h4>
	 * <p>
	 * Locate item and load it.
	 * </p>
	 *
	 * @param string $item
	 *        item name
	 * @param string $ext
	 *        ext path
	 * @param bool $mode
	 *        loading mode
	 *        0: load custom item if exists or app item
	 *        1: load both, app item in advance
	 * @return string loaded content
	 */
	protected function load($item = null, $ext = null, $mode = 0)
	{
		if ($item == null || !is_string($item))
		{
			return null;
		}
		### add ext to item
		if (strcasecmp(substr($item, -strlen(_EXT)), _EXT) != 0)
		{
			$item .= _EXT;
		}

		$location = $this->locate($item, $ext);
		$app = self::pick(self::APP, $location);
		$base = self::pick(self::BASE, $location);

		### if loading both, app item is in advance
		if ($mode == 0)
		{
			ob_start();
			if ($app != null)
			{
				include _LOCAL . $app;
			}
			if ($base != null)
			{
				include _LOCAL . $base;
			}
			return ob_get_clean();
		}

		### if loading single, custom item is in advance
		if ($base != null)
		{
			ob_start();
			include _LOCAL . $base;
			return ob_get_clean();
		}
		if ($app != null)
		{
			ob_start();
			include _LOCAL . $app;
			return ob_get_clean();
		}
		return null;
	}

	/**
	 * <h3>Check if runtime item exists</h3>
	 *
	 * @param string $name
	 *        item name
	 * @return bool true if exists or false
	 */
	public function offsetExists($name)
	{
		return isset($this->runtime[$name]);
	}

	/**
	 * <h3>Read runtime item</h3>
	 *
	 * @param string $name
	 *        item name
	 * @return mixed item value
	 */
	public function offsetGet($name)
	{
		if (!$this->offsetExists($name))
		{
			$this->runtime[$name] = '';
		}
		return $this->runtime[$name];
	}

	/**
	 * <h3>Set runtime item</h3>
	 *
	 * @param string $name
	 *        item name
	 * @param mixed $value
	 *        item value
	 * @return void
	 */
	public function offsetSet($name, $value)
	{
		$this->runtime[$name] = $value;
	}

	/**
	 * <h3>Unset runtime item</h3>
	 *
	 * @param string $name
	 *        item name
	 * @return void
	 */
	public function offsetUnset($name)
	{
		unset($this->runtime[$name]);
	}

	public function __get($item)
	{
		Log::logf(__FUNCTION__, array(get_class($this), $item), __CLASS__, Log::NOTICE);
		return $this->$item = null;
	}

	public function __call($name, $params)
	{
		Log::logf(__FUNCTION__, array(get_class($this), $name), __CLASS__, Log::WARING);
		return null;
	}

	public function __toString()
	{
		return get_class($this);
	}

	/**
	 * <h4>Initialize class</h4>
	 * <p>
	 * Apply presets and runtime settings.
	 * </p>
	 *
	 * @param array $settings
	 *        runtime settings
	 * @return void
	 */
	protected function __construct($settings = null)
	{
		$this->event = $GLOBALS['event'];

		### apply presets and runtime settings
		$class = get_class($this);
		$this->fit(self::config($class), $this->config);
		$this->fit($settings, $this->config);
		$this->fit($this->config);
	}
}
