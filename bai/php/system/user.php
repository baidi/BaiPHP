<?php

### 权限检查
if (! defined('_ISSUE'))
	exit('对不起！请走正门……');

/**
 * Bai用户配置文件
 *
 * @author    白晓阳
 * @copyright Copyright (c) 2011 - 2012, 白晓阳
 * @link       http://www.dacbe.com
 * @version    V1.0.0 2012/03/21 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 */

/** 网站标题 */
$cTitle = 'BaiPHP - 简单PHP开发框架';

$cSample = 'BaiPHP - 简单编程（这行文字是通过用户配置文件加入的内容）';

/** 主菜单 */
$cMenu = array
(
		'home'    => '主页',
		'design' => '设计模式',
		'driver' => '驱动模式',
		'sample' => '案例学习',
		'reference' => '参考资料',
		'contact' => '联系方式',
);

/** 输入检验: 默认 */
$cCheck[_DEFAULT] = array();
/** 输入检验: 案例3 */
$cCheck['sampleCheck'] = array
(
		'sampleInt' => 'required min=3 max=5 type=number',
		'sampleLetter' => 'required min=3 max=10 type=letter',
);

?>