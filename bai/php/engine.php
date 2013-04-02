<?php
/**
 * <h2>BaiPHP（简单PHP）开发框架</h2>
 * @link      http://www.baiphp.com
 * @copyright Copyright (c) 2011 - 2012, 白晓阳
 * @author    白晓阳
 * @version   1.0.0 2012/03/31 首版
 *            2.0.0 2012/07/01 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 * <p>欢迎提供各种形式的捐助。任何捐助者自动获得仅限于捐助者自身的商业使用（不包括再发行和再授权）授权。</p>
 */

/**
 * <h2>BaiPHP（简单PHP）开发框架</h2>
 * <h3>启动引擎</h3>
 * <p>
 * 启动框架的最简环境。
 * 主要包括默认目标设置、对象自动加载和出错清尾。
 * </p>
 * @author 白晓阳
 */


### 设置错误报告级别，默认：E_ERROR（错误）
ini_set('error_reporting', E_ALL | E_STRICT);
### 设置页面错误显示，默认：0（不显示）
ini_set('display_errors', 1);
### 设置日志错误记录，默认：1（记录）
ini_set('log_errors', 0);


/** 默认项 */
define('_DEFAULT', '_');

/** 目录符 */
define('_DIR', '/');

/** 扩展名 */
define('_EXT', '.php');

/** 本地路径 */
define('_LOCAL', dirname($_SERVER['SCRIPT_FILENAME'])._DIR);

### 解析网络路径
$server = empty($_SERVER['HTTPS']) ? 'http://' : 'https://';
$server .= $_SERVER['SERVER_NAME'];
if ($_SERVER['SERVER_PORT'] != 80)
{
	$server .= ':'.$_SERVER['SERVER_PORT'];
}
$path = dirname($_SERVER['SCRIPT_NAME']);
if (strlen($path) > 1)
{
	$server .= $path;
}
/** 网络路径 */
define('_WEB', $server._DIR);
unset($server, $path);


/** 全局配置 */
$config = array();

### 全局配置：默认
$config[_DEFAULT] = array(
	### 系统路径，存放系统框架文件
	'Bai'      => 'bai'._DIR,
	### 服务路径，存放用户响应文件
	'Service'  => 'service'._DIR,
	### 基本路径，相对于系统路径和服务路径，存放核心文件
	'Root'     => substr(_EXT, 1)._DIR,
	### 分支路径，相对于系统路径和服务路径，存放扩展文件
	'Branches' => array(),
	'Error'    => '<hr/><p>一个不注意，就会出问题。回头多努力，做出好程序。</p><p>:-)%s</p>',
	'Notice'   => '<hr/><p>虽然没有大问题，但小地方要留意。</p><p>:-)%s</p>',
);
if (! empty($_REQUEST['service']))
{
	$config['Service'] = $_REQUEST['service']._DIR;
}
else if (! empty($_SESSION['service']))
{
	$config['Service'] = $_SESSION['service']._DIR;
}


### 对象自动加载
spl_autoload_register(function($class)
{
	global $config, $target;
	if ($target == null)
	{
		$target = $config[_DEFAULT];
	}
	### 加载路径
	$bai     = _LOCAL.$target['Bai'];
	$service = _LOCAL.$target['Service'];
	$root    = $target['Root'];
	$branch  = null;
	foreach ($target['Branches'] as $item => $mode)
	{
		if (preg_match($item, $class))
		{
			$branch = $mode;
			break;
		}
	}
	### 加载文件
	$file = $class._EXT;
	if ($branch != null)
	{
		### 加载用户扩展文件
		if (is_file($service.$branch.$file))
		{
			require_once $service.$branch.$file;
			return true;
		}
		### 加载系统扩展文件
		if (is_file($bai.$branch.$file))
		{
			require_once $bai.$branch.$file;
			return true;
		}
	}
	### 加载用户核心文件
	if (is_file($service.$root.$file))
	{
		require_once $service.$root.$file;
		return true;
	}
	### 加载系统核心文件
	if (is_file($bai.$root.$file))
	{
		require_once $bai.$root.$file;
		return true;
	}
	return false;
}, true);


### 错误自动清理
register_shutdown_function(function()
{
	### 错误信息
	$error = error_get_last();
	if ($error == null)
	{
		return true;
	}
	$type = $error['type'];
	global $config, $target;
	if ($target == null)
	{
		$target = $config[_DEFAULT];
	}
	### 程序被迫中止时
	if ($type == E_ERROR || $type == E_USER_ERROR || $type == E_PARSE
			|| $type == E_COMPILE_ERROR || $type == E_CORE_ERROR
			|| $type == E_RECOVERABLE_ERROR)
	{
		#ob_clean();
		echo sprintf($target['Error'], $error['message']);
		return false;
	}
	### 程序受到影响时
	echo sprintf($target['Notice'], $error['message']);
	return false;
});
