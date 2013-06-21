<?php
/**
 * 日志工场测试场景
 */
global $config;
$config[Test::TEST][Test::LOG] = array(
	### 构建
	array(
		Test::TITEM   => Test::LOG,
		Test::TMODE   => Test::TMODE_BUILD,
		Test::TPARAM  => array(
			'level'   => Log::ALL,
		    'size'    => 10,
			'store'   => array(
			    Test::TEST => array(
    				'logs'  => '日志工场测试：无参数日志',
    				'logf'  => '日志工场测试：单参数日志【%s】',
    				'logf2' => '日志工场测试：多参数日志【%s、%s】',
			    ),
			),
		),
	),
	### entrust（无项目）
	array(
		Test::TITEM     => 'entrust',
		Test::TEXPECTED => null,
	),
	### entrust（即时日志）
	array(
		Test::TITEM     => 'entrust',
		Test::TEXPECTED => '日志工场测试：即时日志',
		Test::TPARAM    => '日志工场测试：即时日志',
	),
	### entrust（未定义项目）
	array(
		Test::TITEM     => 'entrust',
		Test::TEXPECTED => null,
		Test::TPARAM    => array('_logs', Test::TEST),
	),
	### entrust（未定义类别）
	array(
		Test::TITEM     => 'entrust',
		Test::TEXPECTED => null,
		Test::TPARAM    => array('logs', _DEF.Test::TEST),
	),
	### entrust(无参数日志)
	array(
		Test::TITEM     => 'entrust',
		Test::TEXPECTED => '日志工场测试：无参数日志',
		Test::TPARAM    => array('logs', Test::TEST),
	),
	### entrust（单参数日志）
	array(
		Test::TITEM     => 'entrust',
		Test::TEXPECTED => '日志工场测试：单参数日志【参数1】',
		Test::TPARAM    => array(
			'logf',
			Test::TEST,
			'参数1',
		),
	),
	### entrust(多参数日志)
	array(
		Test::TITEM     => 'entrust',
		Test::TEXPECTED => '日志工场测试：多参数日志【参数1、参数2】',
		Test::TPARAM    => array(
			'logf2',
			Test::TEST,
			array('参数1', '参数2'),
		),
	),
	### entrust（级别过滤）
	array(
		Test::TITEM     => 'entrust',
		Test::TEXPECTED => '日志工场测试：无参数日志',
		Test::TPARAM    => array(
		    'logs',
		    Test::TEST,
		    null,
		    Log::DEBUG,
		),
	),
	### entrust（未知级别）
	array(
		Test::TITEM     => 'entrust',
		Test::TEXPECTED => '日志工场测试：无参数日志',
		Test::TPARAM    => array(
		    'logs',
		    Test::TEST,
		    null,
		    5,
		),
	),
	### entrust（清空缓冲区）
	array(
		Test::TITEM     => 'entrust',
		Test::TEXPECTED => null,
		Test::TPARAM    => array(Log::FLUSH),
	),
	### logs
	array(
		Test::TITEM     => 'logs',
		Test::TEXPECTED => '日志工场测试：无参数日志',
		Test::TPARAM    => array(
		    'logs',
		    Test::TEST,
		),
	),
	### logf
	array(
		Test::TITEM     => 'logf',
		Test::TEXPECTED => '日志工场测试：单参数日志【参数1】',
		Test::TPARAM    => array('logf', '参数1', Test::TEST),
	),
	### logf(多参数日志)
	array(
		Test::TITEM     => 'logf',
		Test::TEXPECTED => '日志工场测试：多参数日志【参数1、参数2】',
		Test::TPARAM    => array(
			'日志工场测试：多参数日志【%s、%s】',
			array('参数1', '参数2'),
		),
	),
);
?>
