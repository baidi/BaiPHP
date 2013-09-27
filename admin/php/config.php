<?php
/**
 * <b>化简PHP（BaiPHP）开发框架</b>
 * @author		白晓阳
 * @copyright	Copyright (c) 2011 - 2012, 白晓阳
 * @link		http://dacbe.com
 * @version    V1.0.0 2012/03/31 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 */

/**
 * <b>化简PHP（BaiPHP）开发框架</b><br/>
 * <b>用户配置</b>
 * <p>
 * 系统运行时由用户设置与维护的数据，可根据需要自行增删
 * </p>
 * @author 白晓阳
 */


global $config;

### 调试模式
$config[_DEF][Target::DEBUG] = true;

### 全局配置：检验工场
$config[Work::CHECK][Work::EVENT] = array(
	'flow' => array(
		'aservice' => 'required type=char',
	),
	'action' => array(
		'aservice' => 'required type=char',
		'aevent' => 'required type=char',
	),
	'basinCreate' => array(
		'abasin' => 'required min=3 max=20 type=char',
	),
);

$config[Work::LOG]['dic']['BasinCreateAction'] = array(
	'engage' => '该流域已存在……',
	'basin' => '新建流域：%s',
	'fail' => '流域创建失败……',
);

$config['BasinAction'] = array(
	'exclude' => array(
		'.' => true,
		'..' => true,
		'.git' => true,
		'.runtime' => true,
		'.settings' => true,
	)
);
$config['BasinCreateAction'] = array(
	'include' => array(
		'php' => true,
		'css' => true,
		'js' => true,
		Flow::PAGE => true,
		Flow::ACTION => true,
		Work::LANG => true,
	)
);
$config['FlowAction'] = array(
	'include' => array(
		Flow::ACTION => '#^(?<'.Bai::EVENT.'>[a-zA-Z0-9_\x7f-\xff]+)Action\.php$#i',
		Flow::PAGE => '#^(?<'.Bai::EVENT.'>[a-zA-Z0-9_\x7f-\xff]+)\.php$#i',
	)
);

$config ['JS'] = array (
	'$config$' => json_encode ( array (
		'alt' => array(
			'class' => 'className',
			'html' => 'innerHTML',
			'text' => 'innerText',
		),
		'check' => $config [Work::CHECK],
		'ajax' => array(
			'timeout' => 5000,
		),
		'bubble' => array (
			'shade' => '.shade',
			'title' => '.bubble .title',
			'content' => '.bubble .content',
			'bubbled' => '.bubbled',
		),
		'message' => array (
			'check' => $config [Work::LOG] ['dic'] [Work::CHECK],
			'bubble' => array (
				'title' => '提示',
				'content' => '没有内容……',
				'load' => '请稍候，正在加载……',
				'fail' => '内容加载失败……',
				'blank' => '加载完成，但是没有内容……',
			),
		),
	) )
);
