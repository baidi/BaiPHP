<?php
/**
 * 原始虚类测试场景
 */
return array(
	### Log测试：构建
	array(
		Test::ITEM     => Page::PAGE,
		Test::MODE     => Test::MODE_BUILD,
		Test::EXPECTED => Page::BAI,
		Test::PARAMS   => array(
			'css' => array('_test.css'),
		),
	),
	### 测试情景：config（缺少项目）
	array(
		Test::ITEM     => 'config',
		Test::EXPECTED => $GLOBALS['config'],
	),
	### 测试情景：config（未知项目）
	array(
		Test::ITEM     => 'config',
		Test::EXPECTED => null,
		Test::PARAMS   => _DEFAULT.Page::FLOW,
	),
	### 测试情景：config（多级项目）
	array(
		Test::ITEM     => 'config',
		Test::EXPECTED => $GLOBALS['config'][Page::FLOW][Page::CONTROL],
		Test::PARAMS   => array(Page::FLOW, Page::CONTROL),
	),
	### 测试情景：pick（缺少项目）
	array(
		Test::ITEM     => 'pick',
		Test::EXPECTED => null,
	),
	### 测试情景：pick（从列表）
	array(
		Test::ITEM     => 'pick',
		Test::EXPECTED => 'av',
		Test::PARAMS   => array(
			'ak',
			array('ak' => 'av', 'bk' => 'bv'),
		),
	),
	### 测试情景：pick（从全局）
	array(
		Test::ITEM     => 'pick',
		Test::EXPECTED => $GLOBALS['config'][Page::FLOW],
		Test::PARAMS   => array(
			Page::FLOW,
			null,
			false,
		),
	),
	### 测试情景：drop
	array(
		Test::ITEM     => 'drop',
		Test::EXPECTED => '',
		Test::PARAMS   => array(
			'ak',
			array('ak' => '', 'bk' => 'bv'),
		),
	),
	### 测试情景：stuff（缺少数据）
	array(
		Test::ITEM     => 'stuff',
		Test::EXPECTED => false,
	),
	### 测试情景：stuff（填充对象自身）
	array(
		Test::ITEM     => 'stuff',
		Test::EXPECTED => true,
		Test::PARAMS   => array(
			array('ak' => 'av', 'bk' => array('ck' => 'cv', 'dk' => array('fk' => 'fv'))),
		),
	),
	### 测试情景：build（类名无效）
	array(
		Test::ITEM     => 'build',
		Test::EXPECTED => null,
		Test::PARAMS   => 1
	),
	### 测试情景：build（对象未知）
	array(
		Test::ITEM     => 'build',
		Test::EXPECTED => null,
		Test::PARAMS   => _DEFAULT.Page::PAGE,
	),
	### 测试情景：build（分支对象）
	array(
		Test::ITEM     => 'build',
		Test::EXPECTED => 'TestAction',
		Test::PARAMS   => Page::ACTION,
	),
	### 测试情景：build（静态构建）
	array(
		Test::ITEM     => 'build',
		Test::EXPECTED => Work::LOG,
		Test::PARAMS   => Work::LOG,
	),
	### 测试情景：load（缺少文件）
	array(
		Test::ITEM     => 'load',
		Test::EXPECTED => null,
	),
	### 测试情景：load（无效文件）
	array(
		Test::ITEM     => 'load',
		Test::EXPECTED => null,
		Test::PARAMS   => '$Test',
	),
	### 测试情景：__get
	array(
		Test::ITEM     => '__get',
		Test::EXPECTED => null,
		Test::PARAMS   => '_test_',
	),
	### 测试情景：__toString
	array(
		Test::ITEM     => '__toString',
		Test::EXPECTED => Page::PAGE,
	),
	### 测试情景：entrust
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => false,
		Test::PARAMS   => array(
			array(
				'css'      => Test::NIL,
				'js'       => Test::NIL,
				'html'     => Test::NIL,
				'_test1'   => false,
				'Data'     => false,
				'_test2'   => null,
			),
		),
	),
);
?>
