<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<meta name="author" content="<?php Lang::fetch('author'); ?>" />
<meta name="keywords" content="<?php Lang::fetch('keywords'); ?>" />
<meta name="description" content="<?php Lang::fetch('description'); ?>" />
<link rel="icon" href="<?php echo Style::img('favicon.ico') ?>" />
<title><?php Lang::fetch('title'); ?></title>
<style type="text/css">
.page {margin: 0 auto; min-width: 480px; max-width: 980px;}
.box {margin: 0.5em; margin-bottom: 1em; padding: 0.5em; border: 1px solid #99cc99;}
.t {font-size: 1.25em; font-weight: bold; margin-top: 0.5em; padding-bottom: 0.2em; border-bottom: 1px solid #99cc99;}
.notice {padding: 0.5em 1em; color: #f04000;}
.tr {text-align: right;}
hr {border: 1px solid #cceccc;}
</style>
</head>
<body>
	<div class="page">
		<div class="box">
			<div class="t">提示：</div>
			<div class="notice">
			    <?php echo $this->target->notice; ?>
			</div>
			<hr/>
			<div class="tr">
			    <a>确定</a>
			    <a>返回</a>
			</div>
		</div>
	</div>
</body>
</html>
