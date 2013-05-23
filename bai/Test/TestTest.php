<?php
return array
(
		### Log测试：构建
		array
		(
				Test::ITEM   => 'access',
				Test::EXPECT => 'Log',
				Test::TYPE   => Test::TYPE_BUILD,
		),
		### Log测试：即时日志
		array
		(
				Test::ITEM   => 'logs',
				Test::PARAMS => 'Log测试：即时日志',
				Test::EXPECT => 'Log测试：即时日志',
				Test::TAB    => 'Log测试：即时日志',
		),
		### Log测试：参数即时日志
		array
		(
				Test::ITEM   => 'logf',
				Test::PARAMS => array('Log测试：参数〖%s〗即时日志', '参数1'),
				Test::EXPECT => 'Log测试：参数〖参数1〗即时日志',
				Test::TAB    => 'Log测试：参数即时日志',
		),
		### Log测试：数组即时日志
		array
		(
				Test::ITEM   => 'logf',
				Test::PARAMS => array('Log测试：数组〖%s〗〖%s〗即时日志', array('参数1', '参数2')),
				Test::EXPECT => 'Log测试：数组〖参数1〗〖参数2〗即时日志',
				Test::TAB    => 'Log测试：数组即时日志',
		),
		### Log测试：预置日志
		array
		(
				Test::ITEM   => 'logs',
				Test::PARAMS => array('logs', 'Test'),
				Test::EXPECT => 'Log测试：预置日志',
				Test::TAB    => 'Log测试：预置日志',
		),
		### Log测试：参数预置日志
		array
		(
				Test::ITEM   => 'logf',
				Test::PARAMS => array('logf', '参数1', 'Test'),
				Test::EXPECT => 'Log测试：参数〖参数1〗预置日志',
				Test::TAB    => 'Log测试：参数〖参数1〗预置日志',
		),
		### Log测试：数组预置日志
		array
		(
				Test::ITEM   => 'logf',
				Test::PARAMS => array('logf2', array('参数1', '参数2'), 'Test'),
				Test::EXPECT => 'Log测试：数组〖参数1〗〖参数2〗预置日志',
				Test::TAB    => 'Log测试：数组预置日志',
		),
		### 测试情景：未定义项目
		array
		(
				Test::ITEM   => 'assign',
				Test::PARAMS => array('$item', 'Test'),
				Test::EXPECT => '〖未定义测试项：$item〗',
				Test::TAB    => '未定义项目',
		),
		### 测试情景：未定义类别
		array
		(
				Test::ITEM   => 'assign',
				Test::PARAMS => array('$item', '$Test'),
				Test::EXPECT => '〖未定义信息项：$Test〗',
				Test::TAB    => '未定义类别',
		),
		### 测试情景：无参数
		array
		(
				Test::ITEM   => 'assign',
				Test::PARAMS => array(),
				Test::EXPECT => null,
				Test::TAB    => '无参数',
		),
);
?>
