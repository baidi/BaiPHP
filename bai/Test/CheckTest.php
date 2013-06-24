<?php
/**
 * 检验工场测试场景
 */
global $config;
$config[Work::TEST][Work::CHECK] = array(
	### 构建
	array(
		Test::ITEM     => Work::CHECK,
		Test::MODE     => Test::MODE_BUILD,
		Test::PARAM    => array(
			'gap'       => ',',
		),
	),
	### entrust（未设检验）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => true,
	),
	### entrust（检验内容为空）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => true,
		Test::PARAM    => array(
			array('input-risk' => ''),
		),
	),
	### entrust（检验内容为空格）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => true,
		Test::PARAM    => array(
			array('input-risk' => ' '),
		),
	),
	### entrust（风险字符检验）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => false,
		Test::PARAM    => array(
			array('input-risk' => 'risk'),
		),
	),
	### entrust（必须项检验）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => false,
		Test::PARAM    => array(
			array('input-required' => 'risk required'),
		),
	),
	### entrust（最小长度检验）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => false,
		Test::PARAM    => array(
			array('input-min[3]' => 'risk required min=6'),
		),
	),
	### entrust（最小长度检验）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => true,
		Test::PARAM    => array(
			array('input-min[3]' => 'min=s'),
		),
	),
	### entrust（最大长度检验）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => false,
		Test::PARAM    => array(
			array('input-max[3.3]' => 'min=3 max=6'),
		),
	),
	### entrust（最大长度检验）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => true,
		Test::PARAM    => array(
			array('input-max[3.3]' => 'max=0'),
		),
	),
	### entrust（最大长度检验）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => true,
		Test::PARAM    => array(
			array('input-max[3.3]' => 'max=8'),
		),
	),
	### entrust（最大长度检验：整数）
	array
	(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => false,
		Test::PARAM    => array(
			array('input-max[3.3]' => 'max=5,3'),
		),
	),
	### entrust（最大长度检验：整数）
	array
	(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => true,
		Test::PARAM    => array(
			array('input-max[3]' => 'max=5,2'),
		),
	),
	### entrust（最大长度检验：小数）
	array
	(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => false,
		Test::PARAM    => array(
			array('input-max[3.3]' => 'max=5,2'),
		),
	),
	### entrust（最大长度检验：小数）
	array
	(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => true,
		Test::PARAM    => array(
			array('input-max[3.3]' => 'max=6,3'),
		),
	),
	### entrust（数值范围检验：设置有误）
	array
	(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => true,
		Test::PARAM    => array(
			array('input-range[1-100]' => 'range=1,F0'),
		),
	),
	### entrust（数值范围检验：失败）
	array
	(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => false,
		Test::PARAM    => array(
			array('input-range[1-100]' => 'range=1,100'),
		),
	),
	### entrust（数值范围检验：成功）
	array
	(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => true,
		Test::PARAM    => array(
			array('input-range[1-100]' => 'range=1,1000'),
		),
	),
	### entrust（单选检验：未设置）
	array
	(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => true,
		Test::PARAM    => array(
			array('input-enum[a-d]' => 'enum'),
		),
	),
	### entrust（单选检验：失败）
	array
	(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => false,
		Test::PARAM    => array(
			array('input-enum[a-d]' => 'enum=a,b,c,d'),
		),
	),
	### entrust（单选检验：成功）
	array
	(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => true,
		Test::PARAM    => array(
			array('input-enum[a-d]' => 'enum=a,b,c,d,1'),
		),
	),
	### entrust（多选检验：未设置）
	array
	(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => true,
		Test::PARAM    => array(
			array('input-set[a-d]' => 'set'),
		),
	),
	### entrust（多选检验：失败）
	array
	(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => false,
		Test::PARAM    => array(
			array('input-set[a-d]' => 'set=a,b,c,d'),
		),
	),
	### entrust（多选检验：成功）
	array
	(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => true,
		Test::PARAM    => array(
			array('input-set[a-d]' => 'set=a,b,c,d,2'),
		),
	),
	### entrust（属性检验：失败）
	array
	(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => false,
		Test::PARAM    => array(
			array('input-max[3.3]' => 'type=float'),
		),
	),
	### entrust（属性检验：成功）
	array
	(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => true,
		Test::PARAM    => array(
			array('input-max[3]' => 'type=char'),
		),
	),
	### entrust（外部调用检验）
	array
	(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => false,
		Test::PARAM    => array(
			array('input-max[3]' => 'call=_callNone_'),
		),
	),
);
?>
