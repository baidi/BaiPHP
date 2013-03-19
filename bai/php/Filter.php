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
 * <h3>过滤工场</h3>
 * <p>
 * 过滤非法访问与非法数据。
 * </p>
 * @author 白晓阳
 */
class Filter extends Work
{
	const UP = 'UP';
	const LOW = 'LOW';
	/** 过滤工场静态入口 */
	static private $ACCESS = null;

	/**
	 * <h4>获取过滤工场入口</h4>
	 * @param array $setting 自定义配置
	 * @return Style 输入工场
	 */
	static public function access($setting = null)
	{
		if ($setting != null || self::$ACCESS == null)
		{
			self::$ACCESS = new Filter($setting);
		}
		return self::$ACCESS;
	}

	protected function address()
	{
		$preset = $this->pick(__FUNCTION__, $this->preset);
		if ($preset == null || ! is_array($preset))
		{
			return true;
		}
		### 访问地址过滤
		$client = $this->rename($_SERVER['REMOTE_ADDR']);
		foreach ($preset as $item => $mode)
		{
			$item = $this->rename($item);
			if ($mode === self::UP && $client > $item
					|| $mode == self::LOW && $client < $item
					|| ! $mode && $client != $item)
			{
				return true;
			}
		}
		return true;
	}
	
	protected function rename($ip = null)
	{
		if ($ip == null || ! is_string($ip))
		{
			return null;
		}
		$preset = $this->pick(__FUNCTION__, $this->preset);
		if ($preset == null || ! is_string($preset))
		{
			return null;
		}
		preg_match($preset, $ip, $range);
		if ($range == null)
		{
			Log::logf(__FUNCTION__, $ip, __CLASS__, Log::NOTICE);
			return null;
		}
		$result = 0;
		for ($i = 1, $m = count($range); $i < $m; $i ++)
		{
			$result += $range[$i] * (1 << ($i - 1) * 8);
		}
		return $result;
	}

	protected function request()
	{
		
	}

	protected function user()
	{
		
	}

	protected function data()
	{
		
	}
}