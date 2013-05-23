<?php
return array
(
	### Event测试：构建
	array
	(
		Test::PARAMS => array(array('issue' => 'dev')),
		Test::EXPECT => 'Event',
		Test::TYPE   => Test::TYPE_BUILD,
	),
	### Event测试：分流名
	array
	(
		Test::ITEM   => 'issue',
		Test::EXPECT => 'dev',
		Test::TAB    => 'Event测试：分流名',
		Test::TYPE   => Test::TYPE_PROPERTY,
	),
	### Event测试：事件名
	array
	(
		Test::ITEM   => 'event',
		Test::EXPECT => Event::EVENT,
		Test::TAB    => 'Event测试：事件名',
		Test::TYPE   => Test::TYPE_SUBITEM,
	),
	### Event测试：赋值
	array
	(
		Test::ITEM   => 'assign',
		Test::PARAMS => array('test1', 'Event测试'),
		Test::EXPECT => 'Event测试',
		Test::TAB    => 'Event测试：赋值',
	),
	### Event测试：取值
	array
	(
		Test::ITEM   => 'assign',
		Test::PARAMS => array('test1'),
		Test::EXPECT => 'Event测试',
		Test::TAB    => 'Event测试：取值',
	),
	### Event测试：会话赋值
	array
	(
		Test::ITEM   => 'assign',
		Test::PARAMS => array('test2', 'Session测试', true),
		Test::EXPECT => 'Session测试',
		Test::TAB    => 'Event测试：会话赋值',
	),
	### Event测试：会话取值
	array
	(
		Test::ITEM   => 'assign',
		Test::PARAMS => array('test2', null, true),
		Test::EXPECT => 'Session测试',
		Test::TAB    => 'Event测试：会话取值',
	),
	### Event测试：无参数
	array
	(
		Test::ITEM   => 'assign',
		Test::EXPECT => null,
		Test::TAB    => 'Event测试：无参数',
	),
);
?>
