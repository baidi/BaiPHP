<?php
/**
 * <b>BaiPHP（简单PHP）开发框架</b>
 * @author		白晓阳
 * @copyright	Copyright (c) 2011 - 2012, 白晓阳
 * @link		http://dacbe.com
 * @version    V1.0.0 2012/03/31 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 */

/**
 * <b>BaiPHP（简单PHP）开发框架</b><br/>
 * <b>数据缓存工场（APC）</b>
 * <p>更新缓存数据并从缓存中提取数据</p>
 *
 * @author 白晓阳
 * @see Work
 */
class Cache extends Work
{
	/** 缓存清空标识 */
	const CLEAR = '-CLEAR';
	
	/** 缓存静态入口 */
	static private $ACCESS = null;

	/** 缓存过期时间 */
	private $timeout = _CACHE_TIMEOUT;

	/**
	 * <b>获取缓存工场入口</b><br/>
	 * 该入口是静态单一入口
	 *
	 * @param array $preset 预设缓存配置
	 * @param integer $timeout 缓存过期时间
	 *
	 * @return Cache 缓存工场
	 */
	public static function access(array $preset = null, $timeout = null)
	{
		if (! self::$ACCESS)
		{
			self::$ACCESS = new Cache($preset, $timeout);
		}
		return self::$ACCESS;
	}

	/**
	 * <b>更新缓存数据或提取缓存数据</b><br/>
	 * 缓存数据非空则更新，否则提取<br/>
	 * 若缓存项目为'-CLEAR'，则清空缓存
	 *
	 * @param string $item 缓存项目
	 * @param mixed $data 缓存数据
	 * @param bool $hard 缓存方式： false：内存；true：文件
	 *
	 * @return mixed 缓存数据或缓存结果
	 */
	public function entrust($item = null, $data = null, $hard = false)
	{
		if (! defined('_CACHE') || ! $item)
		{
			return false;
		}
		Log::logs(__CLASS__, 'Event');

		### 清空缓存
		if ($item == self::CLEAR)
		{
			apc_clear_cache();
			$files = scandir(_CACHE);
			if (! $files)
			{
				return self::CLEAR;
			}
			### 清空缓存文件
			foreach ($files as $file)
			{
				if (substr($file, - strlen(__CLASS__)) == __CLASS__
						&& ! unlink(_CACHE.$file))
				{
					return false;
				}
			}
			return self::CLEAR;
		}

		### 提取缓存数据
		if (! $data)
		{
			return $this->fetch($item, $hard);
		}

		### 更新缓存数据
		return $this->push($item, $data, $hard);
	}

	/**
	 * <b>提取数据缓存</b><br/>
	 *
	 * @param string $item 缓存主键
	 * @param boolean $hard 缓存方式： false：内存；true：文件
	 *
	 * @return mixed 缓存数据
	 */
	protected function fetch($item, $hard = false)
	{
		### 转换缓存项目
		$item = $this->name($item);
		### 缓存不存在
		$data = apc_fetch($item);
		if (! $data)
		{
			return false;
		}

		### 文件缓存
		if (substr($data, -6) == '.'.__CLASS__)
		{
			### 文件不存在
			if (! is_file($data))
			{
				return false;
			}
			### 文件过期
			#if (time() - filemtime($data) > _CACHE_TIMEOUT)
			#{
			#	return false;
			#}
			### 读取缓存文件
			Log::logf(__FUNCTION__, $item, __CLASS__);
			Log::logs($data);
			ob_start();
			include $data;
			$file = ob_get_clean();
			return $file;
		}

		### 内存缓存
		Log::logf(__FUNCTION__, $item, __CLASS__);
		return $data;
	}

	/**
	 * <b>更新数据缓存</b><br/>
	 *
	 * @param string $item 缓存项目
	 * @param mixed $data 缓存数据
	 * @param bool $hard 缓存方式： false：内存；true：文件
	 *
	 * @return bool 缓存结果
	 */
	protected function push($item, $data, $hard = false)
	{
		### 转换缓存主键
		$item = $this->name($item);
		Log::logf(__FUNCTION__, $item, __CLASS__);

		### 写入内存
		if (! $hard)
		{
			return apc_store($item, $data, $this->timeout);
		}

		### 写入文件
		$filename = _CACHE.$item.'.'.__CLASS__;
		$file = fopen($filename, 'w');
		if (! $file)
		{
			Log::logs('file', __CLASS__);
			return false;
		}
		flock($file, LOCK_EX);
		fwrite($file, $data);
		fflush($file);
		flock($file, LOCK_UN);
		fclose($file);
		### 保存文件名
		return apc_store($item, $filename);
	}

	/**
	 * <b>缓存项目命名</b><br/>
	 *
	 * @param string $item 缓存项目
	 *
	 * @return string 转换名
	 */
	protected function name($item)
	{
		$item = md5($_SERVER['REQUEST_URI'].'-'.$item);
		return $item;
	}

	/**
	 * <b>缓存工场初始化</b><br/>
	 *
	 * @param array $preset 预设缓存配置
	 * @param integer $timeout 缓存过期时间
	 */
	private function __construct(array $preset = null, $timeout = null)
	{
		if ($preset)
		{
			$this->preset = $preset;
		}
		if (is_int($timeout) && $timeout > 0)
		{
			$this->timeout = $timeout;
		}
	}
}
?>
