var msg={addHome:"设为主页失败，请手动设置……",addFavor:"加入收藏失败，请按下Ctrl+D手动收藏……",timeout:3000};function addHome(){try{if(window.opera){this.title=document.title;this.href=location.href}else if(document.all){document.body.style.behavior="url(#default#homepage)";document.body.setHomePage(document.location)}else if(window.sidebar){if(window.netscape){netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect")};var prefs=Components.classes["@mozilla.org/preferences-service;1"].getService(Components.interfaces.nsIPrefBranch);prefs.setCharPref("browser.startup.homepage",document.location)}else{alert(msg.addHome)}}catch(e){alert(msg.addHome)}};function addFavor(){try{if(document.all){window.external.AddFavorite(document.location,document.title)}else if(window.sidebar){window.sidebar.addPanel(document.title,document.location,"")}else{alert(msg.addFavor)}}catch(e){alert(msg.addFavor)}};function select(){if(this.id=="cFont"){$("body").css({"font-size":this.value});$.post("/",{"event":this.id,"cFont":this.value});return};if(this.id=="cStyle"){$.post("/",{"event":this.id,"cStyle":this.value},function(data){window.location.reload()});return}};function pick(speed,load){var box=$(this);if(box.hasClass("on"))return false;var last=box.siblings(".on");last.removeClass("on");box.addClass("on");if(!load){if(speed){box.children("div").slideDown(speed);last.children("div").fadeOut(speed);return false};last.children("div").toggle();box.children("div").toggle();return false};var loader=$("#_"+box.parents("[id]:first").attr("id"));var picker=box.children("div:hidden:first");if(picker.length==0){var pickId=box.attr("id");if(!pickId){pickId=box.children("div[id]:first").attr("id")};if(!pickId){loader.html(box.html());return false};loader.load(pickId,function(data){picker=$('<div>'+data+"</div>");picker.hide();box.append(picker);home.call(loader)});return false};if(!speed){loader.html(picker.html());home.call(loader);return false};loader.fadeOut(speed,function(){loader.html(picker.html());loader.fadeIn(speed);home.call(loader)});return false};function roll(id,interval){var list=$(id);if(list.prop("on")){setTimeout("roll('"+id+"',"+interval+")",interval);return};var first=list.children(":first");var callback=function(){var box=$(this);box.parent().children(":last").after(box);box.toggle()};if(list.hasClass("rollUp")){first.slideUp("normal",callback)}else if(list.hasClass("rollLeft")){first.animate({"width":"toggle"},"normal",callback)}else{first.hide("normal",callback)};setTimeout("roll('"+id+"',"+interval+")",interval)};function move(id,dir){var list=$(id,$(this).parents("[id]:first"));if(list.prop("on"))return;list.prop("on",1);if(dir=="right"){var box=list.children(":last");box.toggle();list.children(":first").before(box);box.animate({"width":"toggle"},"normal",function(){var item=$(this);item.parent().prop("on",0);item.click()});return};if(dir=="left"){var box=list.children(":first");var width=box.width();box.animate({"width":"toggle"},"normal",function(){var item=$(this);item.parent().prop("on",0);item.next().click();item.parent().children(":last").after(this);item.toggle()});return};if(dir=="down"){var box=list.children(":last");box.toggle();list.children(":first").before(box);box.slideDown("normal",function(){var item=$(this);item.parent().prop("on",0);item.click()});return};if(dir=="up"){var box=list.children(":first");box.slideUp("normal",function(){var item=$(this);item.parent().prop("on",0);item.next().click();item.parent().children(":last").after(this);item.toggle()})}};timing={};function home(){$("body").css({"font-size":$("#cFont").val()});var range=$(this).attr("id");if(!range){range=""}else{range="#"+range};var box;box=$(range+" select");box.change(Function("select.call(this);"));box=$(range+" .tag:visible li");box.mouseover(Function("pick.call(this, 'normal', false);"));box.first().mouseover();box=$(range+" [class^=roll]:visible");box.mouseover(Function("this.on = 1;"));box.mouseout(Function("this.on = 0;"));box.each(function(i){if(!range||!timing[range]){timing[range]=true;setTimeout("roll('"+range+" [class^=roll]:visible:eq("+i+")', msg.timeout);",msg.timeout)}});box=$(range+" [class^=pick]:visible");box.each(function(i){var item=$(this);item.children("li").click(Function("pick.call(this, false, true)"));item.children("li:first").click()});$(range+" .input").focus(Function("hint.call(this, true)"));$(range+" .input").change(Function("hint.call(this)"));$(range+" .input").blur(Function("hint.call(this)"));box=$("#"+$(".checked",this).val());box.addClass("checked");box.focus(Function("$(this).removeClass('checked');"));baislide.loader=$(this);baislide.add();baipac.add($(this))};$(document).ready(Function("home.call($('body'));"));