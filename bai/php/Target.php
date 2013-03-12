<?php
/**
 * <h2>BaiPHP（简单PHP）开发框架</h2>
 * @link      http://www.baiphp.com
 * @copyright Copyright (c) 2011 - 2012, 白晓阳
 * @author    白晓阳
 * @version   1.0.0 2012/03/31 首版
 *            2.0.0 2012/07/01 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 * <p>欢迎提供捐助。任何捐助者自动获得仅限于捐助者自身的商业使用（不包括再发行和再授权）授权。</p>
 */

/**
 * <h2>BaiPHP（简单PHP）开发框架</h2>
 * <h3>目标</h3>
 * <p>
 * 目标主体，收集并提供目标数据，以描述目标。
 * </p>
 * @author 白晓阳
 */
class Target extends Bai implements ArrayAccess
{
	/**
	 * <h4>委托当前目标到流程</h4>
	 * @param array $setting 自定义配置
	 * @return mixed
	 */
	public function entrust($setting = null)
	{
		$event = $this->Service.$this->Event;
		Log::logf(__FUNCTION__, $event, __CLASS__);
		Log::logf('start', date('Y-m-d H:m:s', _START), __CLASS__, Log::PERFORM);
		$this->result = $this->run($setting);
		Log::logf('close', microtime(true) - _START, __CLASS__, Log::PERFORM);
		Log::logf('deliver', $event, __CLASS__);
		return $this->result;
	}

	/**
	 * <h4>构建目标</h4>
	 * <p>
	 * 根据$_SESSION、$_GET、$_POST、预置数据、自定义数据构建当前目标。
	 * 优先级依次提升，但预置数据中的当前事项和服务路径会被提交的数据覆盖。
	 * </p>
	 * @param array $setting 自定义配置文件
	 */
	public function __construct($setting = null)
	{
		### 启动会话
		if (! isset($_SESSION))
		{
			session_start();
		}
		### 加载配置文件
		if ($setting != null)
		{
			$bai = _LOCAL.$this->config(_DEFAULT, self::BAI);
			$service = _LOCAL.$this->config(_DEFAULT, self::SERVICE);
			$main = $this->config(_DEFAULT, 'Root');
			if (! is_array($setting))
			{
				$setting = array($setting);
			}
			foreach ($setting as $item)
			{
				if (substr($item, - strlen(_EXT)) !== _EXT)
				{
					$item .= _EXT;
				}
				if (is_file($bai.$main.$item))
				{
					include $bai.$main.$item;
				}
				if (is_file($service.$main.$item))
				{
					include $service.$main.$item;
				}
			}
		}
		### 应用预置数据
		$this->stuff($this->config(_DEFAULT));
		$this->stuff($this->config(self::BAI));
		$this->stuff($this->config(self::TARGET));
		### 应用全局数据
		$this->stuff($_SESSION);
		$this->stuff($_GET);
		$this->stuff($_POST);
		### 应用目标事项
		if ($this->event != null)
		{
			$this->Event = $this->event;
		}
		### 应用目标入口
		if ($this->service != null)
		{
			$this->Service = $this->service;
		}
		$this->target = $this;
	}

	/**
	 * <h3>判断项目是否存在</h3>
	 * @param string $item 项目名
	 * @return bool 是否存在
	 */
	public function offsetExists($item)
	{
		return isset($this->$item);
	}

	/**
	 * <h3>读取项目值</h3>
	 * @param string $item 项目名
	 * @return mixed 项目值
	 */
	public function offsetGet($item)
	{
		if (! $this->offsetExists($item))
		{
			$this->$item = null;
		}
		return $this->$item;
	}

	/**
	 * <h3>设定项目值</h3>
	 * @param string $item 项目名
	 * @param mixed $value 项目值
	 * @return void
	 */
	public function offsetSet($item, $value, $session = false)
	{
		if ($session && isset($_SESSION))
		{
			$_SESSION[$item] = $value;
		}
		$this->$item = $value;
	}

	/**
	 * <h3>清除项目</h3>
	 * @param string $item 项目名
	 * @return void
	 */
	public function offsetUnset($item)
	{
		unset($this->$item);
	}

	/**
	 * 用作字符串时，使用当前目标名。
	 */
	public function __toString()
	{
		return $this->Event;
	}
}