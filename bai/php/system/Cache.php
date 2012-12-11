<?php

### 权限检查
if (! defined('_ISSUE'))
	exit('对不起！请走正门……');

/**
 * <b>Bai文件缓存工场：</b>
 * <p>更新文件缓存并提取文件缓存</p>
 *
 * @author    白晓阳
 * @copyright Copyright (c) 2011 - 2012, 白晓阳
 * @link       http://www.dacbe.com
 * @version    V1.0.0 2012/03/21 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 */
class Cache extends Work
{
	/** 日志入口 */
	protected $log = null;
	/** 缓存过期时间 */
	private $timeout = _CACHE_TIMEOUT;
	/** 缓存静态入口 */
	private static $ACCESS = null;

	/**
	 * 获取缓存入口
	 * @param 预设缓存配置： $preset
	 * @param 缓存过期时间： $timeout
	 */
	public static function access($preset = null, $timeout = null)
	{
		if (! self::$ACCESS)
			self::$ACCESS = new Cache($preset, $timeout);
		return self::$ACCESS;
	}

	/**
	 * 更新文件缓存或提取文件缓存
	 * @param 请求事件： $event
	 * @param 文件内容： $file
	 */
	public function assign($event, $file = null)
	{
		if (! defined('_CACHE') || ! $event || ! in_array($event, $this->preset))
			return false;

		$message = $this->log->message(__CLASS__);
		$this->log->assign($message, Log::L_DEBUG);

		if (! $file)
			return $this->fetch($event);
		return $this->push($event, $file);
	}

	/**
	 * 提取文件缓存
	 * @param 请求事件： $event
	 */
	protected function fetch($event)
	{
		$filename = _CACHE.'/'.$this->name($event);
		if (! file_exists($filename))
			return false;

		if (time() - filemtime($filename) > _CACHE_TIMEOUT)
			return false;

		$message = $this->log->message(__FUNCTION__, __CLASS__);
		$this->log->assign($message, Log::L_DEBUG);
		include($filename);
		return true;
	}

	/**
	 * 更新文件缓存
	 * @param 请求事件： $event
	 * @param 页面内容： $file
	 */
	protected function push($event, $file)
	{
		$message = $this->log->message(__FUNCTION__, __CLASS__);
		$this->log->assign($message, Log::L_DEBUG);

		$filename = _CACHE.'/'.$this->name($event);
		$file = fopen($filename, 'w');
		if (! $file)
		{
			$message = $this->log->message('file', __CLASS__);
			$this->log->assign($message, Log::L_WARING);
			return false;
		}
		flock($file, LOCK_EX);
		fwrite($file, $file);
		fflush($file);
		flock($file, LOCK_UN);
		fclose($file);
		return true;
	}

	/**
	 * 缓存文件命名
	 * @param 请求事件： $event
	 */
	protected function name($event)
	{
		#$filename = urlencode($_SERVER['REQUEST_URI']);
		$filename = str_replace('/', '-', $_SERVER['REQUEST_URI']);
		$filename = $event.$filename.'.html';
		return $filename;
	}

	private function __construct($preset, $timeout = null)
	{
		$this->log = Log::access();
		if (is_array($preset))
			$this->preset = $preset;
		if (is_numeric($timeout) && $timeout > 30)
			$this->timeout = $timeout;
	}
}
?>
