/*!
 * baislide v1.1.1 基于jQuery1.6+
 * 2012/03/20
 * http://dacbe.com/
 * History: V1.1.1: 修正IE7下切换不正常
 * History: V1.1.0: 改善了对Firefox和Opera的支持
 * Copyright 2012 白晓阳
 * 版权所有，保留一切权力。未经许可，不得用于商业用途。
 */
var bai = {
	/** 版本 */
	version : "1.1.1",
	/** 更新日期 */
	modified : "2012/03/20",
	/** 作者 */
	author : "白晓阳",
	/** 网址 */
	web : "http://www.dacbe.com/",
	/** QQ */
	qq : 58219758,

	/** 横坐标效果集 */
	x : [ 0, "half", "full", "center", "side", {
		name : "x"
	} ],
	/** 纵坐标效果集 */
	y : [ 0, "half", "full", "center", "side", {
		name : "y"
	} ],
	/** 背景横坐标效果集 */
	bx : [ 0, "half", "full", "center", "side", {
		name : "bx"
	} ],
	/** 背景纵坐标效果集 */
	by : [ 0, "half", "full", "center", "side", {
		name : "by"
	} ],
	/** 宽度效果集 */
	w : [ 0, "full", {
		name : "w"
	} ],
	/** 长度效果集 */
	h : [ 0, "full", {
		name : "h"
	} ],
	/** 透明度效果集 */
	o : [ 0, 1, {
		name : "o"
	} ],

	/** 切换目标 */
	baislide : ".baislide",
	/** 控制条 */
	baislidebar : ".baislidebar",
	/** 最大碎片数 */
	pieces : 5,
	/** 切换间隔：毫秒 */
	interval : 5000,
	/** 切换速度：毫秒 */
	speed : 1500,

	/**
	 * 获取切换效果
	 * 
	 * @param item
	 *            效果集
	 * @param no
	 *            效果序号
	 */
	effect : function(item, no) {
		if (item == null || item.length == 0
				|| item[item.length - 1].name == null)
			return 0;
		if (no == null || isNaN(no) || no < 0)
			no = Math.round(Math.random() * (item.length - 2));
		var name = item[item.length - 1].name;
		switch (name) {
		case "x":
		case "w":
			switch (item[no]) {
			case "half":
				return this.pwidth / 2;
			case "full":
				return this.pwidth;
			case "center":
				return this.swidth / 2;
			case "side":
				return this.swidth;
			default:
				return item[no];
			}
		case "y":
		case "h":
			switch (item[no]) {
			case "half":
				return this.pheight / 2;
			case "full":
				return this.pheight;
			case "center":
				return this.sheight / 2;
			case "side":
				return this.sheight;
			default:
				return item[no];
			}
		case "bx":
			switch (item[no]) {
			case "half":
				return -this.pheight / 2;
			case "full":
				return -this.pheight;
			case "center":
				return -this.sheight / 2;
			case "side":
				return -this.sheight;
			default:
				return item[no];
			}
		case "by":
			switch (item[no]) {
			case "half":
				return -this.pheight / 2;
			case "full":
				return -this.pheight;
			case "center":
				return -this.sheight / 2;
			case "side":
				return -this.sheight;
			default:
				return item[no];
			}
		}
		return item[no];
	},

	/**
	 * 加入切换目标
	 * 
	 * @param row
	 *            切换行：默认[1-6]
	 * @param col
	 *            切换列：默认[1-6]
	 * @param accord
	 *            一致性：默认[0]
	 */
	add : function(row, col, accord) {
		if (this.on != null && this.on) {
			setTimeout("bai.add(" + row + "," + col + "," + accord + ")", this.speed);
			return;
		}
		// 切换目标
		var box = $(this.baislide, this.loader);
		var bar = $(this.baislidebar, this.loader);
		if (box.length == 0)
			return;
		if (this.timeout != null)
			clearTimeout(this.timeout);
		// 获取前景
		var fore = box.children(":visible");
		if (fore.length == 0) {
			fore = box.children(":first");
			fore.addClass("on");
			bar.children(":first").addClass("on");
		}
		// 获取一致性
		if (row == null || isNaN(row)) {
			accord = 0;
		} else if (col == null || isNaN(col)) {
			// 行一致
			accord = 1;
		} else {
			if (accord == null || !accord) {
				// 行列一致
				accord = 2;
			} else {
				// 行列方向一致
				accord = 3;
			}
		}
		// 设置长宽、速度、一致性等私用属性值
		this.length = box.children().length;
		this.swidth = fore.prop("offsetWidth");
		this.sheight = fore.prop("offsetHeight");
		this.box = box;
		this.bar = bar;
		this.accord = accord;
		if (accord > 0) {
			this.row = row;
			this.pheight = this.sheight / row;
		}
		if (accord > 1) {
			this.col = col;
			this.pwidth = this.swidth / col;
		}
		// 控制条事件
		bar.children("a").each(function(i) {
			$(this).click(Function("bai.slide(" + i + ")"));
		});
		this.timeout = setTimeout("bai.slide()", this.interval);
	},

	/**
	 * 执行切换
	 * 
	 * @param no
	 *            切换序号
	 */
	slide : function(no) {
		if (this.box == null || this.box.length == 0 || no < 0
				|| no >= this.length)
			return;
		// 当前显示项不切换
		if (no != null && this.bar.children(":eq(" + no + ")").hasClass("on"))
			return;
		// 正在切换时延后
		if (this.on) {
			if (no != null)
				setTimeout("bai.slide(" + no + ")", this.speed);
			else
				this.timeout = setTimeout("bai.slide()", this.interval);
			return;
		}
		this.on = true;
		// 获取前景
		var fore = this.box.children(":visible");
		if (fore.length == 0) {
			fore = this.box.children(":first");
			fore.addClass("on");
			this.bar.children(":first").addClass("on");
		}
		// 获取背景
		var back;
		if (no != null && !isNaN(no)) {
			back = this.box.children(":eq(" + no + ")");
			if (back.css("display") == "block")
				return;
			this.bar.children(".on").removeClass("on");
			this.bar.children(":eq(" + no + ")").addClass("on");
		} else {
			back = fore.next();
			if (back.length == 0)
				back = this.box.children(":first");
			var a = this.bar.children(".on").next();
			if (a.length == 0)
				a = this.bar.children(":first");
			this.bar.children(".on").removeClass("on");
			a.addClass("on");
			this.timeout = setTimeout("bai.slide()", this.interval);
		}

		// 切换
		if (Math.round(Math.random()))
			this.slideOut(fore, back);
		else
			this.slideIn(fore, back);
	},

	/**
	 * 滑动切出
	 * 
	 * @param row
	 *            碎片行
	 * @param col
	 *            碎片列
	 * @param accord
	 *            一致性
	 */
	slideOut : function(fore, back, accord) {
		// 一致性设置：行列
		if (this.accord < 1) {
			this.row = Math.round(Math.random() * 5 + 1);
			this.pheight = this.sheight / this.row;
		}
		if (this.accord < 2) {
			this.col = Math.round(Math.random() * 5 + 1);
			this.pwidth = this.swidth / this.col;
		}
		// 一致性设置：效果
		var accord = (this.accord >= 3);
		var ex = this.effect(this.x);
		var ey = this.effect(this.y);
		var ew = this.effect(this.w);
		var eh = this.effect(this.h);
		var ebx = this.effect(this.bx);
		var eby = this.effect(this.by);
		// var eo = effect(o);
		back.show();
		fore.removeClass("on");
		fore.hide();
		for ( var r = 0; r < this.row; r++) {
			for ( var c = 0; c < this.col; c++) {
				var piece = $("<div class='on'><div>");
				// 初始状态：正常显示
				piece.css({
					"left" : c * this.pwidth,
					"top" : r * this.pheight,
					"width" : this.pwidth,
					"height" : this.pheight,
					"position" : "absolute"
				});
				if (c == 0 && r == 0)
					piece.html(fore.html());
				else
					piece.html(fore.children("img").prop("outerHTML"));
				//alert(piece.html());
				piece.children("img").css({
					"left" : -c * this.pwidth,
					"top" : -r * this.pheight,
					"position" : "absolute"
				});
				back.before(piece);
				// 切换状态
				piece.animate({
					"left" : accord ? ex : this.effect(this.x),
					"top" : accord ? ey : this.effect(this.y),
					"width" : accord ? ew : this.effect(this.w),
					"height" : accord ? eh : this.effect(this.h),
					"opacity" : 0
				// this.accord ? eo : 0
				}, this.speed, function() {
					$(this).remove();
					back.addClass("on");
					bai.on = false;
				});
				piece.children("img").animate({
					"left" : accord ? ebx : this.effect(this.bx),
					"top" : accord ? eby : this.effect(this.by)
				}, this.speed);
			}
		}
	},

	/**
	 * 滑动切入
	 * 
	 * @param row
	 *            碎片行
	 * @param col
	 *            碎片列
	 * @param accord
	 *            一致性
	 */
	slideIn : function(fore, back, accord) {
		// 一致性设置：行列
		if (this.accord < 1) {
			this.row = Math.round(Math.random() * 5 + 1);
			this.pheight = this.sheight / this.row;
		}
		if (this.accord < 2) {
			this.col = Math.round(Math.random() * 5 + 1);
			this.pwidth = this.swidth / this.col;
		}
		// 一致性设置：效果
		var accord = (this.accord >= 3);
		var ex = this.effect(this.x);
		var ey = this.effect(this.y);
		var ew = this.effect(this.w);
		var eh = this.effect(this.h);
		var ebx = this.effect(this.bx);
		var eby = this.effect(this.by);
		// var eo = effect(o);
		fore.removeClass("on");
		fore.show();
		for ( var r = 0; r < this.row; r++) {
			for ( var c = 0; c < this.col; c++) {
				var piece = $("<div class='on'><div>");
				// 初始状态
				piece.css({
					"left" : accord ? ex : this.effect(this.x),
					"top" : accord ? ey : this.effect(this.y),
					"width" : accord ? ew : this.effect(this.w),
					"height" : accord ? eh : this.effect(this.h),
					"opacity" : 0, // this.accord ? eo : 0,
					"position" : "absolute"
				});
				if (c == 0 && r == 0)
					piece.html(back.html());
				else
					piece.html(back.children("img").prop("outerHTML"));
				piece.children("img").css({
					"left" : accord ? ebx : this.effect(this.bx),
					"top" : accord ? eby : this.effect(this.by),
					"position" : "absolute"
				});
				fore.before(piece);
				// 目标状态：完全显示
				piece.animate({
					"left" : c * this.pwidth,
					"top" : r * this.pheight,
					"width" : this.pwidth,
					"height" : this.pheight,
					"opacity" : 1
				}, this.speed, function() {
					$(this).remove();
					back.addClass("on");
					back.show();
					fore.hide();
					bai.on = false;
				});
				piece.children("img").animate({
					"left" : -c * this.pwidth,
					"top" : -r * this.pheight
				}, this.speed);
			}
		}
	}
};