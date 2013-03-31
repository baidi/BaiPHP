<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="author" content="<?php Lang::fetch('author'); ?>" />
	<meta name="keywords" content="<?php Lang::fetch('keywords'); ?>" />
	<meta name="description" content="<?php Lang::fetch('description'); ?>" />
	<link rel="icon" type="image/x-icon" href="<?php echo Style::img('favicon.ico') ?>"/>
	<title><?php Lang::fetch('title'); ?></title>
	<style type="text/css"><?php echo Style::css('bai.css', true); ?></style>
</head>
<body>
	<div id="page">
		<div class="box">
			<div class="t">出错啦！</div>
			<hr/>
			<?php echo $this->target->error; ?>
		</div>
	</div>
</body>
</html>
