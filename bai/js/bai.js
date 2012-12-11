/**
 * <b>Bai PHP开发框架（简单PHP）</b>
 * @author		白晓阳
 * @copyright	Copyright (c) 2011 - 2012, 白晓阳
 * @link		http://www.baicode.net
 * @version    V1.0.0 2012/03/31 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 */

/**
 * <b>Bai PHP开发框架（简单PHP）</b>
 * <b>客户端检验与提交</b>
 * <p>！！！客户端核心文件，任何不当的修改都可能破坏整个系统的正常运行！！！</p>
 *
 * @author 白晓阳
 */

/** 提示信息 */
var jsm = {
	risk : "含有&lt; &gt; &amp; ' ; % \ 等非法字符……",
	required : "请输入内容……",
	min : "最小长度为?……",
	max : "最大长度为?……",
	type : "属性不符……",
	timeout : 3000
};

/** 请求完成标识 */
var jsd = true;

/**
 * 组织并发起AJAX请求
 * 
 * @param event
 *            事件
 * @param box
 *            范围
 * @param check
 *            检验标识
 * @returns {Boolean}
 */
function jss(event, box, check) {
	// 上一请求未完成
	if (!jsd)
		return false;
	// 清空布告信息
	$(".notice").remove();
	// 组织请求数据
	var data = {
		"event" : event,
		"cipher" : $("#cipher").val()
	};
	if (check == null || check) {
		// 检验输入内容
		var items = $(":input", $(box));
		for ( var i = 0, m = items.length; i < m; i++) {
			// alert(items[i].name + ":" + items[i].value);
			if (jsc.call(items[i]))
				return false;
			data[items[i].name] = items[i].value;
		}
	}
	// 请求开始
	jsd = false;
	$.post("/", data, function(data) {
		if (box != null) {
			// alert(data);
			$(box).html(data);
			home.call($(box));
			// 请求完成
			jsd = true;
		}
	});
	return true;
}

/**
 * 检验输入项目
 * 
 * @returns true : 输入有误 false: 输入正确
 */
function jsc() {
	var box = $(this);
	// 特殊字符检验
	var message = risk(this.value);
	if (message) {
		notice.call(this, message);
		return message;
	}
	// 无检验规则
	if (!box.attr("data-check")) {
		return false;
	}
	// 开始检验
	var checks = box.attr("data-check").split(" ");
	for ( var i = 0, m = checks.length; i < m; i++) {
		var params = checks[i].split("=");
		var check = params[0] + "('" + this.value + "'";
		for ( var j = 1; j < params.length; j++) {
			check += ",'" + params[j] + "'";
		}
		check += ")";
		// alert(check);
		try {
			var message = eval(check);
			if (message) {
				notice.call(this, message);
				return message;
			}
		} catch (e) {
		}
	}
	return false;
}

/**
 * 字符串去除空格
 */
String.prototype.trim = function() {
	return this.replace(/^\s+|\s+$/g, "");
};

/**
 * 非空检验
 * 
 * @param item：
 *            检验项目
 * @return
 */
function required(item) {
	if (item == null || item.trim() == "") {
		return jsm.required;
	}
	return false;
}

/**
 * 特殊字符检验
 * 
 * @param item：
 *            检验项目
 * @return
 */
function risk(item) {
	if (required(item)) {
		return false;
	}
	// 不能包含<、>、&、%、;、'、\
	if (item.match(/[<>&%;\'\\]+/)) {
		return jsm.risk;
	}
	return false;
}

/**
 * 最小长度检验
 * 
 * @param item：
 *            检验项目
 * @param len：
 *            最小长度
 * @return
 */
function min(item, len) {
	if (required(item)) {
		return false;
	}
	if (item.trim().length < len) {
		return jsm.min.replace("?", len);
	}
	return false;
}

/**
 * 最大长度检验
 * 
 * @param item：
 *            检验项目
 * @param len：
 *            最大长度
 * @return
 */
function max(item, len) {
	if (required(item)) {
		return false;
	}
	if (item.trim().length > len) {
		return jsm.max.replace("?", len);
	}
	return false;
}

/**
 * 项目属性检验
 * 
 * @param item：
 *            检验项目
 * @param type：
 *            属性
 */
function type(item, type) {
	if (required(item)) {
		return false;
	}
	var msg = null;
	switch (type.toLowerCase()) {
	case "number": // 数字
		msg = item.match(/^\d+$/);
		break;
	case "float": // 数值
		msg = item.match(/^[+-]?\d+(?:\.\d+)?$/);
		break;
	case "letter": // 英文字母
		msg = item.match(/^[a-zA-Z]+$/);
		break;
	case "char": // 英文字母数字划线
		msg = item.match(/^[a-zA-Z0-9_-]+$/);
		break;
	case "mp": // 移动电话
		msg = item.match(/^(?:\+86)?1[358][0-9]{9}$/);
		break;
	case "fax": // 固话传真
		msg = item.match(/^0[0-9]{2,3}-[1-9][0-9]{6,7}$/);
		break;
	case "url": // 网址
		msg = item.match(/^(?:http:\/\/)?[a-zA-Z0-9-_.\/]+(:\?.+)?$/);
		break;
	case "email": // 邮箱
		msg = item.match(/^[a-zA-Z0-9-_.]+@[a-zA-Z0-9-_.]+$/);
		break;
	case "date": // 日期
		msg = item.match(/^[0-9]{4}[-.\/]?(?:0?[1-9]|1[0-2])[-.\/]?(?:0?[1-9]|[12][0-9]|3[01])$/);
		break;
	case "time": // 时间
		msg = item.match(/^(?:0?[0-9]|1[0-9]|2[0-3])[:-]?(?:0?[0-9]|[1-5][0-9])[:-]?(?:0?[0-9]|[1-5][0-9])$/);
		break;
	default:
		msg = true;
	}
	if (msg != null)
		return false;
	return jsm.type;
}

/**
 * 密码加密
 * 
 * @param ip
 *            原始密码
 */
function p(ip) {
	if (ip == null || ip == "")
		return "";
	ip = encodeURIComponent(input);
	var op = new Array(ip.length);
	for ( var i = 0, m = ip.length; i < m;) {
		var b = ip.charCodeAt(i++);
		op[i] = String.fromCharCode((((b & 0x0F) + (b >> 4)) & 0x0F) + (b & 0xF0));
	}
	return op.join("");
}

/**
 * 提示信息
 * 
 * @param on
 *            显示开关
 */
function hint(on) {
	var msg = this.title;
	// 无提示信息
	if (!msg)
		return;
	var item = $(this);
	// 关闭提示信息
	if (!on) {
		var box = item.siblings(".hint");
		box.slideUp("normal", function() {
			$(this).remove();
		});
		return;
	}
	// 显示提示信息
	var scrollTop = 0;
	var scroll = item.parents("div.show");
	if (scroll.length > 0)
		scrollTop = scroll[0].scrollTop;
	var box = $('<div class="hint">' + msg + '</div>');
	box.css({
		"left" : this.offsetLeft,
		"top" : this.offsetTop + this.offsetHeight - scrollTop
	});
	item.after(box);
	box.slideDown("normal", function() {
		setTimeout("$('#" + item.attr("id") + "').change()", jsm.timeout);
	});
}

/**
 * 布告信息
 * 
 * @param message
 *            信息
 */
function notice(message) {
	// 关闭布告信息
	if (!message) {
		var box = $(this);
		box.next(".input").focus().select();
		box.remove();
		return;
	}
	// 显示布告信息
	var scroll = $(this).parents("div.show");
	if (scroll.length > 0)
		scroll = scroll[0];
	if (scroll.scrollTop > 0)
		scroll.scrollTop = this.offsetTop - scroll.offsetTop - 3;
	var box = $('<div class="notice">' + message + '</div>');
	box.css({
		"left" : this.offsetLeft,
		"top" : this.offsetTop,
		"min-width" : this.offsetWidth - 6,
		"height" : this.offsetHeight
	});
	box.click(Function("notice.call(this)"));
	$(this).before(box);
	setTimeout("$('.notice:first').click()", jsm.timeout);
}

function home() {
	var range = $(this).attr("id");
	if (!range) {
		range = "";
	} else {
		range = "#" + range;
	}
	$(range + " .input").focus(Function("hint.call(this, true)"));
	$(range + " .input").change(Function("hint.call(this)"));
	$(range + " .input").blur(Function("hint.call(this)"));
}

$(document).ready(Function("home.call($('body'));"));
