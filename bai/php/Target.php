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
class Target extends Bai
{
	/** 抛锚点 */
	protected $anchor = null;
	/** 内容过滤 */
	protected $filters = null;

	/**
	 * <h4>委托当前目标到流程</h4>
	 * @param array $setting 即时配置
	 * @return mixed 交付结果
	 */
	public function entrust($setting = null)
	{
		$event = $this[self::SERVICE].$this[self::EVENT];
		Log::logf(__FUNCTION__, $event, __CLASS__);
		Log::logf('start', date('Y-m-d H:m:s', _START), __CLASS__, Log::PERFORM);
		$this->result = $this->run($setting);
		Log::logf('close', microtime(true) - _START, __CLASS__, Log::PERFORM);
		Log::logf('deliver', $event, __CLASS__);
		return $this->result;
	}

	/**
	 * <h4>访问数据过滤</h4>
	 * @param string $inputs 输入数据
	 * @return mixed 过滤后数据
	 */
	protected function filter($inputs = null)
	{
		if ($inputs == null)
		{
			return $inputs;
		}
		if ($this->filters == null || ! is_array($this->filters))
		{
			return $inputs;
		}
		### 过滤文字
		if (! is_array($inputs))
		{
			foreach ($this->filters as $item => $mode)
			{
				$inputs = preg_replace($item, $mode, $inputs);
			}
			return $inputs;
		}
		### 过滤数组
		foreach ($inputs as $item => &$value)
		{
			$value = $this->filter($value);
		}
		return $inputs;
	}

	/**
	 * <h3>设定项目值</h3>
	 * @param string $item 项目名
	 * @param mixed $value 项目值
	 * @return void
	 */
	static public function session($item, $value)
	{
		global $target;
		$_SESSION[$item] = $value;
		$target->$item = $value;
		return $value;
	}

	/**
	 * 用作字符串时，使用当前目标名。
	 */
	public function __toString()
	{
		return $this[self::EVENT];
	}

	/**
	 * <h4>构建目标</h4>
	 * <p>
	 * 根据$_SESSION、$_GET、$_POST、预置数据、自定义数据构建当前目标。
	 * 优先级依次提升，但预置数据中的当前事项和服务路径会被提交的数据覆盖。
	 * </p>
	 * @param array $setting 即时配置
	 */
	public function __construct($setting = null)
	{
		### 启动会话
		#session_id(md5($_SERVER['REMOTE_HOST'].$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']));
		session_start();
		### 加载配置
		if ($setting != null)
		{
			$bai     = _LOCAL.$this->config(_DEFAULT, self::BAI);
			$service = _LOCAL.$this->config(_DEFAULT, self::SERVICE);
			$root    = $this->config(_DEFAULT, 'Root');
			foreach ((array)$setting as $item)
			{
				if ($item == null || ! is_string($item))
				{
					continue;
				}
				if (substr($item, - strlen(_EXT)) !== _EXT)
				{
					$item .= _EXT;
				}
				if (is_file($bai.$root.$item))
				{
					include $bai.$root.$item;
				}
				if (is_file($service.$root.$item))
				{
					include $service.$root.$item;
				}
			}
			$this->preset = $this->config(__CLASS__);
		}
		### 应用预置数据
		$this->stuff($this->config(_DEFAULT));
		$this->stuff($this->config(self::BAI));
		$this->stuff($this->config(self::TARGET));
		### 应用全局数据
		$this->stuff($_SESSION);
		$this->stuff($this->filter($_COOKIE));
		$this->stuff($this->filter($_GET));
		$this->stuff($this->filter($_POST));
		### 应用目标事项
		if ($this[lcfirst(self::EVENT)] != null)
		{
			$this[self::EVENT] = $this[lcfirst(self::EVENT)];
		}
		### 应用目标入口
		if ($this[lcfirst(self::SERVICE)] != null)
		{
			$this[self::SERVICE] = $this[lcfirst(self::SERVICE)];
		}
		$this->target = $this;
	}
}
