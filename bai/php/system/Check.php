<?php

### 权限检查
if (! defined('_ISSUE'))
	exit('对不起！请走正门……');

/**
 * <b>Bai输入检验工场：</b>
 * <p>检验输入内容并返回检验结果</p>
 *
 * @author    白晓阳
 * @copyright Copyright (c) 2011 - 2012, 白晓阳
 * @link       http://www.dacbe.com
 * @version    V1.0.0 2012/03/21 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 */
class Check extends Work
{
	/** 日志入口 */
	protected $log = null;
	/** 检验静态入口 */
	private static $ACCESS = null;

	/**
	 * 获取检验入口
	 * @param 预设检验内容： $preset
	 */
	public static function access($preset = null)
	{
		if (! self::$ACCESS)
			self::$ACCESS = new Check($preset);
		return self::$ACCESS;
	}

	/**
	 * 检验输入项目
	 * 检验规则: required min=9 max=9 type=N function=param
	 *           required: 非空
	 *           min.9   : 最小长度
	 *           max.9   : 最大长度
	 *           type    : 内容属性
	 *           function: 调用函数
	 *
	 * @param 请求事件： $event
	 */
	public function assign($event)
	{
		if (empty($this->preset[$event]))
			return false;
		$checks = $this->preset[$event];

		$message = $this->log->message(__CLASS__);
		$this->log->assign($message, Log::L_DEBUG);

		foreach ($checks as $item => $check)
		{
			if (! $check)
				continue;
			$message = $this->log->messagef(__CLASS__, $item.'【'.$check.'】', __CLASS__);
			$this->log->assign($message, Log::L_DEBUG);
			### 输入内容检验
			$message = $this->checkItem(cRead($item), explode(' ', $check));
			if ($message)
				return $message;
		}
		return false;
	}

	/**
	 * 检验项目内容
	 * @param 项目内容： $value
	 * @param 检验内容： $checks
	 */
	private function checkItem($value, $checks)
	{
		try
		{
			foreach ($checks as $check)
			{
				$params = explode('=', $check);
				$message = $this->$params[0]($value, array_slice($params, 1));
				if ($message)
				{
					$this->log->assign($message);
					return $message;
				}
			}
		}
		catch (Exception $e)
		{
			$message = $this->log->messagef(__FUNCTION__, $check, __CLASS__);
			$this->log->assign($message);
			$this->log->assign($e->getMessage());
			return $message;
		}
		return false;
	}

	/**
	 * 非空检验
	 * @param 项目内容： $value
	 * @param 检验参数： $params
	 */
	public function required($value, $params = null)
	{
		$message = $this->log->message(__FUNCTION__, __CLASS__);
		if ($value == null)
			return $message;
		#if (is_array($value) && ! $value)
		#	return $message;
		if (is_string($value) && trim($value) == '')
			return $message;
		return false;
	}

	/**
	 * 最小长度检验
	 * @param 项目内容： $value
	 * @param 检验参数:  $params
	 */
	public function min($value, $params = null)
	{
		if (! $params)
			return false;
		$message = $this->log->messagef(__FUNCTION__, $params[0], __CLASS__);
		if (mb_strlen($value, 'utf-8') < $params[0])
			return $message;
		return false;
	}

	/**
	 * 最大长度检验
	 * @param 项目内容： $value
	 * @param 检验参数:  $params
	 */
	public function max($value, $params = null)
	{
		if (! $params)
			return false;
		$message = $this->log->messagef(__FUNCTION__, $params[0], __CLASS__);
		if (mb_strlen($value, 'utf-8') > $params[0])
			return $message;
		return false;
	}

	/**
	 * 属性检验
	 * @param 项目内容： $value
	 * @param 检验参数:  $params
	 */
	public function type($value, $params = null) {
		if ((! $value && $value != '0') || ! $params) {
			return false;
		}

		switch (strtolower($params[0]))
		{
			case 'number': ### 数字
				$message = preg_match('#^[1-9]\d*$#', $value);
				break;
			case 'float': ### 数值
				$message = preg_match('#^[+-]?\d+(?:\.\d+)?$#', $value);
				break;
			case 'letter': ### 英文字母
				$message = preg_match('#^[a-zA-Z]+$#', $value);
				break;
			case 'char': ### 英文字母数字划线
				$message = preg_match('#^[a-zA-Z0-9_-]+$#', $value);
				break;
			case 'mp': ### 移动电话
				$message = preg_match('#^(?:\+86)?1[358][0-9]{9}$#', $value);
				break;
			case 'fax': ### 固话传真
				$message = preg_match('#^0[0-9]{2,3}-[1-9][0-9]{6,7}$#', $value);
				break;
			case 'url': ### 网址
				$message = preg_match('#^(?:http://)?[a-zA-Z0-9-_./]+(?:\?.+)?$#', $value);
				break;
			case 'email': ### 邮箱
				$message = preg_match('#^[a-zA-Z0-9-_.]+@[a-zA-Z0-9-_.]+$#', $value);
				break;
			case 'date': ### 日期
				$message = preg_match('#^[0-9]{4}[-./]?(?:0?[1-9]|1[0-2])[-./]?(?:0?[1-9]|[12][0-9]|3[01])$#', $value);
				break;
			case 'time': ### 时间
				$message = preg_match('#^(?:0?[0-9]|1[0-9]|2[0-3])[:-]?(?:0?[0-9]|[1-5][0-9])[:-]?(?:0?[0-9]|[1-5][0-9])$#', $value);
				break;
			default:
				$message = true;
		}
		return $message ? false : $this->log->message(__FUNCTION__, __CLASS__);
	}

	/**
	 * 验证码检验
	 * @param 项目内容： $value
	 * @param 检验参数:  $params
	 */
	public function vcode($value, $params = null)
	{
		$message = $this->log->message(__FUNCTION__, __CLASS__);
		if (empty($value))
			return false;
		if (! $params)
			return $message;
		if (strtoupper($value) != cRead(__FUNCTION__))
			return $message;
		return false;
	}

	private function __construct($preset)
	{
		$this->log = Log::access();
		if (is_array($preset))
			$this->preset = $preset;
		#if (! $this->preset)
		#	error_log('检验内容为空！');
	}
}
?>