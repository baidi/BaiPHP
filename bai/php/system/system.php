<?php

### 权限检查
if (! defined('_ISSUE'))
	exit('对不起！请走正门……');

/**
 * Bai系统配置与公共函数
 *
 * @author    白晓阳
 * @copyright Copyright (c) 2011 - 2012, 白晓阳
 * @link       http://www.dacbe.com
 * @version    V1.0.0 2012/03/21 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 */

/** 默认项目 */
define('_DEFAULT', '_');
/** 默认日志级别 */
define('_LOG_LEVEL', Log::L_DEBUG);
/** 默认错误报告级别 */
error_reporting(E_ALL);
/** 默认缓存过期时间：秒（不开启缓存无效） */
define('_CACHE_TIMEOUT', 300);

/** 数据连接设置 */
$cData = array(
		'dsn'      => 'mysql:dbname=baiphp;host=localhost',
		'user'     => 'root',
		'password' => '',
);

### 日志信息: 默认
$cLog[_DEFAULT] = '【未定义信息：%s】';
### 日志信息: 事件响应
$cLog['Event'] = array(
		_DEFAULT  => '【未定义事件：%s】',
		'start'   => '【接受事件：%s】',
		'end'     => '【完成事件：%s】',
		'Issue'   => '【响应事件：%s】',
		'Action'  => '【处理事件】',
		'Check'   => '【检验输入】',
		'Data'    => '【访问数据】',
		'Page'    => '【生成页面：%s】',
		'Cache'   => '【缓存页面】',
		'error'    => '响应访问时出错!',
		'login'    => '用户名或密码有误……',
		'message'  => '发布留言失败……',
		'chance'   => '没有发现相关内容……',
		'company'  => '请先登录……',
		'business' => '发布商机失败……',
		'filename' => '文件名有误或该文件不存在……',
		'download' => '开始下载文件【%s】……',
);
### 日志信息：数据访问
$cLog['Data'] = array(
		_DEFAULT  => '【未定义数据项：%s】',
		'Data'    => 'SQL语句执行失败！',
		'connect' => '数据库连接失败！',
		'where'   => 'SQL条件未指定！',
		'value'   => 'SQL字段值未指定！',
		'sql'     => 'SQL执行【%d】条数据',
		'count'   => 'SQL统计【%d】条数据',
		'read'    => 'SQL检索【%d】条数据',
		'add'     => 'SQL追加【%d】条数据',
		'update'  => 'SQL更新【%d】条数据',
		'delete'  => 'SQL删除【%d】条数据',
);
### 日志信息: 输入检验
$cLog['Check'] = array(
		_DEFAULT    => '【未定义检验项：%s】',
		'Check'     => '检验输入项目：%s ',
		'checkItem' => '检验输入时异常：%s',
		'required'  => '项目内容不能为空！',
		'min'       => '项目不能小于【%d】位！',
		'max'       => '项目不能大于【%d】位！',
		'type'      => '项目类型不符！',
		'vcode'     => '验证码不符！',
);
### 日志信息: 文件缓存
$cLog['Cache'] = array(
		_DEFAULT    => '【未定义缓存项：%s】',
		'fetch'     => '提取文件缓存',
		'push'      => '更新文件缓存',
		'file'      => '缓存文件写入失败！',
);
### 日志信息: 缓存
$cLog['MemCache'] = array(
		_DEFAULT    => '【未定义缓存项：%s】',
		'push'      => '更新缓存失败！',
);

/**
 * 请求响应文件
 * @param 事件： $event
 * @param 来源： $source
 */
function cRequest($event, $source = 'Issue')
{
	### 响应请求处理
	if (substr($source, -5) == 'Issue')
	{
		$request = 'Action';
		$event = ucfirst($event);
		if (class_exists($event.$request, true))
		{
			$request = $event.$request;
		}
		return $request;
	}
	### 处理请求页面
	if (substr($source, -6) == 'Action') {
		$request = 'Page';
		$event = ucfirst($event);
		if (class_exists($event.$request, true))
		{
			$request = $event.$request;
		}
		return $request;
	}
	### 页面请求文件
	if (substr($source, -4) == 'Page')
	{
		global $cPage;
		if (! empty($cPage[$event]) && file_exists(_PAGE.'/'.$cPage[$event]))
			return $cPage[$event];

		$request = 'Page';
		if (file_exists(_PAGE."/$event$request.php"))
		{
			return _PAGE."/$event$request.php";
		}
		if (file_exists(_PAGE."/{$event}Box.php"))
		{
			return _PAGE."/{$event}Box.php";
		}
		if (file_exists(_PAGE."/$event.php"))
		{
			return _PAGE."/$event.php";
		}
		return _PAGE."/home$request.php";
	}
}

/**
 * 导入页面文件
 * @param 页面名：    $pagename
 * @param 页面参数：  $_param
 * @param CSS类名：   $_css
 * @param 输出标记：  $print
 */
function cImport($pagename, $_param = null, $_css = '', $print = true)
{
	if (substr($pagename, -4) != '.php')
		$pagename .= '.php';
	$pagename = _PAGE."/$pagename";
	if (! file_exists($pagename))
		return;
	ob_start();
	include($pagename);
	$page = ob_get_clean();
	if ($print)
		print($page);
	return $page;
}

/**
 * 读取项目值：
 * 取值顺序为$array、$_REQUEST、$_SESSION、$GLOBALS
 * @param 取值项目： $item
 * @param 默认列表： $array
 */
function cRead($item, $array = null)
{
	if (! $item)
		return false;
	if (is_array($array))
		return empty($array[$item]) ? null : $array[$item];
	$value = empty($_REQUEST[$item]) ? null : $_REQUEST[$item];
	if (! $value)
		$value = empty($_SESSION[$item]) ? null : $_SESSION[$item];
	if (! $value)
		$value = empty($GLOBALS[$item]) ? null : $GLOBALS[$item];
	return $value;
}

/**
 * 输出项目值：
 * 取值顺序为$array、$_REQUEST、$_SESSION、$GLOBALS
 * @param 输出项目： $item
 * @param 默认列表： $array
 */
function cWrite($item, $array = null)
{
	if (! $item)
		return ;
	if (is_array($array))
	{
		if (isset($array[$item]))
			print($array[$item]);
		return;
	}
	if (isset($_REQUEST[$item]))
	{
		print($_REQUEST[$item]);
		return;
	}
	if (isset($_SESSION[$item]))
	{
		print($_SESSION[$item]);
		return;
	}
	if (isset($GLOBALS[$item]))
	{
		print($GLOBALS[$item]);
	}
}

/**
 * 输出一列项目值：
 * 取值顺序为$array、$_REQUEST、$_SESSION、$GLOBALS
 * @param 输出项目： $item1, $item2, ……
 * @param 默认列表： $array
 */
function cWriteEach()
{
	$items = func_get_args();
	if (! $items)
		return ;
	if (is_array(end($items)))
	{
		$array = array_pop($items);
		$glue = ' ';
	}
	else
	{
		$glue = array_pop($items);
		if (is_array(end($items)))
			$array = array_pop($items);
	}
	$end = end($items);
	foreach ($items as $item) {
		cWrite($item, $array);
		if ($item != $end)
			print($glue);
	}
}

/**
 * 根据检验内容生成提示信息
 * @param 检验事件： $event
 * @param 检验字段： $item
 * @param 输出标记： $print
 */
function cHint($event, $item, $print = true)
{
	global $cCheck;
	$check = cRead($event, $cCheck);
	$check = cRead($item, $check);
	if (! $check)
		return;
	$search = array(
			'#required#i',
			'# ?max\=(\d+)#i',
			'# ?min\=(\d+)#i',
			'# ?type\=number#i',
			'# ?type\=float#i',
			'# ?type\=letter#i',
			'# ?type\=char#i',
			'# ?type\=mp#i',
			'# ?type\=fax#i',
			'# ?type\=url#i',
			'# ?type\=email#i',
			'# ?type\=date#i',
			'# ?type\=time#i',
			'# ?\w+\=([^="]+)#',
	);
	$replacHtml = array(
			' required="required"',
			' maxlength="$1"',
	);
	$replaceHint = array(
			'，内容非空',
			'，最大$1位',
			'，最小$1位',
			'，整数型',
			'，数字型',
			'，英文字母型',
			'，英文字符型',
	);
	$hint = preg_replace($search, $replaceHint, $check);
	$hint = substr($hint, strlen('，'));
	$hint = sprintf(' class="input" data-check="%s" title="%s"', $check, $hint);
	$hint = $hint.preg_replace($search, $replacHtml, $check);
	$hint = sprintf(' id="%s" name="%s"', $item, $item).$hint;
	if ($print)
		print($hint);
	return $hint;
}

/**
 * 格式化文章段落
 * @param 文章内容： $file
 * @param 输出标记： $print
 */
function cFile($file, $print = true)
{
	#$file = htmlentities($file);
	/*preg_replace('/<\?(?:php)?\s?(.*)\s?\?>/e', "eval('$1')", $file);*/
	if ($print)
		print($file);
	return $file;
}

/**
 * 生成检验码并输出图片
 * @param 图片宽度：   $width
 * @param 图片高度：   $height
 * @param 检验码长度： $height
 * @param 检验码种类： $type
 *         L：字母型； N：数字型； W：混合型；
 */
function cVImage($width = 60, $height = 20, $length = 4, $type = 'L')
{
	### 生成随机检验码
	$vcode = '';
	if (! $type || $type == 'L' || $type == 'l')
	{
		$text = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		for ($i = 0; $i < $length; $i++)
		{
			$vcode .= $text[rand(0, 25)];
		}
	}
	else if ($type == 'N' || $type == 'n')
	{
		$text = '0123456789';
		for ($i = 0; $i < $length; $i++)
		{
			$vcode .= $text[rand(0, 9)];
		}
	}
	else
	{
		$text = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		for ($i = 0; $i < $length; $i++)
		{
			$vcode .= $text[rand(0, 35)];
		}
	}
	$_SESSION['vcode'] = $vcode;

	### 生成检验码图片
	$png = imagecreate($width, $height);
	### 背景色
	$backColor   = imagecolorallocate($png, rand(100, 200), rand(100, 200), rand(100, 200));
	### 掩饰色
	$maskedColor = imagecolorallocate($png, rand(150, 220), rand(150, 220), rand(150, 220));
	### 文字色
	$textColor   = imagecolorallocate($png, rand(200, 255), rand(200, 255), rand(200, 255));
	imagefill($png, 0, 0, $backColor);
	for ($x = 0; $x <= $width; $x += 5) {
		for ($y = 0; $y <= $height; $y += 5) {
			imagefilledellipse($png, $x, $y, 4, 4, $maskedColor);
		}
	}
	$left = $width / ($length + 1);
	$top = $height / 3;
	#imagefilter($png, IMG_FILTER_GAUSSIAN_BLUR);
	for ($i = 0; $i < $length; $i++)
	{
		imagestring($png, rand(4, 6), rand(0, $left) + $left * $i, rand(0, $top), $vcode[$i], $textColor);
	}

	### 输出检验码图片
	ob_clean();
	header('Content-Type: image/png; charset=binary');
	imagepng($png);
	imagedestroy($png);
}

/**
 * 类文件自动加载
 * @param 类名： $className
 */
function __autoload($className)
{
	if (file_exists(_SYSTEM."/$className.php"))
	{
		include_once(_SYSTEM."/$className.php");
		return;
	}
	if (file_exists(_ACTION."/$className.php"))
	{
		include_once(_ACTION."/$className.php");
		return;
	}
	if (file_exists(_EXTEND."/$className.php"))
	{
		include_once(_EXTEND."/$className.php");
		return;
	}
}
?>