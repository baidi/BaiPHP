<div class="header ad"><div class="page">
	<img class="logo" alt="<?php Lang::cut('logo'); ?>" src="<?php echo Style::img('logo.png'); ?>" />
	<div class="tr">
	<?php
	foreach (Lang::cut('nav', false) as $item => $value) {
		echo '<a href="">', $value, '</a>';
	}
	?>
	</div>
</div></div>
<br/>
