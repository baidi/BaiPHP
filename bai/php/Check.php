<?php
/**
 * <h2>BaiPHP（简单PHP）开发框架</h2>
 * @link      http://www.baiphp.com
 * @copyright Copyright (c) 2011 - 2012, 白晓阳
 * @author    白晓阳
 * @version   1.0.0 2012/03/31 首版
 *            2.0.0 2012/07/01 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 * <p>欢迎提供各种形式的捐助。任何捐助者自动获得仅限于捐助者自身的商业使用（不包括再发行和再授权）授权。</p>
 */

/**
 * <h2>BaiPHP（简单PHP）开发框架</h2>
 * <h3>检验工场</h3>
 * <p>
 * 检验输入内容并返回检验结果。
 * </p>
 * @author 白晓阳
 */
class Check extends Work
{
	/** 检验工场静态入口 */
	static private $ACCESS = null;

	/**
	 * <h4>获取检验工场入口</h4>
	 * @param array $setting 即时配置
	 * @return Check 检验工场
	 */
	static public function access($setting = null)
	{
		if ($setting != null || ! self::$ACCESS instanceof Check)
		{
			self::$ACCESS = new Check($setting);
		}
		return self::$ACCESS;
	}

	/**
	 * <h4>检验输入内容</h4>
	 * 检验规则: required min=9 max=9 type=N function=P<br/>
	 * <ul>
	 * <li>required   : 内容非空</li>
	 * <li>min=9      : 最小长度</li>
	 * <li>max=9      : 最大长度</li>
	 * <li>type=N     : 内容属性</li>
	 * <li>call=P     : 调用函数</li>
	 * </ul>
	 * 各规则可以任意组合，规则之间以空格分隔
	 * 检验规则在用户配置文件（config.php）中的self::Check下设置
	 * @param array $specific 即时配置
	 * @return mixed false：检验通过；string：提示信息
	 */
	public function entrust($specific = null)
	{
		### 读取检验设置
		$preset = $this->pick("$this->target", $this->preset);
		$this->stuff($specific, $preset);
		if ($preset == null || ! is_array($preset))
		{
			return true;
		}

		### 检验输入项目
		foreach ($preset as $item => $mode)
		{
			$this->runtime['item']  = $item;
			$this->runtime['mode']  = $mode;
			$this->runtime['value'] = $this->target[$item];
			$this->error = $this->item();
			if ($this->error)
			{
				$this->target->error = $this->error;
				return false;
			}
		}
		return true;
	}

	/**
	 * <h4>检验输入项目</h4>
	 * @return mixed false：检验通过；string：提示信息
	 */
	protected function item()
	{
		$item = $this->runtime['item'];
		$mode = $this->runtime['mode'];
		Log::logf(__FUNCTION__, array($item, $mode), __CLASS__);
		### 分离检验场景
		$preset = $this->pick(__CLASS__, $this->preset);
		$case = $this->pick('case', $preset);
		$param = $this->pick('param', $preset);
		preg_match_all($case, $mode, $cases, PREG_SET_ORDER);
		foreach ($cases as $case)
		{
			$call = $this->pick('call', $case);
			$params = $this->pick('params', $case);
			if ($params != null && is_string($params))
			{
				$params = explode($param, $params);
			}
			### 执行检验场景
			if ($params === null)
			{
				$message = $this->$call();
			}
			else if (! is_array($params))
			{
				$message = $this->$call($params);
			}
			else
			{
				$message = call_user_func_array(array($this, $call), $params);
			}
			if ($message)
			{
				return $message;
			}
		}
		return false;
	}

	/**
	 * <h4>风险字符检验</h4>
	 * @return mixed false：检验通过；string：提示信息
	 */
	protected function risk()
	{
		$value = $this->pick('value', $this->runtime);
		$preset = $this->pick(__CLASS__, $this->preset);
		$mode = $this->pick(__FUNCTION__, $preset);
		if ($value != null && preg_match($mode, $value))
		{
			return Log::logs(__FUNCTION__, __CLASS__);
		}
		return false;
	}

	/**
	 * <h4>非空检验</h4>
	 * @return mixed false：检验通过；string：提示信息
	 */
	protected function required()
	{
		$value = $this->pick('value', $this->runtime);
		if ($value == null)
		{
			return Log::logs(__FUNCTION__, __CLASS__);
		}
		return false;
	}

	/**
	 * <h4>最小长度检验</h4>
	 * @param int $length 长度
	 * @return mixed false：检验通过；string：提示信息
	 */
	protected function min($length)
	{
		$value = $this->pick('value', $this->runtime);
		if ($value == null || mb_strlen($value, 'utf-8') >= $length)
		{
			return false;
		}
		return Log::logf(__FUNCTION__, $length, __CLASS__);
	}

	/**
	 * <h4>最大长度检验</h4>
	 * @param int $length 长度
	 * @return mixed false：检验通过；string：提示信息
	 */
	protected function max($length)
	{
		$value = $this->pick('value', $this->runtime);
		if ($value == null || mb_strlen($value, 'utf-8') <= $length)
		{
			return false;
		}
		return Log::logf(__FUNCTION__, $length, __CLASS__);
	}

	/**
	 * <h4>属性检验</h4>
	 * <p>
	 * 根据正则表达式检验输入内容。
	 * </p>
	 * @param string $type 属性
	 * @return mixed false：检验通过；string：提示信息
	 */
	protected function type($type)
	{
		$value = $this->pick('value', $this->runtime);
		$preset = $this->pick(__CLASS__, $this->preset);
		$mode = $this->pick($type, $preset);
		if ($value == null || $mode == null || preg_match($mode, $value))
		{
			return false;
		}
		return Log::logs(__FUNCTION__, __CLASS__);
	}

	/**
	 * <h4>外部检验</h4>
	 * @param string $name 外部方法名
	 * @param array $params 参数
	 */
	public function __call($name, $params)
	{
		### 调用外部方法进行检验
		if (is_callable($name))
		{
			return call_user_func_array($name, $params);
		}
		return cLog(__FUNCTION__, __CLASS__);
	}
}
?>
