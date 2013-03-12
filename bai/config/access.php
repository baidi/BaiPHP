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
 * <b>公共初始流程</b>
 * <p>！！！事件响应的核心文件，任何不当的修改都可能破坏整个系统的正常运行！！！</p>
 *
 * @author 白晓阳
 */

$startTime = microtime(true);

### 导入系统全局配置
include _SYSTEM.'config.php';
### 导入用户全局配置
include _LOCAL._ISSUE.'user.php';
### 导入公共函数
include _SYSTEM.'common.php';
### 导入扩展模块
foreach (c('Extend') as $extend)
{
	if (is_file(_EXTEND.$extend))
	{
		include _EXTEND.$extend;
	}
}

### 启动会话
if (empty($_SESSION))
{
	session_regenerate_id();
	session_start();
}

### 初始化日志入口
Log::access(c('Log'), _LOG_LEVEL);
### 初始化检验入口
Check::access(c('Check'));
### 初始化数据库入口
Data::access(c('DB'));
### 初始化缓存入口
if (defined('_CACHE'))
{
	Cache::access(c('Cache'), _CACHE_TIMEOUT);
}

### 初始化事件
$event = new Event();

### 事件开始
Log::logf('start', _ISSUE.$event, 'Event');

### 事件分流
$issue = cFlow($event);
$issue = new $issue();
$event->$issue = $issue;
$issue->entrust($event);

### 事件结束
Log::logf('end', _ISSUE.$event, 'Event');

$endTime = microtime(true);
Log::logf('time', $endTime - $startTime, 'Event');
?>
