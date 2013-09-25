<?php
$service = $this->target['service'];
$adata = $this->target[Flow::ACTION];
?>
<div class="box">
	<div class="t">
		<span>流域</span>
		<a class="fr tg" onclick="bai.bubble('<?php echo $this->url('basinCreate', $service); ?>')">新建</a>
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
	<span>流域是相关流程的集合，主要包含php、js、css、img、Action、Page等目录，初始系统包含bai、service和admin三个流域。</span>
</div>
