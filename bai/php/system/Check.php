<?php
/**
 * <b>BaiPHP（简单PHP）开发框架</b>
 * @author		白晓阳
 * @copyright	Copyright (c) 2011 - 2012, 白晓阳
 * @link		http://dacbe.com
 * @version     V1.0.0 2012/03/31 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 */

/**
 * <b>BaiPHP（简单PHP）开发框架</b><br/>
 * <b>输入检验工场</b>
 * <p>检验输入内容并返回提示信息</p>
 *
 * @author 白晓阳
 * @see Work
 */
class Check extends Work
{
	/** 检验项目分割符 */
	const GAP_CHECK = ' ';
	/** 检验条件分隔符 */
	const GAP_PARAM = '=';
	/** 检验工场静态入口 */
	static private $ACCESS = null;

	/**
	 * <b>获取检验工场入口</b><br/>
	 * 该入口是静态单一入口
	 * 
	 * @param string $preset 预置检验规则
	 * 
	 * @return Check
	 */
	static public function access(array $preset = null)
	{
		if (! self::$ACCESS) {
			self::$ACCESS = new Check($preset);
		}
		return self::$ACCESS;
	}

	/**
	 * <b>检验输入内容</b><br/>
	 * 检验规则: required min=9 max=9 type=N function=P<br/>
	 * <ul>
	 * <li>required : 非空</li>
	 * <li>min=9 : 最小长度</li>
	 * <li>max=9 : 最大长度</li>
	 * <li>type=N : 内容属性</li>
	 * <li>function=P : 调用函数</li>
	 * </ul>
	 * 各规则可以任意组合，规则之间以空格分隔
	 * 检验规则在用户配置文件（user.php）中的Check下设置
	 * 
	 * @param Event $event 事件
	 * 
	 * @return mixed false：检验通过；string：提示信息
	 */
	public function entrust(Event $event = null)
	{
		### 检验规则未设置
		if (empty($this->preset[$event->event]))
		{
			return false;
		}
	
		### 检验规则
		$checks = $this->preset[$event->event];
		Log::logs(__CLASS__, 'Event');
		foreach ($checks as $item => $check)
		{
			Log::logf(__CLASS__, $item.'〖'.$check.'〗', __CLASS__);
			### 危险字符检验
			$message = $this->risk($event->$item);
			if ($message)
			{
				$event[__CLASS__][$item] = $message;
				continue;
			}
			if (! $check)
			{
				continue;
			}
			### 输入项目检验
			$message = $this->checkItem($event->$item, explode(self::GAP_CHECK, $check));
			if ($message)
			{
				$event[__CLASS__][$item] = $message;
			}
		}
		return ! empty($event[__CLASS__]);
	}

	/**
	 * <b>检验输入项目</b><br/>
	 * 
	 * @param string $item 检验项目
	 * @param array $checks 检验内容
	 * 
	 * @return mixed false：检验通过；string：提示信息
	 */
	private function checkItem($item, array $checks)
	{
		foreach ($checks as $check)
		{
			$params = explode(self::GAP_PARAM, $check);
			### 调用检验方法
			$message = $this->$params[0]($item, array_slice($params, 1));
			if ($message)
			{
				### 检验未通过
				return $message;
			}
		}
		return false;
	}

	/**
	 * <b>敏感字符检验</b><br/>
	 * 
	 * @param string $item 检验项目
	 * 
	 * @return mixed false：检验通过；string：提示信息
	 */
	protected function risk($item)
	{
		if ($item && preg_match(c('Input', 'Risk'), $item))
		{
			return Log::logs(__FUNCTION__, __CLASS__);
		}
		return false;
	}

	/**
	 * <b>非空检验</b><br/>
	 * 
	 * @param string $item 检验项目
	 * @param array $params 检验参数
	 * 
	 * @return mixed false：检验通过；string：提示信息
	 */
	protected function required($item, array $params = null)
	{
		if (! $item && $item != '0')
		{
			return Log::logs(__FUNCTION__, __CLASS__);
		}
		return false;
	}

	/**
	 * <b>最小长度检验</b><br/>
	 * 
	 * @param string $item 检验项目
	 * @param array $params 检验参数
	 * 
	 * @return mixed false：检验通过；string：提示信息
	 */
	protected function min($item, array $params = null)
	{
		if ((! $item && $item != '0') || ! $params
				|| mb_strlen($item, 'utf-8') >= $params[0])
		{
			return false;
		}
		return Log::logf(__FUNCTION__, $params[0], __CLASS__);
	}

	/**
	 * <b>最大长度检验</b><br/>
	 * 
	 * @param string $item 检验项目
	 * @param array $params 检验参数
	 * 
	 * @return mixed false：检验通过；string：提示信息
	 */
	protected function max($item, array $params = null)
	{
		if ((! $item && $item != '0') || ! $params
				|| mb_strlen($item, 'utf-8') <= $params[0])
		{
			return false;
		}
		return Log::logf(__FUNCTION__, $params[0], __CLASS__);
	}

	/**
	 * <b>属性检验</b><br/>
	 * 根据全局配置中的正则表达式检验输入内容
	 * 
	 * @param string $item 检验项目
	 * @param array $params 检验参数
	 * 
	 * @return mixed false：检验通过；string：提示信息
	 */
	protected function type($item, array $params = null)
	{
		if ((! $item && $item != '0') || ! $params)
		{
			return false;
		}

		$rule = c('Input', 'Type', $params[0]);
		if (! $rule || preg_match($rule, $item))
		{
			return false;
		}

		return Log::logs(__FUNCTION__, __CLASS__);
	}
	
	/**
	 * <b>自动调用外部方法进行检验</b><br/>
	 * 外部方法参数应声明为：($item：检验内容；array $params：参数列表)
	 *
	 * @param string $name 方法名
	 * @param array $params 检验参数
	 *
	 * @return mixed false：检验通过；string：提示信息
	 */
	public function __call($name, $params)
	{
		if (function_exists($name))
		{
			return $name($params[0], $params[1]);
		}
		return Log::logs(__FUNCTION__, __CLASS__);
	}

	/**
	 * <b>检验工场初始化</b><br/>
	 * 初始化各事件的检验规则
	 * 
	 * @param string $preset 预置检验规则
	 */
	private function __construct(array $preset)
	{
		if ($preset)
		{
			$this->preset = $preset;
		}
	}
}
?>
