<?php
/**
 * 日志工场测试场景
 */
return array(
	### Log测试：构建
	array(
		Test::ITEM   => Log::LOG,
		Test::MODE   => Test::MODE_BUILD,
		Test::PARAMS => array(
			Log::LOG    => Log::ALL,
			Log::TEST   => array(
				'logs'  => 'Log测试：预置日志',
				'logf'  => 'Log测试：单一参数<%s>日志',
				'logf2' => 'Log测试：数组参数<%s>、<%s>日志',
			),
		),
	),
	### 测试情景：entrust（缺少项目）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => null,
	),
	### Log测试：entrust（即时日志）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => 'Log测试：即时日志',
		Test::PARAMS   => 'Log测试：即时日志',
	),
	### 测试情景：entrust（未定义项目）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => null,
		Test::PARAMS   => array('_logs', Test::TEST),
	),
	### 测试情景：entrust（未定义类别）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => null,
		Test::PARAMS   => array('logs', _DEFAULT.Test::TEST),
	),
	### Log测试：entrust(预置日志)
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => 'Log测试：预置日志',
		Test::PARAMS   => array('logs', Test::TEST),
	),
	### Log测试：entrust（单一参数）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => 'Log测试：单一参数<参数1>日志',
		Test::PARAMS   => array(
			'Log测试：单一参数<%s>日志',
			null,
			'参数1',
		),
	),
	### Log测试：entrust(数组参数)
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => 'Log测试：数组参数<参数1>、<参数2>日志',
		Test::PARAMS   => array(
			'logf2',
			Test::TEST,
			array('参数1', '参数2'),
		),
	),
	### Log测试：entrust（级别过滤）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => 'Log测试：预置日志',
		Test::PARAMS   => array('logs', Test::TEST, null, Log::DEBUG),
	),
	### Log测试：logs
	array(
		Test::ITEM     => 'logs',
		Test::EXPECTED => 'Log测试：预置日志',
		Test::PARAMS   => array('logs', Test::TEST),
	),
	### Log测试：logf
	array(
		Test::ITEM     => 'logf',
		Test::EXPECTED => 'Log测试：单一参数<参数1>日志',
		Test::PARAMS   => array('logf', '参数1', Test::TEST),
	),
);
?>
