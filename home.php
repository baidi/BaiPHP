<?php
/**
 * Bai访问入口
 *
 * @author    白晓阳
 * @copyright Copyright (c) 2011 - 2012, 白晓阳
 * @link       http://www.dacbe.com
 * @version    V1.0.0 2012/03/21 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 */

### 系统设置
$rootPath = str_replace('\\', '/', dirname(__FILE__));
/** 系统路径 */
define('_SYSTEM', $rootPath.'/bai/php/system');
/** 扩展路径 */
define('_EXTEND', $rootPath.'/bai/php/extend');
/** 缓存路径 */
#define('_CACHE', $rootPath.'/cache');
/** 日志路径 */
define('_LOG', $rootPath.'/log');

/** 响应路径 */
define('_ISSUE', $rootPath.'/web');
/** 处理路径 */
define('_ACTION', _ISSUE.'/action');
/** 页面路径 */
define('_PAGE', _ISSUE.'/page');
/** 样式路径 */
define('_STYLE', '/web/style');

### 初始化
include(_SYSTEM.'/bai.php');

?>