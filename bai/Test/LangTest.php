<?php
/**
 * 样式工场测试场景
 */
global $config;
$config[Work::TEST][Work::LANG] = array(
	### 构建
	array(
		Test::ITEM => Work::LANG,
		Test::MODE => Test::MODE_BUILD
	),
	### entrust（项目为空）
	array(
		Test::ITEM => 'entrust',
		Test::EXPECTED => null
	),
	### entrust（项目未设置）
	array(
		Test::ITEM => 'entrust',
		Test::EXPECTED => null,
		Test::PARAM => array(
			'_title'
		)
	),
	### offsetGet
	array(
		Test::ITEM => 'offsetGet',
		Test::EXPECTED => 'BaiPHP-化简PHP',
		Test::PARAM => array(
			'title'
		)
	),
	### fetch
	array(
		Test::ITEM => 'fetch',
		Test::EXPECTED => 'BaiPHP-化简PHP',
		Test::PARAM => array(
			'title'
		)
	)
);
?>
