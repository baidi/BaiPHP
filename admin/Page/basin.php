<div class="box">
	<div class="t">
		<span>流域</span>
		<a class="fr tg" onclick="dialog.call(this)">新建</a>
	</div>
	<div id="services">
		<div class="text item tpl">
			<input type="text" name="aservice" value="" />
			<span class="w100 fr">
				<a href="">保存</a>
				<a href="">删除</a>
			</span>
		</div>
<?php
$adata = $this->target[Flow::ACTION];
$service = $this->target['service'];
foreach ($adata as $item => $mode) {
	$url = $this->url('event', $service, 'aservice='.$item);
	$operation = $mode ? '<a>修改</a> <a>删除</a>' : '';
	echo
<<<BOX
		<div class="text item">
			<input type="hidden" id="aservice" name="aservice" value="$item" />
			<a class="b" href="$url">$item</a>
			<span class="w100 fr">
				<a href="$url">查看</a> $operation
			</span>
		</div>
BOX;
}
?>
	</div>
</div>
<div class="q row">
	<span class="tg b">::</span>
	<span>流域是相关流程的集合，主要包含php、js、css、img、Action（事件处理流程）、Page（页面内容页面内容）等目录，初始系统包含bai、service和admin三个流域。</span>
</div>
<table class="screen h"><tr><td>
	<div class="dialog s">
		<div class="dialog-title t ad tg">新建流域</div>
		<div class="dialog-content text tl">
			<label>流域名：</label>
			<input type="text" id="aservice" name="aservice" />
		</div>
		<div class="dialog-toolbar-okcancel f al">
			<a class="button default" onclick="bai.pick('.screen', 1).set('class', '+h');">确定</a>
			<a class="button" onclick="bai.pick('.screen', 1).set('class', '+h');">取消</a>
		</div>
	</div>
</td></tr></table>

<script type="text/javascript">
<!--
function dialog(title, content) {
	var screen = bai.pick('.screen', 1);
	var dialog = screen.pick('.dialog', 1);
	if (title) {
		dialog.pick('.dialog-title', 1).set('innerHTML', title);
	}
	if (content) {
		dialog.pick('.dialog-content', 1).set('innerHTML', content);
	}
	screen.set('class', '-h');
}
//-->
</script>
