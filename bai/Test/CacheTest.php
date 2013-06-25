<?php
/**
 * 缓存工场测试场景
 */
global $config;
$config[Work::TEST][Work::CACHE] = array(
	### 构建
	array(
		Test::ITEM     => Work::CACHE,
		Test::MODE     => Test::MODE_BUILD,
		Test::PARAM    => array('valid' => true),
	),
	### entrust（缺少参数）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => false,
	),
	### entrust（存入）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => true,
		Test::PARAM    => array('cache1', '缓存内容1'),
	),
	### entrust（更新）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => true,
		Test::PARAM    => array('cache1', '缓存内容1-2'),
	),
	### entrust（取出）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => '缓存内容1-2',
		Test::PARAM    => 'cache1',
	),
	### entrust（取出）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => false,
		Test::PARAM    => 'cache-1',
	),
	### entrust（存入文件）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => true,
		Test::PARAM    => array('cache2', '缓存内容2', true),
	),
	### entrust（更新文件）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => true,
		Test::PARAM    => array('cache2', '缓存内容2-2', true),
	),
	### entrust（取出文件）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => '缓存内容2-2',
		Test::PARAM    => 'cache2',
	),
	### entrust（清空）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => Cache::CLEAR,
		Test::PARAM    => Cache::CLEAR,
	),
);
?>
