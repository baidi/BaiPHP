<?php
/**
 * <h2>化简PHP（BaiPHP）开发框架</h2>
 * @link      http://www.baiphp.net
 * @copyright Copyright 2011 - 2013, 白晓阳
 * @author    白晓阳
 * @version   1.0.0 2012/03/31 首版
 *            2.0.0 2013/07/01 全面重构代码，弃用公共函数，独立启动引擎，优化配置文件结构，增加
 *            原始虚类、目标工场、样式工场、模板工场、语言工场和记录工场，并优化代码结构。
 * @license
 * <p>化简PHP（BaiPHP）开发框架，是依据"面向目标"的设计思想、基于"服务-流程-工场"的设计模式、以简洁
 * 灵活为方向、由白晓阳设计和开发的一套PHP应用框架。该框架的核心是基于配置并即时可控的流程走向和流程覆
 * 盖，它采用了简洁优雅的实现方式，不但显著提升框架的易学性和易用性，而且最大限度地释放出应用的灵活性和扩
 * 展性，从而尽可能地降低程序的开发和维护成本。</p>
 * <p>化简PHP（BaiPHP）开发框架完全开放源代码，任何人都可以自由地复制、传播、修改和使用该代码，但未经
 * 授权，不得用于商业目的。</p>
 * <p>欢迎对该框架提供任何形式的捐助，捐助者自动获得仅限于捐助者自身的商业使用（不包括再发行和再授权）授
 * 权。</p>
 * <p>化简PHP（BaiPHP）开发框架由白晓阳持有版权，并保留一切权利。</p>
 */

/**
 * <h2>化简PHP（BaiPHP）开发框架</h2>
 * <h3>启动引擎</h3>
 * <p>
 * 启动框架的最简环境。
 * 主要包括默认目标设置、对象自动加载和出错清尾。
 * </p>
 * @copyright Copyright 2011 - 2013, 白晓阳
 * @author 白晓阳
 */


### 设置错误报告级别，默认：E_ERROR（错误）
ini_set('error_reporting', E_ALL | E_STRICT);
### 设置页面错误显示，默认：0（不显示）
ini_set('display_errors', 1);
### 设置日志错误记录，默认：1（记录）
ini_set('log_errors', 1);


/** 应用名 */
define('_APP', 'BaiPHP');

/** 默认项 */
define('_DEF', '_');

/** 目录符 */
define('_DIR', '/');

/** 扩展名 */
define('_EXT', '.php');

/** 本地路径 */
define('_LOCAL', dirname($_SERVER['SCRIPT_FILENAME'])._DIR);

### 解析网络路径
$server = empty($_SERVER['HTTPS']) ? 'http://' : 'https://';
$server .= $_SERVER['SERVER_NAME'];
if ($_SERVER['SERVER_PORT'] != 80) {
	$server .= ':'.$_SERVER['SERVER_PORT'];
}
$path = dirname($_SERVER['SCRIPT_NAME']);
if (strlen($path) > 1) {
	$server .= $path;
}
/** 网络路径 */
define('_WEB', $server._DIR);
unset($server, $path);


/** 全局配置 */
$config = array();

### 全局配置：默认
$config[_DEF] = array(
	### 系统路径，存放系统框架文件
	'Bai'       => 'bai'._DIR,
	### 服务路径，存放用户响应文件
	'Service'   => 'service'._DIR,
	### 运行路径，存放运行时产生的文件
	'Runtime'   => '.runtime'._DIR,
	### 基本路径，相对于系统路径和服务路径，存放核心文件
	'Root'      => substr(_EXT, 1)._DIR,
	### 扩展路径，相对于系统路径和服务路径，存放扩展文件
	'Extent'    => array(
		'/^[a-zA-Z0-9_\x7f-\xff]+Action$/' => 'Action'._DIR,
		'/^[a-zA-Z0-9_\x7f-\xff]+Work$/'   => 'Work'._DIR,
	),
	'Error'     => '一个不注意，就会出问题：[%s] %s',
	'Exception' => '不是没做到，就是没想到：[%s] %s',
	'Notice'    => '大体还可以，细节要留意：[%s] %s',
);
if (! empty($_REQUEST['service'])) {
	$config[_DEF]['Service'] = preg_replace('#[\/]#', '', $_REQUEST['service'])._DIR;
}


### 对象自动加载
spl_autoload_register(function($class)
{
	$config = $GLOBALS['config'][_DEF];
	### 加载路径
	$bai     = _LOCAL.$config['Bai'];
	$service = _LOCAL.$config['Service'];
	$root    = $config['Root'];
	$branch  = null;
	foreach ((array)$config['Extent'] as $item => $mode) {
		if (preg_match($item, $class)) {
			$branch = $mode;
			break;
		}
	}
	### 加载文件
	$file = $class._EXT;
	if ($branch != null) {
		### 加载用户扩展文件
		if (is_file($service.$branch.$file)) {
			require_once $service.$branch.$file;
			return class_exists($class, false);
		}
		### 加载系统扩展文件
		if (is_file($bai.$branch.$file)) {
			require_once $bai.$branch.$file;
			return class_exists($class, false);
		}
	}
	### 加载用户核心文件
	if (is_file($service.$root.$file)) {
		require_once $service.$root.$file;
		return class_exists($class, false);
	}
	### 加载系统核心文件
	if (is_file($bai.$root.$file)) {
		require_once $bai.$root.$file;
		return class_exists($class, false);
	}
	return false;
}, true);


### 异常自动捕获
set_exception_handler(function($e)
{
	$config = $GLOBALS['config'][_DEF];
	if (empty($config['Debug'])) {
		ob_clean();
	}
	$file = basename($e->getFile()).' :> '.$e->getLine().' :> '.get_class($e);
	echo sprintf($config['Exception'], $file, $e->getMessage());
});


### 错误自动清理
register_shutdown_function(function()
{
	### 错误信息
	$error = error_get_last();
	if ($error == null) {
		return true;
	}
	$config = $GLOBALS['config'][_DEF];
	$type = $error['type'];
	$file = basename($error['file']).' :> '.$error['line'];
	### 程序被迫中止时
	if ($type == E_ERROR || $type == E_USER_ERROR || $type == E_CORE_ERROR
			|| $type == E_COMPILE_ERROR || $type == E_PARSE
			|| $type == E_RECOVERABLE_ERROR) {
		if (empty($config['Debug'])) {
			ob_clean();
		}
		echo sprintf($config['Error'], $file, $error['message']);
		return false;
	}
	### 程序受到影响时
	if (! empty($config['Debug'])) {
		echo sprintf($config['Notice'], $file, $error['message']);
		return false;
	}
});
