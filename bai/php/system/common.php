<?php
/**
 * <b>BaiPHP（简单PHP）开发框架</b>
 * @author		白晓阳
 * @copyright	Copyright (c) 2011 - 2012, 白晓阳
 * @link		http://www.baiphp.com/
 * @version     V1.0.0 2012/03/31 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 */

/**
 * <b>BaiPHP（简单PHP）开发框架</b><br/>
 * <b>露天工场（公共函数）</b>
 * <p>
 * 一组支撑系统正常运行的关键函数
 * ！！！任何不当的修改都可能破坏整个系统的正常运行！！！
 * </p>
 * @author 白晓阳
 */

/**
 * <b>读取全局配置</b><br/>
 * 根据项目名，读取系统配置文件和用户配置文件中定义的全局配置<br/>
 * 如果传入多个项目名，则会逐层读取，直至非数组项或读取结束
 *
 * @param string $item1 项目1
 * @param string $item2 项目2
 * @param string $item... 项目...
 *
 * @return mixed 项目值
 */
function c()
{
	### 系统（config.php）和用户（user.php）定义的全局配置
	global $config;
	if (! $config)
	{
		return null;
	}
	### 读取传入项目名
	$items = func_get_args();
	if (! $items)
	{
		return null;
	}
	### 根据项目名逐级读取全局配置
	$value = $config;
	foreach ($items as $item)
	{
		if (! isset($value[$item]))
		{
			return null;
		}
		$value = $value[$item];
		if (! is_array($value))
		{
			break;
		}
	}
	return $value;
}

/**
 * <b>读取项目值</b><br/>
 * 取值顺序为默认列表、$_REQUEST、$_SESSION、全局配置
 *
 * @param string $item 项目名
 * @param array $array 默认列表
 * @param bool $limit 是否限定默认列表
 * @param bool $print 是否输出
 *
 * @return mixed 项目值
 */
function cRead($item, array $array = null, $limit = true, $print = false)
{
	if (! $item)
	{
		return null;
	}
	$value = null;
	### 优先从默认列表取值
	if (is_array($array))
	{
		$value = isset($array[$item]) ? $array[$item] : null;
		if ($limit)
		{
			if ($print)
			{
				print $value;
			}
			return $value;
		}
	}
	### 从全局集中取值
	if ($value == null)
	{
		$value = isset($_REQUEST[$item]) ? $_REQUEST[$item] : null;
	}
	if ($value == null)
	{
		$value = isset($_SESSION[$item]) ? $_SESSION[$item] : null;
	}
	if ($value == null)
	{
		$value = c($item);
	}
	if ($print)
	{
		print $value;
	}
	return $value;
}

/**
 * <b>输出项目值</b><br/>
 * 取值顺序为默认列表、$_REQUEST、$_SESSION、全局配置
 *
 * @param string $item 项目名
 * @param array $array 默认列表
 * @param bool $limit 是否限定默认列表
 *
 * @return mixed 项目值
 */
function cWrite($item, array $array = null, $limit = true)
{
	return cRead($item, $array, $limit, true);
}

/**
 * <b>请求系统流程</b><br/>
 * 请求系统对于当前事件的流程，以此件确定系统流程的走向
 *
 * @param string $event 事件
 * @param string $source 来源
 *
 * @return string 响应文件
 */
function cFlow($event, $source = 'Event')
{
	if (! $event)
	{
		return null;
	}
	$event = "$event";
	### 系统响应配置
	$cFlow = c('Flow');
	if (! is_array($cFlow))
	{
		return null;
	}
	### 确定系统响应
	foreach ($cFlow as $item => $value)
	{
		if (preg_match($item, $source))
		{
			$flow = $value;
			break;
		}
	}
	### 加载响应文件
	if ($flow)
	{
		$event = ucfirst($event);
		if (class_exists($event.$flow, true))
		{
			$flow = $event.$flow;
		}
		return $flow;
	}
	### 加载页面文件
	$path = _LOCAL._PAGE;
	### 页面重定向
	$cPage = c('Page', $event);
	if ($cPage && is_file($path.$cPage))
	{
		return $path.$cPage;
	}
	### 默认定向
	### ！！！建议完整页面以Page结尾，Ajax页面块以Box结尾，公共控件无后缀！！！
	### ！！！强烈建议一个事件仅对应一个页面文件（不管何种类型）！！！
	if (is_file($path.$event.'Page.php'))
	{
		return $path.$event.'Page.php';
	}
	if (is_file($path.$event.'Box.php'))
	{
		return $path.$event.'Box.php';
	}
	if (is_file($path.$event.'.php'))
	{
		return $path.$event.'.php';
	}
	return $path.'homePage.php';
}

/**
 * <b>加载页面块文件</b><br/>
 * 页面块文件中可以使用$_param和$_css参数，将用传入参数替换
 *
 * @param string $file 文件名
 * @param string $_param 页面参数，用于页面文件内
 * @param string $_css CSS类名，用于页面文件内
 * @param bool $print 是否输出
 *
 * @return string 页面内容
 */
function cLoad($file, $_param = null, $_css = null, $print = true)
{
	if (! $file)
	{
		return null;
	}
	#if (substr($file, -4) != '.php') {
	#	$file .= '.php';
	#}
	$file = _LOCAL._PAGE.$file;
	if (! is_file($file))
	{
		return null;
	}
	ob_start();
	include $file;
	$page = ob_get_clean();
	if ($print)
	{
		print $page;
	}
	return $page;
}

/**
 * <b>生成输入项HTML提示信息</b><br/>
 * 根据输入项目的检验内容，生成对应的HTML提示信息
 *
 * @param string $event 事件
 * @param string $item 检验项目
 * @param bool $print 是否输出
 *
 * @return string HTML提示信息
 */
function cInput($event, $item, $print = true)
{
	### 读取检验规则
	$cCheck = c('Check', $event, $item);
	if (! $cCheck)
	{
		return null;
	}
	### 输入项检验条目
	$cItems = c('Input', 'Items');
	if (! is_array($cItems))
	{
		return null;
	}
	### 输入项提示信息
	$cHints = c('Input', 'Hints');
	### 输入项HTML片段
	$cHtmls = c('Input', 'Htmls');
	### 生成提示信息
	$input = preg_replace($cItems, $cHints, $cCheck);
	$input = substr($input, strlen(c('Input', 'Gap')));
	$input = ' data-check="'.$cCheck.'" title="'.$input.'"';
	$input = ' class="input"'.preg_replace($cItems, $cHtmls, $cCheck).$input;
	$input = ' id="'.$event.'_'.$item.' name="'.$event.'['.$item.']"'.$input;
	if ($print)
	{
		print $input;
	}
	return $input;
}

/**
 * <b>生成安全暗号</b><br/>
 * 由服务器端生成并发送到客户端，客户端原样提交，用于检验客户端的唯一性
 *
 * @param string $event 事件
 * @param integer $seed 随机种子
 *
 * @return string 安全暗号
 */
function cCipher($event, $seed = 10000)
{
	if (! defined('_CIPHER') || ! _CIPHER || empty($_SESSION))
	{
		return null;
	}
	$cipher = md5($event.date('YmdHis').rand(0, $seed));
	$_SESSION[_CIPHER] = $cipher;
	return $cipher;
}

/**
 * <b>还原加密数据</b><br/>
 * 还原客户端发送的加密数据，仅在开启安全暗号时有效
 *
 * @param string $data 加密数据
 *
 * @return string 原始数据
 */
function cReset($data)
{
	if (! $data || ! defined('_CIPHER') || ! _CIPHER || empty($_SESSION))
	{
		return null;
	}
	for ($i = 0, $m = strlen($data); $i < $m; $i++)
	{
		$b = ord($data[$i]);
		$data[$i] = chr(((($b & 0x0F) - ($b >> 4)) & 0x0F) + ($b & 0xF0));
	}
	return urldecode($data);
}

/**
 * 类文件自动加载<br/>
 *
 * @param string $class 类名
 */
function __autoload($class)
{
	if (is_file(_SYSTEM.$class.'.php'))
	{
		include_once _SYSTEM.$class.'.php';
		return true;
	}
	if (is_file(_LOCAL._ACTION.$class.'.php'))
	{
		include_once _LOCAL._ACTION.$class.'.php';
		return true;
	}
	if (is_file(_EXTEND.$class.'.php'))
	{
		include_once _EXTEND.$class.'.php';
		return true;
	}
	return false;
}

#set_error_handler(
#		function($type, $message, $file, $line, $context)
#		{
#			Log::logf('file', array($file, $line), 'Event', Log::L_ERROR);
#			die(Log::logs('error', $message, 'Event', Log::L_ERROR));
#		}
#);
?>
