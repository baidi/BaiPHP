<?php
/**
 * 原始虚类测试场景
 */
return array(
	### Log测试：构建
	array(
		Test::ITEM     => Target::TARGET,
		Test::MODE     => Test::MODE_BUILD,
		Test::PARAMS   => 'config',
	),
	### 测试情景：entrust
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => null,
		Test::PARAMS   => array(
			array(
				Flow::CONTROL => Test::NIL,
			),
		),
	),
);
?>
