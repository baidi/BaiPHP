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

### 全局配置：页面流程
$config[Flow::PAGE]['js'][] = 'jquery.js';

### 全局配置：检验工场
$config[Work::CHECK][Work::EVENT] = array(
	'event' => array(
		'aservice' => 'required type=char',
	),
	'flow' => array(
		'aservice' => 'required type=char',
		'aevent' => 'required type=char',
	),
);

$config['ServiceAction'] = array(
	'exclude' => array(
		'.' => true,
		'..' => true,
		'.git' => true,
		'.runtime' => true,
		'.settings' => true,
		'bai' => true
	)
);
$config['EventAction'] = array(
	'include' => array(
		Flow::ACTION => '#^(?<'.Bai::EVENT.'>[a-zA-Z0-9_\x7f-\xff]+)Action\.php$#i',
		Flow::PAGE => '#^(?<'.Bai::EVENT.'>[a-zA-Z0-9_\x7f-\xff]+)\.php$#i',
	)
);
