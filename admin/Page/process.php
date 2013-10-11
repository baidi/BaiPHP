<?php
$service = $this->target['service'];
$abasin = $this->target['abasin'];
$aevent = $this->target['aevent'];
$urlBackBasin = $this->url('basin', $service);
$urlBackFlow = $this->url('flow', $service, 'abasin=' . $abasin);
?>
<div class="box">
	<div class="t">
		<a class="fr tg"><?php Lang::cut('create'); ?></a>
		<a href="<?php echo $urlBackBasin; ?>"><?php echo $abasin; ?></a>
		<span class="tg">&raquo;</span>
		<a href="<?php echo $urlBackFlow; ?>"><?php echo $aevent; ?></a>
		<span class="tg">&raquo;</span>
		<span><?php Lang::cut('process'); ?></span>
	</div>
	<div>
<?php
$adata = $this->target[Flow::ACTION];
foreach ($adata as $item => $value) {
	echo
<<<BOX
		<div class="text">
			<div class="b item">$item</div>
			<ol class="al">
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
