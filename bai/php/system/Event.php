<?php
/**
 * <b>BaiPHP（简单PHP）开发框架</b>
 * @author		白晓阳
 * @copyright	Copyright (c) 2011 - 2012, 白晓阳
 * @link		http://dacbe.com
 * @version     V1.0.0 2012/08/02 V1.1
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 */

/**
 * <b>BaiPHP（简单PHP）开发框架</b><br/>
 * <b>事件工场：</b>
 * <p>存储用户提交的数据与服务器返回的数据</p>
 *
 * @author 白晓阳
 * @see Work
 */
final class Event extends Work implements ArrayAccess
{
	/** 默认事件名 */
	const EVENT = 'home';

	/**
	 * 获取项目或设定项目
	 *
	 * @param string $key 项目名
	 * @param mixed $value 项目值
	 *
	 * @see Bai::entrust()
	 */
	public function entrust($item = null, $value = null, $session = false)
	{
		if ($item == null)
		{
			return null;
		}
		if ($value != null)
		{
			### 设置项目
			$this->$item = $value;
			if ($session)
			{
				$_SESSION[$item] = $value;
			}
			return $value;
		}
		### 读取项目
		if ($session && isset($_SESSION[$item]))
		{
			return $_SESSION[$item];
		}
		if (isset($this->$item))
		{
			return $this->$item;
		}
		return null;
	}

	/**
	 * 事件工场初始化
	 *
	 * @param array $data 用户数据
	 */
	public function __construct(array $data = null)
	{
		if (! $data)
		{
			$data = $_REQUEST;
		}
		foreach ($data as $item => $value)
		{
			$this->$item = $value;
		}
		if ($this->issue)
		{
			#define('_ISSUE', _WEB.$this->$issue.'/');
		}
		if ($this->event == null)
		{
			$this->event = self::EVENT;
		}
		$this->Event = ucfirst($this->event);
	}

	/**
	 * 判断项目是否存在
	 *
	 * @param string $item 项目名
	 *
	 * @return bool
	 *
	 * @see ArrayAccess::offsetExists()
	 */
	public function offsetExists($item)
	{
		return isset($this->$item);
	}

	/**
	 * 获取项目
	 *
	 * @param string $item 项目名
	 *
	 * @return mixed 项目值
	 *
	 * @see ArrayAccess::offsetGet()
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
	 * 设定项目
	 *
	 * @param string $item 项目名
	 * @param mixed $value 项目值
	 *
	 * @see ArrayAccess::offsetSet()
	 */
	public function offsetSet($item, $value)
	{
		$this->$item = $value;
	}

	/**
	 * 清除项目
	 *
	 * @param string $item 项目名
	 *
	 * @see ArrayAccess::offsetUnset()
	 */
	public function offsetUnset($item)
	{
		if ($this->offsetExists($item))
		{
			unset($this->$item);
		}
	}

	/**
	 * 转换成字符串时，返回当前事件名
	 * @see Bai::__toString()
	 */
	public function __toString()
	{
		$item = strtolower(__CLASS__);
		if ($this->$item) {
			return $this->$item;
		}
		return self::EVENT;
	}
}