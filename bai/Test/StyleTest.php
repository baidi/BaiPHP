<?php
/**
 * 样式工场测试场景
 */
global $config;
$config[Work::TEST][Work::STYLE] = array(
	### 构建
	array(
		Test::ITEM    => Work::STYLE,
		Test::MODE    => Test::MODE_BUILD,
	),
	### entrust（项目为空）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => null,
	),
	### entrust（分支为空）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => '',
		Test::PARAM    => array(
			1,
		),
	),
	### css
	array(
		Test::ITEM     => 'css',
		Test::EXPECTED => sprintf($this->config(Work::STYLE, 'links', 'css'), _WEB.'bai/css/bai.css'),
		Test::PARAM    => array(
			'bai.css',
		),
	),
	### img
	array(
		Test::ITEM     => 'img',
		Test::EXPECTED => _WEB.'bai/img/logo.png',
		Test::PARAM    => array(
			'logo.png',
		),
	),
	### file
	array(
		Test::ITEM     => 'file',
		Test::EXPECTED => null,
		Test::PARAM    => array(
			'_None',
		),
	),
	### js
	array(
		Test::ITEM     => 'js',
		Test::EXPECTED => '',
		Test::PARAM    => array(
			'_None.js',
		    true,
		),
	),
	### entrust
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => '',
		Test::PARAM    => array(
			'_None',
		    'Test',
		    true,
		),
	),
);
?>
