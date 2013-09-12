<div class="box">
	<div class="t">
<?php
$service = $this->target['service'];
$aevent = $this->target['aevent'];
$aservice = $this->target['aservice'];
$backs = $this->url('service', $service);
$backe = $this->url('event', $service, 'aservice=' . $aservice);
echo
<<<BOX
		<a href="$backs">$aservice</a>
		<span class="tg">&raquo;</span>
		<a href="$backe">$aevent</a>
		<span class="tg">&raquo;</span>
		<span>处理</span>
		<a class="fr tg">新建</a>
	</div>
	<div>
BOX;
$adata = $this->target[Flow::ACTION];
foreach ($adata as $item => $value) {
	echo
<<<BOX
		<div class="text">
			<div class="b item">$item</div>
			<ol>
BOX;
	foreach ($value as $name => $mode) {
		echo
<<<BOX
				<li class="item">
					<span class='w100'>{$name}</span>
					<span class='tg'>|</span>
					<span>{$mode}</span>
				</li>
BOX;
	}
	echo
<<<BOX
			</ol>
		</div>
BOX;
}
?>
	</div>
</div>
<div class="q row">
	<span class="tg b">::</span>
	处理由全局配置：流程（$config[Flow::FLOW]）决定，总是优先选用当前服务下的流程和工场。
</div>
