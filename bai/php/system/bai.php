<?php

/**
 * Bai公共初始包
 *
 * @author    白晓阳
 * @copyright Copyright (c) 2011 - 2012, 白晓阳
 * @link       http://www.dacbe.com
 * @version    V1.0.0 2012/03/21 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 */

### 权限检查
if (! defined('_ISSUE'))
	exit('对不起！请走正门……');

### 系统配置文件
include('system.php');
### 用户配置文件
include('user.php');

### 启动会话
if (empty($_SESSION))
{
	session_start();
	#$_SESSION['startTime'] = time();
}

### 初始化日志入口
Log::access($cLog, _LOG_LEVEL);
### 初始化检验入口
Check::access($cCheck);
### 初始化数据入口
Data::access($cData);
### 初始化缓存入口
if (defined('_CACHE'))
	Cache::access($cCache, _CACHE_TIMEOUT);

### 获取事件
$event = empty($_REQUEST['event']) ? 'home' : $_REQUEST['event'];

### 响应事件
$log = Log::access();
$message = $log->messagef('start', $event);
$log->assign($message);
$log->assign('请求：'.join('|', $_REQUEST), Log::L_DEBUG);
$issue = new Issue();
$issue->assign($event);
$message = $log->messagef('end', $event);
$log->assign($message);

?>
