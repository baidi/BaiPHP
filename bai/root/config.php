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
 * <h3>Global config</h3>
 * <p>
 * 框架运行时的各项配置。
 * </p>
 *
 * @copyright Copyright (C) 2011-2014 Xiao Yang, Bai
 * @author Xiao Yang, Bai
 */
global $config;

### 全局配置：流程
### 对象名 => (键名 => 键值, 键名 => 键值, ...)
### 键名：当前对象的方法名（可以无参调用），或要委托的其他对象名。
### 键值：true：继续执行；false：结束执行；Bai::NIL:跳过；字符串：出错时跳转到该项，否则正常执行。
$config[Bai::PROCESS] = array(
	Bai::EVENT => array(
		Process::CONTROL => false,
		'error' => false
	),
	Process::CONTROL => array(
		'checkin' => true,
		'filter' => true,
		'limit' => true,
		Process::ACTION => false,
		'error' => false
	),
	Process::ACTION => array(
		'prepare' => true,
		'check' => Process::PAGE,
		#'Cache' => true,
		'data' => true,
		'engage' => true,
		Process::PAGE => false,
		'error' => false
	),
	Process::PAGE => array(
		'setup' => true,
		'format' => false,
		'error' => false
	)
);

### global config: Event
$config[Bai::EVENT] = array(
	### 数据过滤
	'filters' => array(
		'#<script.*>.*</script\s*>#Ui' => '',
		'#javascript\s*:#i' => ''
	)
);

### 全局配置：调度流程
$config[Process::CONTROL] = array(
	### 访问地址过滤
	'filters' => array(
		'#127\.0\.0\.1#' => true
	),
	### 每秒最大访问频度
	'visitLimit' => 10
);

### 全局配置：页面流程
$config[Process::PAGE] = array(
	### 页面样式
	'css' => array(
		'bai.css',
		'bootstrap-theme.css',
		'bootstrap.css'
	),
	### 页面脚本
	'js' => array(
		'zepto.js',
		'spine.js',
		'bootstrap.js',
	),
	### 页面修整
	'trims' => array(
		### HTML注释
		'#<!--.*-->#Us' => '',
		### 多行注释
		'#/\*.*\*/#Us' => '',
		### 单行注释
		'#\s*(?<!:)//.*$#m' => '',
		### 行空白
		'#^\s+|\s+$#m' => '',
		### 连续空白
		'#\s{2,}#' => ' ',
		### 空行
		'#\n[\r\n]+#' => '\n'
	)
);

### 全局配置：数据工场
$config['Data'] = array(
	'dbtype' => 'mysql',
	'dbhost' => 'localhost',
	'dbport' => '',
	'dbname' => 'bai',
	'user' => 'root',
	'password' => '',
	'charset' => 'utf8',
	'templates' => array(
		'mysql' => 'mysql:host={$dbhost};{$dbport ? port=$dbport;}dbname={$dbname}',
		'pgsql' => 'pgsql:host={$dbhost};{$dbport ? port=$dbport;}dbname={$dbname}',
		'sqlite' => 'sqlite:{$dbhost}'
	)
);

### 全局配置：日志工场
$config['Log'] = array(
	'place' => $config[_APP][Bai::RUNTIME] . 'Log' . _DIR,
	'level' => Log::ALL | Log::DEBUG | Log::PERFORMANCE,
	'ending' => "\n",
	'names' => array(
		LOG::FATAL => ' [致命] ',
		LOG::ERROR => ' [错误] ',
		LOG::EXCEPTION => ' [异常] ',
		LOG::WARING => ' [警告] ',
		LOG::NOTICE => ' [提示] ',
		LOG::INFO => ' [信息] ',
		LOG::UNKNOWN => ' [未知] ',
		LOG::DEBUG => ' [调试] ',
		LOG::PERFORMANCE => ' [性能] '
	),
	'dic' => array(
		Bai::BAI => array(
			'run' => '-> run method: %s->%s',
			'entrust' => '+> entrust event to: %s',
			'build' => 'build unknown class: %s',
			'__get' => 'read unknown property: %s->%s',
			'__call' => 'call unknown method: %s->%s',
		),
		Bai::EVENT => array(
			'entrust' => '>> entrust event: %s <<',
			'deliver' => '>> deliver event: %s <<',
			'start' => '.. start time: %s',
			'close' => '.. process time: %0.3f(s)'
		),
		Bai::WORK => array(
			'dependences' => "extension %s isn't installed or loaded",
			'place' => "work place %s can't be set up",
		),
		Process::CONTROL => array(
			'client' => 'client: %s(%s:%d)@%s [%s]',
			'server' => 'server: %s(%s:%d)@%s:%s [%s]',
			'script' => 'script: %s?%s',
			'filter' => 'request is denied',
			'limit' => 'request is restricted in %d seconds'
		),
		Process::PAGE => array(
		),
		'Check' => array(
			'config' => 'parsing mode not set',
			'checkItem' => 'check item: %s [%s]',
			'cipher' => '安全暗号不符或已过期，请刷新页面后重试……',
			'risk' => 'this item can\'t contain &lt; &gt; &amp; \' " ; % \\',
			'required' => 'this item is required',
			'min' => 'the min size is %d',
			'max' => 'the max size is %d',
			'range' => 'this item is between %d and %d',
			'enum' => 'please choose correct item',
			'set' => 'please choose correct item',
			'type' => 'this item doesn\'t match the type',
			'__call' => 'item check config incorrect'
		),
		'Data' => array(
			'config' => 'DB config incorrect',
			'connect' => 'DB connection fail',
			'entrust' => 'SQL语句执行出错……',
			'table' => 'SQL数据表未指定……',
			'values' => 'SQL字段值未指定……',
			'where' => 'SQL条件未指定……',
			'sql' => '执行SQL语句：%s',
			'count' => 'SQL统计<%d>条数据',
			'read' => 'SQL检索<%d>条数据',
			'create' => 'SQL追加<%d>条数据',
			'update' => 'SQL更新<%d>条数据',
			'delete' => 'SQL删除<%d>条数据',
			'show' => 'SQL描述<%s>数据表'
		),
		'Record' => array(
			'table' => '记录工场：表名未设置……',
			'show' => '记录工场：字段配置读取失败……',
			'id' => '记录工场：记录ID未设置',
			'refresh' => '记录工场：记录刷新成功',
			'save' => '记录工场：记录保存成功',
			'delete' => '记录工场：记录删除成功'
		),
		'Cache' => array(
			'read' => 'read %s from cache',
			'write' => 'write %s to cache',
			'file' => 'write %s to file'
		),
		'Style' => array(
			'inset' => '内嵌样式：%s',
			'link' => '外链样式：%s'
		),
		'Test' => array(
			'testee' => '测试对象或方法无效：%s',
			'source' => '测试文件无效：%s',
			'case' => '测试场景无效：%s',
			'refer' => '引用对象无效：%s',
			'test' => '执行测试：%s',
			'result' => '测试结果：%s',
			'output' => '测试中产生输出：%s',
			'error' => '执行测试出错：%s',
			'testResult' => '实际：%s <预期：%s>'
		)
	)
);

### 全局配置：检验工场
$config['Check'] = array(
	### 字符编码
	'charset' => 'utf-8',
	### 参数分割符
	'gap' => ',',
	### 检验模式
	'mode' => '/([^\\s=]+)(?:=([^\\s]+))?/i',
	### 类型模式
	'types' => array(
		### 风险字符
		'risk' => "/[<>&%'\\\\]+/",
		### 整数
		'integer' => '/^[+-]?[1-9]\\d*$/',
		### 小数
		'float' => '/^[+-]?\\d+(?:\\.\\d+)?$/',
		### 英文字母
		'letter' => '/^[a-zA-Z]+$/',
		### 英文字符
		'char' => '/^[a-zA-Z0-9_-]+$/',
		### 移动电话
		'mobile' => '/^(?:\\+86)?1[358][0-9]{9}$/',
		### 固话传真
		'fax' => '/^0[0-9]{2,3}-[1-9][0-9]{6,7}$/',
		### 网址
		'url' => '/^(?:https?:\\/\\/)?[a-zA-Z0-9-_.\\/]+(?:\\?.+)?$/',
		### 邮箱
		'email' => '/^[a-zA-Z0-9-_.]+@[a-zA-Z0-9-_.]+$/',
		### 日期
		'date' => '/^[0-9]{4}[-.\\/]?(?:0?[1-9]|1[0-2])[-.\\/]?(?:0?[1-9]|[12][0-9]|3[01])$/',
		### 时间
		'time' => '/^(?:0?[0-9]|1[0-9]|2[0-3])[:-]?(?:0?[0-9]|[1-5][0-9])[:-]?(?:0?[0-9]|[1-5][0-9])$/'
	),
	'dependences' => array(
		'mbstring'
	),
	Bai::EVENT => array()
);

### 全局配置：缓存工场
$config['Cache'] = array(
	### 是否开启
	'valid' => false,
	### 缓存时间：秒
	'timeout' => 600,
	### 缓存目录
	'place' => $config[_DEF][Bai::RUNTIME] . 'Cache' . _DIR,
	### 依赖扩展
	'dependences' => array(
		'apc'
	)
);

### 全局配置：样式工场
$config['Style'] = array(
	### 默认图片（原图无效时使用）
	'img' => '_blank.png',
	### 可内嵌类型
	'insets' => array(
		'css' => '<style type="text/css">%s</style>',
		'js' => '<script type="text/javascript">%s</script>',
		'Template' => '%s'
	),
	### 外链模板
	'links' => array(
		'css' => '<link rel="stylesheet" type="text/css" href="%s"/>',
		'js' => '<script type="text/javascript" src="%s"></script>'
	)
);

$config['CSS'] = array(
	'$min$' => '480px',
	'$max$' => '980px',
	'$font$' => '14px/20px "verdana", "helvetica", "arial", sans-serif',
	'$color$' => '#333f33',
	'$background$' => 'transparent',
	'$lightanchor$' => '#009f3c',
	'$darkanchor$' => '#006000',
	'$lightline$' => '#cceccc',
	'$darkline$' => '#99cc99',
	'$lightarea$' => '#f9fff9',
	'$darkarea$' => '#f6fcf6',
	'$shadowcolor$' => '#d0f9d0',
	'$errorcolor$' => '#ff0000',
	'$noticecolor$' => '#99cc99',
	'$lockedcolor$' => '#cccccc'
);

$config['JS'] = array(
	'$config$' => json_encode(array(
		'alt' => array(
			'class' => 'className',
			'html' => 'innerHTML',
			'text' => 'innerText'
		),
		'check' => $config['Check'],
		'ajax' => array(
			'timeout' => 5000
		),
		'bubble' => array(
			'shade' => '.shade',
			'title' => '.bubble .title',
			'content' => '.bubble .content',
			'bubbled' => '.bubbled'
		),
		'message' => array(
			'check' => $config['Log']['dic']['Check'],
			'ajax' => array(
				'fail' => '内容加载失败……'
			),
			'bubble' => array(
				'title' => '提示',
				'content' => '没有提示内容……',
				'load' => '请稍候，正在加载……',
				'fail' => '内容加载失败……',
				'' => '加载完成，但是没有内容……',
				'success' => '操作成功',
				'complete' => '操作完成',
				'failure' => '操作失败'
			)
		)
	))
);

### 全局配置：测试工场
$config['Test'] = array(
	'success' => '过',
	'failure' => '挂',
	'skip' => '略',
	'error' => '错',
	'dependences' => array(
		'xdebug'
	)
);

### 全局配置：模板工场
$config['Template'] = array(
	'peg' => '$',
	'mode' => '#\{\$(?<' . Template::ITEM . '>[a-zA-Z0-9_\x7f-\xff]+)\s*(?:(?<' . Template::HANDLE . '>[?!.])\s*(?:(?<' .
			 Template::PRIMARY . '>[^|}]+)\s*(?:\|\s*(?<' . Template::SECORDARY . '>[^|}]+)\s*)?)?)?\}#',
	'handler' => array(
		null => 'param',
		'?' => 'choose',
		'!' => 'loop',
		'.' => 'call'
	),
	'looper' => array(
		'$value',
		'$key'
	),
	'dic' => array(
		'div' => '<div {$id ? id="$id"} {$class ? class="$class"}>{$content}</div>',
		'p' => '<p {$class ? class="$class"}>{$content}</p>'
	)
);

$config['Input'] = array(
	'templates' => array(
		'text' => '<input id="{$item}" name="{$item}" {$type ? type="$type" | type="text" }{$value ? $value }{$class ? class="$class" }{$check ? $check }{$hint ? placeholder="$hint" }/>',
		'hidden' => '<input id="{$item}" name="{$item}" type="hidden" {$value ? $value }{$class ? class="$class" }{$check ? $check }/>'
	),
	'values' => array(
		null => 'value="%s"'
	),
	'types' => array(
		null => 'text',
		'float' => 'number'
	),
	'checks' => array(
		_DEF => 'data-check="%s"',
		'required' => 'required="required"',
		'max' => 'maxlength="%d"'
	),
	'hints' => array(
		'required' => '非空',
		'max' => '最大%d位',
		'min' => '最小%d位',
		'number' => '整数型',
		'float' => '数值型',
		'letter' => '英文字母',
		'char' => '英文字母、数字、划线',
		'mp' => '移动电话',
		'tel' => '电话号码',
		'url' => '合法英文网址',
		'email' => '合法英文邮箱',
		'date' => '合法日期（年月日无分割或以-、/、空格分割）',
		'time' => '合法时间（时分秒无分割或以:、-、空格分割）'
	)
);

$config['Lang'] = array(
);

$config['Record'] = array(
	'mode' => '#^(?<' . Record::ITEM . '>[A-Za-z_]+)(?:\((?<' . Record::PARAM . '>[^()]+)\))?#',
	'types' => array(
		### 整数
		'TINYINT' => 'type=integer range=-128,127',
		'SMALLINT' => 'type=integer range=-32768,32767',
		'MEDIUMINT' => 'type=integer range=-8388608,8388607',
		'INT' => 'type=integer range=-2147483648,2147483647',
		'INTEGER' => 'type=integer range=-2147483648,2147483647',
		'BIGINT' => 'type=integer',
		### 非负整数
		'TINYINT+' => 'type=integer range=0,255',
		'SMALLINT+' => 'type=integer range=0,65535',
		'MEDIUMINT+' => 'type=integer range=0,16777215',
		'INT+' => 'type=integer range=0,4294967295',
		'INTEGER+' => 'type=integer range=0,4294967295',
		'BIGINT+' => 'type=integer range=0,',
		### 小数
		'FLOAT' => 'type=float',
		'REAL' => 'type=float',
		'DOUBLE' => 'type=float',
		'DECIMAL' => 'type=float',
		'DEC' => 'type=float',
		'NUMERIC' => 'type=float',
		'FIXED' => 'type=float',
		### 非负小数
		'FLOAT+' => 'type=float range=0,',
		'REAL+' => 'type=float range=0,',
		'DOUBLE+' => 'type=float range=0,',
		'DECIMAL+' => 'type=float range=0,',
		'DEC+' => 'type=float range=0,',
		'NUMERIC+' => 'type=float range=0,',
		'FIXED+' => 'type=float range=0,',
		### 日期时间
		'DATE' => 'type=date',
		'DATETIME' => 'type=datetime',
		'TIMESTAMP' => 'type=datetime',
		'TIME' => 'type=time',
		'YEAR' => 'type=integer max=4 range=1901,2155',
		### 真假
		'BOOL' => 'type=checkbox',
		'BOOLEAN' => 'type=checkbox',
		### 文本
		'ENUM' => 'type=select',
		'SET' => 'type=select'
	)
);
