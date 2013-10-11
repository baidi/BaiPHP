<?php
$service = $this->target['service'];
$adata = $this->target[Flow::ACTION];
?>
<div class="box">
	<div class="t">
		<a class="fr tg" onclick="basinCreate(/\$abasin\$/g)"><?php Lang::cut('create'); ?></a>
		<span><?php Lang::cut('basin'); ?></span>
	</div>
	<div id="basin-list">
<?php
foreach ($adata as $item => $mode) {
	$url = $this->url('flow', $service, 'abasin='.$item);
	$operation = $mode ? '<a class="button" onclick="basinUpdate.call(this, \''.$item.'\')">'.Lang::cut('update', 0).'</a> <a class="button" onclick="basinDelete.call(this, \''.$item.'\')">'.Lang::cut('delete', 0).'</a>' : '';
	echo
<<<ITEM
		<div class="text item">
			<input type="hidden" name="abasin" class="input" value="$item" />
			<a class="b" href="$url">$item</a>
			<span class="fr">
				$operation
			</span>
		</div>
ITEM;
}
?>
	</div>
</div>
<div class="q row">
	<span class="tg b">::</span>
	<span>流域是相关流程的集合，主要包含php、js、css、img、Action、Page等目录，初始系统包含bai、service和admin三个流域。</span>
</div>
<?php
$urlCreate = $this->url('basinCreate', $service);
$urlnUpdate = $this->url('basinUpdate', $service);
$urlDelete = $this->url('basinDelete', $service);
$urlFlow = $this->url('flow', $service, '&abasin=$abasin$');
?>
<div class="tpl">
	<div class="text item tpl-basin">
		<input type="hidden" name="abasin" class="input" value="$abasin$" />
		<a class="b" href="<?php echo $urlFlow; ?>">$abasin$</a>
		<span class="fr">
			<a class="button" onclick="basinUpdate.call(this, '$abasin$')"><?php Lang::cut('update'); ?></a>
			<a class="button" onclick="basinDelete.call(this, '$abasin$')"><?php Lang::cut('delete'); ?></a>
		</span>
	</div>
	<div class="text tpl-basinCreate">
		<label><?php Lang::cut('basin-name'); ?></label>
		<?php echo Input::cut('abasin', array('event' => 'basinCreate', 'class' => 'w300')); ?>
	</div>
	<div class="text tpl-basinUpdate">
		<label><?php Lang::cut('basin-name'); ?></label>
		<?php echo Input::cut('abasin', array('event' => 'basinUpdate', 'class' => 'w300')); ?>
		<?php echo Input::cut('obasin', array('event' => 'basinUpdate'), 'hidden'); ?>
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
	var title = '<?php Lang::cut('basin-create'); ?>';
	var content = bai.pick('.tpl .tpl-basinCreate').cloneNode(true);
	var url = '<?php echo $urlCreate; ?>';
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
				return true;
			}
			bai.bubble(data);
		});
	});
};
var basinUpdate = function(basin) {
	var title = '<?php Lang::cut('basin-update'); ?>';
	var content = bai.pick('.tpl .tpl-basinUpdate').cloneNode(true);
	content.pick('.input[name=abasin]', 1).value = basin;
	content.pick('.input[name=obasin]', 1).value = basin;
	var url = '<?php echo $urlnUpdate; ?>';
	var view = this.pick('.item', -1);
	bai.bubble(content, title, function(e) {
		var check = bai.check(content);
		if (! check || check.input != null) {
			return false;
		}
		var abasin = content.pick('.input[name=abasin]', 1).value;
		bai.ajax(url, check.result, function(data) {
			if (data && data.status) {
				var last = bai.pick('.tpl .tpl-basin').cloneNode(true);
				last.innerHTML = last.innerHTML.replace(/\$abasin\$/g, abasin);
				bai.pick('#basin-list').replaceChild(last, view);
				return true;
			}
			bai.bubble(data);
		});
	});
};
var basinDelete = function(basin) {
	var title = '<?php Lang::cut('basin-delete'); ?>';
	var content = bai.pick('.tpl .tpl-basinDelete').cloneNode(true);
	var url = '<?php echo $urlDelete; ?>' + '&abasin=' + basin;
	var view = this.pick('.item', -1);
	bai.bubble(content, title, function(e) {
		bai.ajax(url, function(data) {
			if (data && data.status) {
				view.parentNode.removeChild(view);
				return true;
			}
			bai.bubble(data);
		});
	});
};
//-->
</script>
