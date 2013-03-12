<?php
/**
 * <h2>BaiPHP（简单PHP）开发框架</h2>
 * @link      http://www.baiphp.com
 * @copyright Copyright (c) 2011 - 2012, 白晓阳
 * @author    白晓阳
 * @version   1.0.0 2012/03/31 首版
 *            2.0.0 2012/07/01 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 * <p>欢迎提供捐助。任何捐助者自动获得仅限于捐助者自身的商业使用（不包括再发行和再授权）授权。</p>
 */

/**
 * <h2>BaiPHP（简单PHP）开发框架</h2>
 * <h3>全局系统配置</h3>
 * <p>
 * 定义系统运行时的各种配置。
 * </p>
 * @author 白晓阳
 */

/** 默认项 */
define('_DEFAULT', '_BAI');

/** 本地路径 */
define('_LOCAL', dirname($_SERVER['SCRIPT_FILENAME']).'/');

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
define('_WEB', $server.'/');
unset($server, $path);


### 设置报告内容，默认值：E_ERROR
ini_set('error_reporting', E_ALL | E_STRICT);
### 设置错误显示，默认值：false
ini_set('display_errors', false);
### 设置错误记录，默认值：true
ini_set('log_errors', true);
### 设置日志后缀，默认值："\r\n"
ini_set('error_append_string', "\r\n");


/**
 * <h3>BaiPHP（简单PHP）全局配置</h3>
 * <p>
 * 系统配置项一般首字母大写，用户配置项一般首字母小写。
 * </p>
*/
$config = array();

### 全局配置：系统
$config['Target'] = array(
		'Event'   => 'home',       ### 默认目标
		'Core'    => 'bai/php/',   ### 内核路径
		'Service' => 'service/',   ### 服务路径
		'Log'     => 'log/',       ### 日志路径
		'Cache'   => 'cache/',     ### 缓存路径
		'Action'  => 'action/',    ### 处理路径
		'Page'    => 'page/',      ### 页面路径
		'Class'   => array('Action', 'Page', 'Work'),
);

### 全局配置：流程
$config['Flow'] = array(
		'#Target$#'  => 'Control', ### 委托目标=>调度流程
		'#Control$#' => 'Action',  ### 调度流程=>处理流程
		'#Action$#'  => 'Page',    ### 处理流程=>页面流程
		'#Page$#'    => null,      ### 页面流程=>流程结束
);

### 全局配置：工场
$config['Work'] = array(
);

### 全局配置：服务
$config['Work'] = array(
);

### 全局配置：数据库
$config['Data'] = array(
		'dbtype'   => 'mysql',
		'dbhost'   => 'localhost',
		'dbport'   => '',
		'dbname'   => 'baiphp',
		'user'     => 'root',
		'password' => '',
		'mysql'    => 'mysql:host=<dbhost>;<:dbport>;dbname=<dbname>',
		'pgsql'    => 'pgsql:host=<dbhost>;port=<dbport>;dbname=<dbname>',
		'mssql'    => 'sqlsrv:Server=<dbhost><,dbport>;Database=<dbname>',
		'sqlite'   => 'sqlite:<dbhost>',
);

### 页面重定向设置
$config['Page'] = array(
		_DEFAULT => 'homePage',
);

### 全局配置：页面模板
$config['Layout'] = array(
		_DEFAULT => 'layout/layout.php',
);

### 输入项检验条目
$config['Input']['Items'] = array(
		'#required#i',
		'#\smax=(\d+)#i',
		'#\smin=(\d+)#i',
		'#\stype=number#i',
		'#\stype=float#i',
		'#\stype=letter#i',
		'#\stype=char#i',
		'#\stype=mp#i',
		'#\stype=fax#i',
		'#\stype=url#i',
		'#\stype=email#i',
		'#\stype=date#i',
		'#\stype=time#i',
		'#\s\w+=([^\s"]+)#',
);

### 输入项提示信息
$config['Input']['Hints'] = array(
		'required' => '，内容非空',
		'max'      => '，最大$1位',
		'min'      => '，最小$1位',
		'number'   => '，单纯数字',
		'float'    => '，合法整数或小数',
		'letter'   => '，大小写英文字母',
		'char'     => '，大小写英文字母、数字、划线',
		'mp'       => '，移动电话号码',
		'fax'      => '，固话传真号码（带区号，以-连接）',
		'url'      => '，合法英文网址',
		'email'    => '，合法英文邮箱',
		'date'     => '，合法日期（年月日无分割或以-、/、空格分割）',
		'time'     => '，合法时间（时分秒无分割或以:、-、空格分割）',
);

### 提示信息头
$config['Input']['Gap'] = '，';

### 输入项HTML片段
$config['Input']['Htmls'] = array(
		' required="required"',
		' maxlength="$1"',
);

### 危险字符检验：正则
$config['Input']['Risk'] = '/[<>&%\'\\\]+/';

### 类型检验：正则
$config['Input']['Type'] = array(
		### 数字
		'number' => '/^[1-9]\d*$/',
		### 数值
		'float'  => '/^[+-]?\d+(?:\.\d+)?$/',
		### 英文字母
		'letter' => '/^[a-zA-Z]+$/',
		### 英文字母数字划线
		'char'   => '/^[a-zA-Z0-9_-]+$/',
		### 移动电话
		'mp'     => '/^(?:\+86)?1[358][0-9]{9}$/',
		### 固话传真
		'fax'    => '/^0[0-9]{2,3}-[1-9][0-9]{6,7}$/',
		### 网址
		'url'    => '/^(?:https?:\/\/)?[a-zA-Z0-9-_.\/]+(?:\?.+)?$/',
		### 邮箱
		'email'  => '/^[a-zA-Z0-9-_.]+@[a-zA-Z0-9-_.]+$/',
		### 日期
		'date'   => '/^[0-9]{4}[-.\/]?(?:0?[1-9]|1[0-2])[-.\/]?(?:0?[1-9]|[12][0-9]|3[01])$/',
		### 时间
		'time'   => '/^(?:0?[0-9]|1[0-9]|2[0-3])[:-]?(?:0?[0-9]|[1-5][0-9])[:-]?(?:0?[0-9]|[1-5][0-9])$/',

);

### 日志信息：默认
$config['Log'][_DEFAULT] = '〖未定义信息项：%s〗';
### 日志信息: 系统
$config['Log']['Target'] = array(
		_DEFAULT   => '〖未定义系统项：%s〗',
		'start'    => '【事件开始：%s】',
		'end'      => '【事件结束：%s】',
		'error'    => '【系统运行时出错：%s】',
		'file'     => '【系统运行时出错：因为%s文件%s行】',
		'time'     => '〖事件用时：%0.3f秒〗',
		'Issue'    => '〖分流事件：%s〗',
		'Action'   => '〖处理事件〗',
		'Check'    => '〖检验输入〗',
		'Data'     => '〖访问数据〗',
		'Page'     => '〖输出页面：%s〗',
		'Cache'    => '〖缓存页面〗',
		'__get'    => '访问未定义属性：%s',
		'__call'   => '访问未定义方法：%s',
);
### 日志信息：数据库访问
$config['Log']['Data'] = array(
		_DEFAULT  => '〖未定义数据库项：%s〗',
		'Data'    => 'SQL语句执行失败……',
		'connect' => '数据库连接失败……',
		'access'  => '数据库参数有误……',
		'assign'  => 'SQL执行〖%d〗条数据',
		'count'   => 'SQL统计〖%d〗条数据',
		'read'    => 'SQL检索〖%d〗条数据',
		'create'  => 'SQL追加〖%d〗条数据',
		'update'  => 'SQL更新〖%d〗条数据',
		'delete'  => 'SQL删除〖%d〗条数据',
);
### 日志信息：数据
$config['Log']['Record'] = array(
		_DEFAULT  => '〖未定义数据项：%s〗',
		'Record'    => '数据表名未设定……',
		'assign'  => '数据操作未定义〖%d〗',
		'count'   => 'SQL统计〖%d〗条数据',
		'read'    => 'SQL检索〖%d〗条数据',
		'create'  => 'SQL追加〖%d〗条数据',
		'update'  => 'SQL更新〖%d〗条数据',
		'delete'  => 'SQL删除〖%d〗条数据',
);
### 日志信息: 输入检验
$config['Log']['Check'] = array(
		_DEFAULT    => '〖未定义检验项：%s〗',
		'Check'     => '输入项检验：%s',
		'checkItem' => '输入项检验时异常：%s',
		_CIPHER     => '安全暗号不符或已过期，请刷新页面后重试……',
		'__call'    => '输入项检验设置有误：%s',
		'risk'      => '输入项不能包含&lt; &gt; &amp; \' " ; % \ 等非法字符……',
		'required'  => '输入项不能为空……',
		'min'       => '输入项不能小于〖%d〗位……',
		'max'       => '输入项不能大于〖%d〗位……',
		'type'      => '输入项属性不符……',
);
### 日志信息: 缓存
$config['Log']['Cache'] = array(
		_DEFAULT    => '〖未定义缓存项：%s〗',
		'fetch'     => '提取缓存数据：%s',
		'push'      => '更新缓存数据：%s',
		'file'      => '缓存文件写入失败……',
);
### 日志信息：测试
$config['Log']['Test'] = array(
		_DEFAULT => '〖未定义测试项：%s〗',
		'Test'   => '执行测试时出错……',
		'file'   => '无效的测试文件：%s',
		'testee' => '无效的测试对象：%s',
		'buildCase' => '构建对象无效：%s',
		'||'  => '跳过测试：%s',
		'testAll' => '执行测试',
		'testCase' => '执行测试情景：%s->%s',
		'logs' => 'Log测试：预置日志',
		'logf' => 'Log测试：参数〖%s〗预置日志',
		'logf2' => 'Log测试：数组〖%s〗〖%s〗预置日志',
		'logf3' => 'Log测试：数组〖%s〗〖%s〗〖%s〗预置日志',
);

$config['Log']['Issue'] = array(
		'client'    => '访问来源：%s(%s:%d)@%s [%s]',
		'server'    => '访问目标：%s(%s:%d)@%s:%s [%s]',
		'response'  => '真实响应：%s?%s',
);

### 单元测试
$config['Test'] = array(
		#'Log' => _TEST.'LogTest.php',
		#'Event' => _TEST.'EventTest.php',
		#'Check' => _TEST.'CheckTest.php',
		#'Data' => _TEST.'DataTest.php',
		'Cache' => _TEST.'CacheTest.php',
);

$config['Check']['Test'] = array(
		'letter' => 'required min=3 max=10 type=letter',
		'number' => 'required min=1 max=5 type=number',
);


/** 类文件自动加载 */
function __autoload($class)
{
	global $config, $target;
	$error = '全局配置项缺失，请检查全局配置文件【config.php】……';
	### 必需全局配置项
	if (empty($config['Target']['Event']) || empty($config['Target']['Core'])
			|| empty($config['Target']['Service']))
	{
		exit($error);
	}
	### 系统路径
	$event = $config['Target']['Event'];
	$core = $config['Target']['Core'];
	$service = $config['Target']['Service'];
	### 目标路径
	if ($target)
	{
		$event = $target->Event;
		$core = $target->Core;
		$service = $target->Service;
	}
	### 包含路径
	if (! empty($config['Target']['Class']) && is_array($config['Target']['Class']))
	{
		foreach ($config['Target']['Class'] as $item)
		{
			if (preg_match('#[a-zA-Z0-9_]+'.$item.'$#', $class))
			{
				break;
			}
			$item = null;
		}
	}
	if (! $item)
	{
		if (is_file(_LOCAL.$service.'/'.$item.'/'.$class.'.php'))
		{
			require_once _LOCAL.$service.'/'.$item.'/'.$class.'.php';
			return true;
		}
		if (is_file(_LOCAL.$core.'/'.$item.'/'.$class.'.php'))
		{
			require_once _LOCAL.$core.'/'.$item.'/'.$class.'.php';
			return true;
		}
	}
	if (is_file(_LOCAL.$core.'/Core/'.$class.'.php'))
	{
		require_once _LOCAL.$core.'/Core/'.$class.'.php';
		return true;
	}
	return false;
}

/** 页面错误自动捕获 */
#set_error_handler(
#		function($type, $message, $file, $line, $context)
#		{
#			Log::logf('file', array($file, $line), 'Event', Log::L_ERROR);
#			die(Log::logs('error', $message, 'Event', Log::L_ERROR));
#		}
#);

#register_shutdown_function($function);
