/** 提示信息 */
var msg = {
	addHome : "设为主页失败，请手动设置……",
	addFavor : "加入收藏失败，请按下Ctrl+D手动收藏……",
	timeout: 3000
};

/**
 * 设为主页
 */
function addHome() {
	try {
		if (window.opera) {
			// Opera
			this.title = document.title;
			this.href = location.href;
		} else if (document.all) {
			// IE
			document.body.style.behavior = "url(#default#homepage)";
			document.body.setHomePage(document.location);
		} else if (window.sidebar) {
			// FireFox
			if (window.netscape) {
				netscape.security.PrivilegeManager
						.enablePrivilege("UniversalXPConnect");
			}
			var prefs = Components.classes["@mozilla.org/preferences-service;1"]
					.getService(Components.interfaces.nsIPrefBranch);
			prefs.setCharPref("browser.startup.homepage", document.location);
		} else {
			alert(msg.addHome);
		}
	} catch (e) {
		alert(msg.addHome);
	}
}
/**
 * 加入收藏
 */
function addFavor() {
	try {
		if (document.all) {
			// IE
			window.external.AddFavorite(document.location, document.title);
		} else if (window.sidebar) {
			// FireFox
			window.sidebar.addPanel(document.title, document.location, "");
		} else {
			alert(msg.addFavor);
		}
	} catch (e) {
		alert(msg.addFavor);
	}
}

/**
 * 选项变更
 */
function select() {
	// 字体
	if (this.id == "cFont") {
		$("body").css({
			"font-size" : this.value
		});
		$.post("/", {
			"event" : this.id,
			"cFont" : this.value
		});
		return;
	}
	// 外观
	if (this.id == "cStyle") {
		$.post("/", {
			"event" : this.id,
			"cStyle" : this.value
		}, function(data) {
			window.location.reload();
		});
		return;
	}
}

/**
 * 项目切换
 * 
 * @param speed
 *            切换速度
 * @param load
 *            加载内容
 */
function pick(speed, load) {
	var box = $(this);
	if (box.hasClass("on"))
		return false;
	// 切换CSS标记
	var last = box.siblings(".on");
	last.removeClass("on");
	box.addClass("on");
	if (!load) {
		// 切换效果
		if (speed) {
			// last.children("div").slideUp(speed);
			box.children("div").slideDown(speed);
			last.children("div").fadeOut(speed);
			// box.children("div").fadeIn(speed);
			return false;
		}
		last.children("div").toggle();
		box.children("div").toggle();
		return false;
	}
	// 取得切换目标
	var loader = $("#_" + box.parents("[id]:first").attr("id"));
	var picker = box.children("div:hidden:first");
	if (picker.length == 0) {
		// 需要Ajax加载
		var pickId = box.attr("id");
		if (!pickId) {
			pickId = box.children("div[id]:first").attr("id");
		}
		if (!pickId) {
			// 没有Ajax内容，直接切换
			loader.html(box.html());
			return false;
		}
		// Ajax加载
		loader.load(pickId, function(data) {
			picker = $('<div>' + data + "</div>");
			picker.hide();
			box.append(picker);
			//alert(data);
			home.call(loader);
		});
		return false;
	}
	if (!speed) {
		// 直接切换
		loader.html(picker.html());
		home.call(loader);
		return false;
	}
	// 切换效果
	loader.fadeOut(speed, function() {
		loader.html(picker.html());
		loader.fadeIn(speed);
		home.call(loader);
	});
	return false;
}

/**
 * 循环滚动
 * 
 * @param id
 *            标识
 * @param interval
 *            间隔
 */
function roll(id, interval) {
	var list = $(id);
	// 鼠标悬停时延后
	if (list.prop("on")) {
		setTimeout("roll('" + id + "'," + interval + ")", interval);
		return;
	}
	var first = list.children(":first");
	// 结束后移到末尾
	var callback = function() {
		var box = $(this);
		box.parent().children(":last").after(box);
		box.toggle();
	};
	if (list.hasClass("rollUp")) {
		first.slideUp("normal", callback);
	} else if (list.hasClass("rollLeft")) {
		first.animate({
			"width" : "toggle"
		}, "normal", callback);
	} else {
		first.hide("normal", callback);
	}
	setTimeout("roll('" + id + "'," + interval + ")", interval);
}

/**
 * 双向移动
 * 
 * @param id
 *            标识
 * @param dir
 *            方向
 */
function move(id, dir) {
	var list = $(id, $(this).parents("[id]:first"));
	// 执行中返回
	if (list.prop("on"))
		return;
	list.prop("on", 1);
	// 右移
	if (dir == "right") {
		var box = list.children(":last");
		box.toggle();
		list.children(":first").before(box);
		box.animate({
			"width" : "toggle"
		}, "normal", function() {
			var item = $(this);
			item.parent().prop("on", 0);
			item.click();
		});
		return;
	}
	// 左移
	if (dir == "left") {
		var box = list.children(":first");
		var width = box.width();
		box.animate({
			"width" : "toggle"
		}, "normal", function() {
			var item = $(this);
			item.parent().prop("on", 0);
			item.next().click();
			item.parent().children(":last").after(this);
			item.toggle();
		});
		return;
	}
	// 下移
	if (dir == "down") {
		var box = list.children(":last");
		box.toggle();
		list.children(":first").before(box);
		box.slideDown("normal", function() {
			var item = $(this);
			item.parent().prop("on", 0);
			item.click();
		});
		return;
	}
	// 上移
	if (dir == "up") {
		var box = list.children(":first");
		box.slideUp("normal", function() {
			var item = $(this);
			item.parent().prop("on", 0);
			item.next().click();
			item.parent().children(":last").after(this);
			item.toggle();
		});
	}
}

/* 计时队列 */
timing = {};

/**
 * 主函数
 */
function home() {
	$("body").css({
		"font-size" : $("#cFont").val()
	});
	var range = $(this).attr("id");
	if (!range) {
		range = "";
	} else {
		range = "#" + range;
	}
	var box;
	// 下拉列表
	box = $(range + " select");
	box.change(Function("select.call(this);"));
	// 活页标签
	box = $(range + " .tag:visible li");
	box.mouseover(Function("pick.call(this, 'normal', false);"));
	box.first().mouseover();
	// 滚动列表
	box = $(range + " [class^=roll]:visible");
	box.mouseover(Function("this.on = 1;"));
	box.mouseout(Function("this.on = 0;"));
	box.each(function(i) {
		if (!range || !timing[range]) {
			timing[range] = true;
			setTimeout("roll('" + range + " [class^=roll]:visible:eq(" + i
					+ ")', msg.timeout);", msg.timeout);
		}
	});
	// 选择列表
	box = $(range + " [class^=pick]:visible");
	box.each(function(i) {
		var item = $(this);
		item.children("li").click(Function("pick.call(this, false, true)"));
		item.children("li:first").click();
	});
	// 输入区域
	$(range + " .input").focus(Function("hint.call(this, true)"));
	$(range + " .input").change(Function("hint.call(this)"));
	$(range + " .input").blur(Function("hint.call(this)"));
	// 检验未通过
	box = $("#" + $(".checked", this).val());
	box.addClass("checked");
	box.focus(Function("$(this).removeClass('checked');"));
	// 语法高亮
	//SyntaxHighlighter.all();
	baislide.loader = $(this);
	baislide.add();
	baipac.add($(this));
}

$(document).ready(Function("home.call($('body'));"));
