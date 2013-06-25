<?php
/**
 * <h2>化简PHP（BaiPHP）开发框架</h2>
 * @link      http://www.baiphp.net
 * @copyright Copyright (c) 2011 - 2012, 白晓阳
 * @author    白晓阳
 * @version   1.0.0 2012/03/31 首版
 *            2.0.0 2012/07/01 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 * <p>欢迎提供各种形式的捐助。任何捐助者自动获得仅限于捐助者自身的商业使用（不包括再发行和再授权）授权。</p>
 */

/**
 * <h2>化简PHP（BaiPHP）开发框架</h2>
 * <h3>检验工场</h3>
 * <p>
 * 检验输入内容并返回检验结果。
 * </p>
 * @author 白晓阳
 */
class Check extends Work
{
	/** 检验工场标识：检验项目 */
	const ITEM  = 'ITEM';
	/** 检验工场标识：检验参数 */
	const PARAM = 'PARAM';

	/** 字符编码 */
	protected $charset = 'utf-8';
	/** 参数分割符 */
	protected $gap     = ',';
	/** 检验模式 */
	protected $mode    = null;
	/** 类型模式 */
	protected $types   = null;
	/** 检验内容 */
	protected $events  = null;

	/** 检验工场静态入口 */
	private static $ACCESS = null;

	/**
	 * <h4>获取检验工场入口</h4>
	 * @param array $setting 即时配置
	 * @return Check 检验工场
	 */
	public static function access($setting = null)
	{
		if ($setting != null || ! self::$ACCESS instanceof Check) {
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
	 * @param array $setting 即时配置
	 * @return mixed false：检验通过；string：提示信息
	 */
	public function entrust($setting = null)
	{
		if ($this->mode == null || ! is_string($this->mode)) {
			Log::logs('config', __CLASS__, Log::NOTICE);
			return true;
		}

		### 读取检验设置
		$event = $this->pick("$this->target", $this->events);
		$this->stuff($setting, $event);
		if ($event == null || ! is_array($event)) {
			return true;
		}

		### 检验输入项目
		foreach ($event as $item => $mode) {
			if ($item == null || ! is_string($item) || $mode == null || ! is_string($mode)) {
				continue;
			}
			$this['item']  = $item;
			$this['mode']  = $mode;
			$this['value'] = $this->target[$item];
			$this->notice = $this->checks();
			if ($this->notice) {
				#$this->target->notice = $this->notice;
				return false;
			}
		}
		return true;
	}

	/**
	 * <h4>检验输入项目</h4>
	 * @return mixed false：检验通过；string：提示信息
	 */
	protected function checks()
	{
		### 执行数据
		$item = $this['item'];
		$mode = $this['mode'];
		$value = $this['value'];
		Log::logf(__FUNCTION__, array($item, $mode), __CLASS__);
		### 解析检验模式
		if (! preg_match_all($this->mode, $mode, $cases, PREG_SET_ORDER)) {
			return false;
		}
		foreach ($cases as $case) {
			$check = $this->pick(self::ITEM,  $case);
			if ($check !== 'required' && $value == null) {
				continue;
			}
			$param = $this->pick(self::PARAM, $case);
			if ($param != null) {
				$param = explode($this->gap, $param);
			}
			### 执行检验场景
			if ($param === null) {
				$message = $this->$check();
			} else {
				$message = call_user_func_array(array($this, $check), $param);
			}
			if ($message) {
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
		$value = $this['value'];
		$mode  = $this->pick(__FUNCTION__, $this->types);
		if ($value != null && preg_match($mode, $value)) {
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
		$value = $this['value'];
		if ($value == null) {
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
		if ($length === null || ! is_numeric($length) || $length < 0) {
			Log::logs('config', __CLASS__, Log::NOTICE);
			return false;
		}
		$value = $this['value'];
		if (mb_strlen($value, $this->charset) >= $length) {
			return false;
		}
		return Log::logf(__FUNCTION__, $length, __CLASS__);
	}

	/**
	 * <h4>最大长度检验</h4>
	 * @param int $length 长度
	 * @param int $decimal 小数长度
	 * @return mixed false：检验通过；string：提示信息
	 */
	protected function max($length = null, $decimal = 0)
	{
		if ($length === null || ! is_numeric($length) || $length <= 0) {
			Log::logs('config', __CLASS__, Log::NOTICE);
			return false;
		}
		$value = $this['value'];
		if (is_numeric($decimal) && $decimal > 0) {
			### 检验数字
			$numbers = explode('.', $value);
			$size = count($numbers);
			### 整数部分长度
			if ($size == 1 && mb_strlen($numbers[0], $this->charset) <= $length - $decimal) {
				return false;
			}
			### 整数部分及小数部分
			if ($size == 2 && mb_strlen($numbers[0], $this->charset) <= $length - $decimal
					&& mb_strlen($numbers[1], $this->charset) <= $decimal) {
				return false;
			}
			return Log::logf(__FUNCTION__, $length, __CLASS__);
		}
		if (mb_strlen($value, $this->charset) <= $length) {
			return false;
		}
		return Log::logf(__FUNCTION__, $length, __CLASS__);
	}

	/**
	 * <h4>数值范围检验</h4>
	 * @param number $min 最小值
	 * @param number $max 最大值
	 * @return mixed false：检验通过；string：提示信息
	 */
	protected function range($min = null, $max = null)
	{
		if ($min === null || $max === null || ! is_numeric($min) || ! is_numeric($max)) {
			Log::logs('config', __CLASS__, Log::NOTICE);
			return false;
		}
		$value = $this['value'];
		if ($value >= $min && $value <= $max) {
			return false;
		}
		return Log::logf(__FUNCTION__, array($min, $max), __CLASS__);
	}

	/**
	 * <h4>单选项检验</h4>
	 * @return mixed false：检验通过；string：提示信息
	 */
	protected function enum()
	{
		$options = func_get_args();
		if ($options == null) {
			Log::logs('config', __CLASS__, Log::NOTICE);
			return false;
		}
		$value = $this['value'];
		if (array_search($value, $options) !== false
				|| array_search("'$value'", $options) !== false) {
			return false;
		}
		return Log::logs(__FUNCTION__, __CLASS__);
	}

	/**
	 * <h4>单选项检验</h4>
	 * @return mixed false：检验通过；string：提示信息
	 */
	protected function set()
	{
		$options = func_get_args();
		if ($options == null) {
			Log::logs('config', __CLASS__, Log::NOTICE);
			return false;
		}
		$values = $this['value'];
		$values = explode($this->gap, $values);
		foreach ($values as $value) {
			if ($value == null || array_search($value, $options) !== false
					|| array_search("'$value'", $options) !== false) {
				continue;
			}
			return Log::logs(__FUNCTION__, __CLASS__);
		}
		return false;
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
		$value = $this['value'];
		$mode  = $this->pick($type, $this->types);
		if ($mode == null || preg_match($mode, $value)) {
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
		if (is_callable($name)) {
			return call_user_func_array($name, $params);
		}
		return Log::logs(__FUNCTION__, __CLASS__);
	}
}
?>
