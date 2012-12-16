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
 * 定义公共基础常量、单一开放入口和内部共享行为。
 * 服务、流程、工场和其他所有的类都应当继承该类。
 * </p>
 * @author 白晓阳
 */
abstract class Bai
{
	/** 标识：服务 */
	const SERVICE = 'Service';
	/** 标识：流程 */
	const FLOW = 'Flow';
	/** 标识：工场 */
	const WORK = 'Work';
	/** 标识：目标 */
	const TARGET = 'Target';
	/** 标识：空 */
	const NIL = '_NIL';

	/** 预置方程 */
	protected $preset = array();
	/** 当前目标 */
	protected $target = null;

	/**
	 * <h3>委托目标</h3>
	 * <p>
	 * （外部）委托当前对象处理目标（并交付结果）。<br/>
	 * 所有子对象必须以该行为作为非静态单一开放入口。
	 * </p>
	 */
	abstract public function entrust();

	/**
	 * <h3>读取配置项目</h3>
	 * <p>
	 * 根据项目名，逐层读取全局配置（由$config定义），直至非数组项或读取结束。
	 * </p>
	 * 
	 * @param string $item1 项目1
	 * @param string $item... 项目...
	 * @return mixed 项目值
	 */
	protected function config()
	{
		### 全局配置
		global $config;
		if (! $config)
		{
			return null;
		}
		### 读取项目名
		$items = func_get_args();
		if (! $items)
		{
			return null;
		}
		### 根据项目名逐级读取全局配置
		$value = $config;
		foreach ($items as $item)
		{
			if (! isset($value[$item]))
			{
				return null;
			}
			$value = $value[$item];
			if (! is_array($value))
			{
				break;
			}
		}
		return $value;
	}

	/**
	 * <b>读取项目值</b><br/>
	 * 取值顺序为默认列表、$_REQUEST、$_SESSION、全局配置
	 *
	 * @param string $item 项目名
	 * @param array $array 默认列表
	 * @param bool $limit 是否限定默认列表
	 * @param bool $print 是否输出
	 *
	 * @return mixed 项目值
	 */
	protected function pick($item, array $array = null, $limit = true, $print = false)
	{
		if (! $item)
		{
			return null;
		}
		$value = null;
		### 优先从默认列表取值
		if (is_array($array))
		{
			$value = isset($array[$item]) ? $array[$item] : null;
			if ($limit)
			{
				if ($print)
				{
					print $value;
				}
				return $value;
			}
		}
		### 从全局集中取值
		if ($value == null)
		{
			$value = isset($_REQUEST[$item]) ? $_REQUEST[$item] : null;
		}
		if ($value == null)
		{
			$value = isset($_SESSION[$item]) ? $_SESSION[$item] : null;
		}
		if ($value == null)
		{
			$value = c($item);
		}
		if ($print)
		{
			print $value;
		}
		return $value;
	}

	/**
	 * <b>加载页面块文件</b><br/>
	 * 页面块文件中可以使用$_param和$_css参数，将用传入参数替换
	 *
	 * @param string $file 文件名
	 * @param string $_param 页面参数，用于页面文件内
	 * @param string $_css CSS类名，用于页面文件内
	 * @param bool $print 是否输出
	 *
	 * @return string 页面内容
	 */
	protected function load($file, $_param = null, $_css = null, $print = true)
	{
		if (! $file)
		{
			return null;
		}
		$file = _LOCAL._PAGE.$file;
		if (! is_file($file))
		{
			return null;
		}
		ob_start();
		include $file;
		$page = ob_get_clean();
		if ($print)
		{
			print $page;
		}
		return $page;
	}

	/**
	 * <h3>启动（目标方程）</h3>
	 * <p>
	 * 启动自身目标方程以处理事件。<br/>
	 * 目标方程由系统配置：$config['Target']设定。
	 * </p>
	 */
	protected function run()
	{
		if (! $this->target)
		{
			return null;
		}
		### 系统配置：目标方程
		$cTarget = c(self::TARGET, "$this->target");
		if (! $cTarget || ! is_array($cTarget))
		{
			return null;
		}
		### 启动目标方程
		foreach ($cTarget as $item => $mode)
		{
			$result = $this->$item();
			if ($mode && ! $result)
			{
				break;
			}
		}
		return $result;
	}

	/**
	 * <h3>驱动（工场）</h3>
	 * <p>
	 * （流程）驱动工场处理事件。<br/>
	 * （流程）驱动项目由系统配置：$config['Work']设定。
	 * </p>
	 */
	protected function drive()
	{
		if (! $this->target)
		{
			return null;
		}
		### 系统配置：（流程）驱动项目
		$cWork = c(self::WORK, "$this->target");
		if (! $cWork || ! is_array($cWork))
		{
			return null;
		}
		### 驱动（工场）项目
		foreach ($cWork as $item => $value)
		{
			if (preg_match($item, "$this"))
			{
				break;
			}
		}
	}

	/**
	 * <h3>传递（事件）</h3>
	 * <p>
	 * （流程）传递事件到后续流程。<br/>
	 * （流程）传递模式由系统配置：$config['Flow']设定。
	 * </p>
	 */
	protected function pass()
	{
		if (! $this->target)
		{
			return null;
		}
		### 系统配置：（流程）传递模式
		$cFlow = c(self::FLOW);
		if (! $cFlow || ! is_array($cFlow))
		{
			return null;
		}
		### 检出后续流程
		$source = get_class($this);
		foreach ($cFlow as $item => $value)
		{
			if (preg_match($item, $source))
			{
				$flow = $value;
				break;
			}
		}
		if (! $flow)
		{
			return null;
		}
		### 传递事件到后续流程
		$event = ucfirst($this->target);
		if (class_exists($event.$flow, true))
		{
			$flow = new $event.$flow($this->target);
			return $flow->entrust();
		}
		$flow = new $flow($this->target);
		return $flow->entrust();
	}

	/**
	 * 属性未知时，返回空（null）。
	 */
	public function __get($item)
	{
		# Log::logf(__FUNCTION__, get_class($this).'->'.$item, self::EVENT);
		return null;
	}

	/**
	 * 行为未知时，返回空（null）。
	 */
	public function __call($item, $params)
	{
		Log::logf(__FUNCTION__, get_class($this).'->'.$item, self::EVENT, Log::L_WARING);
		return null;
	}

	/**
	 * 用作字符串时，使用当前对象名。
	 */
	public function __toString()
	{
		return get_class($this);
	}

	public function __construct(Target $target)
	{
		$this->target = $target;
	}
}
?>
