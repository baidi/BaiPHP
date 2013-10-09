<?php
$service = $this->target['service'];
$adata = $this->target[Flow::ACTION];
$basinCreate = $this->url('basinCreate', $service);
$basinDelete = $this->url('basinDelete', $service);
?>
<script type="text/javascript">
<!--
var basinCreate = function(basin) {
	var content = bai.pick('.bubble .content');
	var check = bai.check(content);
	if (! check || check.input != null) {
		return false;
	}
	bai.ajax('<?php echo $basinCreate; ?>', check.result, function(data, url) {
		if (bai.is(data, bai.is.JSON)) {
			if (! data.status) {
				content.innerHTML = data.notice || bai.message($name, 'failure');
				return false;
			}
			var tpl = bai.pick('.tpl', 1);
			tpl.parentNode.appendChild(tpl.cloneNode(true));
			return true;
		}
		result.innerHTML = data;
		return false;
	});

};
var basinDelete = function(basin, e) {
	var item = this;
	var msg = '<?php echo Log::logs('delete', 'BasinDeleteAction', Log::FETCH); ?>';
	bai.bubble(msg, '删除流域', function(e) {
		var content = bai.pick('.bubble .content');
		bai.ajax('<?php echo $basinDelete; ?>' + '&abasin=' + basin, function(data, url) {
			if (bai.is(data, bai.is.JSON)) {
				if (! data.status) {
					content.innerHTML = data.notice || bai.message($name, 'failure');
					return false;
				}
				item.parentNode.removeChild(item);
				return true;
			}
			result.innerHTML = data;
			return false;
		});
		return false;
	});
};
//-->
</script>
<div class="box">
	<div class="t">
		<span>流域</span>
		<a class="fr tg" onclick="bai.bubble('<?php echo $basinCreate; ?>', '新建流域', '<?php echo $basinCreate; ?>')">新建</a>
	</div>
	<div id="services">
		<div class="text item tpl">
			<input type="text" name="aservice" value="" />
			<span class="w100 fr">
				<a href="">查看</a>
				<a href="">变更</a>
				<a href="">删除</a>
			</span>
		</div>
<?php
foreach ($adata as $item => $mode) {
	$url = $this->url('flow', $service, 'abasin='.$item);
	$operation = $mode ? '<a>修改</a> <a>删除</a>' : '';
	echo
<<<BOX
		<div class="text item" onclick="basinDelete.call(this, '$item', event)">
			<input type="hidden" id="abasin" name="abasin" class="input" value="$item" />
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
