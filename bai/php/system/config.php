<?php
/**
 * <b>BaiPHP（简单PHP）开发框架</b>
 * @author		白晓阳
 * @copyright	Copyright (c) 2011 - 2012, 白晓阳
 * @link		http://dacbe.com
 * @version     V1.0.0 2012/03/31 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 */

/**
 * <b>BaiPHP（简单PHP）开发框架</b>
 * <b>系统配置</b>
 * <p>！！！系统运行时的重要配置，慎重修改！！！</p>
 *
 * @author 白晓阳
 */

/** BaiPHP扩展路径（可自定义） */
define('_EXTEND', _LOCAL.'bai/php/extend/');

/** 日志路径（可自定义） */
define('_LOG', _LOCAL.'log/');

/** 缓存路径（可自定义） */
#define('_CACHE', _LOCAL.'cache/');

/** 测试路径 */
define('_TEST', _LOCAL.'dev/test/');


/** 默认分流路径（可自定义） */
define('_ISSUE', _WEB.'web/');

/** 处理路径（可自定义） */
define('_ACTION', _ISSUE.'action/');

/** 页面路径（可自定义） */
define('_PAGE', _ISSUE.'page/');

/** 样式路径（可自定义） */
define('_STYLE', _ISSUE.'style/');

/**
 * 调试模式开启标识
 * ！！！正常运行时务必设为false（关闭）！！！
 */
define('_DEBUG', true);

/**
 * 安全暗号开启标识
 * ！！！建议设为cipher（开启）！！！
 */
define('_CIPHER', false);

/**
 * 默认日志级别
 * ！！！正常运行时建议设为4或2！！！
 */
define('_LOG_LEVEL', 64);

/**
 * 默认缓存过期时间：秒（不开启缓存无效）
 * ！！！建议不小于300（5分钟）！！！
 */
define('_CACHE_TIMEOUT', 300);

/** 默认项目 */
define('_DEFAULT', '_');

/**
 * 默认错误报告级别
 * ！！！正常运行时建议设为E_WARING或E_ERROR！！！
 */
error_reporting(E_ALL | E_STRICT);


/**
 * <b>BaiPHP全局配置</b>
 * 系统设置项一般首字母大写
 * 用户设置项一般首字母小写
 */
$config = array();

### 数据连接设置
if ($_SERVER['SERVER_NAME'] == 'localhost')
{
	$config['DB'] = array(
			'dsn'      => 'mysql:dbname=baiphp;host=localhost',
			'user'     => 'root',
			'password' => '',
	);
}
else
{
	$config['DB'] = array(
			'dsn'      => 'mysql:dbname=baiphp;host=localhost',
			'user'     => 'root',
			'password' => '123654',
	);
}

### 流程传递模式
$config['Flow'] = array(
		'#^Event$#' => 'Shunt',  ### 事件请求分流
		'#Issue$#'  => 'Action', ### 分流请求处理
		'#Action$#' => 'Page',   ### 处理请求页面
		'#Page$#'   => null,     ### 页面请求文件
);

### 页面重定向设置
$config['Page'] = array(
		_DEFAULT => 'homePage',
);

### 页面布局设置
$config['Layout'] = array(
		_DEFAULT => 'layout/layout.php',
);

### 扩展工场设置
### 根据需要导入扩展工场文件
$config['Extend'] = array(
		'baiExtend.php',
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
$config['Log']['Event'] = array(
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
?>
