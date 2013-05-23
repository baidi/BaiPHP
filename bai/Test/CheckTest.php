<?php
return array
(
	### Check测试：构建
	array
	(
		Test::ITEM     => Check::CHECK,
		Test::MODE     => Test::MODE_BUILD,
	),
	### Check测试：entrust（无检验配置）
	array
	(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => true,
	),
	### Check测试：构建
	array
	(
		Test::ITEM     => Check::CHECK,
		Test::MODE     => Test::MODE_BUILD,
		Test::PARAMS   => array(
			'test' => array(
				'check1' => 'required',
			),
		),
	),
	### Check测试：entrust（必须项不符）
	array
	(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => false,
	),
	### Check测试：构建
	array
	(
		Test::ITEM     => Check::CHECK,
		Test::MODE     => Test::MODE_BUILD,
		Test::PARAMS   => array(
			'test' => array(
				'check2'   => 'required min=6',
			),
		),
	),
	### Check测试：entrust（最小长度不符）
	array
	(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => false,
	),
	### Check测试：构建
	array
	(
		Test::ITEM     => Check::CHECK,
		Test::MODE     => Test::MODE_BUILD,
		Test::PARAMS   => array(
			'test' => array(
				'check2'   => 'required min=2 max=4',
			),
		),
	),
	### Check测试：entrust（最大长度不符）
	array
	(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => false,
	),
	### Check测试：构建
	array
	(
		Test::ITEM     => Check::CHECK,
		Test::MODE     => Test::MODE_BUILD,
		Test::PARAMS   => array(
			'test' => array(
				'check2'   => 'required min=3 max=5 type=letter',
			),
		),
	),
	### Check测试：entrust（属性不符）
	array
	(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => false,
	),
	### Check测试：构建
	array
	(
		Test::ITEM     => Check::CHECK,
		Test::MODE     => Test::MODE_BUILD,
		Test::PARAMS   => array(
			'test' => array(
				'check2'   => 'required min=3 max=5 type=number',
			),
		),
	),
	### Check测试：entrust（通过）
	array
	(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => true,
	),
);
?>
