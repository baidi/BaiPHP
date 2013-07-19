<div class="box">
	<div class="t">
<?php
$service = $this->target['service'];
$aservice = $this->target['aservice'];
$back = $this->url('service', $service);
echo
<<<BOX
		<span>事件一览</span>
		<span class="tg">&bull;</span>
		<a href="$back">$aservice</a>
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
