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
 * <h3>Process</h3>
 * <p>流程具备下述特点：</p>
 * <ol>
 * <li>流程是精明的组织者，通过有序的组织工场以及与其他流程相复合，进而实现高级的复杂的功能。</li>
 * <li>流程相对易变，入口数据的不同可能导致流程的改变。</li>
 * <li>流程只开放一个常规公开入口。</li>
 * <li>流程具备动态执行自身方法的能力。</li>
 * <li>流程具备动态获取与使用工场和其他流程的能力。</li>
 * <li>流程能够按照预置程序（像一道流水线一样）智能运作。</li>
 * </ol>
 *
 * @copyright Copyright (C) 2011-2014 Xiao Yang, Bai
 * @author Xiao Yang, Bai
 */
abstract class Process extends Bai
{
	/**
	 * ID: Control process
	 */
	const CONTROL = 'Control';
	/**
	 * ID: Action process
	 */
	const ACTION = 'Action';
	/**
	 * ID: Page process
	 */
	const PAGE = 'Page';

    /**
     * <h4>Pre action</h4>
     *
     * @return boolean
     */
    protected function prepare()
    {
        return true;
    }

    /**
     * <h4>Custom action</h4>
     *
     * @return boolean
     */
    protected function engage()
    {
        return true;
    }
}
