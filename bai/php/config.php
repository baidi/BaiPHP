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
 * <h3>全局配置</h3>
 * <p>
 * 框架运行时的各项配置。
 * </p>
 * @author 白晓阳
 */


global $config;

### 全局配置：系统，可覆盖默认配置
$config[Bai::BAI] = array(
	'Event'       => 'home',                ### 目标事项
	Work::LANG    => 'zh_CN',               ### 当前语言
	Work::LOG     => 'log'._DIR,            ### 日志路径
	Work::CACHE   => 'cache'._DIR,          ### 缓存路径
	'Upload'      => 'upload'._DIR,         ### 上传路径
	'Download'    => 'download'._DIR,       ### 下载路径
	'Branches'    => array(                 ### 扩展路径
		'/^[a-zA-Z0-9_\x7f-\xff]+Action$/' => Flow::ACTION._DIR,
		// '/^[a-zA-Z0-9_\x7f-\xff]+Work$/' => 'Work'._DIR,
	),
	'Error'    => '<hr/><p>一个不注意，就会出问题。回头多努力，做出好程序。</p><p>:-)%s</p>',
	'Notice'   => '<hr/><p>虽然没有大问题，但小地方要留意。</p><p>:-)%s</p>',
);

### 全局配置：流程
$config[Flow::FLOW] = array(
	Flow::TARGET      => array(
		'start'       => true,
		Flow::CONTROL => true,
		'close'       => false,
	),
	Flow::CONTROL     => array(
        'checkin'     => true,
        'filter'      => true,
        'limit'       => true,
        Flow::ACTION  => false,
        'error'       => false,
	),
	Flow::ACTION      => array(
        'check'       => Flow::PAGE,
        //'Cache'      => true,
        'data'        => true,
        'engage'      => true,
        Flow::PAGE    => false,
        'error'       => false,
	),
	Flow::PAGE        => array(
        'html'        => true,
        'format'      => false,
        'error'       => false,
	),
);

$config[Flow::CONTROL] = array(
	'filter' => array(
		'#127\.0\.0\..*#' => true,
	),
	'limit' => array(
		'limit' => 10,  ### 每秒10次
		'count' => 'limit_count',
		'time'  => 'limit_time',
	),
);

### 全局配置：数据工场
$config[Work::DATA] = array(
	'dsn'        => 'mysql:host=localhost;dbname=bai',
	'user'       => 'root',
	'password'   => '',
	'collation'  => 'utf8',
	'persistent' => false,
	'dbtype'     => 'mysql',
	'_dbhost'    => 'localhost',
	'_dbport'    => '',
	'_dbname'    => 'bai',
	'mysql'      => 'mysql:host=_dbhost;port=_dbport;dbname=_dbname',
	'pgsql'      => 'pgsql:host=_dbhost;port=_dbport;dbname=_dbname',
	'sqlite'     => 'sqlite:_dbhost',
);

### 全局配置：日志信息
$config[Work::LOG] = array(
	_DEFAULT          => array(
		'primary'     => Log::ALL | Log::DEBUG | Log::PERFORM,
		'branch'      => Work::LOG._DIR,
	),
	Work::LOG         => Log::ALL | Log::DEBUG | Log::PERFORM,
	Work::BAI         => array(
		'run'         => '->执行方法：%s',
		'entrust'     => '+>委托目标：%s',
		'instance'    => '对象未知：%s',
		'__get'       => '属性未知：%s',
		'__call'      => '方法未知：%s',
	),
	Work::TARGET      => array(
		'entrust'     => '>>目标启动：%s<<',
		'deliver'     => '>>目标交付：%s<<',
		'start'       => '..启动时间：%s',
		'close'       => '..目标用时：%0.3f秒',
	),
	Flow::CONTROL     => array(
		Flow::CONTROL => '--调度流程--',
		'client'      => '访问来源：%s(%s:%d)@%s [%s]',
		'server'      => '访问目标：%s(%s:%d)@%s:%s [%s]',
		'script'      => '实际响应：%s?%s',
		'filter'      => '访问被拒绝：可能是由于你试图攻击网站或者你的浏览器被劫持',
		'limit'       => '你的浏览器可能被劫持了，给它放一小会儿假吧……',
	),
	Flow::ACTION      => array(
		Flow::ACTION  => '--处理流程--',
	),
	Flow::PAGE        => array(
		Flow::PAGE    => '--页面流程--',
	),
	Work::CHECK       => array(
		Work::CHECK   => '--检验工场--',
		'checkItem'   => '输入项目检验：%s[%s]',
		'cipher'      => '安全暗号不符或已过期，请刷新页面后重试……',
		'risk'        => '输入项不能包含&lt; &gt; &amp; \' " ; % \ 等非法字符……',
		'required'    => '输入项不能为空……',
		'min'         => '输入项不能小于〖%d〗位……',
		'max'         => '输入项不能大于〖%d〗位……',
		'type'        => '输入项属性不符……',
		'__call'      => '输入项检验设置有误：%s',
	),
	Work::DATA        => array(
		Work::DATA    => '--数据工场--',
		'connect'     => '数据库连接失败……',
		'entrust'     => 'SQL语句执行出错……',
		'table'       => 'SQL数据表未指定……',
		'values'      => 'SQL字段值未指定……',
		'where'       => 'SQL条件未指定……',
		'count'       => 'SQL统计<%d>条数据',
		'read'        => 'SQL检索<%d>条数据',
		'create'      => 'SQL追加<%d>条数据',
		'update'      => 'SQL更新<%d>条数据',
		'delete'      => 'SQL删除<%d>条数据',
	),
	Work::CACHE       => array(
		Work::CACHE   => '--缓存工场--',
		'fetch'       => '提取缓存数据：%s',
		'push'        => '更新缓存数据：%s',
		'file'        => '缓存文件写入失败：%s',
	),
	Work::TEST        => array(
		Work::TEST    => '--测试工场--',
		'testee'      => '测试对象无效：%s',
		'tester'      => '测试文件无效：%s',
		'buildCase'   => '构建对象无效：%s',
		'||'          => '跳过测试：%s',
		'testCase'    => '执行测试场景：%s',
		'testResult'  => '测试场景：%s',
		'error'       => '执行测试出错：',
	),
);

### 测试工场
$config[Work::TEST] = array(
	'success'   => '过',
	'failure'   => '挂',
	'skip'      => '略',
	'error'     => '错',
);

$config[Work::CACHE] = array(
	Work::CACHE => false,
	'timeout'   => 600,
);

$config[Work::STYLE] = array(
	'inset'   => array(
		'css' => true,
		'js'  => true,
	),
	'link'    => array(
		'css' => '<link rel="stylesheet" type="text/css" href="%s"/>',
		'js'  => '<script type="text/javascript" src="%s"></script>',
	),
);

$config[Work::INPUT] = array(
	//_DEFAULT => 'text',
	_DEFAULT => '/(?P<item>[^\s=]+)(?:=(?P<value>"[^"]+"|\'[^\']+\'|[^\s=]+))?/',
	'type'  => array(
		_DEFAULT => '/type=([a-zA-Z0-9-_]+)/i',
		'float'  => 'number',
	),
	'value' => array(
		'/^checkbox|radio$/i' => 'checked="checked"',
		'/^[a-zA-Z0-9-_]+$/' => 'value="%s"',
	),
	'check' => array(
		'/^.*required.*$/i' => 'required="required"',
		'/^.*max=(\d+).*$/i' => 'maxlength="$1"',
		'/^(.+)$/' => 'data-check="$1"',
	),
	'hint'  => array(
		'required' => '非空',
		'max'      => '最大$1位',
		'min'      => '最小$1位',
		'number'   => '整数型',
		'float'    => '数值型',
		'letter'   => '英文字母',
		'char'     => '英文字母、数字、划线',
		'mp'       => '移动电话',
		'tel'      => '电话号码',
		'url'      => '合法英文网址',
		'email'    => '合法英文邮箱',
		'date'     => '合法日期（年月日无分割或以-、/、空格分割）',
		'time'     => '合法时间（时分秒无分割或以:、-、空格分割）',
	),
	'format' => '<input id="{$event}_{$item}" name="{$event}[{$item}]" type="{$type}" {$value} {$check} placeholder="{$hint}"/>',
);

$config['Lang'] = array(
	_DEFAULT => Lang::ZH,
);

### 输入项检验条目
// $config['Input']['Items'] = array(
// 		'/required/i',
// 		'/\smax=(\d+)/i',
// 		'/\smin=(\d+)/i',
// 		'/\stype=number/i',
// 		'/\stype=float/i',
// 		'/\stype=letter/i',
// 		'/\stype=char/i',
// 		'/\stype=mp/i',
// 		'/\stype=fax/i',
// 		'/\stype=url/i',
// 		'/\stype=email/i',
// 		'/\stype=date/i',
// 		'/\stype=time/i',
// 		'/\s\w+=([^\s"]+)/',
// );

// ### 提示信息头
// $config['Input']['Gap'] = '，';

// ### 输入项HTML片段
// $config['Input']['Htmls'] = array(
// 		' required="required"',
// 		' maxlength="$1"',
// );

// $config['Input']['Template'] = '<input id="" name="" type="" class="" data-check="" title="" value="" />';

// ### 类型检验：正则
// $config['Input']['Type'] = array(
// 		### 数字
// 		'number' => '/^[1-9]\d*$/',
// 		### 数值
// 		'float'  => '/^[+-]?\d+(?:\.\d+)?$/',
// 		### 英文字母
// 		'letter' => '/^[a-zA-Z]+$/',
// 		### 英文字母数字划线
// 		'char'   => '/^[a-zA-Z0-9_-]+$/',
// 		### 移动电话
// 		'mp'     => '/^(?:\+86)?1[358][0-9]{9}$/',
// 		### 固话传真
// 		'fax'    => '/^0[0-9]{2,3}-[1-9][0-9]{6,7}$/',
// 		### 网址
// 		'url'    => '/^(?:https?:\/\/)?[a-zA-Z0-9-_.\/]+(?:\?.+)?$/',
// 		### 邮箱
// 		'email'  => '/^[a-zA-Z0-9-_.]+@[a-zA-Z0-9-_.]+$/',
// 		### 日期
// 		'date'   => '/^[0-9]{4}[-.\/]?(?:0?[1-9]|1[0-2])[-.\/]?(?:0?[1-9]|[12][0-9]|3[01])$/',
// 		### 时间
// 		'time'   => '/^(?:0?[0-9]|1[0-9]|2[0-3])[:-]?(?:0?[0-9]|[1-5][0-9])[:-]?(?:0?[0-9]|[1-5][0-9])$/',

// );
