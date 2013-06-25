<?php
/**
 * 数据工场测试场景
 */
global $config;
$config[Work::TEST][Work::DATA] = array(
	### 构建（即时连接）
	array(
		Test::ITEM     => 'Data',
		Test::MODE     => Test::MODE_BUILD,
		Test::PARAM    => array(
			'lasting' => false,
		),
	),
	### 构建（持久连接）
	array(
		Test::ITEM     => 'Data',
		Test::MODE     => Test::MODE_BUILD,
		Test::PARAM    => array(
			'lasting' => true,
		),
	),
	### entrust（SQL缺失）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => false,
	),
	### entrust（建表）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => 0,
		Test::PARAM    =>
			'CREATE TABLE IF NOT EXISTS `_Test` ('.
			'  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,'.
			'  `name` varchar(20) NOT NULL,'.
			'  `role` varchar(20) NOT NULL,'.
			'  PRIMARY KEY (`id`)'.
			') ENGINE=InnoDB DEFAULT CHARSET=utf8;',
	),
	### entrust（插入）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => 1,
		Test::PARAM    => array(
			'insert into `_Test` (`name`, `role`) values (:name, :role)',
			array('name' => '张三', 'role' => '营销'),
		),
	),
	### entrust（检索）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => array(
			array('ID' => '1'),
		),
		Test::PARAM    => array(
			'SELECT `id` FROM `_Test`',
		),
	),
	### entrust（失败）
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => false,
		Test::PARAM    => array(
			'SELECT `id` IN `_Test`',
		),
	),
	### create（缺少表名）
	array(
		Test::ITEM     => 'create',
		Test::EXPECTED => false,
	),
	### create（缺少字段值）
	array(
		Test::ITEM     => 'create',
		Test::EXPECTED => false,
		Test::PARAM    => array(
			'_Test',
		),
	),
	### create（成功）
	array(
		Test::ITEM     => 'create',
		Test::EXPECTED => 2,
		Test::PARAM    => array(
			'_Test',
			array('name' => '李四', 'role' => '研发'),
		),
	),
	### create（失败）
	array(
		Test::ITEM     => 'create',
		Test::EXPECTED => false,
		Test::PARAM    => array(
			'_Test',
			array('id' => 2, 'name' => '李四', 'role' => '营销'),
		),
	),
	### update（缺少表名）
	array(
		Test::ITEM     => 'update',
		Test::EXPECTED => false,
	),
	### update（缺少字段值）
	array(
		Test::ITEM     => 'update',
		Test::EXPECTED => false,
		Test::PARAM    => array(
			'_Test',
		),
	),
	### update（缺少条件）
	array(
		Test::ITEM     => 'update',
		Test::EXPECTED => false,
		Test::PARAM    => array(
			'_Test',
			array('role' => '营销'),
		),
	),
	### update（成功）
	array(
		Test::ITEM     => 'update',
		Test::EXPECTED => 1,
		Test::PARAM    => array(
			'_Test',
			array('role' => '营销'),
			array('role' => '研发'),
		),
	),
	### update（失败）
	array(
		Test::ITEM     => 'update',
		Test::EXPECTED => false,
		Test::PARAM    => array(
			'_Test',
			array('role_' => '营销_'),
			array('id'    => 2),
		),
	),
	### count（缺少表名）
	array(
		Test::ITEM     => 'count',
		Test::EXPECTED => false,
	),
	### count（条件不符）
	array(
		Test::ITEM     => 'count',
		Test::EXPECTED => false,
		Test::PARAM    => array(
			'_Test',
			'id'
		),
	),
	### count（成功）
	array(
		Test::ITEM     => 'count',
		Test::EXPECTED => 2,
		Test::PARAM    => array(
			'_Test',
			array('role' => '营销'),
		),
	),
	### count（失败）
	array(
		Test::ITEM     => 'count',
		Test::EXPECTED => false,
		Test::PARAM    => array(
			'_Test',
			array('role_' => '营销'),
		),
	),
	### read（缺少表名）
	array(
		Test::ITEM     => 'read',
		Test::EXPECTED => false,
	),
	### read（条件不符）
	array(
		Test::ITEM     => 'read',
		Test::EXPECTED => false,
		Test::PARAM    => array(
			'_Test',
			'role_',
		),
	),
	### read（成功）
	array(
		Test::ITEM     => 'read',
		Test::EXPECTED => array(
			array('ID' => '1', 'NAME' => '张三', 'ROLE' => '营销'),
			array('ID' => '2', 'NAME' => '李四', 'ROLE' => '营销'),
		),
		Test::PARAM    => array(
			'_Test',
			array('role' => '营销'),
			'id',
		),
	),
	### read（成功）
	array(
		Test::ITEM     => 'read',
		Test::EXPECTED => array(
			array('ID' => '2', 'NAME' => '李四', 'ROLE' => '营销'),
		),
		Test::PARAM    => array(
			'_Test',
			array('role' => '营销'),
			array('id'),
			1,
			1,
		),
	),
	### read（失败）
	array(
		Test::ITEM     => 'read',
		Test::EXPECTED => false,
		Test::PARAM    => array(
			'Test_',
		),
	),
	### show（缺少表名）
	array(
		Test::ITEM     => 'show',
		Test::EXPECTED => false,
	),
	### show（成功）
	array(
		Test::ITEM     => 'show',
		Test::EXPECTED => array(
			array(
				'FIELD' => 'id',
				'TYPE' => 'int(3) unsigned',
				'COLLATION' => NULL,
				'NULL' => 'NO',
				'KEY' => 'PRI',
				'DEFAULT' => NULL,
				'EXTRA' => 'auto_increment',
				'PRIVILEGES' => 'select,insert,update,references',
				'COMMENT' => '',
			),
			array(
				'FIELD' => 'name',
				'TYPE' => 'varchar(20)',
				'COLLATION' => 'utf8_general_ci',
				'NULL' => 'NO',
				'KEY' => '',
				'DEFAULT' => NULL,
				'EXTRA' => '',
				'PRIVILEGES' => 'select,insert,update,references',
				'COMMENT' => '',
			),
			array (
				'FIELD' => 'role',
				'TYPE' => 'varchar(20)',
				'COLLATION' => 'utf8_general_ci',
				'NULL' => 'NO',
				'KEY' => '',
				'DEFAULT' => NULL,
				'EXTRA' => '',
				'PRIVILEGES' => 'select,insert,update,references',
				'COMMENT' => '',
			),
		),
		Test::PARAM    => array(
			'_Test',
		),
	),
	### show（失败）
	array(
		Test::ITEM     => 'show',
		Test::EXPECTED => false,
		Test::PARAM    => array(
			'Test_',
		),
	),
	### delete（缺少表名）
	array(
		Test::ITEM     => 'delete',
		Test::EXPECTED => false,
	),
	### delete（缺少条件）
	array(
		Test::ITEM     => 'delete',
		Test::EXPECTED => false,
		Test::PARAM    => array(
			'_Test',
		),
	),
	### delete（成功）
	array(
		Test::ITEM     => 'delete',
		Test::EXPECTED => 1,
		Test::PARAM    => array(
			'_Test',
			array('id' => 1),
		),
	),
	### delete（失败）
	array(
		Test::ITEM     => 'delete',
		Test::EXPECTED => false,
		Test::PARAM    => array(
			'_Test',
			array('id_' => 2),
		),
	),
	### 删除数据表
	array(
		Test::ITEM     => 'entrust',
		Test::EXPECTED => 0,
		Test::PARAM    => array(
			'DROP TABLE IF EXISTS _Test',
		),
	),
);
?>
