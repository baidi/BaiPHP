<?php
################################################################################
# BaiPHP Mobile Framework
# http://www.baiphp.com
# Copyright (C) 2011-2014 Xiao Yang, Bai
#
# Anyone obtaining a copy of BaiPHP gets permission to use, copy, modify, merge,
# publish, distribute, and/or sell it for non-profit purpose.
# Any contributor to BaiPHP gets for-profit permission for itself only, which
# can't be transferred or rent.
# Authors or copyright holders don't take any for all the consequences arising
# therefrom.
# By using BaiPHP, you are unconditionally agree to this notice and must keep it
# in the copy.
################################################################################


/**
 * <h2>BaiPHP Mobile Framework</h2>
 * <h3>Application runtime</h3>
 * <p>
 * <ol>
 * <li>define global consts</li>
 * <li>define global config</li>
 * <li>register class autoloader</li>
 * <li>register exception/error handler</li>
 * </ol>
 * </p>
 *
 * @copyright Copyright (C) 2011-2014 Xiao Yang, Bai
 * @author Xiao Yang, Bai
 */

/**
 * Start time
 */
define('_START', microtime(true));

/**
 * App name
 */
define('_APP', 'BaiPHP');

/**
 * Default item
 */
define('_DEF', '_');

/**
 * Directory separator
 */
define('_DIR', '/');

/**
 * File extension
 */
define('_EXT', '.php');

/**
 * Local path
 */
define('_LOCAL', dirname($_SERVER['SCRIPT_FILENAME']) . _DIR);

### parse web path
$host = empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off' ? 'http://' : 'https://';
$host .= $_SERVER['SERVER_NAME'];
if ($_SERVER['SERVER_PORT'] != 80)
{
	$host .= ':' . $_SERVER['SERVER_PORT'];
}
$path = dirname($_SERVER['SCRIPT_NAME']);
if (strlen($path) > 1)
{
	$host .= $path;
}
/**
 * Web path
 */
define('_WEB', $host . _DIR);
unset($host, $path);

/**
 * Global config
 */
$config = array();

### global config [default]
$config[_DEF] = array(
	### app base
	'App' => 'bai' . _DIR,
	### custom base
	'Base' => 'base' . _DIR,
	### runtime base
	'Runtime' => '.runtime' . _DIR,
	### root folder, under base
	'Root' => 'root' . _DIR,
	### service folder, under base
	'Service' => 'service' . _DIR,
	### ext folder, under base
	'Ext' => array(
		'/^[a-zA-Z0-9_\x7f-\xff]+Control$/' => 'Control' . _DIR,
		'/^[a-zA-Z0-9_\x7f-\xff]+Action$/' => 'Action' . _DIR,
		'/^[a-zA-Z0-9_\x7f-\xff]+Work$/' => 'Work' . _DIR
	),
	### default event
	'Event' => 'index',
	### default message
	'Message' => '[Notice] %s(%s): %s (see log for details)'
);

### global config [app]
$config[_APP] = $config[_DEF];
if (!empty($_REQUEST['base']))
{
	$config[_APP]['Base'] = preg_replace('#[\/]#', '', $_REQUEST['base']) . _DIR;
}

### register class autoloader
spl_autoload_register(function ($class)
{
	### generate loading path
	$config = $GLOBALS['config'][_APP];
	$app = _LOCAL . $config['App'];
	$base = _LOCAL . $config['Base'];
	$root = $config['Root'];
	$ext = null;
	foreach ((array) $config['Ext'] as $item => $path)
	{
		if (preg_match($item, $class))
		{
			$ext = $path;
			break;
		}
	}
	$file = $class . _EXT;

	### load class file from ext path
	if ($ext != null)
	{
		if (is_file($path = $base . $ext . $file))
		{
			require_once $path;
			return class_exists($class, false);
		}
		if (is_file($path = $app . $ext . $file))
		{
			require_once $path;
			return class_exists($class, false);
		}
	}

	### load class file from root path
	if (is_file($path = $base . $root . $file))
	{
		require_once $path;
		return class_exists($class, false);
	}
	if (is_file($path = $app . $root . $file))
	{
		require_once $path;
		return class_exists($class, false);
	}
	return false;
}, true);

### register exception handler
set_exception_handler(function ($e)
{
	$config = $GLOBALS['config'][_APP];
	if (empty($config['Debug']))
	{
		ob_clean();
	}
	$file = basename($e->getFile());
	echo sprintf($config['Message'], $file, $e->getLine(), $e->getMessage());
	foreach ($e->getTrace() as $trace)
	{
		echo '<br/>-- ', basename($trace['file']),
			"({$trace['line']}) {$trace['class']}{$trace['type']}{$trace['function']}";
	}
});

### register error handler
register_shutdown_function(function ()
{
	$error = error_get_last();
	if ($error == null)
	{
		return true;
	}

	$config = $GLOBALS['config'][_APP];
	$type = $error['type'];
	$file = basename($error['file']);

	### when error and breaking down
	if ($type == E_ERROR || $type == E_USER_ERROR || $type == E_CORE_ERROR ||
			 $type == E_COMPILE_ERROR || $type == E_PARSE || $type == E_RECOVERABLE_ERROR)
	{
		if (empty($config['Debug']))
		{
			ob_clean();
		}
		echo sprintf($config['Message'], $file, $error['line'], $error['message']);
		return false;
	}

	### when notice and continuing
	if (empty($config['Debug']))
	{
		ob_clean();
	}
	return false;
});
