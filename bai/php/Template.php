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
 * <h3>Html工场</h3>
 * <p>
 * 生成Html标签。
 * </p>
 * @author 白晓阳
 */
class Template extends Work
{
	/** 输入工场静态入口 */
	static private $ACCESS = null;

	/**
	 * <h4>获取输入工场入口</h4>
	 * @param array $setting 自定义配置
	 * @return Style 输入工场
	 */
	static public function access($setting = null)
	{
		if ($setting != null || self::$ACCESS == null)
		{
			self::$ACCESS = new Style($setting);
		}
		return self::$ACCESS;
	}

	/**
	 * <h4>生成输入项</h4>
	 * <p>
	 * 生成输入项。
	 * </p>
	 * @param array $item 输入项
	 * @return string 输入项（HTML）
	 */
	public function entrust($item = null)
 	{
 		if ($item == null)
 		{
 			return null;
 		}
 		$event = $this->target[self::EVENT];
 	}
}
