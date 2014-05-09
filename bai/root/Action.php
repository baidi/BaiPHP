<?php
################################################################################
# BaiPHP Mobile Framework
# http://www.baiphp.com
# Copyright (C) 2011-2014 Xiao Yang, Bai
#
# Anyone obtaining a copy of BaiPHP gets permission to use, copy, modify, merge,
# publish, distribute, and/or sell it for non-profit purpose.
# Any contributor to BaiPHP gets for-profit permission for itself only, which
# can't be transferred or rent.
# Authors or copyright holders don't take any for all the consequences arising
# therefrom.
# By using BaiPHP, you are unconditionally agree to this notice and must keep it
# in the copy.
################################################################################


/**
 * <h2>BaiPHP Mobile Framework</h2>
 * <h3>Action process</h3>
 * <p>
 * 检验输入、更新缓存、访问数据库并分派至相应页面
 * ·check：输入检验方法，系统检验无法满足需求时应重写该方法。
 * 如果仍需要系统检验，可使用parent::check($event)。
 * ·data：数据访问方法，需要访问数据库时应重写该方法，调用Data工场实现。
 * </p>
 *
 * @copyright Copyright (C) 2011-2014 Xiao Yang, Bai
 * @author Xiao Yang, Bai
 */
class Action extends Process
{

	/**
	 * <h4>Check inputs</h4>
	 * <p>
	 * Check datas from current event.
	 * Override this method to customize check logic.
	 * </p>
	 *
	 * @return mixed true: pass;
	 *         string: error message;
	 */
	protected function check()
	{
		$check = self::build('Check');
		$result = $check->entrust();
		$this->message = $check->message;
		return $result;
	}

	/**
	 * <h4>Access database</h4>
	 * <p>
	 * Override this method to customize data logic.
	 * </p>
	 *
	 * @return mixed
	 */
	protected function data()
	{
		return true;
	}
}
?>
