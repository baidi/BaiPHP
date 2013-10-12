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
				<span>$actionFile</span>
				<a onclick="flowAction.call(this, '$item', '$actionFile')">$actionAction</a>
			</span>
			<span class="w2">
				<span>$pageFile</span>
				<a onclick="flowPage.call(this, '$item', '$pageFile')">$pageAction</a>
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
$urlActionCreate = $this->url('actionCreate', $service, 'abasin=' . $abasin);
$urlActionDelete = $this->url('actionDelete', $service, 'abasin=' . $abasin);
$urlPageCreate = $this->url('pageCreate', $service, 'abasin=' . $abasin);
$urlPageDelete = $this->url('pageDelete', $service, 'abasin=' . $abasin);
$urlProcess = $this->url('process', $service, 'abasin=' . $abasin . '&aevent=$aevent$');
?>
<div class="tpl">
	<div class="text item tpl-flow">
		<span class="w2">
			<a class="b" href="<?php echo $urlProcess; ?>">$aevent$</a>
		</span>
		<span class="w3">
			<span class="flow-action" onclick="flowAction.call(this, '$aevent$', '$aevent$Action.php')">$aevent$Action.php</span>
			<a><?php echo $lblDelete; ?></a>
		</span>
		<span class="w2">
			<span>$aevent$.php</span>
			<a onclick="flowPage.call(this, '$aevent$', '$aevent$.php')"><?php echo $lblDelete; ?></a>
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
	<div class="tpl-action-create">
		<p>确定要建立该处理文件？</p>
		<p>新建文件只是一个示范的模板，不包含具体处理！</p>
	</div>
	<div class="tpl-action-delete">
		<p>确定要删除该处理文件？</p>
		<p>文件删除后无法恢复！</p>
	</div>
	<div class="tpl-page-create">
		<p>确定要建立该页面文件？</p>
		<p>新建文件只是一个示范的模板，你需要手动添加你期望的内容！</p>
	</div>
	<div class="tpl-page-delete">
		<p>确定要删除该页面文件？</p>
		<p>页面文件删除后无法恢复！</p>
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
// 新建或删除处理文件
var flowAction = function(event, file) {
	var title = null, content = null, url = null;
	if (file) {
		title = '<?php Lang::cut('action-delete'); ?>';
		content = bai.pick('.tpl .tpl-action-delete').cloneNode(true);
		url = '<?php echo $urlActionDelete; ?>' + '&aevent=' + event;
	} else {
		title = '<?php Lang::cut('action-create'); ?>';
		content = bai.pick('.tpl .tpl-action-create').cloneNode(true);
		url = '<?php echo $urlActionCreate; ?>' + '&aevent=' + event;
	}
	var view = this;
	bai.bubble(content, title, function(e) {
		bai.ajax(url, function(data) {
			if (data && data.status) {
				if (file) {
					view.previousElementSibling.innerHTML = '';
					view.innerHTML = '<?php echo $lblCreate;?>';
					view.onclick = Function("flowAction.call(this, '" + event + "', '')");
					return true;
				}
				view.previousElementSibling.innerHTML = data.file;
				view.innerHTML = '<?php echo $lblDelete;?>';
				view.onclick = Function("flowAction.call(this, '" + event + "', '" + data.file + "')");
				return true;
			}
			bai.bubble(data);
		});
	});
};
// 新建或删除页面文件
var flowPage = function(event, file) {
	var title = null, content = null, url = null;
	if (file) {
		title = '<?php Lang::cut('page-delete'); ?>';
		content = bai.pick('.tpl .tpl-page-delete').cloneNode(true);
		url = '<?php echo $urlPageDelete; ?>' + '&aevent=' + event;
	} else {
		title = '<?php Lang::cut('action-create'); ?>';
		content = bai.pick('.tpl .tpl-action-create').cloneNode(true);
		url = '<?php echo $urlPageCreate; ?>' + '&aevent=' + event;
	}
	var view = this;
	bai.bubble(content, title, function(e) {
		bai.ajax(url, function(data) {
			if (data && data.status) {
				if (file) {
					view.previousElementSibling.innerHTML = '';
					view.innerHTML = '<?php echo $lblCreate;?>';
					view.onclick = Function("flowPage.call(this, '" + event + "', '')");
					return true;
				}
				view.previousElementSibling.innerHTML = data.file;
				view.innerHTML = '<?php echo $lblDelete;?>';
				view.onclick = Function("flowPage.call(this, '" + event + "', '" + data.file + "')");
				return true;
			}
			bai.bubble(data);
		});
	});
};
//-->
</script>
