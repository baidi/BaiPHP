<?php
return array
(
	### Cache测试：构建
	array
	(
		Test::ITEM     => Cache::CACHE,
		Test::MODE     => Test::MODE_BUILD,
		Test::PARAMS   => array(Cache::CACHE, true),
	),
	### Cache测试：entrust（缺少参数）
	array
	(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => false,
	),
	### Cache测试：entrust（存入）
	array
	(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => true,
		Test::PARAMS   => array('cache1', '缓存内容1'),
	),
	### Cache测试：entrust（存入）
	array
	(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => true,
		Test::PARAMS   => array('cache1', '缓存内容1-1'),
	),
	### Cache测试：取出
	array
	(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => '缓存内容1-1',
		Test::PARAMS   => 'cache1',
	),
	### Cache测试：存入文件
	array
	(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => true,
		Test::PARAMS   => array('cache2', '缓存内容2', true),
	),
	### Cache测试：存入文件
	array
	(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => true,
		Test::PARAMS   => array('cache2', '缓存内容2-1', true),
	),
	### Cache测试：取出文件
	array
	(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => '缓存内容2-1',
		Test::PARAMS   => 'cache2',
	),
	### Cache测试：清空
	array
	(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => Cache::CLEAR,
		Test::PARAMS   => Cache::CLEAR,
	),
);
?>
