<?php
/**
 * 目标测试场景
 */
global $config;
$config[Work::TEST][Work::TARGET] = array(
	### 构建
	array(
		Test::ITEM    => Target::TARGET,
		Test::MODE    => Test::MODE_BUILD,
		Test::PARAM   => 'config',
	),
);
?>
