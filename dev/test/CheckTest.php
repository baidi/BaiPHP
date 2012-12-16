<?php
return array
(
		### Check测试：构建
		array
		(
				Test::ITEM   => 'access',
				Test::EXPECT => 'Check',
				Test::TYPE   => Test::TYPE_BUILD,
		),
		### Check测试：风险字符
		array
		(
				Test::ITEM   => 'assign',
				Test::PARAMS => new Event(array('event' => 'Test', 'letter' => 'abc%XYZ', 'number' => 1234)),
				Test::EXPECT => true,
				Test::TAB    => 'Check测试：风险字符',
		),
		### Check测试：必须项
		array
		(
				Test::ITEM   => 'assign',
				Test::PARAMS => new Event(array('event' => 'Test', 'letter' => '', 'number' => 1234)),
				Test::EXPECT => true,
				Test::TAB    => 'Check测试：必须项',
		),
		### Check测试：最小长度
		array
		(
				Test::ITEM   => 'assign',
				Test::PARAMS => new Event(array('event' => 'Test', 'letter' => 'a', 'number' => 1234)),
				Test::EXPECT => true,
				Test::TAB    => 'Check测试：最小长度',
		),
		### Check测试：最大长度
		array
		(
				Test::ITEM   => 'assign',
				Test::PARAMS => new Event(array('event' => 'Test', 'letter' => 'abcdefghXYZ', 'number' => 1234)),
				Test::EXPECT => true,
				Test::TAB    => 'Check测试：最大长度',
		),
		### Check测试：英文字母属性
		array
		(
				Test::ITEM   => 'assign',
				Test::PARAMS => new Event(array('event' => 'Test', 'letter' => 'abc汉字', 'number' => 1234)),
				Test::EXPECT => true,
				Test::TAB    => 'Check测试：英文字母属性',
		),
		### Check测试：验证通过
		array
		(
				Test::ITEM   => 'assign',
				Test::PARAMS => new Event(array('event' => 'Test', 'letter' => 'abcXYZ', 'number' => 1234)),
				Test::EXPECT => false,
				Test::TAB    => 'Check测试：验证通过',
		),
);
?>
