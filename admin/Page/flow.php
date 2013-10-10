<?php
$service = $this->target['service'];
$abasin = $this->target['abasin'];
$urlBack = $this->url('basin', $service);
?>
<div class="box">
	<div class="t">
		<a href="<?php echo $urlBack; ?>"><?php echo $abasin; ?></a>
		<span class="tg">&raquo;</span>
		<span>流程</span>
		<a class="fr tg" onclick="flowCreate(/\$aevent\$/g)">新建</a>
	</div>
	<div>
<?php
$adata = $this->target[Flow::ACTION];
foreach ($adata as $item => $value) {
	$actionFile = $this->pick(Flow::ACTION, $value);
	$actionAction = $actionFile == null ? '新建' : '删除';
	$pageFile = $this->pick(Flow::PAGE, $value);
	$pageAction = $pageFile == null ? '新建' : '删除';
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
				<a class="button">修改</a>
				<a class="button">删除</a>
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
			$aevent$Action.php
			<a>删除</a>
		</span>
		<span class="w2">
			$aevent$.php
			<a>删除</a>
		</span>
		<span class="fr">
			<a class="button">修改</a>
			<a class="button">删除</a>
		</span>
	</div>
	<div class="text tpl-flowCreate">
		<label>流程名：</label>
		<?php echo Input::cut('aevent', array('event' => 'flowCreate', 'class' => 'w300')); ?>
	</div>
	<div class="text tpl-flowUpdate">
		<label>流程名：</label>
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
	var title = '新建流程';
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
				bai.pick('#basin-list').appendChild(view);
			}
		});
	});
};
var basinUpdate = function(basin) {
	var title = '修改流域';
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
				var changed = bai.pick('.tpl .tpl-basin').cloneNode(true);
				changed.innerHTML = changed.innerHTML.replace(/\$abasin\$/g, abasin);
				bai.pick('#basin-list').replaceChild(changed, view);
			}
		});
	});
};
var basinDelete = function(basin) {
	var title = '删除流域';
	var content = bai.pick('.tpl .tpl-basinDelete').cloneNode(true);
	var url = '<?php echo $urlDelete; ?>' + '&abasin=' + basin;
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
