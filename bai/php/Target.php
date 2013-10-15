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
 * <h3>目标</h3>
 * <p>
 * 目标主体，收集并提供目标数据，以描述目标。
 * </p>
 *
 * @author 白晓阳
 */
class Target extends Bai
{
	/**
	 * 当前流域
	 */
	protected $basin = null;
	/**
	 * 当前事项
	 */
	protected $event = null;
	/**
	 * 抛锚点
	 */
	protected $anchor = null;
	/**
	 * 内容过滤
	 */
	protected $filters = null;
	/**
	 * 配置文件
	 */
	protected $configs = null;

	/**
	 * <h4>委托当前目标到流程</h4>
	 *
	 * @param array $setting 即时配置
	 * @return mixed 交付结果
	 */
	public function entrust ($setting = null)
	{
		$event = $this->basin . $this->event;
		Log::logf(__FUNCTION__, $event, __CLASS__);
		Log::logf('start', date('Y-m-d H:m:s.B', _START), __CLASS__);
		$this->result = $this->run($setting);
		Log::logf('close', microtime(true) - _START, __CLASS__);
		Log::logf('deliver', $event, __CLASS__);
		return $this->result;
	}

	/**
	 * <h4>访问数据过滤</h4>
	 *
	 * @param string $inputs 输入数据
	 * @return mixed 过滤后数据
	 */
	protected function filter ($inputs = null)
	{
		if ($inputs == null || $this->filters == null || ! is_array($this->filters)) {
			return $inputs;
		}
		foreach ($this->filters as $item => $mode) {
			$inputs = preg_replace($item, $mode, $inputs);
		}
		return $inputs;
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
			if (isset($this->$item)) {
				$this->runtime[$item] = $this->$item;
			}
		}
		return $this->runtime[$item];
	}

	/**
	 * 用作字符串时，使用当前目标名。
	 */
	public function __toString ()
	{
		return $this->event;
	}

	/**
	 * <h4>构建目标</h4>
	 * <p>
	 * 根据$_SESSION、$_GET、$_POST、预置数据、自定义数据构建当前目标。
	 * 数据优先级依次提升，但预置数据中的当前事项和服务路径会被提交的数据覆盖。
	 * </p>
	 *
	 * @param array $setting 即时配置
	 */
	public function __construct ($setting = 'config.php')
	{
		### 启动会话
		if (session_id() == null) {
			session_id($_SERVER['SERVER_NAME']);
			session_start();
		}
		### 加载配置
		foreach ((array) $setting as $item) {
			$this->load($item, true, $this->config(_DEF, 'Root'));
		}
		$configs = $this->config(__CLASS__, 'configs');
		if ($configs != null) {
			$configs = array_diff((array) $configs, (array) $setting);
			foreach ($configs as $item) {
				$this->load($item, true, $this->config(_DEF, 'Root'));
			}
		}
		### 应用预置数据
		$this->stuff($this->config(__CLASS__));
		### 应用全局数据
		$this->stuff($_SESSION, $this->runtime);
		$this->stuff($this->filter($_COOKIE), $this->runtime);
		$this->stuff($this->filter($_GET), $this->runtime);
		$this->stuff($this->filter($_POST), $this->runtime);
		### 应用目标事项
		$event = $this['event'];
		if ($event == null) {
			$event = $this->config(_DEF, self::EVENT);
		}
		if ($event != null) {
			$this->event = $event;
		}
		### 应用服务入口
		$basin = $this['basin'];
		if ($basin == null) {
			$basin = $this->config(_DEF, self::BASIN);
		}
		if ($basin != null) {
			$this->basin = $basin;
		}
		$this->target = $this;
	}
}
