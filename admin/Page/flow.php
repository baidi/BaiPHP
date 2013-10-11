<?php
$service = $this->target['service'];
$abasin = $this->target['abasin'];
$urlBack = $this->url('basin', $service);
$lblCreate = Lang::cut('create', 0);
$lblUpdate = Lang::cut('update', 0);
$lblDelete = Lang::cut('delete', 0);
?>
<div class="box">
	<div class="t">
		<a class="fr tg" onclick="flowCreate(/\$aevent\$/g)"><?php echo $lblCreate; ?></a>
		<a href="<?php echo $urlBack; ?>"><?php echo $abasin; ?></a>
		<span class="tg">&raquo;</span>
		<span><?php Lang::cut('flow'); ?></span>
	</div>
	<div id="flow-list">
<?php
$adata = $this->target[Flow::ACTION];
foreach ($adata as $item => $value) {
	$actionFile = $this->pick(Flow::ACTION, $value);
	$actionAction = $actionFile == null ? $lblCreate : $lblDelete;
	$pageFile = $this->pick(Flow::PAGE, $value);
	$pageAction = $pageFile == null ? $lblCreate : $lblDelete;
	$url = $this->url('process', $service, 'abasin=' . $abasin . '&aevent=' . $item);
	echo
<<<ITEM
		<div class="text item">
			<span class="w2">
				<a class="b" href="$url">$item</a>
			</span>
			<span class="w3">
				$actionFile
				<a>$actionAction</a>
			</span>
			<span class="w2">
				$pageFile
				<a>$pageAction</a>
			</span>
			<span class="fr">
				<a class="button" onclick="flowUpdate.call(this, '$item')">$lblUpdate</a>
				<a class="button" onclick="flowDelete.call(this, '$item')">$lblDelete</a>
			</span>
		</div>
ITEM;
}
?>
	</div>
</div>
<div class="q row">
	<span class="tg b">::</span>
	流程主要基于页面内容（/流域名/Page/事件名.php）和（或）处理内容（/流域名/Action/事件名Action.php）等文件。
</div>
<?php
$urlCreate = $this->url('flowCreate', $service, 'abasin=' . $abasin);
$urlnUpdate = $this->url('flowUpdate', $service, 'abasin=' . $abasin);
$urlDelete = $this->url('flowDelete', $service, 'abasin=' . $abasin);
$urlProcess = $this->url('process', $service, 'abasin=' . $abasin . '&aevent=$aevent$');
?>
<div class="tpl">
	<div class="text item tpl-flow">
		<span class="w2">
			<a class="b" href="<?php echo $urlProcess; ?>">$aevent$</a>
		</span>
		<span class="w3">
			<span class="flow-action">$aevent$Action.php</span>
			<a><?php echo $lblDelete; ?></a>
		</span>
		<span class="w2">
			<span>$aevent$.php</span>
			<a><?php echo $lblDelete; ?></a>
		</span>
		<span class="fr">
			<a class="button" onclick="flowUpdate.call(this, '$aevent$')"><?php echo $lblUpdate; ?></a>
			<a class="button" onclick="flowDelete.call(this, '$aevent$')"><?php echo $lblDelete; ?></a>
		</span>
	</div>
	<div class="text tpl-flowCreate">
		<label><?php Lang::cut('flow-name'); ?></label>
		<?php echo Input::cut('aevent', array('event' => 'flowCreate', 'class' => 'w300')); ?>
	</div>
	<div class="text tpl-flowUpdate">
		<label><?php Lang::cut('flow-name'); ?></label>
		<?php echo Input::cut('aevent', array('event' => 'flowUpdate', 'class' => 'w300')); ?>
		<?php echo Input::cut('oevent', array('event' => 'flowUpdate'), 'hidden'); ?>
		<div class="q text">
			<span class="tg b">::</span>
			<span>仅仅修改该流程对应的文件名与类名，而不做其他检查和修改。</span>
		</div>
	</div>
	<div class="tpl-flowDelete">
		<p>确定要删除该流程？</p>
		<p>该流程对应的所有文件都会被删除，并且无法恢复！</p>
	</div>
</div>
<script type="text/javascript">
<!--
var flowCreate = function(event) {
	var title = '<?php Lang::cut('flow-create'); ?>';
	var content = bai.pick('.tpl .tpl-flowCreate').cloneNode(true);
	var url = '<?php echo $urlCreate; ?>';
	bai.bubble(content, title, function(e) {
		var check = bai.check(content);
		if (! check || check.input != null) {
			return false;
		}
		var aevent = content.pick('.input[name=aevent]', 1).value;
		bai.ajax(url, check.result, function(data) {
			if (data && data.status) {
				var view = bai.pick('.tpl .tpl-flow').cloneNode(true);
				view.innerHTML = view.innerHTML.replace(event, aevent);
				var action = view.pick('.flow-action', 1);
				action.innerHTML = action.innerHTML[0].toUpperCase() + action.innerHTML.substring(1);
				bai.pick('#flow-list').appendChild(view);
				return true;
			}
			bai.bubble(data);
		});
	});
};
var flowUpdate = function(event) {
	var title = '<?php Lang::cut('flow-update'); ?>';
	var content = bai.pick('.tpl .tpl-flowUpdate').cloneNode(true);
	content.pick('.input[name=aevent]', 1).value = event;
	content.pick('.input[name=oevent]', 1).value = event;
	var url = '<?php echo $urlnUpdate; ?>';
	var view = this.pick('.item', -1);
	bai.bubble(content, title, function(e) {
		var check = bai.check(content);
		if (! check || check.input != null) {
			return false;
		}
		var aevent = content.pick('.input[name=aevent]', 1).value;
		bai.ajax(url, check.result, function(data) {
			if (data && data.status) {
				var last = bai.pick('.tpl .tpl-flow').cloneNode(true);
				last.innerHTML = last.innerHTML.replace(/\$aevent\$/g, aevent);
				var action = last.pick('.flow-action', 1);
				action.innerHTML = action.innerHTML[0].toUpperCase() + action.innerHTML.substring(1);
				bai.pick('#flow-list').replaceChild(last, view);
				return true;
			}
			bai.bubble(data);
		});
	});
};
var flowDelete = function(event) {
	var title = '<?php Lang::cut('flow-delete'); ?>';
	var content = bai.pick('.tpl .tpl-flowDelete').cloneNode(true);
	var url = '<?php echo $urlDelete; ?>' + '&aevent=' + event;
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
