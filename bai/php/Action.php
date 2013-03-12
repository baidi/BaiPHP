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
 * <h3>处理流程</h3>
 * <p>
 * 检验输入、更新缓存、访问数据库并分派至相应页面
 * ！！！服务器端响应客户端请求的核心流程，根据需要继承该类以实现不同的处理！！！
 * ·check：输入检验方法，系统检验无法满足需求时应重写该方法。
 *          如果仍需要系统检验，可使用parent::check($event)。
 * ·data：数据访问方法，需要访问数据库时应重写该方法，调用Data工场实现。
 * ·cache：提取缓存方法，需要操作缓存数据时应重写该方法。
 * ·page：分派页面方法，一般不建议重写该方法。
 * ·assign：事件处理方法，一般不建议重写该方法，除非需要打乱现有处理流程。
 *           ！！！打乱现有流程可能引发不可预料的结果，谨慎处置！！！
 * </p>
 * @author 白晓阳
 */
class Action extends Flow
{
	/**
	 * <h4>检验输入</h4>
	 * <p>
	 * 根据预置检验场景检验输入内容。
	 * 可重写该方法，以自定义检验场景。
	 * </p>
	 * @return mixed false：检验通过；string：提示信息
	 */
	protected function check()
	{
		$check = $this->build(Work::CHECK);
	    return $check->entrust();
	}

	/**
	 * <h4>访问数据库</h4>
	 * <p>
	 * 可重写该方法，以自定义数据访问。
	 * </p>
	 * @return mixed
	 */
	protected function data()
	{
	    return true;
	}
}
?>
