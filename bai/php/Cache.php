<?php
/**
 * <h2>化简PHP（BaiPHP）开发框架</h2>
 * @link      http://www.baiphp.net
 * @copyright Copyright (c) 2011 - 2012, 白晓阳
 * @author    白晓阳
 * @version   1.0.0 2012/03/31 首版
 *            2.0.0 2012/07/01 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 * <p>欢迎提供捐助。任何捐助者自动获得仅限于捐助者自身的商业使用（不包括再发行和再授权）授权。</p>
 */

/**
 * <h2>化简PHP（BaiPHP）开发框架</h2>
 * <h3>缓存工场</h3>
 * <p>
 * 缓存数据并从中提取数据。
 * 基于APC。
 * </p>
 * @author 白晓阳
 */
class Cache extends Work
{
	/** 标识：清空缓存 */
	const CLEAR = '_CLEAR';

	/** 是否开启 */
	protected $valid   = false;
	/** 缓存时间：秒 */
	protected $timeout = 600;
	/** 缓存目录 */
	protected $root    = 'Cache/';
	/** 缓存项目 */
	protected $items   = null;

	/** 缓存静态入口 */
	private static $ACCESS = null;

	/**
	 * <h4>获取缓存工场入口</h4>
	 * @param array $setting 即时配置
	 * @return Cache 缓存工场
	 */
	public static function access($setting = null)
	{
		if (self::$ACCESS == null) {
			self::$ACCESS = new Cache($setting);
		}
		return self::$ACCESS;
	}

	/**
	 * <h4>更新缓存或提取缓存</h4>
	 * <p>
	 * 缓存数据非空（NIL）则更新，否则提取。
	 * 若缓存项目为清空（CLEAR），则清空缓存。
	 * </p>
	 * @param string $item 缓存项目
	 * @param mixed $data 缓存数据
	 * @param bool $hard 缓存方式： false：内存；true：文件
	 * @return mixed 缓存数据或缓存结果
	 */
	public function entrust($item = null, $data = null, $hard = false)
	{
		if (! $this->valid || $item == null || ! $this->pick($item, $this->items)) {
			return false;
		}

		### 执行数据
		$this->runtime['item'] = $this->rename($item);
		$this->runtime['data'] = $data;
		$this->runtime['hard'] = $hard;

		### 清空缓存
		if ($item === self::CLEAR) {
			$this->result = $this->clear();
			return $this->result;
		}
		### 提取缓存
		if ($data === null) {
			$this->result = $this->fetch();
			return $this->result;
		}
		### 更新缓存
		$this->result = $this->push();
		return $this->result;
	}

	/**
	 * <h4>提取数据缓存</h4>
	 * @return mixed 缓存数据
	 */
	protected function fetch()
	{
		### 执行数据
		$item = $this->pick('item', $this->runtime);
		Log::logf(__FUNCTION__, $item, __CLASS__);

		### 提取缓存
		$data = apc_fetch($item, $result);
		if ($result === false) {
			return $result;
		}

		if (! is_string($data) || substr($data, - strlen(__CLASS__)) !== __CLASS__) {
			### 内存缓存
			return $data;
		}

		### 文件缓存
		if (! is_file($data)) {
			return false;
		}
		### 读取缓存文件
		ob_start();
		include $data;
		return ob_get_clean();
	}

	/**
	 * <h4>更新缓存数据</h4>
	 * @return bool 缓存结果
	 */
	protected function push()
	{
		### 执行数据
		$item = $this->pick('item', $this->runtime);
		$data = $this->pick('data', $this->runtime);
		$hard = $this->pick('hard', $this->runtime);
		Log::logf(__FUNCTION__, $item, __CLASS__);

		### 写入内存
		if (! $hard) {
			return apc_store($item, $data, $this->timeout);
		}

		### 写入文件
		$filename = _LOCAL.$this->root.$item.'.'.__CLASS__;
		$file = fopen($filename, 'w');
		if (! $file) {
			Log::logf('file', $filename, __CLASS__);
			return false;
		}
		flock($file, LOCK_EX);
		fwrite($file, $data);
		fflush($file);
		flock($file, LOCK_UN);
		fclose($file);
		### 缓存文件名
		return apc_store($item, $filename);
	}

	/**
	 * <h4>清空缓存</h4>
	 */
	protected function clear()
	{
		### 清空缓存
		apc_clear_cache();
		### 缓存文件
		$root = _LOCAL.$this->root;
		$files = scandir($root);
		if ($files == null) {
			return self::CLEAR;
		}
		### 清空缓存文件
		foreach ($files as $file) {
			if (substr($file, - strlen(__CLASS__)) === __CLASS__ && ! unlink($path.$file)) {
				return false;
			}
		}
		return self::CLEAR;
	}

	/**
	 * <h4>缓存项目命名</h4>
	 * @param string $item 缓存项目
	 * @return string 转换名
	 */
	protected function rename($item)
	{
		$item = $this->target->service.$this->target->event._DEF.$item;
		$item = urlencode($item);
		return $item;
	}

	/**
	 * <h4>构建缓存工场</h4>
	 * @param array $setting 即时配置
	 */
	protected function __construct($setting = null)
	{
		parent::__construct($setting);
		### 构建缓存目录
		$root = _LOCAL;
		foreach (explode(_DIR, $this->root) as $dir) {
			if ($dir == null || ! is_dir($root.$dir._DIR) && ! mkdir($root.$dir._DIR)) {
				break;
			}
			$root .= $dir._DIR;
		}
		$this->root = substr($root, strlen(_LOCAL));
	}
}
?>
