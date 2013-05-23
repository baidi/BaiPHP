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
 * <b>事件处理流程：</b>
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
class TestAction extends Action
{
	/**
	 * <h4>执行测试</h4>
	 */
	protected function engage()
	{
		$this->target['check1'] = '';
		$this->target['check2'] = '12345';
		$testee = $this->target['testee'];
		if ($testee != null)
		{
			Log::logf('entrust', Test::TEST, self::BAI);
			$test = $this->build(Test::TEST);
			$result = $test->entrust($testee, $testee.Test::TEST);
			$this->target[__CLASS__] = $result;
		}
	}
}
?>
