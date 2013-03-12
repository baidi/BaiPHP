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
 * <h2>BaiPHP（简单PHP）开发框架</h2>
 * <h3>流程</h3>
 * <p>流程具备下述特点：</p>
 * <ol>
 * <li>流程是精明的组织者，通过有序的组织工场以及与其他流程相复合，进而实现高级的复杂的功能。</li>
 * <li>流程相对易变，入口数据的不同可能导致流程的改变。</li>
 * <li>流程只开放一个常规公开入口。</li>
 * <li>流程具备动态执行自身方法的能力。</li>
 * <li>流程具备动态获取与使用工场和其他流程的能力。</li>
 * <li>流程能够按照预置程序（像一道流水线一样）智能运作。</li>
 * </ol>
 */
abstract class Flow extends Bai
{
	/** 标识：调度流程 */
	const CONTROL = 'Control';
	/** 标识：处理流程 */
	const ACTION = 'Action';
	/** 标识：页面流程 */
	const PAGE = 'Page';

	/**
	 * <h4>自定义事务</h4>
	 * @return boolean
	 */
	protected function engage()
	{
		return true;
	}
}
?>
