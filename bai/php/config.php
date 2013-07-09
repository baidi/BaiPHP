<?php
/**
 * <h2>化简PHP（BaiPHP）开发框架</h2>
 * @link      http://www.baiphp.net
 * @copyright Copyright (c) 2011 - 2012, 白晓阳
 * @author    白晓阳
 * @version   1.0.0 2012/03/31 首版
 *            2.0.0 2012/07/01 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 * <p>欢迎提供捐助。任何捐助者自动获得仅限于捐助者自身的商业使用（不包括再发行和再授权）授权。</p>
 */

/**
 * <h2>化简PHP（BaiPHP）开发框架</h2>
 * <h3>全局配置</h3>
 * <p>
 * 框架运行时的各项配置。
 * </p>
 * @author 白晓阳
 */
global $config;

### 全局配置：流程
### 对象名 => (键名 => 键值, 键名 => 键值, ...)
### 键名：当前对象的方法名（可以无参调用），或要委托的其他对象名。
### 键值：true：继续执行；false：结束执行；Bai::NIL:跳过；字符串：出错时跳转到该项，否则正常执行。
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
		#'Cache'      => true,
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
$config[Bai::TARGET] = array(
	### 当前服务
	'service' => 'service'._DIR,
	### 当前事项
	'event'   => 'home',
	### 数据过滤
	'filters' => array(
		'#<script.*>.*</script\s*>#Ui' => '',
		'#javascript\s*:#i' => '',
	),
);

### 全局配置：调度流程
$config[Flow::CONTROL] = array(
	### 访问地址过滤
	'filters'    => array(
		'#127\.0\.0\.1#' => true,
	),
	### 每秒最大访问频度
	'visitLimit' => 10,
	### 访问次数键值（用于$_SESSION）
	'visitCount' => '_visit_count',
	### 访问时间键值（用于$_SESSION）
	'visitTime'  => '_visit_time',
);

### 全局配置：数据工场
$config[Work::DATA] = array(
	'dbtype'   => 'mysql',
	'dbhost'   => 'localhost',
	'dbport'   => '',
	'dbname'   => 'bai',
	'user'     => 'root',
	'password' => '',
	'charset'  => 'utf8',
	'template' => array(
		'mysql'    => 'mysql:host={$dbhost};{$dbport ? port=$dbport;}dbname={$dbname}',
		'pgsql'    => 'pgsql:host={$dbhost};{$dbport ? port=$dbport;}dbname={$dbname}',
		'sqlite'   => 'sqlite:{$dbhost}',
	),
);

### 全局配置：日志工场
$config[LOG::LOG] = array(
	'dir'         => $config[_DEF][LOG::RUNTIME].LOG::LOG._DIR,
	'level'       => Log::ALL | Log::DEBUG | Log::PERFORM,
	'ending'      => "\n",
	'names'       => array(
		LOG::FATAL     => ' [致命] ',
		LOG::ERROR     => ' [错误] ',
		LOG::EXCEPTION => ' [异常] ',
		LOG::WARING    => ' [警告] ',
		LOG::NOTICE    => ' [提示] ',
		LOG::INFO      => ' [信息] ',
		LOG::UNKNOWN   => ' [未知] ',
		LOG::DEBUG     => ' [调试] ',
		LOG::PERFORM   => ' [性能] ',
	),
	'dic' => array(
		Work::BAI         => array(
			'run'         => '->执行方法：%s',
			'entrust'     => '+>委托目标：%s',
			'build'       => '对象未知：%s',
			'__get'       => '属性未知：%s',
			'__call'      => '方法未知：%s',
			'__construct' => '%s扩展尚未安装或开启',
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
			Work::CHECK   => '--检验工场（Check）--',
			'config'      => '检验工场（Check）设置有误……',
			'checks'      => '检验输入项目：%s[%s]',
			'cipher'      => '安全暗号不符或已过期，请刷新页面后重试……',
			'risk'        => '输入项不能包含&lt; &gt; &amp; \' " ; % \\ 等非法字符……',
			'required'    => '输入项不能为空……',
			'min'         => '输入项不能小于〖%d〗位……',
			'max'         => '输入项不能大于〖%d〗位……',
			'range'       => '请输入%d到%d间的数值……',
			'enum'        => '请选择正确的选项……',
			'set'         => '请选择正确的选项……',
			'type'        => '请输入正确的内容……',
			'__call'      => '输入项检验设置有误：%s',
		),
		Work::DATA        => array(
			Work::DATA    => '--数据工场--',
			'config'      => '数据工场（Data）设置有误……',
			'connect'     => '数据库连接失败……',
			'entrust'     => 'SQL语句执行出错……',
			'table'       => 'SQL数据表未指定……',
			'values'      => 'SQL字段值未指定……',
			'where'       => 'SQL条件未指定……',
			'sql'         => '执行SQL语句：%s',
			'count'       => 'SQL统计<%d>条数据',
			'read'        => 'SQL检索<%d>条数据',
			'create'      => 'SQL追加<%d>条数据',
			'update'      => 'SQL更新<%d>条数据',
			'delete'      => 'SQL删除<%d>条数据',
			'show'        => 'SQL描述<%s>数据表',
		),
		Work::RECORD      => array(
			'show'        => '',
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
			'source'      => '测试文件无效：%s',
			'case'        => '测试场景无效：%s',
			'test'        => '执行测试：%s',
			'result'      => '测试结果：%s',
			'error'       => '执行测试出错：%s',
			'testResult'  => '实际：%s <预期：%s>',
		),
	),
);

### 全局配置：检验工场
$config[Work::CHECK] = array(
	### 字符编码
	'charset'     => 'utf-8',
	### 参数分割符
	'gap'   => ',',
	### 检验模式
	'mode'        => '/(?<'.Check::ITEM.'>[^\s=]+)(?:=(?<'.Check::PARAM.'>[^\s]+))?/',
	### 类型模式
	'types'       => array(
		### 风险字符
		'risk'    => '/[<>&%\'\\\\]+/',
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
	'extensions' => array(
		'mbstring',
	),
);

### 全局配置：页面流程
$config[Flow::PAGE] = array(
	### 页面布局
	'layout'  => '_page.php',
	### 页面样式
	'css'     => array(
		'bai.css',
	),
	### 页面脚本
	'js'      => array(
		'bai.js',
	),
	### 页面版式
	'formats' => array(
		'$min$'         => '480px',
		'$max$'         => '980px',
		'$font$'        => '14px/20px "verdana", "helvetica", "arial", sans-serif',
		'$color$'       => '#333f33',
		'$background$'  => 'transparent',
		'$lightanchor$' => '#009f3c',
		'$darkanchor$'  => '#008000',
		'$lightline$'   => '#cceccc',
		'$darkline$'    => '#99cc99',
		'$lightarea$'   => '#f0fff0',
		'$darkarea$'    => '#f6fcf6',
		'$shadowcolor$' => '#d0f9d0',
		'$errorcolor$'  => '#ff0000',
		'$noticecolor$' => '#99cc99',
		'$lockedcolor$' => '#cccccc',
		'$message$'     => json_encode($config[Work::LOG]['store']),
		'$type$'        => json_encode($config[Work::CHECK]['types']),
	),
	### 页面修整
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
);

### 全局配置：缓存工场
$config[Work::CACHE] = array(
	### 是否开启
	'valid'     => false,
	### 缓存时间：秒
	'timeout'   => 600,
	### 缓存目录
	'dir'       => $config[_DEF][LOG::RUNTIME].Work::CACHE._DIR,
	### 依赖扩展
	'extensions' => array(
		'apc',
	),
);

### 全局配置：样式工场
$config[Work::STYLE] = array(
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
);

### 全局配置：测试工场
$config[Work::TEST] = array(
	'success'    => '过',
	'failure'    => '挂',
	'skip'       => '略',
	'error'      => '错',
	'extensions' => array(
		'xdebug',
	),
);

### 全局配置：模板工场
$config[Work::TEMPLATE] = array(
	'peg'       => '$',
	'mode'      => '#\{\$(?<' . Template::ITEM . '>[a-zA-Z0-9_\x7f-\xff]+)\s*(?:(?<' . Template::HANDLE .
			 '>[?!])\s*(?:(?<' . Template::PRIMARY . '>[^|}]+)\s*(?:\|\s*(?<' . Template::SECORDARY .
			 '>[^|}]+)\s*)?)?)?\}#',
	'handler'   => array(
		null    => 'param',
		'?'     => 'choose',
		'!'     => 'loop',
	),
	'looper'    => array('$value', '$key'),
	'dic'       => array(
		'div'   => '<div {$id ? id="$id"} {$class ? class="$class"}>{$content}</div>',
		'p'     => '<p {$class ? class="$class"}>{$content}</p>',
	),
);

$config[Work::INPUT] = array(
	'dic' => array(
		'text' => '<input id="{$item}" name="{$item}" {$type ? type="$type" | type="text" }{$value ? $value }{$class ? class="$class" }{$check ? $check }{$hint ? placeholder="$hint" }/>',
	),
	'values' => array(
		null => 'value="%s"',
	),
	'types'  => array(
		null     => 'text',
		'float'  => 'number',
	),
	'checks' => array(
		_DEF => 'data-check="%s"',
		'required' => 'required="required"',
		'max' => 'maxlength="%d"',
	),
	'hints'  => array(
		'required' => '非空',
		'max'      => '最大%d位',
		'min'      => '最小%d位',
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
);

$config['Lang'] = array(
	'primary' => Lang::ZH,
);

$config['Record'] = array(
	'types'   => array(
		### 整数
		'TINYINT'    => 'type=number range=-128,127',
		'SMALLINT'   => 'type=number range=-32768,32767',
		'MEDIUMINT'  => 'type=number range=-8388608,8388607',
		'INT'        => 'type=number range=-2147483648,2147483647',
		'INTEGER'    => 'type=number range=-2147483648,2147483647',
		'BIGINT'     => 'type=number',
		### 非负整数
		'TINYINT+'   => 'type=number range=0,255',
		'SMALLINT+'  => 'type=number range=0,65535',
		'MEDIUMINT+' => 'type=number range=0,16777215',
		'INT+'       => 'type=number range=0,4294967295',
		'INTEGER+'   => 'type=number range=0,4294967295',
		'BIGINT+'    => 'type=number range=0,',
		### 小数
		'FLOAT'      => 'type=float',
		'REAL'       => 'type=float',
		'DOUBLE'     => 'type=float',
		'DECIMAL'    => 'type=float',
		'DEC'        => 'type=float',
		'NUMERIC'    => 'type=float',
		'FIXED'      => 'type=float',
		### 非负小数
		'FLOAT+'     => 'type=float range=0,',
		'REAL+'      => 'type=float range=0,',
		'DOUBLE+'    => 'type=float range=0,',
		'DECIMAL+'   => 'type=float range=0,',
		'DEC+'       => 'type=float range=0,',
		'NUMERIC+'   => 'type=float range=0,',
		'FIXED+'     => 'type=float range=0,',
		### 日期日期
		'DATE'       => 'type=date',
		'DATETIME'   => 'type=datetime',
		'TIMESTAMP'  => 'type=datetime',
		'TIME'       => 'type=time',
		'YEAR'       => 'type=number max=4 range=1901,2155',
		### 真假
		'BOOL'       => 'type=checkbox',
		'BOOLEAN'    => 'type=checkbox',
		### 文本
		'ENUM'       => 'type=select',
		'SET'        => 'type=list',
	),
);
