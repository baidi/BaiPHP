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

/** 网站标题 */
$config['title'] = 'BaiCode-简单开发 BaiPHP开发框架 BaiJS应用脚本';
$config['keywords'] = 'BaiCode-简单开发 BaiPHP开发框架 BaiJS应用脚本';
$config['description'] = 'BaiCode-简单开发 BaiPHP开发框架 BaiJS应用脚本';

$config['sample'] = 'BaiPHP - 简单编程（这行文字是通过用户配置文件加入的内容）';

### 主菜单
$config['menu'] = array
(
		'home'      => '主页',
		'design'    => '设计模式',
		'driver'    => '驱动模式',
		'sample'    => '案例学习',
		'reference' => '参考资料',
		'contact'   => '联系方式',
);

### 链接
$config['link'] = array
(
		'home'     => '首页',
		'version'  => '版本',
		'manual'   => '手册',
		'example'  => '示例',
		'theory'   => '原理',
		'download' => '下载',
);


### 输入项检验: 默认
$config['Check'][_DEFAULT] = array();

### 输入项检验: 案例3
$config['Check']['sampleCheck'] = array
(
		'sampleInt' => 'required min=3 max=5 type=number',
		'sampleLetter' => 'required min=3 max=10 type=letter',
);

### js全局配置
$config['JS'] = array
(
		'message' => $config['Log']['Check'],
		'type' => $config['Input']['Type'],
		'risk' => $config['Input']['Risk'],
		'timeout' => 3000,
		'cipher' => _CIPHER,
);

### css全局配置
$config['CSS'] = array
(
		'$width$' => '990px',
		'$font$' => '14px/20px normal',
		#'$color$' => '#000000',
		#'$background$' => '#ffffff',
		'$acolor$' => '#009933',
		'$linecolor$' => '#99cc99',
		'$areacolor$' => '#f0f9f0',
		'$shadowcolor$' => '#d0f9d0',
		'$errorcolor$' => '#ff0000',
		'$noticecolor$' => '#99cc99',
		'$lockedcolor$' => '#cccccc',
);
?>
