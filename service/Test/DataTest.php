<?php
/**
 * 数据工场测试场景
 */
return array
(
	### DB测试：构建（确少参数）
	array
	(
		Test::ITEM     => 'Data',
		Test::MODE     => Test::MODE_BUILD,
		Test::PARAMS   => array(
			'user'     => null,
		),
	),
	### DB测试：构建（连接失败）
	array
	(
		Test::ITEM     => 'Data',
		Test::MODE     => Test::MODE_BUILD,
		Test::PARAMS   => array(
			'user'     => 'user_',
			'password' => 'user_',
		),
	),
	### DB测试：构建（即时连接）
	array
	(
		Test::ITEM     => 'Data',
		Test::MODE     => Test::MODE_BUILD,
		Test::PARAMS   => array(
			'persistent' => false,
		),
	),
	### DB测试：构建（持久连接）
	array
	(
		Test::ITEM     => 'Data',
		Test::MODE     => Test::MODE_BUILD,
		Test::PARAMS   => array(
			'persistent' => true,
		),
	),
	### DB测试：entrust（SQL缺失）
	array
	(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => false,
	),
	### DB测试：entrust（SQL执行）
	array
	(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => 0,
		Test::PARAMS   => 
			'CREATE TABLE IF NOT EXISTS `data` ('.
			'  `id` int(3) unsigned NOT NULL,'.
			'  `name` varchar(20) NOT NULL,'.
			'  `role` varchar(20) NOT NULL,'.
			'  PRIMARY KEY (`id`)'.
			') ENGINE=InnoDB DEFAULT CHARSET=utf8;',
	),
	### DB测试：entrust（SQL检索）
	array
	(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => array(),
		Test::PARAMS   => array(
			'SELECT `id` FROM `data`',
		),
	),
	### DB测试：entrust（成功）
	array
	(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => 1,
		Test::PARAMS   => array(
			'insert into `data` (`id`, `name`, `role`) values (:id, :name, :role)',
			array('id' => 1, 'name' => '张三', 'role' => '营销'),
		),
	),
	### DB测试：entrust（失败）
	array
	(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => false,
		Test::PARAMS   => array(
			'SELECT `id` IN `data`',
		),
	),
	### DB测试：create（缺少表名）
	array
	(
		Test::ITEM     => 'create',
		Test::EXPECTED => false,
	),
	### DB测试：create（缺少字段值）
	array
	(
		Test::ITEM     => 'create',
		Test::EXPECTED => false,
		Test::PARAMS   => array(
			'data',
			'id'
		),
	),
	### DB测试：create（成功）
	array
	(
		Test::ITEM     => 'create',
		Test::EXPECTED => 1,
		Test::PARAMS   => array(
			'data',
			array('id' => 2, 'name' => '李四', 'role' => '研发'),
		),
	),
	### DB测试：create（失败）
	array
	(
		Test::ITEM     => 'create',
		Test::EXPECTED => false,
		Test::PARAMS   => array(
			'data',
			array('id' => 2, 'name' => '李四', 'role' => '营销'),
		),
	),
	### DB测试：update（缺少表名）
	array
	(
		Test::ITEM     => 'update',
		Test::EXPECTED => false,
	),
	### DB测试：update（缺少字段值）
	array
	(
		Test::ITEM     => 'update',
		Test::EXPECTED => false,
		Test::PARAMS   => array(
			'data',
			'role',
		),
	),
	### DB测试：update（缺少条件）
	array
	(
		Test::ITEM     => 'update',
		Test::EXPECTED => false,
		Test::PARAMS   => array(
			'data',
			array('role' => '营销'),
			'id',
		),
	),
	### DB测试：update（成功）
	array
	(
		Test::ITEM     => 'update',
		Test::EXPECTED => 1,
		Test::PARAMS   => array(
			'data',
			array('role' => '营销'),
			array('id'   => 2),
		),
	),
	### DB测试：update（失败）
	array
	(
		Test::ITEM     => 'update',
		Test::EXPECTED => false,
		Test::PARAMS   => array(
			'data',
			array('role_' => '营销_'),
			array('id'    => 2),
		),
	),
	### DB测试：count（缺少表名）
	array
	(
		Test::ITEM     => 'count',
		Test::EXPECTED => false,
	),
	### DB测试：count（条件不符）
	array
	(
		Test::ITEM     => 'count',
		Test::EXPECTED => false,
		Test::PARAMS   => array(
			'data',
			'id'
		),
	),
	### DB测试：count（成功）
	array
	(
		Test::ITEM     => 'count',
		Test::EXPECTED => 2,
		Test::PARAMS   => array(
			'data',
			array('role' => '营销'),
		),
	),
	### DB测试：count（失败）
	array
	(
		Test::ITEM     => 'count',
		Test::EXPECTED => false,
		Test::PARAMS   => array(
			'data',
			array('role_' => '营销'),
		),
	),
	### DB测试：read（缺少表名）
	array
	(
		Test::ITEM     => 'read',
		Test::EXPECTED => false,
	),
	### DB测试：read（条件不符）
	array
	(
		Test::ITEM     => 'read',
		Test::EXPECTED => false,
		Test::PARAMS   => array(
			'data',
			'role_',
		),
	),
	### DB测试：read（成功）
	array
	(
		Test::ITEM     => 'read',
		Test::EXPECTED => array(
			array('id' => '1', 'name' => '张三', 'role' => '营销'),
			array('id' => '2', 'name' => '李四', 'role' => '营销'),
		),
		Test::PARAMS   => array(
			'data',
			array('role' => '营销'),
			'id'
		),
	),
	### DB测试：read（成功）
	array
	(
		Test::ITEM     => 'read',
		Test::EXPECTED => array(
			array('id' => '2', 'name' => '李四', 'role' => '营销'),
		),
		Test::PARAMS   => array(
			'data',
			array('role' => '营销'),
			array('id'),
			5,
			1,
		),
	),
	### DB测试：read（失败）
	array
	(
		Test::ITEM     => 'read',
		Test::EXPECTED => false,
		Test::PARAMS   => array(
			'data_',
		),
	),
	### DB测试：delete（缺少表名）
	array
	(
		Test::ITEM     => 'delete',
		Test::EXPECTED => false,
	),
	### DB测试：delete（缺少条件）
	array
	(
		Test::ITEM     => 'delete',
		Test::EXPECTED => false,
		Test::PARAMS   => array(
			'data',
			'id',
		),
	),
	### DB测试：delete（成功）
	array
	(
		Test::ITEM     => 'delete',
		Test::EXPECTED => 1,
		Test::PARAMS   => array(
			'data',
			array('id' => 1),
		),
	),
	### DB测试：delete（失败）
	array
	(
		Test::ITEM     => 'delete',
		Test::EXPECTED => false,
		Test::PARAMS   => array(
			'data',
			array('' => 2),
		),
	),
	### DB测试：删除数据表
	array
	(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => 0,
		Test::PARAMS   => array(
			'DROP TABLE IF EXISTS data',
		),
	),
);
?>
