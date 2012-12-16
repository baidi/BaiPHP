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
 * @see Work
 * @author 白晓阳
 */
final class Target extends Bai implements ArrayAccess
{
	/**
	 * <h3>读取或设定目标描述项</h3>
	 * @param string $item 项目名
	 * @param mixed $value 项目值
	 * @param bool $duration 是否保持
	 * @return mixed
	 */
	public function entrust($item = null, $value = self::NIL, $duration = false)
	{
		if ($item == null)
		{
			return null;
		}
		if ($value != self::NIL)
		{
			### 设定目标描述项
			$this->$item = $value;
			if ($duration)
			{
				$_SESSION[$item] = $value;
			}
			return $value;
		}
		### 读取目标描述项
		if ($duration && isset($_SESSION[$item]))
		{
			return $_SESSION[$item];
		}
		return $this->$item;
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
	public function offsetSet($item, $value)
	{
		$this->$item = $value;
	}

	/**
	 * <h3>清除项目</h3>
	 * @param string $item 项目名
	 * @return void
	 */
	public function offsetUnset($item)
	{
		if ($this->offsetExists($item))
		{
			unset($this->$item);
		}
	}

	/**
	 * <h3>目标构建</h3>
	 * <p>
	 * 根据全局配置、$_SESSION、$_GET、$_POST、自定义数据构建目标。
	 * </p>
	 * @param array $data 自定义数据
	 */
	public function __construct(array $data = null)
	{
		### 全局配置：目标默认项
		if ($config = $this->config(__CLASS__))
		{
			foreach ($config as $item => $value)
			{
				$this->$item = $value;
			}
		}
		if ($_SESSION)
		{
			foreach ($_SESSION as $item => $value)
			{
				$this->$item = $value;
			}
		}
		foreach ($_GET as $item => $value)
		{
			$this->$item = $value;
		}
		foreach ($_POST as $item => $value)
		{
			$this->$item = $value;
		}
		if ($data)
		{
			foreach ($data as $item => $value)
			{
				$this->$item = $value;
			}
		}
		### 目标入口
		if ($this->entry)
		{
			$this->Entry = $this->entry;
		}
		### 目标事件
		if ($this->event)
		{
			$this->Event = $this->event;
		}
	}

	/**
	 * 用作字符串时，使用当前目标名。
	 */
	public function __toString()
	{
		return $this->target;
	}
}