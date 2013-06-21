<?php
/**
 * 目标测试场景
 */
global $config;
$config[Test::TEST][Flow::TARGET] = array(
	### 构建
	array(
		Test::TITEM    => Target::TARGET,
		Test::TMODE    => Test::TMODE_BUILD,
		Test::TPARAM   => 'config',
	),
);
?>
