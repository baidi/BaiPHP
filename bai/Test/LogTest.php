<?php
/**
 * 日志工场测试场景
 */
global $config;
$config[Work::TEST][Work::LOG] = array(
	### 构建
	array(
		Test::ITEM   => Work::LOG,
		Test::MODE   => Test::MODE_BUILD,
		Test::PARAM  => array(
			'level'   => Log::ALL,
			'size'    => 10,
			'dic'     => array(
				Work::TEST => array(
					'logs'  => '日志工场测试：无参数日志',
					'logf'  => '日志工场测试：单参数日志【%s】',
					'logf2' => '日志工场测试：多参数日志【%s、%s】',
				),
			),
		),
	),
	### entrust（无项目）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => null,
	),
	### entrust（即时日志）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => '日志工场测试：即时日志',
		Test::PARAM    => '日志工场测试：即时日志',
	),
	### entrust（未定义项目）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => null,
		Test::PARAM    => array('_logs', Work::TEST),
	),
	### entrust（未定义类别）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => null,
		Test::PARAM    => array('logs', _DEF.Work::TEST),
	),
	### entrust(无参数日志)
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => '日志工场测试：无参数日志',
		Test::PARAM    => array('logs', Work::TEST),
	),
	### entrust（单参数日志）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => '日志工场测试：单参数日志【参数1】',
		Test::PARAM    => array(
			'logf',
			Work::TEST,
			'参数1',
		),
	),
	### entrust(多参数日志)
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => '日志工场测试：多参数日志【参数1、参数2】',
		Test::PARAM    => array(
			'logf2',
			Work::TEST,
			array('参数1', '参数2'),
		),
	),
	### entrust（级别过滤）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => '日志工场测试：无参数日志',
		Test::PARAM    => array(
			'logs',
			Work::TEST,
			null,
			Log::DEBUG,
		),
	),
	### entrust（未知级别）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => '日志工场测试：无参数日志',
		Test::PARAM    => array(
			'logs',
			Work::TEST,
			null,
			5,
		),
	),
	### entrust（清空缓冲区）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => null,
		Test::PARAM    => array(Log::FLUSH),
	),
	### logs
	array(
		Test::ITEM     => 'logs',
		Test::EXPECTED => '日志工场测试：无参数日志',
		Test::PARAM    => array(
			'logs',
			Work::TEST,
		),
	),
	### logf
	array(
		Test::ITEM     => 'logf',
		Test::EXPECTED => '日志工场测试：单参数日志【参数1】',
		Test::PARAM    => array('logf', '参数1', Work::TEST),
	),
	### logf(多参数日志)
	array(
		Test::ITEM     => 'logf',
		Test::EXPECTED => '日志工场测试：多参数日志【参数1、参数2】',
		Test::PARAM    => array(
			'日志工场测试：多参数日志【%s、%s】',
			array('参数1', '参数2'),
		),
	),
);
?>
