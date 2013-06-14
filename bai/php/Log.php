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
 * <h3>日志工场</h3>
 * <p>
 * 根据预置内容生成日志信息并输出。
 * </p>
 * @author 白晓阳
 */
class Log extends Work
{
	/** 日志级别: 致命级 */
	const FATAL     = 1;
	/** 日志级别: 错误级 */
	const ERROR     = 2;
	/** 日志级别: 异常级 */
	const EXCEPTION = 4;
	/** 日志级别: 警告级 */
	const WARING    = 8;
	/** 日志级别: 提示级 */
	const NOTICE    = 16;
	/** 日志级别: 信息级 */
	const INFO      = 32;
	/** 日志级别: 未知级 */
	const UNKNOWN   = 64;
	/** 日志级别: 调试级 */
	const DEBUG     = 128;
	/** 日志级别: 性能级 */
	const PERFORM   = 256;
	/** 日志级别: 全部 */
	const ALL       = 127;

	/** 日志级别 */
	protected $level  = self::ALL;
	/** 日志目录 */
	protected $root   = null;
	/** 日志结束符 */
	protected $ending = "\n";
	/** 日志级别名 */
	protected $ranks  = null;
	/** 日志缓存 */
	protected $buffer = array();
	/** 缓存大小（行） */
	protected $size   = 512;

	/** 日志工场静态入口 */
	private static $ACCESS = null;

	/**
	 * <h4>获取日志工场入口</h4>
	 * @param array $setting 即时配置
	 * @return Log 日志工场
	 */
	public static function access($setting = null)
	{
		if ($setting != null || self::$ACCESS == null) {
			self::$ACCESS = new Log($setting);
		}
		return self::$ACCESS;
	}

	/**
	 * <h4>记录无参数日志</h4>
	 * @param string $item 日志项目
	 * @param string $type 日志类别
	 * @param integer $level 日志级别
	 * @return string 日志信息
	 */
	public static function logs($item = null, $type = null, $level = self::INFO)
	{
		$log = Log::access();
		return $log->entrust($item, $type, null, $level);
	}

	/**
	 * <h4>记录带参数日志</h4>
	 * @param string $item 日志项目
	 * @param mixed $params 日志参数
	 * @param string $type 日志类别
	 * @param integer $level 日志级别
	 * @return string 日志信息
	 */
	public static function logf($item = null, $params = null, $type = null, $level = self::INFO)
	{
		$log = Log::access();
		return $log->entrust($item, $type, $params, $level);
	}

	/**
	 * <h4>获取日志信息并记录日志</h4>
	 * <p>
	 * 日志记录内容根据日志级别进行过滤，但日志信息总是会返回。
	 * 如果未指定日志类别，则作为即时信息直接输出。
	 * </p>
	 * @param string $item 日志项目
	 * @param string $type 日志类别
	 * @param mixed $params 日志参数
	 * @param integer $level 首选级别
	 * @return string 日志信息
	 */
	public function entrust($item = null, $type = null, $params = null, $level = self::INFO)
	{
		if ($item == null) {
			return null;
		}
		### 执行数据
		$this['item']   = "$item";
		$this['type']   = "$type";
		$this['params'] = $params;
		$this['level']  = $level;
		### 处理日志
		$this->result = $this->fetch();
		if ($this->result != null) {
			$this->result = $this->format();
			$this->result = $this->record();
		}
		return $this->result;
	}

	/**
	 * <h4>读取日志信息</h4>
	 * <p>
	 * 从预置日志信息中读取日志类别下的日志项目内容。
	 * </p>
	 * @return string 日志信息
	 */
	protected function fetch()
	{
		### 执行数据
		$item = $this['item'];
		$type = $this['type'];
		### 即时日志
		if ($type == null) {
			return $item;
		}
		### 读取预置日志
		$message = $this->pick($type, $this->preset);
		$message = $this->pick($item, $message);
		return $message;
	}

	/**
	 * <h4>格式化日志信息</h4>
	 * <p>
	 * 为日志信息格式化参数。
	 * </p>
	 * @return string 日志信息
	 */
	protected function format()
	{
		### 执行数据
		$params = $this['params'];
		if ($params == null) {
			return $this->result;
		}
		### 单一参数
		if (! is_array($params)) {
			return sprintf($this->result, $params);
		}
		### 数组参数
		array_unshift($params, $this->result);
		return call_user_func_array('sprintf', $params);
	}

	/**
	 * <h4>记录日志到缓存</h4>
	 * <p>
	 * 将日志信息记入日志缓存。
	 * </p>
	 * @return string 日志信息
	 */
	protected function record()
	{
		### 执行数据
		$level = $this['level'];
		if (($level & $this->level) == 0) {
			return $this->result;
		}
		### 记录日志
		$rank = $this->pick($level, $this->ranks);
		if ($rank == null) {
			$rank = $this->pick(self::UNKNOWN, $this->ranks);
		}
		$this->buffer[] = date('[Y-m-d H:i:s]').$rank.$this->result.$this->ending;
		if (count($this->buffer) > $this->size) {
			$this->flush();
		}
		return $this->result;
	}

	/**
	 * <h4>输出日志到文件</h4>
	 * <p>
	 * 将日志信息写入日志文件。
	 * </p>
	 * @return string 日志信息
	 */
	protected function flush()
	{
		$filename = _LOCAL.$this->root._APP._DEF.date('Y-m-d').'.log';
		file_put_contents($filename, $this->buffer, FILE_APPEND);
		$this->buffer = array();
	}

	/**
	 * <h4>构建日志工场</h4>
	 * <p>
	 * 设置日志信息和日志级别
	 * </p>
	 * @param array $setting 即时配置
	 */
	protected function __construct($setting = null)
	{
		parent::__construct($setting);
		### 构建日志目录
		$root = _LOCAL;
		foreach (explode(_DIR, $this->root) as $dir) {
			if ($dir == null || ! is_dir($root.$dir._DIR) && ! mkdir($root.$dir._DIR)) {
				break;
			}
			$root .= $dir._DIR;
		}
		$this->root = substr($root, strlen(_LOCAL));
	}

	/**
	 * <h4>撤销日志工场</h4>
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
