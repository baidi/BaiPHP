<?php
/**
 * <b>BaiPHP（简单PHP）开发框架</b>
 * @author		白晓阳
 * @copyright	Copyright (c) 2011 - 2012, 白晓阳
 * @link		http://dacbe.com
 * @version     V1.0.0 2012/03/31 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 */

/**
 * <h2>BaiPHP（简单PHP）开发框架</h2>
 * <h3>日志工场</h3>
 * <p>
 * 生成日志信息并记录日志
 * </p>
 * @see Work
 * @author 白晓阳
 */
class Log extends Work
{
	/** 日志级别: 致命级 */
	const L_FATAL     = 1;
	/** 日志级别: 错误级 */
	const L_ERROR     = 2;
	/** 日志级别: 异常级 */
	const L_EXCEPTION = 4;
	/** 日志级别: 警告级 */
	const L_WARING    = 8;
	/** 日志级别: 提示级 */
	const L_NOTICE    = 16;
	/** 日志级别: 信息级 */
	const L_INFO      = 32;
	/** 日志级别: 未知级 */
	const L_UNKNOWN   = 64;
	/** 日志级别: 调试级 */
	const L_DEBUG     = 128;
	/** 日志级别: 性能级 */
	const L_PERFORM   = 256;

	/** 日志工场静态入口 */
	static private $ACCESS = null;

	/** 日志级别信息 */
	protected $levelInfo = array
	(
			self::L_FATAL     => ' [致命] ',
			self::L_ERROR     => ' [错误] ',
			self::L_EXCEPTION => ' [异常] ',
			self::L_WARING    => ' [警告] ',
			self::L_NOTICE    => ' [提示] ',
			self::L_INFO      => ' [信息] ',
			self::L_UNKNOWN   => ' [未知] ',
			self::L_DEBUG     => ' [调试] ',
			self::L_PERFORM   => ' [性能] ',
	);
	/** 预设日志级别 */
	protected $level = self::L_INFO;

	/**
	 * <b>获取日志工场入口</b><br/>
	 * 该入口是静态单一入口
	 *
	 * @param array $preset 预置日志信息
	 * @param integer $level 预置日志级别
	 *
	 * @return Log 日志工场
	 */
	static public function access(array $preset = null, $level = null)
	{
		if (! self::$ACCESS)
		{
			self::$ACCESS = new Log($preset, $level);
		}
		return self::$ACCESS;
	}

	/**
	 * <b>记录无参数日志</b><br/>
	 * 
	 * @param string $item 日志项目
	 * @param string $type 日志类别
	 * @param integer $level 日志级别
	 *
	 * @return string 日志信息
	 */
	static public function logs($item, $type = null, $level = Log::L_INFO)
	{
		$log = Log::access();
		return $log->entrust($item, $type, null, $level);
	}

	/**
	 * <b>记录带参数日志</b><br/>
	 *
	 * @param string $item 日志项目
	 * @param mixed $params 日志参数
	 * @param string $type 日志类别
	 * @param integer $level 日志级别
	 *
	 * @return string 日志信息
	 */
	static public function logf($item, $params = null, $type = null, $level = Log::L_INFO)
	{
		$log = Log::access();
		return $log->entrust($item, $type, $params, $level);
	}

	/**
	 * <b>获取日志信息并记录日志</b><br/>
	 * 日志记录会根据日志级别进行过滤，但日志信息总是会返回<br/>
	 * 如果没有日志类别，则作为即时日志直接输出<br/>
	 *
	 * @param string $item 日志项目
	 * @param string $type 日志类别
	 * @param mixed $params 日志参数
	 * @param integer $level 日志级别
	 *
	 * @return string 日志信息
	 */
	public function entrust($item = null, $type = null, $params = null, $level = self::L_INFO)
	{
		if ($item == null)
		{
			return null;
		}
		if ($type == null)
		{
			### 直接信息直接输出
			$message = $item;
		}
		else
		{
			### 获取日志信息
			$message = $this->fetch($item, $type);
		}
		### 格式化日志信息
		if ($params !== null)
		{
			$message = $this->format($message, $params);
		}
		$this->write($message, $level);
		return $message;
	}

	/**
	 * <b>获取日志信息</b><br/>
	 * 从预置日志信息中读取日志类别下的日志项目内容
	 *
	 * @param string $item 日志项目
	 * @param string $type 日志类别
	 *
	 * @return string 日志信息
	 */
	protected function fetch($item, $type)
	{
		### 如果日志类别不存在，则直接读取日志项目
		if (empty($this->preset[$type]))
		{
			if (empty($this->preset[$item]))
			{
				### 如果日志项目也不存在，则返回默认日志
				return sprintf($this->preset[_DEFAULT], $type);
			}
			return $this->preset[$item];
		}
		### 读取日志类别下的日志项目
		if (empty($this->preset[$type][$item]))
		{
			return sprintf($this->preset[$type][_DEFAULT], $item);
		}
		return $this->preset[$type][$item];
	}

	/**
	 * <b>格式化日志信息</b><br/>
	 * 为日志信息格式化参数
	 *
	 * @param string $message 日志信息
	 * @param mixed $params 日志参数
	 *
	 * @return 日志信息
	 */
	protected function format($message, $params)
	{
		if (! is_array($params))
		{
			return sprintf($message, $params);
		}
		### 日志参数为数组
		array_unshift($params, $message);
		$message = call_user_func_array('sprintf', $params);
		return $message;
	}

	/**
	 * <b>记录日志并处理日志文件</b><br/>
	 *
	 * @param string $message 日志信息
	 * @param integer $level 日志级别
	 *
	 * @return bool
	 */
	protected function write($message, $level)
	{
		if ($level > $this->level)
		{
			return false;
		}
		$message = date('[Y-m-d H:i:s]').$this->levelInfo[$level].$message."\r\n";
		$filename = _LOG.'log-'.date('Y-m-d').'.log';
		return error_log($message, 3, $filename);
	}

	/**
	 * <b>日志工场初始化</b><br/>
	 * 初始化日志信息和日志级别
	 *
	 * @param array $preset 预置日志信息
	 * @param integer $level 预置日志级别
	 *
	 * @return void
	 */
	private function __construct(array $preset, $level = null)
	{
		if ($preset)
		{
			$this->preset = $preset;
		}
		if (array_key_exists($level, $this->levelInfo))
		{
			$this->level = $level;
		}
	}
}
?>
