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
	Work::EVENT   => 'home',				### 目标事项
	'Branches'    => array(				 ### 扩展路径
		'/^[a-zA-Z0-9_\x7f-\xff]+Action$/' => Flow::ACTION._DIR,
		# '/^[a-zA-Z0-9_\x7f-\xff]+Work$/' => Work::WORK._DIR,
	),
);

### 全局配置：流程
$config[Flow::FLOW] = array(
	Flow::TARGET      => array(
		Flow::CONTROL => false,
		'error'       => false,
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

### 全局配置：目标
$config[Work::TARGET] = array(
	_DEFAULT => array(
		'filters' => array(
			'#<script.*>.*</script\s*>#i' => '',
			'#javascript\s*:#i' => '',
		),
	),
);

### 全局配置：调度流程
$config[Flow::CONTROL] = array(
	'filter' => array(
		'#127\.0\.0\..*#' => true,
	),
	'limit' => array(
		### 最大访问频度：每秒10次
		'limit' => 10,
		'count' => 'limit_count',
		'time'  => 'limit_time',
	),
);

### 全局配置：页面流程
$config[Flow::PAGE] = array(
	_DEFAULT      => array(
		'layout'  => '_page.php',
		'css'     => array(
			'bai.css',
		),
		'js'      => array(
			'sizzle.js',
			'bai.js',
		),
		'formats'       => array(
			'$width$' => '990px',
			'$font$'  => '14px/20px "verdana", "helvetica", "arial", sans-serif',
			#'$color$' => '#000000',
			#'$background$' => '#ffffff',
			'$acolor$' => '#009f3c',
			//'$bgcolor$' => '#d0d0d0',
			'$linecolor$' => '#99cc99',
			'$areacolor$' => '#f0f9f0',
			'$shadowcolor$' => '#d0f9d0',
			'$errorcolor$' => '#ff0000',
			'$noticecolor$' => '#99cc99',
			'$lockedcolor$' => '#cccccc',
			//'$message$' => json_encode($config[Work::LOG][Work::CHECK]),
			//'$type$'    => json_encode($config['Input']['Type']),
		),
		'trims' => array(
			### HTML注释
			'#<!--.*-->#Us'     => '',
			### 多行注释
			'#/\*.*\*/#Us'      => '',
			### 单行注释
			'#\s*(?<!:)//.*$#m' => '',
			### 行空白
			'#^\s+|\s+$#m'      => '',
			### 连续空白
			'#\s{2,}#'          => ' ',
			### 空行
			'#\n[\r\n]+#'       => '\n',
		),
	),
);

### 全局配置：数据工场
$config[Work::DATA] = array(
	_DEFAULT     => array(
		'dsn'		=> 'mysql:host=localhost;dbname=bai',
		'user'       => 'root',
		'password'   => '',
		'charset'    => 'utf8',
		'lasting'    => false,
	),
	'dbtype'     => 'mysql',
	'_dbhost'    => 'localhost',
	'_dbport'    => '',
	'_dbname'    => 'bai',
	'mysql'      => 'mysql:host=_dbhost;port=_dbport;dbname=_dbname',
	'pgsql'      => 'pgsql:host=_dbhost;port=_dbport;dbname=_dbname',
	'sqlite'     => 'sqlite:_dbhost',
);

### 全局配置：日志工场
$config[Work::LOG] = array(
	_DEFAULT          => array(
		### 日志级别
		'level'       => Log::ALL | Log::DEBUG | Log::PERFORM,
		### 日志目录
		'root'        => Work::LOG._DIR,
		### 日志结束符
		'ending'      => "\r\n",
	),
	Work::BAI         => array(
		'run'         => '->执行方法：%s',
		'entrust'     => '+>委托目标：%s',
		'build'       => '对象未知：%s',
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
		'item'   => '输入项目检验：%s[%s]',
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
	Work::STYLE       => array(
		Work::STYLE   => '--样式工场--',
		'inset'       => '内嵌样式：%s',
		'link'        => '外链样式：%s',
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

### 全局配置：检验工场
$config[Work::CHECK] = array(
	_DEFAULT      => array(
		### 字符编码
		'charset'     => 'utf-8',
		### 参数分割符
		'delimiter'   => ',',
		### 检验模式
		'mode'        => '/(?<check>[^\s=]+)(?:=(?<params>[^\s]+))?/',
		### 类型模式
		'types'       => array(
			### 风险字符
			'risk'    => '/[<>&%\'\\\]+/',
			### 整数
			'integer' => '/^[1-9]\d*$/',
			### 小数
			'float'   => '/^[+-]?\d+(?:\.\d+)?$/',
			### 英文字母
			'letter'  => '/^[a-zA-Z]+$/',
			### 英文字符
			'char'    => '/^[a-zA-Z0-9_-]+$/',
			### 移动电话
			'mobile'  => '/^(?:\+86)?1[358][0-9]{9}$/',
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
		),
	),
);

### 全局配置：缓存工场
$config[Work::CACHE] = array(
	_DEFAULT => array(
		### 是否开启
		'valid'     => false,
		### 缓存时间：秒
		'timeout'   => 600,
		### 缓存目录
		'root'      => Work::CACHE._DIR,
		### 缓存项目
		'items'     => array(
			'home'  => true,
		),
	),
);

### 全局配置：样式工场
$config[Work::STYLE] = array(
	_DEFAULT  => array(
		### 默认图片（原图无效时使用）
		'img' => '_blank.png',
		### 可内嵌类型
		'insets'   => array(
			'css' => '<style type="text/css">%s</style>',
			'js'  => '<script type="text/javascript">%s</script>',
		),
		### 外链模板
		'links'    => array(
			'css' => '<link rel="stylesheet" type="text/css" href="%s"/>',
			'js'  => '<script type="text/javascript" src="%s"></script>',
		),
	),
);

### 全局配置：测试工场
$config[Work::TEST] = array(
	_DEFAULT => array(
		'success'   => '过',
		'failure'   => '挂',
		'skip'      => '略',
		'error'     => '错',
	),
);

### 全局配置：测试工场
$config[Work::TEMPLATE] = array(
	_DEFAULT        => array(
	    'param'     => '#\{\$(?<name>[a-zA-Z0-9_\x7f-\xff]+)\s*(?:(?<handle>[?!])\s*(?<first>[^|}]+)\s*(?:\|\s*(?<last>[^|}]+)\s*)?)?\}#',
	    'handler'   => array(
    	    '?'     => 'choose',
    	    '!'     => 'loop',
	    ),
	    'peg'       => '$',
	    'looper'    => array('$value', '$key'),
		'templates' => array(
	        'div'   => '<div {$id ? id="$id"} {$class ? class="$class"} {$style ? style="$style"}>{$content}</div>',
	    ),
	),
);

$config[Work::INPUT] = array(
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
	_DEFAULT => array(
		'primary' => Lang::ZH,
	),
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
