<?php
/**
 * 样式工场测试场景
 */
$data = Data::access();
$data->entrust('');
$data->entrust('');
global $config;
$config[Work::TEST][Work::RECORD] = array(
	### 准备
	array(
		Test::ITEM => Work::DATA,
		Test::MODE => Test::MODE_BUILD,
		Test::EXPECTED => Work::DATA
	),
	### 准备
	array(
		Test::ITEM => 'entrust',
		Test::MODE => Test::MODE_REFER,
		Test::EXPECTED => Work::DATA,
		Test::PARAM => array(
			"CREATE TABLE IF NOT EXISTS `_TEST` (\n" .
					 "  `ID` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,\n" .
					 "  `NAME` VARCHAR(20) NOT NULL,\n" . "  `SEX` ENUM('MALE','FEMALE') NOT NULL,\n" .
					 "  `CREATED` DATE DEFAULT NULL,\n" .
					 "  `SALARY` DECIMAL(7,2) UNSIGNED DEFAULT '0.00',\n" . "  PRIMARY KEY (`ID`)\n" .
					 ") DEFAULT CHARSET=UTF8;"
		)
	),
	### 构建
	array(
		Test::ITEM => Work::RECORD,
		Test::MODE => Test::MODE_BUILD,
		Test::PARAM => array(
			'table' => '_TEST'
		)
	),
	### entrust（保存失败）
	array(
		Test::ITEM => 'entrust',
		Test::EXPECTED => false,
		Test::PARAM => array(
			Record::SAVE
		)
	),
	### entrust（删除失败）
	array(
		Test::ITEM => 'entrust',
		Test::EXPECTED => false,
		Test::PARAM => array(
			Record::DELETE
		)
	),
	### offsetGet
	array(
		Test::ITEM => 'offsetGet',
		Test::EXPECTED => null,
		Test::PARAM => array(
			'NAME'
		)
	),
	### offsetSet
	array(
		Test::ITEM => 'offsetSet',
		Test::EXPECTED => '张三',
		Test::PARAM => array(
			'NAME',
			'张三'
		)
	),
	### offsetGet
	array(
		Test::ITEM => 'offsetGet',
		Test::EXPECTED => '张三',
		Test::PARAM => array(
			'NAME'
		)
	),
	### offsetSet
	array(
		Test::ITEM => 'offsetSet',
		Test::EXPECTED => 'A',
		Test::PARAM => array(
			'SEX',
			'A'
		)
	),
	### entrust（保存失败）
	array(
		Test::ITEM => 'entrust',
		Test::EXPECTED => false,
		Test::PARAM => array(
			Record::SAVE
		)
	),
	### offsetSet
	array(
		Test::ITEM => 'offsetSet',
		Test::EXPECTED => 'MALE',
		Test::PARAM => array(
			'SEX',
			'MALE'
		)
	),
	### entrust（保存成功）
	array(
		Test::ITEM => 'entrust',
		Test::EXPECTED => true,
		Test::PARAM => array(
			Record::SAVE
		)
	),
	### entrust（更新失败）
	array(
		Test::ITEM => 'entrust',
		Test::EXPECTED => true,
		Test::PARAM => array(
			Record::SAVE
		)
	),
	### offsetSet
	array(
		Test::ITEM => 'offsetSet',
		Test::EXPECTED => '李四',
		Test::PARAM => array(
			'NAME',
			'李四'
		)
	),
	### entrust（更新成功）
	array(
		Test::ITEM => 'entrust',
		Test::EXPECTED => true,
		Test::PARAM => array(
			Record::SAVE
		)
	),
	### offsetSet
	array(
		Test::ITEM => 'offsetSet',
		Test::EXPECTED => 'A',
		Test::PARAM => array(
			'SEX',
			'A'
		)
	),
	### entrust（更新失败）
	array(
		Test::ITEM => 'entrust',
		Test::EXPECTED => false,
		Test::PARAM => array(
			Record::SAVE
		)
	),
	### entrust（刷新）
	array(
		Test::ITEM => 'entrust',
		Test::EXPECTED => true,
		Test::PARAM => array(
			Record::REFRESH
		)
	),
	### offsetGet
	array(
		Test::ITEM => 'offsetGet',
		Test::EXPECTED => '0.00',
		Test::PARAM => array(
			'SALARY'
		)
	),
	### entrust（删除）
	array(
		Test::ITEM => 'entrust',
		Test::EXPECTED => true,
		Test::PARAM => array(
			Record::DELETE
		)
	),
	### 清理
	array(
		Test::ITEM => 'entrust',
		Test::MODE => Test::MODE_REFER,
		Test::EXPECTED => Work::DATA,
		Test::PARAM => array(
			'DROP TABLE IF EXISTS _TEST'
		)
	)
);
?>
