<?php

### 权限检查
if (! defined('_ISSUE'))
	exit('对不起！请走正门……');

/**
 * <b>Bai日志输出工场：</b>
 * <p>生成日志信息并输出日志</p>
 *
 * @author    白晓阳
 * @copyright Copyright (c) 2011 - 2012, 白晓阳
 * @link       http://www.dacbe.com
 * @version    V1.0.0 2012/03/21 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 */
class Log extends Work
{
	/** 日志级别: 错误级 */
	const L_ERROR     = 1;
	/** 日志级别: 异常级 */
	const L_EXCEPTION = 2;
	/** 日志级别: 警告级 */
	const L_WARING    = 4;
	/** 日志级别: 提示级 */
	const L_NOTICE    = 8;
	/** 日志级别: 信息级 */
	const L_INFO      = 16;
	/** 日志级别: 未知级 */
	const L_UNKNOWN   = 32;
	/** 日志级别: 调试级 */
	const L_DEBUG     = 64;

	/** 日志级别信息 */
	private $levelInfo = array
	(
			self::L_ERROR     => '错误',
			self::L_EXCEPTION => '异常',
			self::L_WARING    => '警告',
			self::L_NOTICE    => '提示',
			self::L_INFO      => '信息',
			self::L_UNKNOWN   => '未知',
			self::L_DEBUG     => '调试'
	);
	/** 预设日志级别 */
	private $level = self::L_INFO;
	/** 日志缓存大小 */
	#private $bufferSize = 1024;
	/** 日志缓存 */
	#private static $buffer = '';
	/** 日志静态入口 */
	private static $ACCESS = null;

	/**
	 * 获取日志入口
	 * @param 预设日志信息： $preset
	 * @param 预设日志级别： $level
	 */
	public static function access($preset = null, $level = null)
	{
		if (! self::$ACCESS)
			self::$ACCESS = new Log($preset, $level);
		return self::$ACCESS;
	}

	/**
	 * 输出日志
	 * @param 日志信息： $message
	 * @param 日志级别： $level
	 */
	public function assign($message, $level = self::L_INFO)
	{
		if ($level > $this->level)
			return;
		if (is_array($message))
			$message = join('|', $message);
		error_log(date("[Y-m-d H:i:s]").' '.$this->levelInfo[$level].' '.$message."\n",
				3, _LOG.'/'.date("Y-m-d").'.log');
		#self::$buffer .= date("[Y-m-d H:i:s]").' '.$this->levelInfo[$level].' '.$message."\n";
		#if (strlen($this->buffer) > $this->bufferSize)
		#{
		#	error_log($this->buffer, 3, _LOG.'/'.date("Y-m-d").'.log');
		#	$this->buffer = '';
		#}
	}

	/**
	 * 生成日志信息
	 * @param 日志项目： $item
	 * @param 日志类别： $type
	 */
	public function message($item, $type = 'Event')
	{
		if (! $item)
			return null;
		if (! $type)
			return empty($this->preset[$item]) ?
					sprintf($this->preset[_DEFAULT], $item) : $this->preset[$item];
		return empty($this->preset[$type][$item]) ?
				sprintf($this->preset[$type][_DEFAULT], $item) : $this->preset[$type][$item];
	}


	/**
	 * 生成日志信息并格式化
	 * @param 日志项目： $item
	 * @param 格式参数： $params
	 * @param 日志类别： $type
	 */
	public function messagef($item, $params, $type = 'Event')
	{
		$message = $this->message($item, $type);
		if ($params && $message)
			return sprintf($message, $params);
		return $message;
	}

	private function __construct($preset, $level = null)
	{
		if (is_numeric($level))
			$this->level = $level;
		if (! array_key_exists($level, $this->levelInfo))
			$level = self::L_UNKNOWN;
		if (is_array($preset))
			$this->preset = $preset;
		#if (! $this->preset) error_log('日志信息为空！');
	}

	#public function __destruct()
	#{
	#	if (self::$buffer)
	#	{
	#		error_log(self::$buffer, 3, _LOG.'/'.date("Y-m-d").'.log');
	#		self::$buffer = '';
	#	}
	#}
}
?>
