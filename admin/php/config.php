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
	'basinCreate' => array(
		'abasin' => 'required min=2 max=20 type=char',
	),
	'basinUpdate' => array(
		'obasin' => 'required min=2 max=20 type=char',
		'abasin' => 'required min=2 max=20 type=char',
	),
	'basinDelete' => array(
		'abasin' => 'required min=2 max=20 type=char',
	),
	'flow' => array(
		'abasin' => 'required type=char',
	),
	'flowCreate' => array(
		'abasin' => 'required type=char',
		'aevent' => 'required min=2 max=20 type=char',
	),
	'flowUpdate' => array(
		'abasin' => 'required type=char',
		'aevent' => 'required min=2 max=50 type=char',
		'oevent' => 'required min=2 max=50 type=char',
	),
	'flowDelete' => array(
		'abasin' => 'required type=char',
		'aevent' => 'required min=2 max=20 type=char',
	),
	'actionCreate' => array(
		'abasin' => 'required type=char',
		'aevent' => 'required min=2 max=20 type=char',
	),
	'actionDelete' => array(
		'abasin' => 'required type=char',
		'aevent' => 'required min=2 max=20 type=char',
	),
	'pageCreate' => array(
		'abasin' => 'required type=char',
		'aevent' => 'required min=2 max=20 type=char',
	),
	'pageDelete' => array(
		'abasin' => 'required type=char',
		'aevent' => 'required min=2 max=20 type=char',
	),
	'process' => array(
		'abasin' => 'required type=char',
		'aevent' => 'required type=char',
	),
);

$config[Work::LOG]['dic']['BasinCreateAction'] = array(
	'engage' => '该流域已存在……',
	'basin' => '新建流域：%s',
	'fail' => '流域创建失败……',
);
$config[Work::LOG]['dic']['BasinUpdateAction'] = array(
	'deleted' => '该流域已被删除或无法修改……',
	'existed' => '该流域已存在……',
	'basin' => '修改流域 %s 到 %s',
	'fail' => '流域修改失败……',
);
$config[Work::LOG]['dic']['BasinDeleteAction'] = array(
	'engage' => '删除流域： %s',
	'fail' => '流域 %s 删除失败……',
);
$config[Work::LOG]['dic']['FlowCreateAction'] = array(
	'existed' => '该流程已存在……',
	'engage' => '新建流程：%s',
	'fail' => '流程创建失败……',
);
$config[Work::LOG]['dic']['FlowUpdateAction'] = array(
	'engage' => '修改流程：%s',
	'fail' => '流程修改失败……',
);
$config[Work::LOG]['dic']['FlowDeleteAction'] = array(
	'engage' => '删除流程：%s',
	'fail' => '流程删除失败……',
);
$config[Work::LOG]['dic']['ActionCreateAction'] = array(
	'existed' => '该处理已存在……',
	'engage' => '新建处理：%s',
	'fail' => '处理创建失败……',
);
$config[Work::LOG]['dic']['ActionDeleteAction'] = array(
	'engage' => '删除处理：%s',
	'fail' => '处理删除失败……',
);
$config[Work::LOG]['dic']['PageCreateAction'] = array(
	'existed' => '该页面已存在……',
	'engage' => '新建页面：%s',
	'fail' => '页面创建失败……',
);
$config[Work::LOG]['dic']['PageDeleteAction'] = array(
	'engage' => '删除页面：%s',
	'fail' => '页面删除失败……',
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
		'img' => true,
		Flow::PAGE => true,
		Flow::ACTION => true,
		Work::LANG => true,
	)
);
$config['BasinUpdateAction'] = array(
	'exclude' => array(
		'.' => true,
		'..' => true,
		'.git' => true,
		'.runtime' => true,
		'.settings' => true,
		'bai' => true,
		'basin' => true,
		'admin' => true,
	),
);
$config['BasinDeleteAction'] = array(
	'exclude' => array(
		'.' => true,
		'..' => true,
		'.git' => true,
		'.runtime' => true,
		'.settings' => true,
		'bai' => true,
		'basin' => true,
		'admin' => true,
	),
);
$config['FlowAction'] = array(
	'include' => array(
		Flow::ACTION => '#^(?<'.Bai::EVENT.'>[a-zA-Z0-9_\x7f-\xff]+)Action\.php$#i',
		Flow::PAGE => '#^(?<'.Bai::EVENT.'>[a-zA-Z0-9_\x7f-\xff]+)\.php$#i',
	)
);
$config['FlowCreateAction'] = array(
	'include' => array(
		Flow::PAGE => '%s'._EXT,
		Flow::ACTION => '%s'.Flow::ACTION._EXT,
	)
);
$config['FlowUpdateAction'] = array(
	'include' => $config['FlowCreateAction']['include'],
);
$config['FlowDeleteAction'] = array(
	'include' => $config['FlowCreateAction']['include'],
);
$config['ActionCreateAction'] = array(
	'include' => Flow::ACTION._DIR.'%s'.Flow::ACTION._EXT,
);
$config['ActionDeleteAction'] = array(
	'include' => Flow::ACTION._DIR.'%s'.Flow::ACTION._EXT,
);
$config['PageCreateAction'] = array(
	'include' => Flow::PAGE._DIR.'%s'._EXT,
);
$config['PageDeleteAction'] = array(
	'include' => Flow::PAGE._DIR.'%s'._EXT,
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
			'ajax' => array(
				'fail' => '内容加载失败……',
			),
			'bubble' => array (
				'title' => '提示',
				'content' => '没有提示内容……',
				'load' => '请稍候，正在加载……',
				'fail' => '内容加载失败……',
				'' => '加载完成，但是没有内容……',
				'success' => '操作成功',
				'complete' => '操作完成',
				'failure' => '操作失败',
			),
		),
	) )
);
