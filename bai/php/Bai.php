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
 * <h3>元始虚类</h3>
 * <p>
 * 定义公共基础标识、单一开放入口和内部共享行为。
 * 服务、流程、工场和其他框架内部类都应当继承该类。
 * </p>
 * @author 白晓阳
 */
abstract class Bai implements ArrayAccess
{
	/** 标识：元类 */
	const BAI     = 'Bai';
	/** 标识：服务 */
	const SERVICE = 'Service';
	/** 标识：流程 */
	const FLOW    = 'Flow';
	/** 标识：工场 */
	const WORK    = 'Work';
	/** 标识：目标 */
	const TARGET  = 'Target';
	/** 标识：事项 */
	const EVENT   = 'Event';
	/** 标识：空 */
	const NIL     = '_NIL';

	/** 预置数据 */
	protected $preset  = array();
	/** 执行数据 */
	protected $runtime = array();
	/** 当前目标 */
	protected $target  = null;
	/** 执行结果 */
	protected $result  = null;
	/** 错误信息 */
	protected $error   = null;

	/**
	 * <h4>委托目标</h4>
	 * <p>
	 * （外部）委托当前对象完成目标并交付结果。
	 * 所有子对象必须以该行为作为非静态单一开放入口。
	 * </p>
	 * @param array $setting 即时配置
	 * @return mixed 交付结果
	 */
	public function entrust($setting = null)
	{
		return $this->run($setting);
	}

	/**
	 * <h4>执行目标</h4>
	 * <p>
	 * （流程或工场）根据预置流程执行目标以实现交付。
	 * 预置流程由全局配置：$config[self::FLOW][__CLASS__]设定。
	 * </p>
	 * @param array $setting 即时配置
	 * @return mixed 执行结果
	 */
	protected function run($setting = null)
	{
		### 读取预置流程
		$class = get_class($this);
		$preset = $this->config(self::FLOW, $class);
		if ($preset == null || ! is_array($preset))
		{
			$preset = $this->config(self::FLOW, get_parent_class($this));
		}
		$this->stuff($setting, $preset);
		if ($preset == null || ! is_array($preset))
		{
			return true;
		}
		### 执行预置流程
		$jump = null;
		foreach ($preset as $item => $mode)
		{
			### 跳转模式
			if ($mode === self::NIL || ($jump != null && $jump !== $item))
			{
				continue;
			}
			if (method_exists($this, $item))
			{
				### 执行内部方法
				Log::logf(__FUNCTION__, $class.'->'.$item, __CLASS__);
				$this->result = $this->$item();
			}
			else
			{
				### 委托外部对象
				$flow = $this->build($item);
				if ($flow != null)
				{
					Log::logf('entrust', "$flow", __CLASS__);
					$this->result = $flow->entrust();
					#$this->error = $flow->error;
				}
			}
			if ($this->error == null)
			{
				if ($mode === false)
				{
					break;
				}
				$jump = null;
				continue;
			}
			$this->target->error = $this->error;
			$this->target->anchor = $this;
			if (is_string($mode) && $mode)
			{
				### 进入跳转模式
				$jump = $mode;
				continue;
			}
			### 错误处理
			$jump = 'error';
		}
		return $this->result;
	}

	/**
	 * <h4>输出错误页面</h4>
	 * @return string 错误页面
	 */
	protected function error()
	{
		return $this->load(_DEFAULT.__FUNCTION__, false, Flow::PAGE._DIR);
	}

	/**
	 * <h4>读取全局配置</h4>
	 * <p>
	 * 根据指定项目名，逐级读取全局配置。
	 * 如果未指定项目名，则返回全局配置。
	 * 如果未读取到指定项，则返回空（null）。
	 * 全局配置由$config设定。
	 * </p>
	 * @param string $item1 项目1
	 * @param string $item... 项目...
	 * @return mixed 项目值
	 */
	protected function config()
	{
		### 项目名
		$items = func_get_args();
		$preset = $GLOBALS[__FUNCTION__];
		if ($items == null)
		{
			return $preset;
		}
		### 根据项目名逐级读取全局配置
		foreach ($items as $item)
		{
			if (! is_array($preset) || ! isset($preset["$item"]))
			{
				return null;
			}
			$preset = $preset["$item"];
		}
		return $preset;
	}

	/**
	 * <h4>检出项目值</h4>
	 * <p>
	 * 从列表中检出指定项目。
	 * 优先检索自定义列表，默认检索当前目标及全局配置。
	 * 如果未检索到指定项目，则返回空（null）。
	 * </p>
	 * @param string $item 项目名
	 * @param array $source 数据源
	 * @param bool $limit 是否限定列表
	 * @param bool $print 是否输出
	 * @return mixed 项目值
	 */
	protected function pick($item = null, $source = null, $limit = true, $print = false)
	{
		### 项目名
		if ($item == null)
		{
			return null;
		}
		$value = null;
		### 从自定义列表中检值
		if (is_array($source) && isset($source[$item]))
		{
			$value = $source[$item];
		}
		else if (is_object($source) && isset($source->$item))
		{
			$value = $source->$item;
		}
		if (! $limit)
		{
			### 从当前目标及全局配置中检值
			if ($value === null)
			{
				$value = $this->target->$item;
			}
			if ($value === null)
			{
				$value = $this->config($item);
			}
		}
		if ($print)
		{
			echo $value;
		}
		return $value;
	}

	/**
	 * <h4>输出项目值</h4>
	 * <p>
	 * 从列表中输出指定项目。
	 * 优先检索自定义列表，默认检索当前目标及全局配置。
	 * 如果未检索到指定项目，则输出空（null）。
	 * </p>
	 * @param string $item 项目名
	 * @param array $list 自定义列表
	 * @param bool $limit 是否限定列表
	 * @return mixed 项目值
	 */
	protected function drop($item = null, $list = null, $limit = true)
	{
		return $this->pick($item, $list, $limit, true);
	}

	/**
	 * <h4>填充数据列表</h4>
	 * <p>
	 * 填充数据列表到对象自身或目标列表。
	 * </p>
	 * @param array $list 数据列表
	 * @param array $master 目标列表
	 * @param bool $force 是否更新空项
	 * @return bool 填充结果
	 */
	protected function stuff($list = null, &$master = null, $force = true)
	{
		if (! is_array($list))
		{
			return false;
		}
		### 填充到当前对象
		if ($master === null)
		{
			$master = $this;
		}
		if (is_object($master))
		{
			foreach ($list as $item => $value)
			{
				if ($force || $value != null || ! isset($master->$item))
				{
					$master->$item = $value;
				}
			}
			return true;
		}
		if (! is_array($master) && ! ($master instanceof ArrayAccess))
		{
			$master = $list;
			return true;
		}
		### 填充到目标列表
		foreach ($list as $item => $value)
		{
			if (isset($master[$item]) && is_array($master[$item]))
			{
				$this->stuff($value, $master[$item]);
				continue;
			}
			if ($force || $value != null || ! isset($master[$item]))
			{
				$master[$item] = $value;
			}
		}
		return true;
	}

	/**
	 * <h4>构建对象</h4>
	 * <p>
	 * 根据对象名检测并构建对象。
	 * </p>
	 * @param string $class 对象名
	 * @param array $setting 即时配置
	 * @return mixed 对象实例，对象未知则返回空（null）。
	 */
	protected function build($class = null, $setting = null)
	{
		if ($class == null || ! is_string($class))
		{
			return null;
		}
		$event = ucfirst("$this->target");
		### 优先加载扩展对象
		if (class_exists($event.$class))
		{
			$class = $event.$class;
		}
		else if (! class_exists($class))
		{
			### 对象未知
			$error = Log::logf(__FUNCTION__, $class, __CLASS__, Log::EXCEPTION);
			#trigger_error($error, E_USER_WARNING);
			return null;
		}
		if (method_exists($class, 'access'))
		{
			### 静态构建
			return $class::access($setting);
		}
		### 常态构建
		return new $class($setting);
	}

	/**
	 * <h4>定位文件</h4>
	 * <p>
	 * 根据文件名确定文件基于根目录的相对路径。
	 * </p>
	 * @param string $item 文件名
	 * @param string $branch 分支
	 * @return array 文件路径
	 */
	protected function locate($item = null, $branch = null)
	{
		### 文件名
		if ($item == null || ! is_string($item))
		{
			return null;
		}
		### 分支
		if ($branch == null)
		{
			$branch  = get_class($this)._DIR;
		}
		if (substr($branch, -1) !== _DIR)
		{
			$branch .= _DIR;
		}
		$bai     = $this->target[self::BAI].$branch.$item;
		$service = $this->target[self::SERVICE].$branch.$item;
		$result = array();
		### 系统文件
		if (is_file(_LOCAL.$bai))
		{
			$result[self::BAI] = $bai;
		}
		### 服务文件
		if (is_file(_LOCAL.$service))
		{
			$result[self::SERVICE] = $service;
		}
		return $result;
	}

	/**
	 * <h4>加载文件</h4>
	 * <p>
	 * 根据目标事项或文件名加载页面文件。
	 * 页面文件中可直接使用外部参数$_params。
	 * </p>
	 * @param string $item 文件名
	 * @param bool $all 是否叠加
	 * @return string 页面内容，页面无效则返回空。
	 */
	protected function load($item = null, $all = false, $branch = null)
	{
		### 文件名
		if ($item == null || ! is_string($item))
		{
			return null;
		}
		if (substr($item, - strlen(_EXT)) !== _EXT)
		{
			$item .= _EXT;
		}
		### 路径
		$path    = $this->locate($item, $branch);
		$bai     = $this->pick(self::BAI,     $path);
		$service = $this->pick(self::SERVICE, $path);
		### 加载文件
		if ($all)
		{
			ob_start();
			if ($bai != null)
			{
				include _LOCAL.$bai;
			}
			if ($service != null)
			{
				include _LOCAL.$service;
			}
			return ob_get_clean();
		}
		if ($service != null)
		{
			ob_start();
			include _LOCAL.$service;
			return ob_get_clean();
		}
		if ($bai != null)
		{
			ob_start();
			include _LOCAL.$bai;
			return ob_get_clean();
		}
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
	 * <h3>读取项目</h3>
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
	 * <h3>设定项目</h3>
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
		unset($this->$item);
	}

	/**
	 * 属性未知时，返回空（null）。
	 */
	public function __get($item)
	{
		Log::logf(__FUNCTION__, get_class($this).'->'.$item, __CLASS__, Log::NOTICE);
		$this->$item = null;
		return $this->$item;
	}

	/**
	 * 行为未知时，返回空（null）。
	 */
	public function __call($item, $params)
	{
		Log::logf(__FUNCTION__, get_class($this).'->'.$item, __CLASS__, Log::WARING);
		return null;
	}

	/**
	 * 用作字符串时，使用当前对象名。
	 */
	public function __toString()
	{
		return get_class($this);
	}

	/**
	 * <h4>构建对象</h4>
	 * <p>
	 * 设置当前目标和预置数据。
	 * </p>
	 * @param array $setting 即时配置
	 */
	protected function __construct($setting = null)
	{
		global $target;
		$class = get_class($this);
		$this->target = $target;
		$this->stuff($this->config($class), $this->preset);
		$this->stuff($setting, $this->preset);
		$this->stuff($this->pick(_DEFAULT, $this->preset), $this, false);
	}
}
