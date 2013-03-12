<?php
/**
 * <b>BaiPHP（简单PHP）开发框架</b>
 * @author		白晓阳
 * @copyright	Copyright (c) 2011 - 2012, 白晓阳
 * @link		http://dacbe.com
 * @version    V1.0.0 2012/03/31 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 */

/**
 * <b>BaiPHP（简单PHP）开发框架</b><br/>
 * <b>用户配置</b>
 * <p>
 * 系统运行时由用户设置与维护的数据，可根据需要自行增删
 * ！！！Check务必保留，除非不需要输入检验！！！
 * </p>
 * @author 白晓阳
 */


global $config;

### 全局配置：目标，可覆盖系统配置
$config[Work::TARGET] = array(
);

### 全局配置：检验工场
$config[Work::CHECK] = array(
	Work::CHECK   => array(
		'case'    => '/(?<call>[^\s=]+)(?:=(?<params>[^\s=]+))?/',
		'param'   => ',',
		'risk'    => '/[<>&%\'\\\]+/',                 ### 风险字符
		'integer' => '/^[1-9]\d*$/',                   ### 整数
		'float'   => '/^[+-]?\d+(?:\.\d+)?$/',         ### 小数
		'letter'  => '/^[a-zA-Z]+$/',                  ### 英文字母
		'char'    => '/^[a-zA-Z0-9_-]+$/',             ### 英文字符
		'mobile'  => '/^(?:\+86)?1[358][0-9]{9}$/',    ### 移动电话
		'fax'    => '/^0[0-9]{2,3}-[1-9][0-9]{6,7}$/', ### 固话传真
		'url'    => '/^(?:https?:\/\/)?[a-zA-Z0-9-_.\/]+(?:\?.+)?$/', ### 网址
		'email'  => '/^[a-zA-Z0-9-_.]+@[a-zA-Z0-9-_.]+$/',            ### 邮箱
		'date'   => '/^[0-9]{4}[-.\/]?(?:0?[1-9]|1[0-2])[-.\/]?(?:0?[1-9]|[12][0-9]|3[01])$/', ### 日期
		'time'   => '/^(?:0?[0-9]|1[0-9]|2[0-3])[:-]?(?:0?[0-9]|[1-5][0-9])[:-]?(?:0?[0-9]|[1-5][0-9])$/', ### 时间
	),
	_DEFAULT      => array(
	),
	'sampleCheck' => array(
		'sampleInt' => 'required min=3 max=5 type=number',
		'sampleLetter' => 'required min=3 max=10 type=letter',
	),
);

### 全局配置：页面流程
$config[Flow::PAGE] = array(
	'layout'      => '_layout.php',
	'title'       => 'BaiPHP-简单PHP',
	'author'      => 'baidi-白晓阳',
	'keywords'    => 'BaiPHP-简单PHP',
	'description' => 'BaiPHP-简单PHP',
	'copyright'   => 'Copyright '.date('Y').', All Rights Reserved. 版权('.date('Y').')所有，保留一切权力。',
	'css'         => array(
		'bai.css',
	),
	'js'          => array(
		'bai.js',
	),
	'menu'          => array(
		'home'      => '主页',
		'design'    => '设计模式',
		'driver'    => '驱动模式',
		'sample'    => '案例学习',
		'reference' => '参考资料',
		'contact'   => '联系方式',
	),
	'link'         => array(
		'home'     => '首页',
		'version'  => '版本',
		'manual'   => '手册',
		'example'  => '示例',
		'theory'   => '原理',
		'download' => '下载',
	),
);

### 全局配置：页面css样式
$config['css'] = array(
	'$width$' => '990px',
	'$font$'  => '14px/20px "verdana", "helvetica", "arial", sans-serif',
	#'$color$' => '#000000',
	#'$background$' => '#ffffff',
	'$acolor$' => '#009f3c',
	'$linecolor$' => '#99cc99',
	'$areacolor$' => '#f0f9f0',
	'$shadowcolor$' => '#d0f9d0',
	'$errorcolor$' => '#ff0000',
	'$noticecolor$' => '#99cc99',
	'$lockedcolor$' => '#cccccc',
);

### 全局配置：页面JS脚本
$config['js'] = array(
	'$message$' => $config[Work::LOG][Work::CHECK],
	'$type$'    => $config['Input']['Type'],
	'$timeout$' => 3000,
	'$cipher$'  => '_CIPHER',
);
