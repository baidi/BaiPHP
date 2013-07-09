<?php

/**
 * <h2>化简PHP（BaiPHP）开发框架</h2>
 *
 * @link http://www.baiphp.net
 * @copyright Copyright 2011 - 2013, 白晓阳
 * @author 白晓阳
 * @version 1.0.0 2012/03/31 首版
 *          2.0.0 2013/07/01 全面重构代码，弃用公共函数，独立启动引擎，优化配置文件结构，增加
 *          原始虚类、目标工场、样式工场、模板工场、语言工场和记录工场，并优化代码结构。
 * @license <p>化简PHP（BaiPHP）开发框架，是依据"面向目标"的设计思想、基于"服务-流程-工场"的设计模式、以简洁
 *          灵活为方向、由白晓阳设计和开发的一套PHP应用框架。该框架的核心是基于配置并即时可控的流程走向和流程覆
 *          盖，它采用了简洁优雅的实现方式，不但显著提升框架的易学性和易用性，而且最大限度地释放出应用的灵活性和扩
 *          展性，从而尽可能地降低程序的开发和维护成本。</p>
 *          <p>化简PHP（BaiPHP）开发框架完全开放源代码，任何人都可以自由地复制、传播、修改和使用该代码，但未经
 *          授权，不得用于商业目的。</p>
 *          <p>欢迎对该框架提供任何形式的捐助，捐助者自动获得仅限于捐助者自身的商业使用（不包括再发行和再授权）授
 *          权。</p>
 *          <p>化简PHP（BaiPHP）开发框架由白晓阳持有版权，并保留一切权利。</p>
 */

/**
 * <h2>化简PHP（BaiPHP）开发框架</h2>
 * <h3>元始对象</h3>
 * <p>
 * 定义公共基础标识、单一开放入口和内部共享行为。
 * 服务、流程、工场及其他框架内部类都必须继承该类。
 * </p>
 *
 * @copyright Copyright 2011 - 2013, 白晓阳
 * @author 白晓阳
 */
abstract class Bai implements ArrayAccess
{
	/**
	 * 标识：元始对象
	 */
	const BAI = 'Bai';
	/**
	 * 标识：服务
	 */
	const SERVICE = 'Service';
	/**
	 * 标识：流程
	 */
	const FLOW = 'Flow';
	/**
	 * 标识：工场
	 */
	const WORK = 'Work';
	/**
	 * 标识：目标
	 */
	const TARGET = 'Target';
	/**
	 * 标识：事项
	 */
	const EVENT = 'Event';
	/**
	 * 标识：空
	 */
	const NIL = '_NIL';
	/**
	 * 标识：调试模式
	 */
	const DEBUG = 'Debug';
	/**
	 * 标识：运行路径
	 */
	const RUNTIME = 'Runtime';

	/**
	 * 依赖扩展
	 */
	protected $extensions = array();
	/**
	 * 预置数据
	 */
	protected $preset = array();
	/**
	 * 执行数据
	 */
	protected $runtime = array();
	/**
	 * 当前目标
	 */
	protected $target = null;
	/**
	 * 执行结果
	 */
	protected $result = null;
	/**
	 * 提示信息
	 */
	protected $notice = null;
	/**
	 * 提示页面
	 */
	protected $board = '_notice.php';

	/**
	 * <h4>委托目标</h4>
	 * <p>
	 * （外部）委托当前对象处理目标并交付结果。
	 * 所有子对象应当以该行为作为非静态单一开放入口。
	 * </p>
	 *
	 * @param array $setting 即时配置
	 * @return mixed 交付结果
	 */
	public function entrust ($setting = null)
	{
		return $this->run($setting);
	}

	/**
	 * <h4>执行目标</h4>
	 * <p>
	 * （流程或工场）根据预置流程依次处理目标并交付结果。
	 * 预置流程由全局配置$config[self::FLOW][当前对象名]设定。
	 * 如果当前对象未预置流程，则逐级回溯父对象的预置流程。最后将即时配置并入预置流程。
	 * 如果仍无可用流程，则直接交付。
	 * </p>
	 *
	 * @param array $setting 即时配置
	 * @return mixed 执行结果
	 */
	protected function run ($setting = null)
	{
		### 读取预置流程
		$parent = $class = get_class($this);
		$flow = $this->config(self::FLOW, $class);
		### 回溯父对象的预置流程
		while (($flow == null || ! is_array($flow)) && ($parent = get_parent_class($parent))) {
			$flow = $this->config(self::FLOW, $parent);
		}
		### 合并即时配置
		$this->stuff($setting, $flow);
		if ($flow == null || ! is_array($flow)) {
			### 无可用流程，直接结束
			return true;
		}
		### 执行预置流程
		$jump = null;
		foreach ($flow as $item => $mode) {
			if ($mode === self::NIL || ($jump != null && $jump !== $item)) {
				### 跳转模式
				continue;
			}
			if (method_exists($this, $item)) {
				### 执行自身方法
				Log::logf(__FUNCTION__, $class . '->' . $item, __CLASS__);
				$this->result = $this->$item();
			} else {
				### 委托其他对象
				$step = $this->build($item);
				if ($step != null) {
					Log::logf('entrust', "$step", __CLASS__);
					$this->result = $step->entrust();
					$this->notice = $step->notice;
				}
			}
			### 执行正常
			if ($this->notice == null) {
				if ($mode === false) {
					break;
				}
				$jump = null;
				continue;
			}
			$this->target->notice = $this->notice;
			$this->target->anchor = $this;
			if (is_string($mode) && $mode) {
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
	 * <h4>输出警示</h4>
	 * <p>
	 * 根据警示信息和警示模板输出警示页面
	 * </p>
	 *
	 * @return string 警示页面
	 */
	protected function error ()
	{
		$this->notice = null;
		return $this->load($this->board, false, 'Page');
	}

	/**
	 * <h4>读取全局配置</h4>
	 * <p>
	 * 根据指定项目名，逐级读取全局配置内容。
	 * 如果未指定项目名，则返回全局配置。
	 * 如果未读取到指定项，则返回空（null）。
	 * 全局配置由$config设定。
	 * </p>
	 *
	 * @param string $item1 项目1
	 * @param string $item... 项目...
	 * @return mixed 项目值
	 */
	protected function config ()
	{
		### 项目名
		$items = func_get_args();
		$config = $GLOBALS[__FUNCTION__];
		if ($items == null) {
			return $config;
		}
		### 根据项目名逐级读取全局配置
		foreach ($items as $item) {
			if (! is_array($config) || ! isset($config["$item"])) {
				return null;
			}
			$config = $config["$item"];
		}
		return $config;
	}

	/**
	 * <h4>检出项目值</h4>
	 * <p>
	 * 从自定列表或对象中检出指定项目的值。
	 * 在扩展模式下，如果未检出该项目，会检索当前目标及全局配置。
	 * 如果最终未检索到指定项目，则返回空（null）。
	 * </p>
	 *
	 * @param string $item 项目名
	 * @param array $list 自定列表或对象
	 * @param bool $extra 是否启用扩展模式
	 * @param bool $print 是否输出
	 * @return mixed 项目值
	 */
	protected function pick ($item = null, $list = null, $extra = false, $print = false)
	{
		$value = null;
		### 从自定列表中检值
		if (is_array($list) && isset($list[$item])) {
			$value = $list[$item];
		} else if (is_object($list) && isset($list->$item)) {
			$value = $list->$item;
		}
		if ($extra) {
			### 扩展模式，从当前目标及全局配置中检值
			if ($value === null) {
				$value = $this->target[$item];
			}
			if ($value === null) {
				$value = $this->config($item);
			}
		}
		if ($print) {
			echo $value;
		}
		return $value;
	}

	/**
	 * <h4>输出项目值</h4>
	 * <p>
	 * 从自定列表或对象中检出指定项目的值并输出。
	 * </p>
	 *
	 * @param string $item 项目名
	 * @param array $list 自定列表或对象
	 * @param bool $extra 扩展模式
	 * @return mixed 项目值
	 */
	protected function drop ($item = null, $list = null, $extra = false)
	{
		return $this->pick($item, $list, $extra, true);
	}

	/**
	 * <h4>填充项目</h4>
	 * <p>
	 * 填充列表项目到对象属性或目标列表。
	 * </p>
	 *
	 * @param array $list 源列表
	 * @param array $master 目标列表或对象
	 * @param int $all 空项更新模式： 1：总是更新；0：内容非空或原值未设置时更新；-1：增量更新；
	 * @return bool 填充结果
	 */
	protected function stuff ($list = null, &$master = self::NIL, $mode = 0)
	{
		### 源列表
		if (! is_array($list)) {
			return false;
		}
		if ($master === self::NIL) {
			### 默认填充到当前对象
			$master = $this;
		}
		### 填充到目标对象属性
		if (is_object($master)) {
			foreach ($list as $item => $value) {
				if ($mode === 0 && ($value !== null || ! isset($master->$item)) || $mode > 0 ||
						 $mode < 0 && ! isset($master->$item)) {
					$master->$item = $value;
				}
			}
			return true;
		}
		### 目标无效，以填充列表替代
		if (! is_array($master)) {
			$master = $list;
			return true;
		}
		### 填充到目标列表
		foreach ($list as $item => $value) {
			if (isset($master[$item]) && is_array($master[$item])) {
				$this->stuff($value, $master[$item]);
				continue;
			}
			if ($mode === 0 && ($value !== null || ! isset($master[$item])) || $mode > 0 ||
					 $mode < 0 && ! isset($master[$item])) {
				$master[$item] = $value;
			}
		}
		return true;
	}

	/**
	 * <h4>定位文件</h4>
	 * <p>
	 * 根据文件名确定文件基于根目录的相对路径。
	 * </p>
	 *
	 * @param string $item 文件名
	 * @param string $branch 扩展分支名
	 * @return array 文件路径，最多包含两项内容：self::Bai指向框架核心文件路径，self::Service指向用户服务文件路径
	 */
	protected function locate ($item = null, $branch = null)
	{
		### 文件名
		if ($item == null || ! is_string($item)) {
			return null;
		}
		### 分支
		if ($branch == null) {
			$branch = get_class($this) . _DIR;
		}
		if (substr($branch, - 1) !== _DIR) {
			$branch .= _DIR;
		}
		### 文件路径
		$bai = $this->config(_DEF, self::BAI) . $branch . $item;
		$service = $this->config(_DEF, self::SERVICE) . $branch . $item;
		$result = array();
		### 系统框架文件
		if (is_file(_LOCAL . $bai)) {
			$result[self::BAI] = $bai;
		}
		### 用户服务文件
		if (is_file(_LOCAL . $service)) {
			$result[self::SERVICE] = $service;
		}
		return $result;
	}

	/**
	 * <h4>加载文件</h4>
	 * <p>
	 * 根据目标事项或文件名加载PHP文件。
	 * </p>
	 *
	 * @param string $item 目标事项或文件名（PHP）
	 * @param bool $all 是否叠加
	 * @param string $branch 扩展分支名
	 * @return string 页面内容，页面无效则返回空。
	 */
	protected function load ($item = null, $all = false, $branch = null)
	{
		### 文件名
		if ($item == null || ! is_string($item)) {
			return null;
		}
		### 为目标事项添加后缀名
		if (strcasecmp(substr($item, - strlen(_EXT)), _EXT) != 0) {
			$item .= _EXT;
		}
		### 定位文件路径
		$path = $this->locate($item, $branch);
		$bai = $this->pick(self::BAI, $path);
		$service = $this->pick(self::SERVICE, $path);
		### 加载全部文件，系统框架文件优先
		if ($all) {
			ob_start();
			if ($bai != null) {
				include _LOCAL . $bai;
			}
			if ($service != null) {
				include _LOCAL . $service;
			}
			return ob_get_clean();
		}
		### 加载单个文件，用户服务文件优先
		if ($service != null) {
			ob_start();
			include _LOCAL . $service;
			return ob_get_clean();
		}
		if ($bai != null) {
			ob_start();
			include _LOCAL . $bai;
			return ob_get_clean();
		}
	}

	/**
	 * <h4>构建对象</h4>
	 * <p>
	 * 根据对象名检测并构建对象。
	 * </p>
	 *
	 * @param string $class 对象名
	 * @param array $setting 即时配置
	 * @return mixed 对象实例，对象未知时返回空（null）。
	 */
	protected function build ($class = null, $setting = null)
	{
		### 对象名
		if ($class == null || ! is_string($class)) {
			return null;
		}
		$event = ucfirst("$this->target");
		### 优先加载扩展对象
		if (class_exists($event . $class)) {
			$class = $event . $class;
		} else if (! class_exists($class)) {
			### 对象未知
			$this->notice = Log::logf(__FUNCTION__, $class, __CLASS__, Log::EXCEPTION);
			#trigger_error($error, E_USER_ERROR);
			return null;
		}
		if (method_exists($class, 'access')) {
			### 静态构建
			return $class::access($setting);
		}
		### 常态构建
		return new $class($setting);
	}

	/**
	 * <h4>构建网址</h4>
	 * <p>
	 * 根据事件名与服务名构建相应网址。
	 * </p>
	 *
	 * @param string $event 事件名
	 * @param string $service 服务名
	 * @param string $setting 即时配置
	 * @return string 网址
	 */
	protected function url ($event = null, $service = null, $setting = null)
	{
		$params = array();
		if ($event != null) {
			$params[] = lcfirst(self::EVENT) . '=' . $event;
		}
		if ($service != null) {
			$params[] = lcfirst(self::SERVICE) . '=' . $service;
		}
		if ($setting != null) {
			foreach ((array) $setting as $item => $value) {
				if (is_string($item)) {
					$params[] = $item . '=' . $value;
				}
				if (is_int($item) && $value != null) {
					$params[] = $value;
				}
			}
		}
		$url = _WEB;
		if ($params != null) {
			$url .= '?' . implode('&', $params);
		}
		return $url;
	}

	/**
	 * <h3>判断项目是否存在</h3>
	 *
	 * @param string $item 项目名
	 * @return bool 是否存在
	 */
	public function offsetExists ($item)
	{
		return isset($this->runtime[$item]);
	}

	/**
	 * <h3>读取项目</h3>
	 *
	 * @param string $item 项目名
	 * @return mixed 项目值
	 */
	public function offsetGet ($item)
	{
		if (! $this->offsetExists($item)) {
			$this->runtime[$item] = null;
		}
		return $this->runtime[$item];
	}

	/**
	 * <h3>设定项目</h3>
	 *
	 * @param string $item 项目名
	 * @param mixed $value 项目值
	 * @return void
	 */
	public function offsetSet ($item, $value)
	{
		$this->runtime[$item] = $value;
	}

	/**
	 * <h3>清除项目</h3>
	 *
	 * @param string $item 项目名
	 * @return void
	 */
	public function offsetUnset ($item)
	{
		unset($this->runtime[$item]);
	}

	/**
	 * 属性未知时，返回空（null）。
	 */
	public function __get ($item)
	{
		Log::logf(__FUNCTION__, get_class($this) . '->' . $item, __CLASS__, Log::NOTICE);
		if (! isset($this->$item)) {
			$this->$item = null;
		}
		return null;
	}

	/**
	 * 行为未知时，返回空（null）。
	 */
	public function __call ($item, $params)
	{
		Log::logf(__FUNCTION__, get_class($this) . '->' . $item, __CLASS__, Log::WARING);
		return null;
	}

	/**
	 * 用作字符串时，使用当前对象名。
	 */
	public function __toString ()
	{
		return get_class($this);
	}

	/**
	 * <h4>构建子对象</h4>
	 * <p>
	 * 设置当前目标并应用预置数据和即时配置。
	 * </p>
	 *
	 * @param array $setting 即时配置
	 * @global Target 当前目标
	 */
	protected function __construct ($setting = null)
	{
		### 设置当前目标
		global $target;
		$this->target = $target;
		### 应用预置数据和即时配置
		$class = get_class($this);
		$this->stuff($this->config($class), $this->preset);
		$this->stuff($setting, $this->preset);
		$this->stuff($this->preset);
		### 检查依赖扩展
		foreach ((array) $this->extensions as $item) {
			if ($item != null && ! extension_loaded($item)) {
				$this->notice = Log::logf(__FUNCTION__, $item, __CLASS__, Log::ERROR);
				trigger_error($this->notice, E_USER_ERROR);
			}
		}
	}
}
