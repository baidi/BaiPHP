<?php
return array
(
		### Cache测试：构建
		array
		(
				Test::ITEM   => 'access',
				Test::EXPECT => 'Cache',
				Test::TYPE   => Test::TYPE_BUILD,
		),
		### Cache测试：存入
		array
		(
				Test::ITEM   => 'assign',
				Test::PARAMS => array('cache1', '缓存内容1'),
				Test::EXPECT => true,
				Test::TAB    => 'Cache测试：存入',
		),
		### Cache测试：取出
		array
		(
				Test::ITEM   => 'assign',
				Test::PARAMS => array('cache1'),
				Test::EXPECT => '缓存内容1',
				Test::TAB    => 'Cache测试：取出',
		),
		### Cache测试：存入文件
		array
		(
				Test::ITEM   => 'assign',
				Test::PARAMS => array('cache2', '缓存文件1', true),
				Test::EXPECT => true,
				Test::TAB    => 'Cache测试：存入文件',
		),
		### Cache测试：取出文件
		array
		(
				Test::ITEM   => 'assign',
				Test::PARAMS => array('cache2'),
				Test::EXPECT => '缓存文件1',
				Test::TAB    => 'Cache测试：取出文件',
		),
		### Cache测试：清空
		array
		(
				Test::ITEM   => 'assign',
				Test::PARAMS => array(Cache::CLEAR),
				Test::EXPECT => Cache::CLEAR,
				Test::TAB    => 'Cache测试：清空',
		),
);
?>
