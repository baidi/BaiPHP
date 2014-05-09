<?php
/**
 * 原始对象测试场景
 */
global $config;
$config[Work::TEST][Work::BAI] = array(
	### 构建
	array(
		Test::ITEM     => Flow::ACTION,
		Test::MODE     => Test::MODE_BUILD,
		Test::EXPECTED => Work::BAI,
	),
	### config（未知项目）
	array(
		Test::ITEM     => 'config',
		Test::EXPECTED => null,
		Test::PARAM    => '_None',
	),
	### config（多级项目）
	array(
		Test::ITEM     => 'config',
		Test::EXPECTED => $config[Flow::FLOW][Flow::ACTION],
		Test::PARAM    => array(Flow::FLOW, Flow::ACTION),
	),
	### pick（缺少项目）
	array(
		Test::ITEM     => 'pick',
		Test::EXPECTED => null,
	),
	### pick（从列表）
	array(
		Test::ITEM     => 'pick',
		Test::EXPECTED => 'av',
		Test::PARAM    => array(
			'ak',
			array('ak' => 'av', 'bk' => 'bv'),
		),
	),
	### pick（从全局）
	array(
		Test::ITEM     => 'pick',
		Test::EXPECTED => $config[Flow::FLOW],
		Test::PARAM    => array(
			Flow::FLOW,
		    null,
			true,
		),
	),
	### drop（从列表）
	array(
		Test::ITEM     => 'drop',
		Test::EXPECTED => 'av',
		Test::PARAM    => array(
			'ak',
			array('ak' => 'av', 'bk' => 'bv'),
		),
	),
	### stuff（缺少数据）
	array(
		Test::ITEM     => 'stuff',
		Test::EXPECTED => false,
	),
	### stuff（填充对象自身）
	array(
		Test::ITEM     => 'stuff',
		Test::EXPECTED => true,
		Test::PARAM    => array(
			array('ak' => 'av', 'bk' => array('ck' => 'cv', 'dk' => array('fk' => 'fv'))),
		),
	),
	### stuff（填充对象列表）
	array(
		Test::ITEM     => 'stuff',
		Test::EXPECTED => true,
		Test::PARAM    => array(
			array('ak' => 'av', 'bk' => array('ck' => 'cv', 'dk' => array('fk' => 'fv'))),
		    &$this->event->config,
		),
	),
	### stuff（递归填充对象列表）
	array(
		Test::ITEM     => 'stuff',
		Test::EXPECTED => true,
		Test::PARAM    => array(
			array('ak' => 'av', 'bk' => array('ek' => 'ev', 'dk' => array('fk' => 'fv'))),
		    &$this->event->config,
		),
	),
	### stuff（覆盖非列表）
	array(
		Test::ITEM     => 'stuff',
		Test::EXPECTED => true,
		Test::PARAM    => array(
			array('ak' => 'av', 'bk' => array('ek' => 'ev', 'dk' => array('fk' => 'fv'))),
		    &$this->event->config['ak'],
		),
	),
	### locate（缺少文件名）
	array(
		Test::ITEM     => 'locate',
		Test::EXPECTED => null,
	),
	### locate（文件不存在）
	array(
		Test::ITEM     => 'locate',
		Test::EXPECTED => array(),
		Test::PARAM    => array(
			'_none',
		),
	),
	### locate（文件位置）
	array(
		Test::ITEM     => 'locate',
		Test::EXPECTED => array(
		    Work::BAI     => 'bai/php/config.php',
		    Work::BASE => 'base/php/config.php',
		),
		Test::PARAM    => array(
			'config.php',
		    'php',
		),
	),
	### load（缺少文件名）
	array(
		Test::ITEM     => 'load',
		Test::EXPECTED => null,
	),
	### load（文件不存在）
	array(
		Test::ITEM     => 'load',
		Test::EXPECTED => null,
		Test::PARAM    => array(
			'BaiTest',
		),
	),
	### load（全部）
	array(
		Test::ITEM     => 'load',
		Test::EXPECTED => '',
		Test::PARAM    => array(
			'BaiTest',
		    true,
			Work::TEST,
		),
	),
	### load（用户文件优先）
	array(
		Test::ITEM     => 'load',
		Test::EXPECTED => '',
		Test::PARAM    => array(
			'BaiTest',
		    null,
			Work::TEST,
		),
	),
	### build（缺少类名）
	array(
		Test::ITEM     => 'build',
		Test::EXPECTED => null,
	),
	### build（对象未知）
	array(
		Test::ITEM     => 'build',
		Test::EXPECTED => null,
		Test::PARAM    => _DEF.Flow::ACTION,
	),
	### build（静态构建）
	array(
		Test::ITEM     => 'build',
		Test::EXPECTED => Work::LOG,
		Test::PARAM    => Work::LOG,
	),
	### url（字符串）
	array(
		Test::ITEM     => 'url',
		Test::EXPECTED => _WEB.'?event=test&basin=test&testee=bai',
		Test::PARAM    => array(
		    'test',
		    'test',
		    'testee=bai'
		),
	),
	### url（数组）
	array(
		Test::ITEM     => 'url',
		Test::EXPECTED => _WEB.'?event=test&basin=test&testee=bai&file=bai',
		Test::PARAM    => array(
		    'test',
		    'test',
		    array(
		        'testee' => 'bai',
		        'file=bai'
		    ),
		),
	),
	### offsetGet
	array(
		Test::ITEM     => 'offsetGet',
		Test::EXPECTED => null,
		Test::PARAM    => '_None',
	),
	### offsetUnset
	array(
		Test::ITEM     => 'offsetUnset',
		Test::EXPECTED => null,
		Test::PARAM    => '_None',
	),
	### __get
	array(
		Test::ITEM     => '__get',
		Test::EXPECTED => null,
		Test::PARAM    => '_None',
	),
	### __call
	array(
		Test::ITEM     => '__call',
		Test::EXPECTED => null,
		Test::PARAM    => array(
		    '_None',
		    null,
		),
	),
	### __toString
	array(
		Test::ITEM     => '__toString',
		Test::EXPECTED => 'TestAction',
	),
// 	### entrust
// 	array(
// 		Test::ITEM     => 'entrust',
// 		Test::EXPECTED => false,
// 		Test::PARAM    => array(
// 			array(
// 				'check'    => Test::NIL,
// 				'Cache'    => Test::NIL,
// 				'data'     => false,
// 				'engage'   => false,
// 			),
// 		),
// 	),
);
?>
