
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

/** BaiPHP - 化简PHP */
(function(p) {
	if (p.bai != null) {
		return;
	}

	/** 字符串：去除首尾空格 */
	if (String.prototype.trim == null) {
		String.prototype.trim = function() {
			return this.replace(/^\s+|\s+$/g, '');
		};
	}

	/** 数组：依次处理子项目 */
	Array.prototype.each = NodeList.prototype.each = function(action) {
		if (action == null || action.constructor != Function) {
			return false;
		}
		var result = null;
		for (var i = 0, m = this.length; i < m; i++) {
			result = action.call(this[i], i);
			if (result === false) {
				break;
			}
		}
		return true;
	};

	/** 元素：执行CSS选择器 */
	Element.prototype.pick = Document.prototype.pick = function(query, one) {
		if (query == null || query.constructor != String) {
			return null;
		}
		if (one) {
			return this.querySelector(query);
		}
		return this.querySelectorAll(query);
	};

	/** 元素：读取自身属性 */
	Element.prototype.get = function(item) {
		if (item == null || item.constructor != String) {
			return null;
		}
		if (this.hasAttribute(item)) {
			return this.getAttribute(item);
		}
		return this[item];
	};

	/** 元素：设置自身属性 */
	Element.prototype.set = function(item, value) {
		if (item == null || item.constructor != String) {
			return false;
		}
		value = value || '';
		if (value.constructor != String) {
			value = value.toString();
		}
		if (item == 'class') {
			var list = this.get(item).split(' ');
			if (value[0] == '+') {
				value = value.substr(1);
				if (list.indexOf(value) < 0) {
					list.push(value);
				}
				this[item] = list.join(' ');
				return true;
			}
			if (value[0] == '-') {
				value = value.substr(1);
				var i = -1;
				while ((i = list.indexOf(value)) >= 0) {
					list = list.slice(0, i).concat(list.slice(i + 1));
				}
				this[item] = list.join(' ');
				return true;
			}
		}
		this[item] = value;
		return true;
	};

	/** 元素：设置自身样式 */
	Element.prototype.css = function(item, value) {
		if (item == null) {
			return null;
		}
		var pre = /-[a-z]/g;
		var post = function(prefix) {
			return prefix[1].toUpperCase();
		};
		if (item.constructor == String) {
			if (item.indexOf('-') >= 0) {
				item = item.toLowerCase().replace(pre, post);
			}
			if (value === undefined) {
				return this.style.item;
			}
			return this.style[item] = value || '';
		}
		if (item.constructor == Object) {
			var key = null, proper = null;
			for (key in item) {
				if (key == null) {
					continue;
				}
				if (key.indexOf('-') >= 0) {
					proper = key.toLowerCase().replace(pre, post);
				}
				this.style[proper] = item[key] || '';
			}
		}
		return null;
	};

	/** 元素：绑定事件 */
	Element.prototype.on = function(item, action) {
		if (item == null || item.constructor != String) {
			return false;
		}
		if (item.substr(0, 2) != 'on') {
			item = 'on' + item;
		}
		if (this[item] == undefined) {
			return false;
		}
		if (action != null && (action.constructor == String
				|| action.constructor == Function)) {
			this[item] = action;
			return true;
		}
		this[item] = null;
		return true;
	};

	/** 元素：执行CSS变换 */
	Element.prototype.to = function(css, duration, replay, mode, alt) {
		if (css == null) {
			return false;
		}
		if (duration == null || ! /^(\d{1,2}s|\d{3,}ms)$/.test(duration)) {
			duration = '1s';
		}
		if (replay == null || isNaN(replay) || replay == 0) {
			replay = 'infinite';
		}
		alt = (alt == null ? '' : (alt ? 'alternate' : 'reverse'));
		if (document.styleSheets.length == 0) {
			document.head.innerHTML += '<style id="BaiCSS" type="text/css"></style>';
		}
		var style = document.styleSheets[0];
		if (style.baicss == null) {
			style.baicss = {};
		}
		if (css.constructor == String) {
			if (style.baicss[css] == null) {
				if (navigator.userAgent.indexOf('AppleWebKit') >= 0) {
					style.insertRule('@-webkit-keyframes "' + css + '" {from {} to {' + css + '}}');
				} else {
					style.insertRule('@keyframes "' + css + '" {from {} to {' + css + '}}');
				}
				style.baicss[css] = true;
			}
			var animation = ['"' + css + '"', duration, replay].join(' ');
			this.css({animation: animation, '-webkit-animation': animation});
			return true;
		}
		var scss = [];
		for (var i in css) {
			if (i == null) {
				continue;
			}
			scss.push(i + css[i]);
		}
		scss = scss.join();
		if (style.baicss[scss] == null) {
			if (navigator.userAgent.indexOf('AppleWebKit') >= 0) {
				style.insertRule('@-webkit-keyframes "' + scss + '" {' + scss + '}');
			} else {
				style.insertRule('@keyframes "' + scss + '" {' + scss + '}');
			}
			style.baicss[scss] = true;
		}
		var animation = ['"' + scss + '"', duration, replay].join(' ');
		this.css({animation: animation, '-webkit-animation': animation});
		return true;
	};

	/** 化简JS - BaiJS */
	var $bai = {
		name: 'BaiJS',
		verson: '2.0.0',
		date: '2013-7-30',
		author: '白晓阳',
		history: ''
	};
	/** 化简JS：全局配置 */
	var $config = {
		Check: {
			mode:'/([^\\s=]+)(?:=([^\\s]+))?/i',
			gap:',',
			types:{
				risk:"/[<>&%'\\\\]+/"
			}
		}
	};
	/** 化简JS：私有属性 */
	var $own = {};

	/** 化简JS：读取全局配置 */
	$bai.config = function() {
		var value = $config;
		// 逐层读取配置
		for ( var i = 0, m = arguments.length; i < m; i++) {
			value = value[arguments[i]];
			if (value == null) {
				return null;
			}
		}
		return value;
	};

	/** 化简JS：访问私有属性 */
	$bai.own = function(item, value) {
		if (item == null) {
			return null;
		}
		if (value === undefined) {
			return $own[item];
		}
		return $own[item] = value;
	};

	/** 化简JS：输出信息 */
	$bai.log = function() {
		if (console && console.log && console.log.constructor == Function) {
			console.log.apply(this, arguments);
		}
	};

	/** 化简JS：判断数据类型 */
	$bai.is = function(origin, type) {
		if (origin == null || type == null) {
			return false;
		}
		return origin.constructor == type;
	};
	$bai.is.STRING = String;
	$bai.is.OBJECT = Object;
	$bai.is.ARRAY = Array;
	$bai.is.BOOLEAN = Boolean;
	$bai.is.NUMBER = Number;
	$bai.is.DATE = Date;
	$bai.is.FUNCTION = Function;

	/** 化简JS：转换对象到特定格式 */
	$bai.to = function(origin, type) {
		if (origin == null) {
			return '';
		}
		if (origin.constructor != Object) {
			return origin.toString();
		}
		var result = [], gaps;
		if ($bai.is(type, $bai.is.ARRAY)) {
			gaps = type;
		} else if (type == $bai.to.CSS) {
			gaps = [':', ';', '', ';'];
		} else if (type == $bai.to.POST) {
			gaps = ['=', '&'];
		} else if (type == $bai.to.JSON) {
			gaps = [':', ',', '{', '}'];
		} else {
			gaps = ['', ''];
		}
		for (var i in origin) {
			if (i == null || $bai.is(origin[i], $bai.is.FUNCTION)) {
				continue;
			}
			result.push(i + (gaps[0] || '') + origin[i]);
		}
		result = (gaps[2] || '') + result.join(gaps[1] || '') + (gaps[3] || '');
		return result;
	};
	$bai.to.CSS = 'CSS';
	$bai.to.POST = 'POST';
	$bai.to.JSON = 'JSON';

	/** 化简JS：执行CSS选择器 */
	$bai.pick = function(query, one) {
		return document.pick(query, one);
	};

	p.B = p.bai = $bai;
})(window);

if (window.bai != null) {
	/** 化简JS：输入项检验 */
	window.bai.check = function() {
		/** 输入项非空检验 */
		var required = function(item) {
			if (item == null || (item.constructor == String && item.trim() == "")
					|| (item.constructor == Array && item.length == 0)) {
				return bai.config('Log', 'required');
			}
			return false;
		};

		/** 输入项风险字符检验 */
		var risk = function(item) {
			if (item.constructor == Array) {
				item = item.join('');
			}
			var mode = eval(this.config('Check', 'types', 'risk'));
			if (mode.constructor != RegExp || mode.test(item)) {
				return bai.config('Log', 'risk');
			}
			return false;
		};

		/** 输入项最小长度检验 */
		var min = function(item, len) {
			if (item.constructor == String && item.trim().length < len
					|| item.constructor == Array && item.length < len) {
				return bai.config('Log', 'min').replace('%s', len);
			}
			return false;
		};

		/** 输入项最大长度检验 */
		var max = function(item, len) {
			if (item.constructor == String && item.trim().length > len
					|| item.constructor == Array && item.length > len) {
				return bai.config('Log', 'max').replace('%s', len);
			}
			return false;
		};

		/** 输入项属性检验 */
		var type = function(item, type) {
			var mode = eval(this.config('Check', 'types', type));
			if (mode.constructor != RegExp || ! mode.test(item)) {
				return bai.config('Log', 'type');
			}
			return bai.config('Log', 'type');
		};

		/** 输入项目检验 */
		var checkInput = function(callback) {
			var mode = bai.config('Check', 'mode');
			if (mode == null || (mode = eval(mode + 'g')).constructor != RegExp) {
				return false;
			}
			var checks = this.get('data-check');
			if (checks == null || checks == '') {
				return false;
			}
			var gap = bai.config('Check', 'gap');
			var check, action, params, result;
			while ((check = mode.exec(checks)) != null) {
				if ((action = eval(check[1])).constructor != Function) {
					continue;
				}
				params = [this.value];
				if (check[2] != null) {
					params = params.concat(check[2].split(gap));
				}
				result = action.apply(this, params);
				if (result) {
					if (callback != null && callback.constructor == Function) {
						callback(this, result);
					}
					return result;
				}
			}
			return false;
		};

		var $check = function(scope, callback) {
			if (scope == null) {
				scope = document;
			}
			if (scope != null && scope.constructor == String) {
				scope = document.pick(scope, 1);
			}
			if (scope.nodeType == null || scope.nodeType != 1 && scope.nodeType != 9) {
				return null;
			}
			var types = ['INPUT', 'SELECT', 'TEXTAREA'];
			var inputs = null;
			if (types.indexOf(scope.tagName) < 0) {
				inputs = scope.pick('.input');
				if (! inputs.length) {
					return null;
				}
			} else {
				inputs = [scope];
			}
			var data = [];
			var result = false;
			for (var i = 0, m = inputs.length; i < m; i++) {
				data.push(inputs[i].name + '=' + inputs[i].value);
				if (result = checkInput.call(inputs[i], callback)) {
					return {input: inputs[i], result: result};
				}
			}
			return data;
		};
		return $check;
	}();
}

if (window.bai != null) {
	/** 化简JS：异步访问 */
	window.bai.ajax = function() {
		/** 访问过期时间（毫秒） */
		var timeout = parseInt(bai.config('Bai', 'timeout')) || 5000;

		/** 访问后手处理 */
		var action = function(success, failure) {
			var _action = function() {
				if (this.readyState != 4) {
					return false;
				}
				if (this.status == 200) {
					if (success && success.constructor == Function) {
						return success(this.responseText);
					}
					return false;
				}
				if (failure && failure.constructor == Function) {
					return failure(this.responseText);
				}
				return false;
			};
			return _action;
		};

		/** 访问数据打包 */
		var pack = function(data) {
			if (data == null || data.constructor == String) {
				return data;
			}
			if (data.constructor == Array) {
				return data.join('&');
			}
			if (data.constructor == Object) {
				var result = [];
				for (var i in data) {
					if (i == null) {
						continue;
					}
					if (data.i == null) {
						result.push(i + '=');
						continue;
					}
					if (data.i.constructor == Function) {
						continue;
					}
					if (data.i.constructor == Boolean) {
						result.push(i + '=' + (data.i ? 1 : 0));
						continue;
					}
					result.push(i + '=' + data.i.toString());
				}
				return result.join('&');
			}
			return data.toString();
		};

		var $ajax = function(url, data, success, failure) {
			if (window.XMLHttpRequest == null || url == null) {
				return false;
			}
			if (url.constructor != String) {
				data = url['data'];
				success = url['success'];
				failure = url['failure'];
				url = url['url'];
				if (url == null || url.constructor != String) {
					return false;
				}
			}
			var type = this.ajax.GET;
			if (data == null || data == this.ajax.GET) {
				data = null;
			} else if (data.constructor == Function) {
				if (success) {
					failure = success;
				}
				success = data;
				data = null;
			} else {
				type = this.ajax.POST;
				data = pack(data);
			}

			var xhr = new XMLHttpRequest();
			xhr.timeout = timeout;
			xhr.onreadystatechange = action(success, failure);
			xhr.open(type, url, true);
			if (type == this.ajax.POST) {
				xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			}
			xhr.send(data);
		};

		$ajax.GET = 'GET';
		$ajax.POST = 'POST';
		return $ajax;
	}();
}

/**
 * <b>密码加密</b><br/>
 * 
 * @param String
 *            ip 原始密码
 * 
 * @returns String
 */
var encode = function(ip) {
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


bai.pick('body', 1).onload = function() {
	
};
