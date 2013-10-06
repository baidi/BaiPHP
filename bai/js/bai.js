
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
		if (! bai.is(action, bai.is.FUNCTION)) {
			return false;
		}
		var result = null;
		for (var i = 0, m = this.length; i < m; i++) {
			result = action.call(this[i], i);
			if (result === false) {
				return result;
			}
		}
		return true;
	};

	/** 元素：执行CSS选择器 */
	Element.prototype.pick = Document.prototype.pick = function(query, one) {
		if (one) {
			return this.querySelector(query);
		}
		return this.querySelectorAll(query);
	};

	/** 元素：读取自身属性 */
	Element.prototype.get = function(item) {
		if (! bai.is(item, bai.is.STRING) || item == '') {
			return null;
		}
		item = bai.config('alt', item) || item;
		if (this.hasAttribute(item)) {
			return this.getAttribute(item);
		}
		return this[item];
	};

	/** 元素：设置自身属性 */
	Element.prototype.set = function(item, value) {
		if (! bai.is(item, bai.is.STRING) || item == '') {
			return false;
		}
		item = bai.config('alt', item) || item;
		value = value || '';
		if (item == 'className') {
			var list = this.classList;
			if (value[0] == '+') {
				list.add(value.substr(1));
				return true;
			}
			if (value[0] == '-') {
				list.remove(value.substr(1));
				return true;
			}
		}
		this[item] = value;
		return true;
	};

	/** 元素：查验自身属性 */
	Element.prototype.has = function(item, value) {
		if (! bai.is(item, bai.is.STRING) || item == '') {
			return false;
		}
		item = bai.config('alt', item) || item;
		value = value || '';
		if (this[item] == null || value == '') {
			return false;
		}
		if (item == 'className') {
			return this.classList.contains(value);
		}
		if (this[item].indexOf != null) {
			return this[item].indexOf(value) !== false;
		}
		return false;
	};

	/** 元素：设置自身样式 */
	Element.prototype.css = function(item, value) {
		if (item == null) {
			return null;
		}
		var pre = /-[a-z]/gi;
		var post = function(prefix) {
			return prefix[1].toUpperCase();
		};
		if (bai.is(item, bai.is.STRING)) {
			//设置单个样式
			if (item.indexOf('-') >= 0) {
				item = item.toLowerCase().replace(pre, post);
			}
			if (value === undefined) {
				return this.style.item;
			}
			return this.style[item] = value || '';
		}
		if (bai.is(item, bai.is.JSON)) {
			// 设置一组样式
			var proper = null;
			for (var key in item) {
				if (key == null) {
					continue;
				}
				if (key.indexOf('-') >= 0) {
					proper = key.toLowerCase().replace(pre, post);
				} else {
					proper = key;
				}
				this.style[proper] = item[key] || '';
			}
		}
	};

	/** 元素：绑定事件 */
	Element.prototype.on = function(item, action) {
		if (! bai.is(item, bai.is.STRING)) {
			return false;
		}
		if (item.substr(0, 2) != 'on') {
			item = 'on' + item;
		}
		if (this[item] === undefined) {
			return false;
		}
		if (action === undefined) {
			return this[item]();
		}
		this[item] = action;
		return true;
	};

	/** 元素：执行CSS变换 */
	Element.prototype.to = function(css, duration, replay, alt, mode) {
		if (css == null) {
			return false;
		}
		if (bai.is(duration, bai.is.NUMBER)) {
			duration = (duration >= 100 ? duration + 'ms' : duration + 's');
		} else if (! /^(\d{1,2}s|\d{3,}ms)$/.test(duration)) {
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
		// 简单变换
		if (bai.is(css, bai.is.STRING)) {
			var key = 'BaiCSS-' + bai.encode(css);
			// 建立变换帧
			if (style.baicss[key] == null) {
				try {
					style.insertRule('@keyframes ' + key + ' {0% {} 100% {' + css + '}}', style.cssRules.length);
				} catch (e) {
					style.insertRule('@-webkit-keyframes ' + key + ' {0% {} 100% {' + css + '}}', style.cssRules.length);
				}
				style.baicss[key] = true;
			}
			if (replay === 1 || replay === '1') {
				var $this = this;
				var timeout = parseInt(duration);
				if (duration[duration.length - 2] != 'm') {
					timeout *= 1000;
				}
				setTimeout(function() {
					$this.css(bai.from(css, bai.from.CSS));
				}, timeout);
			}
			// 建立变换样式
			var animation = [key, duration, replay, alt].join(' ');
			this.css({animation: animation, '-webkit-animation': animation});
			return true;
		}
		// 定制变换
		css = bai.to(css);
		var key = 'BaiCSS-' + bai.encode(css);
		if (style.baicss[key] == null) {
			try {
				style.insertRule('@keyframes ' + key + ' {' + css + '}', style.cssRules.length);
			} catch (e) {
				style.insertRule('@-webkit-keyframes ' + key + ' {' + css + '}', style.cssRules.length);
			}
			style.baicss[key] = true;
		}
		if (replay === 1 || replay === '1') {
			var $this = this;
			var timeout = parseInt(duration);
			if (duration[duration.length - 2] != 'm') {
				timeout *= 1000;
			}
			setTimeout(function() {
				$this.css(bai.from(css, bai.from.CSS));
			}, timeout);
		}
		// 建立变换样式
		var animation = [key, duration, replay, alt].join(' ');
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
	var $config = $config$ || {};
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

	/** 化简JS：读取消息配置 */
	$bai.message = function() {
		var params = ['message'];
		for (var i = 0, m = arguments.length; i < m; i++) {
			params.push(arguments[i]);
		}
		return bai.config.apply(this, params);
	};

	/** 化简JS：访问私有属性 */
	$bai.own = function(item, value) {
		if (! bai.is(item, bai.is.STRING) || item == '') {
			return null;
		}
		if (value === undefined) {
			return $own[item];
		}
		return $own[item] = value;
	};

	/** 化简JS：输出日志信息 */
	$bai.log = function() {
		if (console && bai.is(console.log, bai.is.FUNCTION)) {
			console.log.apply(this, arguments);
		}
	};

	$bai.encode = function(origin) {
		if (! bai.is(origin, bai.is.STRING) || origin == '') {
			return '';
		}
//		var result = new Array();
//		for (var i = 0, m = origin.length; i < m; i++) {
//			var b = origin.charCodeAt(i);
//			b = ((((b & 0x0F) + (b >> 4)) & 0x0F) + (b & 0xF0)) % 26 + 65;
//			result[i] = String.fromCharCode(b);
//		}
//		return result.join('');
		var result = 0;
		for (var i = 0, m = origin.length; i < m; i++) {
			var b = origin.charCodeAt(i);
			result = ((result << 5) - result) + b;
			result = result & result;
		}
		return result;
	};

	/** 化简JS：判断数据类型 */
	$bai.is = function(origin, type) {
		if (origin == null || type == null) {
			return false;
		}
		if (type == bai.is.URL) {
			return /^https?:\/\//i.test(origin);
		}
		return origin.constructor == type;
	};
	$bai.is.STRING = String;
	$bai.is.ARRAY = Array;
	$bai.is.BOOLEAN = Boolean;
	$bai.is.NUMBER = Number;
	$bai.is.DATE = Date;
	$bai.is.JSON = Object;
	$bai.is.FUNCTION = Function;
	$bai.is.REGEXP = RegExp;
	$bai.is.URL = 'URL';

	/** 化简JS：转换对象到特定格式字符串 */
	$bai.to = function(origin, type) {
		if (origin == null) {
			return '';
		}
		if (! bai.is(origin, bai.is.JSON)) {
			return origin.toString();
		}
		// 转换结果
		var result = new Array();
		// 间隔符
		var gaps = null;
		if ($bai.is(type, $bai.is.ARRAY)) {
			// 自定义间隔符
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
		// 插入间隔形成特定格式
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

	$bai.from = function(origin, type) {
		if (origin == null) {
			return null;
		}
		if (! bai.is(origin, bai.is.STRING)) {
			return origin;
		}
		// 转换结果
		var result = {};
		// 间隔符
		var gaps = null;
		if (type == $bai.to.CSS) {
			gaps = [':', ';'];
		} else {
			gaps = [':', ','];
		}
		var items = origin.split(gaps[1]);
		for (var i = 0, m = items.length; i < m; i++) {
			var item = items[i].split(gaps[0]);
			if (item[0].trim() == '') {
				continue;
			}
			result[item[0].trim()] = item[1].trim();
		}
		return result;
	};
	$bai.from.CSS = 'CSS';
	$bai.from.JSON = 'JSON';

	/** 化简JS：执行CSS选择器 */
	$bai.pick = function(query, scope) {
		if (scope != null && scope.pick != null) {
			return scope.pick(query, 1);
		}
		return document.pick(query, 1);
	};

	p.B = p.bai = $bai;
})(window);

if (window.bai != null) {

	/** 化简JS：输入项检验 */
	window.bai.check = function() {
		var $name = 'check';
		var $types = ['INPUT', 'SELECT', 'TEXTAREA'];

		/** 非空检验 */
		var required = function(input) {
			if (input == null || input == '' || input.length == 0) {
				return bai.message($name, 'required');
			}
			return false;
		};

		/** 风险字符检验 */
		var risk = function(input) {
			if (bai.is(input, bai.is.ARRAY)) {
				input = input.join('');
			}
			var mode = eval(bai.config($name, 'types', 'risk'));
			if (bai.is(mode, bai.is.REGEXP) && mode.test(input)) {
				return bai.message($name, 'risk');
			}
			return false;
		};

		/** 最小长度检验 */
		var min = function(input, len) {
			if (input.length < len) {
				return bai.message($name, 'min').replace('%d', len);
			}
			return false;
		};

		/** 最大长度检验 */
		var max = function(input, len) {
			if (input.length > len) {
				return bai.message($name, 'max').replace('%d', len);
			}
			return false;
		};

		/** 属性检验 */
		var type = function(item, type) {
			var mode = eval(bai.config($name, 'types', type));
			if (bai.is(mode, bai.is.REGEXP) && ! mode.test(item)) {
				return bai.message($name, 'type');
			}
			return false;
		};

		/** 检验输入项目 */
		var checkInput = function(callback) {
			// 检验内容
			var checks = this.get('data-check');
			if (checks == null || checks == '') {
				return false;
			}
			// 检验条目匹配式
			var mode = bai.config($name, 'mode');
			if (mode == null || ! bai.is((mode = eval(mode + 'g')), bai.is.REGEXP)) {
				return false;
			}
			// 检验条目
			var check = null;
			// 检验处理
			var action = null;
			// 检验参数
			var params = null;
			// 检验结果
			var result = null;
			// 参数分割符
			var gap = bai.config($name, 'gap');
			while ((check = mode.exec(checks)) != null) {
				// 解析检验处理
				if (! bai.is((action = eval(check[1])), bai.is.FUNCTION)) {
					continue;
				}
				// 分组织检验参数
				params = [this.value];
				if (check[2] != null) {
					params = params.concat(check[2].split(gap));
				}
				// 应用检验处理
				result = action.apply(this, params);
				if (result) {
					callback(this, result);
					return result;
				}
			}
			return false;
		};

		/** 提示检验结果 */
		var notice = function(input, result) {
			var focus = input.onfocus;
			input.value = result;
			input.set('class', '+notice');
			input.onfocus = function(e) {
				this.value = '';
				this.set('class', '-notice');
				this.onfocus = focus;
			};
		};

		/**
		 * 化简JS：输入项检验
		 * @param scope 检验区域
		 * @param callback 后手处理
		 */
		var $check = function(scope, callback) {
			if (scope == null) {
				// 默认检验区域为当前文档
				scope = document;
			}
			if (bai.is(scope, bai.is.STRING)) {
				// 从文档挑选检验区域
				scope = document.pick(scope, 1);
			}
			if (scope.nodeType != 1 && scope.nodeType != 9) {
				return false;
			}
			if (! bai.is(callback, bai.is.FUNCTION)) {
				// 设置默认后手处理
				callback = notice;
			}
			var inputs = null;
			if ($types.indexOf(scope.tagName) < 0) {
				// 从检验区域中挑选输入检验项
				inputs = scope.pick('.input');
				if (! inputs.length) {
					return false;
				}
			} else {
				// 检验区域为输入项
				inputs = [scope];
			}
			var data = new Array();
			var result = false;
			for (var i = 0, m = inputs.length; i < m; i++) {
				data.push(inputs[i].name + '=' + inputs[i].value);
				// 依次检验输入项目
				if (result = checkInput.call(inputs[i], callback)) {
					return {input: inputs[i], result: result};
				}
			}
			return {input: null, result: data.join('&')};
		};
		return $check;
	}();
}

if (window.bai != null) {

	/** 化简JS：异步访问 */
	window.bai.ajax = function() {
		var $name = 'ajax';

		/** 元素：异步加载内容 */
		Element.prototype.load = function(url) {
			bai.ajax(url, this);
		};

		/** 元素加载后手处理 */
		var load = function(box) {
			return function(data, url) {
				box.innerHTML = data || bai.message($name, 'fail');
			};
		};

		/**
		 * 异步访问后手处理
		 * @param url 访问地址
		 * @param success 成功后手处理
		 * @param failure 失败后手处理
		 */
		var callback = function(url, success, failure) {
			return function() {
				if (this.readyState != 4) {
					return false;
				}
				if (this.status == 200) {
					var data = this.responseText;
					if (data[0] == '{' && data[data.length - 1] == '}') {
						// 解析JSON对象
						try {
							data = JSON.parse(data);
						} catch(e) {}
					}
					// 成功后手处理
					if (bai.is(success, bai.is.FUNCTION)) {
						return success(data, url);
					}
					return false;
				}
				// 失败后手处理
				if (bai.is(failure, bai.is.FUNCTION)) {
					return failure(this.responseText, url);
				}
				return false;
			};
		};

		/**
		 * 化简JS：异步访问
		 * @param url 访问地址（http|https）
		 * @param data 访问方式或访问数据
		 * @param success 成功后手处理
		 * @param failure 失败后手处理
		 */
		var $ajax = function(url, data, success, failure) {
			if (XMLHttpRequest == null || ! bai.is(url, bai.is.URL)) {
				return false;
			}
			// 访问方式，默认为GET
			var method = bai.ajax.GET;
			if (data == bai.ajax.GET) {
				// data传递访问方式
				data = null;
			} else if (bai.is(data, bai.is.FUNCTION)) {
				// data传递成功后手处理
				failure = success;
				success = data;
				data = null;
			} else if (data.nodeType) {
				// data传递页面元素
				failure = success;
				success = load(data);
				data = null;
			} else {
				// data传递提交数据
				method = bai.ajax.POST;
				data = (data == bai.ajax.POST ? null : bai.to(data, bai.to.POST));
			}

			// 组织异步请求
			var xhr = new XMLHttpRequest();
			xhr.timeout = parseInt(bai.config($name, 'timeout')) || 5000;
			xhr.onreadystatechange = callback(url, success, failure);
			xhr.open(method, url + '&ajax=1', true);
			if (method == bai.ajax.POST) {
				xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			}
			xhr.send(data);
			return null;
		};
		$ajax.GET = 'GET';
		$ajax.POST = 'POST';

		return $ajax;
	}();
}

if (window.bai != null) {

	/** 化简JS：浮出提示 */
	window.bai.bubble = function() {
		var $name = 'bubble';
		var $shade = null, $title = null, $content = null, $toolbar = null, $bubbled = null;

		/** 加载提示内容 */
		var load = function(content, url) {
			if (content == '') {
				$content.innerHTML = bai.message($name, '');
			} else {
				$content.innerHTML = content;
				if (url != null) {
					$bubbled.innerHTML += '<li id="' + url + '">' + content + '</li>';
				}
			}
			var title = $content.pick('.bubble-title', 1);
			if (title != null) {
				$title.innerHTML = title.innerHTML;
			}
			show();
		};

		/** 加载失败信息 */
		var fail = function(content) {
			$content.innerHTML = content || bai.message($name, 'fail');
			show();
		};

		/** 显示提示框体 */
		var show = function() {
			$shade.set('class', '-h');
		};

		/** 关闭提示框体 */
		var close = function(e) {
			$shade.set('class', '+h');
		};

		/** 准备确认处理 */
		var submit = function(action) {
			if (bai.is(action, bai.is.FUNCTION)) {
				return function(e) {
					if (action(e) !== false) {
						close(e);
					}
				};
			}
			if (bai.is(action, bai.is.URL)) {
				return function(e) {
					var check = bai.check($content);
					if (! check || check.input != null) {
						return false;
					}
					bai.ajax(action, check.result, function(data, url) {
						var result = $toolbar.pick('.result', 1);
						if (bai.is(data, bai.is.JSON)) {
							result.innerHTML = data.notice || bai.message($name, 'success');
							return;
						}
						result.innerHTML = data;
					});
				};
			}
			return close;
		};

		/**
		 * 化简JS：浮出提示
		 * @param content 提示内容，若为网址（http|https）则异步加载
		 * @param title 提示标题
		 * @param action 确认处理，默认关闭提示
		 * @param cancel 否认处理，默认关闭提示
		 */
		var $bubble = function(content, title, action, cancel, buttons) {
			// 背景
			$shade = $shade || bai.pick('.shade');
			if ($shade == null) {
				return false;
			}
			// 标题栏
			$title = $title || $shade.pick('.bubble .title', 1);
			// 内容栏
			$content = $content || $shade.pick('.bubble .content', 1);
			// 工具栏
			$toolbar = $toolbar || $shade.pick('.bubble .toolbar', 1);
			// 历史栏
			$bubbled = $bubbled || $shade.pick('.bubbled', 1);
			if ($title == null || $content == null || $toolbar == null || $bubbled == null) {
				return false;
			}
			if (bai.is(title, bai.is.FUNCTION)) {
				cancel = action;
				action = title;
				title = null;
			}
			// 设置标题
			$title.innerHTML = title || bai.message($name, 'title');
			// 设置关闭处理
			$shade.pick('.bclose', 1).onclick = close;
			// 设置确认处理
			$shade.pick('.bokay', 1).onclick = submit(action);
			// 设置取消处理
			var bcancel = $shade.pick('.bcancel', 1);
			if (bai.is(cancel, bai.is.FUNCTION)) {
				bcancel.addEventListener('click', cancel);
			}
			bcancel.addEventListener('click', close);
			// 加载即时内容
			if (! /^https?:\/\//i.test(content)) {
				$content.innerHTML = content || bai.message($name, 'content');
				show();
				return true;
			}
			var last = $bubbled.pick('li[id="' + content + '"]', 1);
			if (last != null) {
				// 加载历史内容
				load(last.innerHTML);
			} else {
				// 加载远程内容
				bai.ajax(content, load, fail);
			}
			return true;
		};
		$bubble.OKCANCEL = ['bokay', 'bcancel'];
		$bubble.YESNO = ['byes', 'bno'];

		return $bubble;
	}();
}
