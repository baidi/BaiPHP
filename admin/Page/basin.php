<?php
$service = $this->target['service'];
$adata = $this->target[Flow::ACTION];
$basinCreate = $this->url('basinCreate', $service);
$basinChange = $this->url('basinChange', $service);
$basinDelete = $this->url('basinDelete', $service);
$flow = $this->url('flow', $service);
?>
<div class="box">
	<div class="t">
		<span>流域</span>
		<a class="fr tg" onclick="basinCreate(/\$abasin\$/g)">新建</a>
	</div>
	<div id="basin-list">
<?php
foreach ($adata as $item => $mode) {
	$url = $this->url('flow', $service, 'abasin='.$item);
	$operation = $mode ? '<a onclick="basinChange.call(this, \''.$item.'\')">修改</a> <a onclick="basinDelete.call(this, \''.$item.'\')">删除</a>' : '';
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
<div class="tpl">
	<div class="text item tpl-basin">
		<input type="hidden" name="abasin" class="input" value="$abasin$" />
		<a class="b" href="<?php echo $flow; ?>&abasin=$abasin$">$abasin$</a>
		<span class="w100 fr">
			<a href="<?php echo $flow; ?>&abasin=$abasin$">查看</a>
			<a onclick="basinChange.call(this, '$abasin$')">修改</a>
			<a onclick="basinDelete.call(this, '$abasin$')">删除</a>
		</span>
	</div>
	<div class="text tpl-basinCreate">
		<label>流域名：</label>
		<?php echo Input::cut('abasin', array('event' => 'basinCreate', 'class' => 'w300')); ?>
	</div>
	<div class="text tpl-basinChange">
		<label>流域名：</label>
		<?php echo Input::cut('abasin', array('event' => 'basinChange', 'class' => 'w300')); ?>
		<?php echo Input::cut('obasin', array('event' => 'basinChange'), 'hidden'); ?>
		<div class="q text">
			<span class="tg b">::</span>
			<span>仅仅修改该流域的目录名，而不会对流域下的文件进行检查和修改。</span>
		</div>
	</div>
	<div class="tpl-basinDelete">
		<p>确定要删除该流域？</p>
		<p>该流域目录下的所有内容都会被清空，并且无法恢复！</p>
	</div>
</div>
<script type="text/javascript">
<!--
var basinCreate = function(basin) {
	var title = '新建流域';
	var content = bai.pick('.tpl .tpl-basinCreate').cloneNode(true);
	var url = '<?php echo $basinCreate; ?>';
	bai.bubble(content, title, function(e) {
		var check = bai.check(content);
		if (! check || check.input != null) {
			return false;
		}
		var abasin = content.pick('.input[name=abasin]', 1).value;
		bai.ajax(url, check.result, function(data) {
			if (data && data.status) {
				var view = bai.pick('.tpl .tpl-basin').cloneNode(true);
				view.innerHTML = view.innerHTML.replace(basin, abasin);
				bai.pick('#basin-list').appendChild(view);
			}
		});
	});
};
var basinChange = function(basin) {
	var title = '修改流域';
	var content = bai.pick('.tpl .tpl-basinChange').cloneNode(true);
	content.pick('.input[name=abasin]', 1).value = basin;
	content.pick('.input[name=obasin]', 1).value = basin;
	var url = '<?php echo $basinChange; ?>';
	var view = this.pick('.item', -1);
	bai.bubble(content, title, function(e) {
		var check = bai.check(content);
		if (! check || check.input != null) {
			return false;
		}
		var abasin = content.pick('.input[name=abasin]', 1).value;
		bai.ajax(url, check.result, function(data) {
			if (data && data.status) {
				var changed = bai.pick('.tpl .tpl-basin').cloneNode(true);
				changed.innerHTML = changed.innerHTML.replace(/\$absin\$/, abasin);
				bai.pick('#basin-list').removeChild(view, changed);
			}
		});
	});
};
var basinDelete = function(basin) {
	var title = '删除流域';
	var content = bai.pick('.tpl .tpl-basinDelete').cloneNode(true);
	var url = '<?php echo $basinDelete; ?>' + '&abasin=' + basin;
	var view = this.pick('.item', -1);
	bai.bubble(content, title, function(e) {
		bai.ajax(url, function(data) {
			if (data && data.status) {
				view.parentNode.removeChild(view);
				return true;
			}
		});
	});
};
//-->
</script>
