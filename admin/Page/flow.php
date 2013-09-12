<div class="box">
	<div class="t">
<?php
$service = $this->target['service'];
$aservice = $this->target['aservice'];
$back = $this->url('service', $service);
echo
<<<BOX
		<a href="$back">$aservice</a>
		<span class="tg">&raquo;</span>
		<span>流程</span>
		<a class="fr tg">新建</a>
	</div>
	<div>
BOX;
$adata = $this->target[Flow::ACTION];
foreach ($adata as $item => $value) {
	$actionFile = $this->pick(Flow::ACTION, $value);
	$pageFile = $this->pick(Flow::PAGE, $value);
	$url = $this->url('flow', $service, 'aservice=' . $aservice . '&aevent=' . $item);
	echo
<<<BOX
		<div class="text item">
			<a class="w1 b" href="$url">$item</a>
			<input type="text" class="w2" value="$actionFile" />
			<input type="text" class="w2" value="$pageFile" />
			<span class="fr">
				<a href="$url">查看</a>
				<a>修改</a>
				<a>删除</a>
			</span>
		</div>
BOX;
}
?>
	</div>
</div>
<div class="q row">
	<span class="tg b">::</span>
	流程主要基于事件页面内容（/服务名/Page/事件名.php）和（或）事件处理流程（/服务名/Action/事件名Action.php）等文件。
</div>
