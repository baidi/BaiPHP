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
 * <h3>Control process</h3>
 * <p>
 * 对目标进行登记、过滤、识别，并对内委托。
 * </p>
 *
 * @copyright Copyright (C) 2011-2014 Xiao Yang, Bai
 * @author Xiao Yang, Bai
 */
class Control extends Process
{
	/**
	 * ID: visit count
	 */
	const COUNT = '_visit_count';
	/**
	 * ID: last time
	 */
	const TIME = '_visit_time';

	/**
	 * IP filters
	 */
	protected $filters = null;
	/**
	 * Visit limits per second
	 */
	protected $limits = 10;
	/**
	 * Restricted period(second)
	 */
	protected $period = 60;

	/**
	 * <h4>Check visitor in</h4>
	 * <p>
	 * Record visitor info: client, server and script.
	 * </p>
	 *
	 * @return void
	 */
	protected function checkin()
	{
		### client info
		$client = array(
			$_SERVER['REMOTE_HOST'],
			$_SERVER['REMOTE_ADDR'],
			$_SERVER['REMOTE_PORT'],
			self::pick('HTTP_REFERER', $_SERVER),
			$_SERVER['HTTP_USER_AGENT']
		);
		Log::logf('client', $client, __CLASS__);

		### server info
		$server = array(
			$_SERVER['SERVER_NAME'],
			$_SERVER['SERVER_ADDR'],
			$_SERVER['SERVER_PORT'],
			$_SERVER['REQUEST_METHOD'],
			$_SERVER['REQUEST_URI'],
			$_SERVER['SERVER_SOFTWARE']
		);
		Log::logf('server', $server, __CLASS__);

		### script info
		$script = array(
			$_SERVER['SCRIPT_FILENAME'],
			$_SERVER['QUERY_STRING']
		);
		Log::logf('script', $script, __CLASS__);
	}

	/**
	 * <h4>Filter client IP</h4>
	 *
	 * @return boolean
	 */
	protected function filter()
	{
		if ($this->filters == null || !is_array($this->filters))
		{
			return true;
		}

		### filter client IP
		$result = true;
		foreach ($this->filters as $item => $mode)
		{
			if (preg_match($item, $_SERVER['REMOTE_ADDR']))
			{
				$result = $mode;
			}
		}
		if (!$result)
		{
			$this->message = Log::logs(__FUNCTION__, __CLASS__, Log::ERROR);
		}
		return $result;
	}

	/**
	 * <h4>Limit visit count per second</h4>
	 *
	 * @return boolean
	 */
	protected function limit()
	{
		if ($this->limits <= 0)
		{
			return true;
		}

		if (session_id() == null)
		{
			session_id($_SERVER['SERVER_NAME']);
			session_start();
		}

		### current visit count
		$count = (int) self::pick(self::COUNT, $_SESSION);
		if ($count >= $this->limits)
		{
			$this->message = Log::logf(__FUNCTION__, $this->period, __CLASS__, Log::ERROR);
			$_SESSION[self::TIME] = $_SERVER['REQUEST_TIME'] + $this->period;
			session_commit();
			return false;
		}

		### last visit time
		$time = self::pick(self::TIME, $_SESSION);
		if ($time != $_SERVER['REQUEST_TIME'])
		{
			### 重新统计
			$_SESSION[self::TIME] = $_SERVER['REQUEST_TIME'];
			$_SESSION[self::COUNT] = 1;
			session_commit();
			return true;
		}

		### 更新访问次数
		$_SESSION[self::COUNT] = $count + 1;
		session_commit();
		return true;
	}
}
?>
