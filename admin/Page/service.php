<div class="box">
	<div class="t">
		<span>服务一览</span>
		<a class="fr tg" onclick="aservice.call(this)">新建</a>
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
	<span class="tg b">&raquo;</span>
	新建服务主要包含php、js、css、img、Action、Page等目录，其中Action目录用于存放事件处理流程，Page目录用于存放事件页面内容。
</div>
<table class="screen cv"><tr><td>
	<div class="dialog s">
		<div class="t bd tg">标题</div>
		<div class="text tl">
			<label>请输入服务名：</label>
			<div>
				<input type="text" id="aservice" name="aservice" />
			</div>
		</div>
		<div class="e bl tr">
			<a>确定</a>
			<a>取消</a>
		</div>
	</div>
</td></tr></table>

<script type="text/javascript">
<!--
function aservice(service) {
	var tpl = $('#services .tpl').clone();
	tpl.removeClass('tpl');
	$('#services').append(tpl);
}
//-->
</script>
