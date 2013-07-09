<?php
/**
 * 样式工场测试场景
 */
$this->target['input-test'] = 'test-value';
global $config;
$config[Work::CHECK][Work::EVENT]['test'] = array(
	'input-test' => 'required min=3 max=9 type=number'
);
$config[Work::TEST][Work::INPUT] = array(
	### 构建
	array(
		Test::ITEM => Work::INPUT,
		Test::MODE => Test::MODE_BUILD
	),
	### entrust（项目为空）
	array(
		Test::ITEM => 'entrust',
		Test::EXPECTED => null
	),
	### entrust
	array(
		Test::ITEM => 'entrust',
		Test::EXPECTED => '<input id="input-none" name="input-none" type="text" value="" />',
		Test::PARAM => array(
			'item' => 'input-none'
		)
	),
	### fetch
	array(
		Test::ITEM => 'fetch',
		Test::EXPECTED => '<input id="input-test" name="input-test" type="number" value="test-value" required="required" maxlength="9" data-check="required min=3 max=9 type=number" placeholder="非空, 最小3位, 最大9位, 整数型" />',
		Test::PARAM => array(
			'item' => 'input-test'
		)
	)
);
?>
