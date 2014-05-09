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
 * <h3>Log work</h3>
 * <p>
 * Fetch log message and record it.
 * </p>
 *
 * @copyright Copyright (C) 2011-2014 Xiao Yang, Bai
 * @author Xiao Yang, Bai
 */
class Log extends Work
{
	/**
	 * Log level: fatal
	 */
	const FATAL = 1;
	/**
	 * Log level: error
	 */
	const ERROR = 2;
	/**
	 * Log level: exception
	 */
	const EXCEPTION = 4;
	/**
	 * Log level: warning
	 */
	const WARING = 8;
	/**
	 * Log level: notice
	 */
	const NOTICE = 16;
	/**
	 * Log level: info
	 */
	const INFO = 32;
	/**
	 * Log level: unknown
	 */
	const UNKNOWN = 64;
	/**
	 * Log level: all
	 */
	const ALL = 127;
	/**
	 * Log level: debug
	 */
	const DEBUG = 128;
	/**
	 * Log level: performance
	 */
	const PERFORMANCE = 256;

	/**
	 * Log level
	 */
	protected $level = self::ALL;
	/**
	 * Level names
	 */
	protected $names = null;
	/**
	 * Log buffer
	 */
	protected $buffer = array();
	/**
	 * Buffer size(row)
	 */
	protected $size = 512;
	/**
	 * Message dictionary
	 */
	protected $dic = null;
	/**
	 * Ending of message
	 */
	protected $ending = "\n";

	/**
	 * Log work static ENTRANCE
	 */
	protected static $ENTRANCE = false;

	/**
	 * <h4>Record simple message</h4>
	 *
	 * @param string $item
	 *        message item in dictionary
	 * @param string $type
	 *        message type in dictionary
	 * @param integer $level
	 *        message level
	 * @return string log message
	 */
	public static function logs($item = null, $type = null, $level = self::INFO)
	{
		$log = Log::access();
		return $log->entrust($item, $type, null, $level);
	}

	/**
	 * <h4>Record message with format params</h4>
	 *
	 * @param string $item
	 *        message item in dictionary
	 * @param mixed $params
	 *        message params
	 * @param string $type
	 *        message type in dictionary
	 * @param integer $level
	 *        message level
	 * @return string log message
	 */
	public static function logf($item = null, $params = null, $type = null, $level = self::INFO)
	{
		$log = Log::access();
		return $log->entrust($item, $type, $params, $level);
	}

	/**
	 * <h4>Fetch and record log message</h4>
	 * <p>
	 * Fetch a message by specified item and type from dictionary,
	 * and record to log file if specified level is under log level.
	 * If no item specified, return null as default.
	 * If flushing, flush log buffer and return null as default.
	 * </p>
	 *
	 * @param string $item
	 *        message item in dictionary
	 * @param string $type
	 *        message type in dictionary
	 * @param mixed $params
	 *        message params
	 * @param integer $level
	 *        message level
	 * @return string log message
	 */
	public function entrust($item = null, $type = null, $params = null, $level = self::INFO)
	{
		### no item specified
		if ($item == null)
		{
			return null;
		}

		### flush log buffer
		if ($item === Bai::FLUSH)
		{
			$this->flush();
			return null;
		}

		### set up runtime params
		$this['item'] = "$item";
		$this['type'] = $type;
		$this['params'] = $params;
		$this['level'] = $level;

		### fetch and record log message
		$this->result = $this->fetch();
		if ($this->result != null)
		{
			$this->result = $this->format();
			$this->result = $this->record();
		}
		return $this->result;
	}

	/**
	 * <h4>Fetch log message</h4>
	 * <p>
	 * Fetch message from dictionary, specified by type and item.
	 * If no type specified, deal item as immediate message.
	 * </p>
	 *
	 * @return string log message
	 */
	protected function fetch()
	{
		$type = $this['type'];
		$item = $this['item'];

		### immediate message
		if ($type == null)
		{
			return $item;
		}

		### fetch message from dictoinary
		$message = self::pick($type, $this->dic);
		$message = self::pick($item, $message);
		return $message;
	}

	/**
	 * <h4>Format log message</h4>
	 * <p>
	 * Format message with params.
	 * </p>
	 *
	 * @return string log message
	 */
	protected function format()
	{
		$params = $this['params'];

		### no params specified
		if ($params == null)
		{
			return $this->result;
		}

		### single param
		if (!is_array($params))
		{
			return sprintf($this->result, $params);
		}

		### array of params
		array_unshift($params, $this->result);
		return call_user_func_array('sprintf', $params);
	}

	/**
	 * <h4>Record log message</h4>
	 * <p>
	 * Record message to log buffer, and flush buffer when full.
	 * If message level is under log level, write nothing.
	 * </p>
	 *
	 * @return string log message
	 */
	protected function record()
	{
		$level = $this['level'];

		### level filter
		if (($level & $this->level) == 0)
		{
			return $this->result;
		}

		### record log message
		$rank = self::pick($level, $this->names);
		if ($rank == null)
		{
			$rank = self::pick(self::UNKNOWN, $this->names);
		}
		$this->buffer[] = date('[Y-m-d H:i:s]') . $rank . $this->result . $this->ending;
		if (count($this->buffer) >= $this->size)
		{
			$this->flush();
		}
		return $this->result;
	}

	/**
	 * <h4>Flush log buffer</h4>
	 * <p>
	 * Flush log buffer to log file.
	 * </p>
	 *
	 * @return string void
	 */
	protected function flush()
	{
		$filename = _LOCAL . $this->place . _APP . _DEF . date('Y-m-d') . '.log';
		file_put_contents($filename, $this->buffer, FILE_APPEND);
		$this->buffer = array();
	}

	/**
	 * <h4>Destruct log work</h4>
	 * <p>
	 * 输出缓存的日志
	 * </p>
	 */
	public function __destruct()
	{
		$this->flush();
	}
}
?>
