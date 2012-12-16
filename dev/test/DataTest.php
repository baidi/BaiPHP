<?php
return array
(
		### DB测试：构建
		array
		(
				Test::ITEM   => 'access',
				Test::EXPECT => 'Data',
				Test::TYPE   => Test::TYPE_BUILD,
		),
		### DB测试：新建数据表
		array
		(
				Test::ITEM   => 'assign',
				Test::PARAMS => 'CREATE TABLE IF NOT EXISTS `test_db` ('
						.' `id` int(9) NOT NULL,'
						.' `name` varchar(20) NOT NULL,'
						.' `description` varchar(255) DEFAULT NULL,'
						.' PRIMARY KEY (`id`)'
						.' ) ENGINE=InnoDB DEFAULT CHARSET=utf8;',
				Test::EXPECT => 0,
				Test::TAB    => 'DB测试：新建数据表',
		),
		### DB测试：新建数据（create）
		array
		(
				Test::ITEM   => 'create',
				Test::PARAMS => array(
						'test_db', 
						array('id' => 1, 'name' => '张三', 'description' => '一组村民'),
				),
				Test::EXPECT => 1,
				Test::TAB    => 'DB测试：新建数据（create）',
		),
		### DB测试：新建数据（assign）
		array
		(
				Test::ITEM   => 'assign',
				Test::PARAMS => array(
						'insert into `test_db` (`id`, `name`, `description`) values (:id, :name, :description)', 
						array('id' => 2, 'name' => '李四', 'description' => '二组村民'),
				),
				Test::EXPECT => 1,
				Test::TAB    => 'DB测试：新建数据（assign）',
		),
		### DB测试：更新数据
		array
		(
				Test::ITEM   => 'update',
				Test::PARAMS => array(
						'test_db', 
						array('description' => '一组村民'),
						array('id' => 2),
				),
				Test::EXPECT => 1,
				Test::TAB    => 'DB测试：更新数据',
		),
		### DB测试：统计数据（全部）
		array
		(
				Test::ITEM   => 'count',
				Test::PARAMS => array(
						'test_db',
				),
				Test::EXPECT => 2,
				Test::TAB    => 'DB测试：统计数据（全部）',
		),
		### DB测试：统计数据（条件）
		array
		(
				Test::ITEM   => 'count',
				Test::PARAMS => array(
						'test_db', 
						array('name' => '李四'),
				),
				Test::EXPECT => 1,
				Test::TAB    => 'DB测试：统计数据（条件）',
		),
		### DB测试：读取数据（全部）
		array
		(
				Test::ITEM   => 'read',
				Test::PARAMS => array(
						'test_db', 
				),
				Test::EXPECT => array(
						array(0 => 1, 'id' => 1, 1 => '张三', 'name' => '张三', 2 => '一组村民', 'description' => '一组村民'),
						array(0 => 2, 'id' => 2, 1 => '李四', 'name' => '李四', 2 => '一组村民', 'description' => '一组村民'),
				),
				Test::TAB    => 'DB测试：读取数据（全部）',
		),
		### DB测试：读取数据（条件）
		array
		(
				Test::ITEM   => 'read',
				Test::PARAMS => array(
						'test_db', 
						array('description' => '一组村民', ),
						array('id'),
						5,
						1,
				),
				Test::EXPECT => array(
						#array(0 => 1, 'id' => 1, 1 => '张三', 'name' => '张三', 2 => '一组村民', 'description' => '一组村民'),
						array(0 => 2, 'id' => 2, 1 => '李四', 'name' => '李四', 2 => '一组村民', 'description' => '一组村民'),
				),
				Test::TAB    => 'DB测试：读取数据（条件）',
		),
		### DB测试：删除数据（delete）
		array
		(
				Test::ITEM   => 'delete',
				Test::PARAMS => array(
						'test_db', 
						array('id' => '1'),
				),
				Test::EXPECT => 1,
				Test::TAB    => 'DB测试：删除数据（delete）',
		),
		### DB测试：删除数据（assign）
		array
		(
				Test::ITEM   => 'assign',
				Test::PARAMS => 'delete from test_db',
				Test::EXPECT => 1,
				Test::TAB    => 'DB测试：删除数据（assign）',
		),
		### DB测试：删除数据表
		array
		(
				Test::ITEM   => 'assign',
				Test::PARAMS => array(
						'DROP TABLE IF EXISTS test_db',
				),
				Test::EXPECT => 0,
				Test::TAB    => 'DB测试：删除数据表',
		),
);
?>
