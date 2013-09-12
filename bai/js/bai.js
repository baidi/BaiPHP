
/**
 * <b>Bai PHP开发框架（简单PHP）</b>
 * @author		白晓阳
 * @copyright	Copyright (c) 2011 - 2012, 白晓阳
 * @link		http://www.baiphp.net
 * @version     V1.0.0 2012/03/31 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 */

/**
 * <b>Bai PHP开发框架（简单PHP）</b>
 * <b>客户端检验与提交</b>
 * <p>！！！客户端核心文件，任何不当的修改都可能破坏整个系统的正常运行！！！</p>
 *
 * @author 白晓阳
 */

/** BaiPHP - 简单PHP */
var Bai = Bai || function() {
	/** 调试开关 */
	var _debug = false;
	/** 请求就绪开关 */
	var _ready = true;
	/** 保守错误信息 */
	var _error = "一不小心出错啦=_=...";
	/** 全局配置，来自PHP全局配置 */
	var $configs = $configs$ || {};

	var Baijs = new Function();
	if (Baijs.ready != null) {
		return Baijs;
	}

	/**
	 * <b>组织请求数据并发起请求</b><br/>
	 * 
	 * @param String
	 *            event 事件名
	 * @param String
	 *            box 范围
	 * @param String
	 *            callback 回调函数
	 * @returns Boolean
	 */
	Baijs.prototype.assign = function(event, box, callback) {
		// 请求尚未就绪
		if (! _ready) {
			return false;
		}
		// 清除布告信息
		$(".notice").remove();
		// 组织请求数据
		var cipher = c("cipher");
		var data = {
			"event" : event,
			"ajax" : "ajax",
			cipher : $("#" + cipher).val()
		};
		// 检验输入内容
		var input = $(box + " :input," + box + " .input");
		for ( var i = 0, m = input.length; i < m; i++) {
			if (_debug) {
				alert(input[i].name + ":" + input[i].value);
			}
			if (check.call(input[i])) {
				return false;
			}
			data[input[i].name] = input[i].value;
		}
		// 请求开始
		_ready = false;
		$.post("/", data, function(response) {
			if (box != null) {
				if (_debug) {
					alert(response);
				}
				$(box).html(response);
			}
			if (callback != null) {
				try {
					eval(callback).call(box);
				} catch (e) {
					if (_debug) {
						alert(e.description);
					}
				}
			}
			// 请求完成
			_ready = true;
		});
		return true;
	};

	/**
	 * <b>输入项目检验</b><br/>
	 * 
	 * @returns any true : 输入有误 false: 输入正确
	 */
	var check = function() {
		var input = $(this);
		var checks = input.attr("data-check") || "";
		// 非空检验
		var message = required(this.value);
		if (message) {
			if (checks.indexOf("required") < 0) {
				return false;
			}
			notice.call(this, message);
			return message;
		}
		// 开始检验
		checks = ("risk " + checks).split(" ");
		for ( var i = 0, m = checks.length; i < m; i++) {
			var params = checks[i].split("=");
			var check = params[0] + "('" + this.value + "'";
			for ( var j = 1; j < params.length; j++) {
				check += ",'" + params[j] + "'";
			}
			check += ")";
			if (_debug) {
				alert(check);
			}
			try {
				message = eval(check);
			} catch (e) {
				if (_debug) {
					alert(e.description);
				}
			}
			if (message) {
				notice.call(this, message);
				return message;
			}
		}
		return false;
	};

	/**
	 * <b>字符串去除空格</b><br/>
	 */
	String.prototype.trim = function() {
		return this.replace(/^\s+|\s+$/g, "");
	};

	/**
	 * <b>读取JS全局配置</b><br/>
	 * 
	 * @param String
	 *            item1 配置项目1
	 * @param String
	 *            item2 配置项目2
	 * @param String
	 *            item... 配置项目...
	 * 
	 * @returns 配置内容
	 */
	var c = function() {
		var value = $configs;
		// 逐层读取配置
		for ( var i = 0, m = arguments.length; i < m; i++) {
			value = value[arguments[i]];
			if (value == null) {
				return _error;
			}
		}
		return value || _error;
	};

	/**
	 * <b>非空检验</b><br/>
	 * 
	 * @param Stirng
	 *            item 检验项目
	 * 
	 * @return Boolean false: 检验通过；非false: 提示信息
	 */
	var required = function(item) {
		if (item == null || (item.trim != null && item.trim() == "")
				|| (item.constructor == Array && item.length == 0)) {
			return c("message", "required");
		}
		return false;
	};

	/**
	 * <b>风险字符检验</b><br/>
	 * 特定的字符如：<、>、&、%、;、'、\ 等可能对系统产生未知影响<br/>
	 * 
	 * @param String
	 *            item 检验项目
	 * 
	 * @return Boolean false: 检验通过；非false: 提示信息
	 */
	var risk = function(item) {
		if (item.constructor == Array) {
			item = item.join("");
		}
		if (item.match(eval(c("risk")))) {
			return c("message", "risk");
		}
		return false;
	};

	/**
	 * <b>最小长度检验</b><br/>
	 * 
	 * @param String
	 *            item 检验项目
	 * @param Integer
	 *            len 最小长度
	 * 
	 * @return Boolean false: 检验通过；非false: 提示信息
	 */
	var min = function(item, len) {
		if (item.trim != null && item.trim().length < len
				|| item.constructor == Array && item.length < len) {
			return c("message", "min").replace("%s", len);
		}
		return false;
	};

	/**
	 * <b>最大长度检验</b><br/>
	 * 
	 * @param String
	 *            item 检验项目
	 * @param Integer
	 *            len 最大长度
	 * 
	 * @return Boolean false: 检验通过；非false: 提示信息
	 */
	var max = function(item, len) {
		if (item.trim != null && item.trim().length > len
				|| item.constructor == Array && item.length > len) {
			return c("message", "min").replace("%s", len);
		}
		return false;
	};

	/**
	 * <b>项目属性检验</b><br/>
	 * 
	 * @param String
	 *            item 检验项目
	 * @param String
	 *            type 属性
	 * 
	 * @return Boolean false: 检验通过；非false: 提示信息
	 */
	var type = function(item, type) {
		if (item.match(eval(c("type", type)))) {
			return false;
		}
		return c("message", "type");
	};

	/**
	 * <b>密码加密</b><br/>
	 * 
	 * @param String
	 *            ip 原始密码
	 * 
	 * @returns String
	 */
	var p = function(ip) {
		if (ip == null || ip.trim() == "") {
			return "";
		}
		ip = encodeURIComponent(ip);
		var op = new Array(ip.length);
		for ( var i = 0, m = ip.length; i < m;) {
			var b = ip.charCodeAt(i++);
			op[i] = String.fromCharCode((((b & 0x0F) + (b >> 4)) & 0x0F) + (b & 0xF0));
		}
		return op.join("");
	};

	/**
	 * <b>显示或关闭提示信息</b><br/>
	 * 输入栏获得焦点时显示提示信息<br/>
	 * 
	 * @param Bollean
	 *            message 信息
	 */
	var hint = function(message) {
		var input = $(this);
		// 关闭提示信息
		if (message == null || !message) {
			var box = $(this);
			box.fadeIn("normal", function() {
				$(this).remove();
			});
			return;
		}
		// 显示提示信息
		var box = $('<div class="hint">' + msg + '</div>');
		box.css({
			"left" : this.offsetLeft,
			"top" : this.offsetTop + this.offsetHeight
		});
		// 布告信息改变关闭
		box.change(Function("hint.call(this);"));
		input.after(box);
		box.fadeOut("normal");
		// 提示信息过期自动关闭
		// setTimeout("$('.hint:first').change()", c("timeout"));
	};

	/**
	 * <b>显示或关闭布告信息</b><br/> 输入内容未通过检验时显示布告信息<br/>
	 * 
	 * @param string
	 *            message 信息
	 */
	var notice = function(message) {
		// 关闭布告信息
		if (message == null || !message) {
			var box = $(this);
			box.siblings(":input").focus().select();
			box.remove();
			return;
		}
		// 显示布告信息
		var box = $('<div class="notice">' + message + '</div>');
		box.css({
			"left" : this.offsetLeft,
			"top" : this.offsetTop,
			"min-width" : this.offsetWidth - 6,
			"height" : this.offsetHeight
		});
		// 布告信息点击关闭
		box.click(Function("notice.call(this);"));
		$(this).before(box);
		// 布告信息过期自动关闭
		// setTimeout("$('.notice:first').click();", c("timeout"));
	};

	Baijs.prototype.ready = true;
	return Baijs;
}();

Bai.dialog = function(title, content) {
	
};

/**
 * 主函数
 */
function home() {
	var range = $(this).attr("id");
	if (! range) {
		range = "";
	} else {
		range = "#" + range;
	}
	var box = null;
	// 输入项
	box = $(range + " :input" + range + " .input");
	box.focus(Function("hint.call(this, this.title)"));
	box.change(Function("hint.call(this)"));
	box.blur(Function("hint.call(this)"));
	// 检验未通过项
	box = $("#" + $(range + ".checked").val());
	box.addClass("checked");
	box.focus(Function("$(this).removeClass('checked');"));
}

$(document).ready(Function("home.call('body');"));
