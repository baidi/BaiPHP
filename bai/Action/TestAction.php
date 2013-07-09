<?php
/**
 * <b>化简PHP（BaiPHP）开发框架</b>
 * @author		白晓阳
 * @copyright	Copyright (c) 2011 - 2012, 白晓阳
 * @link		http://dacbe.com
 * @version     V1.0.0 2012/03/31 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 */

/**
 * <b>化简PHP（BaiPHP）开发框架</b><br/>
 * <b>测试处理流程</b>
 * <p>
 * </p>
 * @author 白晓阳
 */
class TestAction extends Action
{
	/**
	 * <h4>执行测试</h4>
	 */
	protected function engage()
	{
		$testee = ucfirst($this->target['testee']);
		$test = $this->build(Work::TEST);
		$this->target[Flow::ACTION] = $test->entrust($testee, $testee.Work::TEST);
		$this->notice = $test->notice;
		return true;
	}
}
?>
