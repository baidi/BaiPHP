<?php
$service = $this->target['service'];
$adata = $this->target[Flow::ACTION];
$basinCreate = $this->url('basinCreate', $service);
$basinDelete = $this->url('basinDelete', $service);
$flow = $this->url('flow', $service);
?>
<script type="text/javascript">
<!--
var basinCreate = function(basin) {
	var title = '新建流域';
	var url = '<?php echo $basinCreate; ?>';
	bai.bubble(url, title, function(e) {
		var content = bai.pick('.bubble .content');
		var check = bai.check(content);
		if (! check || check.input != null) {
			return false;
		}
		var abasin = content.pick('.input[name=abasin]', 1).value;
		bai.ajax(url, check.result, function(data) {
			if (data && data.status) {
				var tpl = bai.pick('.tpl', 1);
				var item = tpl.cloneNode(true);
				item.innerHTML = item.innerHTML.replace(basin, abasin);
				tpl.parentNode.appendChild(item);
				item.set('class', '-tpl');
				return true;
			}
		});
	});
};
var basinDelete = function(basin) {
	var title = '删除流域';
	var content = this.pick('.item', -1);
	var url = '<?php echo $basinDelete; ?>' + '&abasin=' + basin;
	var msg = '<?php echo Log::logs('delete', 'BasinDeleteAction', Log::FETCH); ?>';
	bai.bubble(msg, title, function(e) {
		bai.ajax(url, function(data) {
			if (data && data.status) {
				content.parentNode.removeChild(content);
				return true;
			}
		});
	});
};
//-->
</script>
<div class="box">
	<div class="t">
		<span>流域</span>
		<a class="fr tg" onclick="basinCreate(/\$abasin\$/g)">新建</a>
	</div>
	<div id="services">
		<div class="text item tpl">
			<input type="hidden" name="abasin" class="input" value="$abasin$" />
			<a class="b" href="<?php echo $flow; ?>&abasin=$abasin$">$abasin$</a>
			<span class="w100 fr">
				<a href="<?php echo $flow; ?>&abasin=$abasin$">查看</a>
				<a>修改</a>
				<a onclick="basinDelete('$abasin$')">删除</a>
			</span>
		</div>
<?php
foreach ($adata as $item => $mode) {
	$url = $this->url('flow', $service, 'abasin='.$item);
	$operation = $mode ? '<a>修改</a> <a onclick="basinDelete.call(this, \''.$item.'\')">删除</a>' : '';
	echo
<<<BOX
		<div class="text item">
			<input type="hidden" name="abasin" class="input" value="$item" />
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
