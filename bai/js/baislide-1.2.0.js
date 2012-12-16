/**
 * baislide Bai简单切换
 * 基于jQuery1.6+
 * 版权所有，保留一切权力。未经许可，不得用于商业用途。
 * 
 * @author		白晓阳
 * @copyright	Copyright (c) 2011 - 2012, 白晓阳
 * @link		http://www.baicode.net
 * @version     V1.2.0 2012/03/22 增加圆角特效，需要CSS3支持
 *              V1.1.1 2012/03/20 修正IE7下切换不正常
 *              V1.1.0 2012/03/19 改善了对Firefox和Opera的支持
 *              V1.0.0 2012/03/18 首版
 */
var baislide = {
	/** 版本 */
	version : "1.2.0",
	/** 更新日期 */
	date : "2012/03/22",
	/** 作者 */
	author : "白晓阳",
	/** 网址 */
	link : "http://www.baicode.net/",

	/** 作用域 */
	loader: undefined,
	/** 切换域 */
	slidebox : ".baislidebox",
	/** 控制条 */
	slidebar : ".baislidebar",
	/** 切换速度：毫秒 */
	speed : 1500,
	/** 切换间隔：毫秒 */
	interval : 5000,
	/*** 切换计时句柄 */
	timeout : undefined,
	/** 最大碎片数 */
	pieces : 5,

	/** 横坐标效果集 */
	x : [ 0, "half", "full", "center", "side", {
		name : "x"
	} ],
	/** 纵坐标效果集 */
	y : [ 0, "half", "full", "center", "side", {
		name : "y"
	} ],
	/** 宽度效果集 */
	w : [ 0, "half", "full", {
		name : "w"
	} ],
	/** 长度效果集 */
	h : [ 0, "half", "full", {
		name : "h"
	} ],
	/** 圆角半径效果集 */
	r : [ 0, "half", "full", {
		name : "r"
	} ],
	/** 背景横坐标效果集 */
	bx : [ 0, "half", "full", "center", "side", {
		name : "bx"
	} ],
	/** 背景纵坐标效果集 */
	by : [ 0, "half", "full", "center", "side", {
		name : "by"
	} ],
	/** 透明度效果集 */
	o : [ 0, 1, {
		name : "o"
	} ],

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
			no = Math.ceil(Math.random() * (item.length - 1) - 1);
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
		case "r":
			switch (item[no]) {
			case "half":
				return this.pwidth > this.pheight ? this.pwidth / 2 : this.pheight / 2;
			case "full":
				return this.pwidth > this.pheight ? this.pwidth : this.pheight;
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
	 * 添加魔术切换域
	 * 
	 * @param row
	 *            切换行：默认[1-6]
	 * @param col
	 *            切换列：默认[1-6]
	 * @param accord
	 *            一致性：默认[0]
	 *            0：完全随机
	 *            1：行指定
	 *            2：行列指定
	 *            3：行列指定，方向一致
	 */
	add : function(row, col, accord) {
		// 正在切换时延后
		if (this.on != null && this.on) {
			setTimeout("baislide.add(" + row + "," + col + "," + accord + ")", this.speed);
			return;
		}
		// 取得切换域
		var box = $(this.slidebox, this.loader);
		var bar = $(this.slidebar, this.loader);
		if (box.length == 0)
			return;
		// 清除切换计时句柄
		if (this.timeout != null)
			clearTimeout(this.timeout);
		// 获取前景
		var fore = box.children(":visible");
		if (fore.length == 0) {
			fore = box.children(":first");
			fore.addClass("on");
			bar.children(":first").addClass("on");
		}
		// 设置一致性
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
		this.swidth = fore[0].offsetWidth;
		this.sheight = fore[0].offsetHeight;
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
			$(this).click(Function("baislide.slide(" + i + ")"));
		});
		this.timeout = setTimeout("baislide.slide()", this.interval);
	},

	/**
	 * 执行魔术切换
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
				setTimeout("baislide.slide(" + no + ")", this.speed);
			else
				this.timeout = setTimeout("baislide.slide()", this.interval);
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
			//if (back.css("display") == "block")
			//	return;
			this.bar.children(".on").removeClass("on");
			this.bar.children(":eq(" + no + ")").addClass("on");
		} else {
			back = fore.next();
			if (back.length == 0)
				back = this.box.children(":first");
			var ba = this.bar.children(".on").next();
			if (ba.length == 0)
				ba = this.bar.children(":first");
			this.bar.children(".on").removeClass("on");
			ba.addClass("on");
			this.timeout = setTimeout("baislide.slide()", this.interval);
		}

		// 切换
		if (Math.round(Math.random()))
			this.slideOut(fore, back);
		else
			this.slideIn(fore, back);
	},

	/**
	 * 魔术切换：切出
	 * 
	 * @param row
	 *            切换行
	 * @param col
	 *            切换列
	 * @param accord
	 *            一致性
	 */
	slideOut : function(fore, back, accord) {
		// 一致性设置：行列
		if (this.accord < 1) {
			this.row = Math.ceil(Math.random() * this.pieces);
			this.pheight = this.sheight / this.row;
		}
		if (this.accord < 2) {
			this.col = Math.ceil(Math.random() * this.pieces);
			this.pwidth = this.swidth / this.col;
		}
		// 一致性设置：效果
		var accord = (this.accord >= 3);
		var ex = this.effect(this.x);
		var ey = this.effect(this.y);
		var ew = this.effect(this.w);
		var eh = this.effect(this.h);
		var er = this.effect(this.r);
		var ebx = this.effect(this.bx);
		var eby = this.effect(this.by);
		// var eo = effect(o);
		back.show();
		fore.removeClass("on");
		fore.hide();
		for ( var r = 0; r < this.row; r++) {
			for ( var c = 0; c < this.col; c++) {
				var piece = $("<div class='on'><div>");
				// 设置碎片初始状态：正常显示
				piece.css({
					"left" : c * this.pwidth,
					"top" : r * this.pheight,
					"width" : this.pwidth,
					"height" : this.pheight,
					"border-radius" : 0,
					"position" : "absolute"
				});
				// 设置碎片内容
				if (c == 0 && r == 0)
					piece.html(fore.html());
				else
					piece.html(fore.children("img").prop("outerHTML"));
				//alert(piece.html());
				// 设置图片初始状态
				piece.children("img").css({
					"left" : -c * this.pwidth,
					"top" : -r * this.pheight,
					"position" : "absolute"
				});
				back.before(piece);
				// 碎片切换
				piece.animate({
					"left" : accord ? ex : this.effect(this.x),
					"top" : accord ? ey : this.effect(this.y),
					"width" : accord ? ew : this.effect(this.w),
					"height" : accord ? eh : this.effect(this.h),
					"border-radius" : accord ? er : this.effect(this.r),
					"opacity" : 0
				// this.accord ? eo : 0
				}, this.speed, function() {
					$(this).remove();
					back.addClass("on");
					baislide.on = false;
				});
				// 图片切换
				piece.children("img").animate({
					"left" : accord ? ebx : this.effect(this.bx),
					"top" : accord ? eby : this.effect(this.by),
				}, this.speed);
			}
		}
	},

	/**
	 * 魔术切换：切入
	 * 
	 * @param row
	 *            切换行
	 * @param col
	 *            切换列
	 * @param accord
	 *            一致性
	 */
	slideIn : function(fore, back, accord) {
		// 一致性设置：行列
		if (this.accord < 1) {
			this.row = Math.ceil(Math.random() * this.pieces);
			this.pheight = this.sheight / this.row;
		}
		if (this.accord < 2) {
			this.col = Math.ceil(Math.random() * this.pieces);
			this.pwidth = this.swidth / this.col;
		}
		// 一致性设置：效果
		var accord = (this.accord >= 3);
		var ex = this.effect(this.x);
		var ey = this.effect(this.y);
		var ew = this.effect(this.w);
		var eh = this.effect(this.h);
		var er = this.effect(this.r);
		var ebx = this.effect(this.bx);
		var eby = this.effect(this.by);
		// var eo = effect(o);
		fore.removeClass("on");
		fore.show();
		for ( var r = 0; r < this.row; r++) {
			for ( var c = 0; c < this.col; c++) {
				var piece = $("<div class='on'><div>");
				// 设置碎片初始状态
				piece.css({
					"left" : accord ? ex : this.effect(this.x),
					"top" : accord ? ey : this.effect(this.y),
					"width" : accord ? ew : this.effect(this.w),
					"height" : accord ? eh : this.effect(this.h),
					"border-radius" : accord ? er : this.effect(this.r),
					"opacity" : 0, // this.accord ? eo : 0,
					"position" : "absolute"
				});
				// 设置碎片内容
				if (c == 0 && r == 0)
					piece.html(back.html());
				else
					piece.html(back.children("img").prop("outerHTML"));
				// 设置图片初始状态
				piece.children("img").css({
					"left" : accord ? ebx : this.effect(this.bx),
					"top" : accord ? eby : this.effect(this.by),
					"position" : "absolute"
				});
				fore.before(piece);
				// 碎片切换
				piece.animate({
					"left" : c * this.pwidth,
					"top" : r * this.pheight,
					"width" : this.pwidth,
					"height" : this.pheight,
					"border-radius" : 0,
					"opacity" : 1
				}, this.speed, function() {
					$(this).remove();
					back.addClass("on");
					back.show();
					fore.hide();
					baislide.on = false;
				});
				// 图片切换
				piece.children("img").animate({
					"left" : -c * this.pwidth,
					"top" : -r * this.pheight
				}, this.speed);
			}
		}
	}
};
