<?php
/**
 * 模板工场测试场景
 */
global $config;
$config[Work::TEST][Work::TEMPLATE] = array(
	### 构建
	array(
		Test::ITEM => Work::TEMPLATE,
		Test::MODE => Test::MODE_BUILD
	),
	### entrust（项目为空）
	array(
		Test::ITEM => 'entrust',
		Test::EXPECTED => null
	),
	### entrust（模板未设置）
	array(
		Test::ITEM => 'entrust',
		Test::EXPECTED => 'title',
		Test::PARAM => array(
			'title'
		)
	),
	### entrust（简单参数）
	array(
		Test::ITEM => 'entrust',
		Test::EXPECTED => '_content',
		Test::PARAM => array(
			'{$content}',
			'_content'
		)
	),
	### entrust（判断）
	array(
		Test::ITEM => 'entrust',
		Test::EXPECTED => '[T] ',
		Test::PARAM => array(
			'{$chooser ? [T] | [F]}',
			array(
				'chooser' => 1
			)
		)
	),
	### entrust（判断）
	array(
		Test::ITEM => 'entrust',
		Test::EXPECTED => '',
		Test::PARAM => array(
			'{$chooser ? [T]}',
			array(
				'chooser' => 0
			)
		)
	),
	### fetch（循环）
	array(
		Test::ITEM => 'fetch',
		Test::EXPECTED => '[1][2][3]',
		Test::PARAM => array(
			'{$looper ! [$value]}',
			array(
				'looper' => array(1, 2, 3)
			)
		)
	),
	### fetch（循环）
	array(
		Test::ITEM => 'fetch',
		Test::EXPECTED => '[One]',
		Test::PARAM => array(
			'{$looper ! [$value]}',
			array(
				'looper' => 'One'
			)
		)
	),
	### fetch（循环）
	array(
		Test::ITEM => 'fetch',
		Test::EXPECTED => '123',
		Test::PARAM => array(
			'{$looper !}',
			array(
				'looper' => array(1, 2, 3)
			)
		)
	)
);
?>
