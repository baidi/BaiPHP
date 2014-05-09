<?php
global $config;
$config['Lang'][Lang::EN] = array(
	'title'       => 'BaiPHP Mobile Framework',
	'author'      => 'Xiao yang, Bai',
	'keywords'    => 'BaiPHP Mobile Framework',
	'description' => 'BaiPHP Mobile Framework',
	'logo' => 'BaiPHP Mobile Framework',
	'copyright'   => 'Copyright '.date('Y').', All Rights Reserved.',
	'nav' => array(
		'home' => 'Home',
		'design' => 'Design',
		'example' => 'Example',
	),
	Work::EVENT => array(
		'test' => array(
			'codes'  => '测试代码',
			'covered' => '已测试',
			'uncovered' => '未测试',
			'normal' => '其他',
		),
	),
);
