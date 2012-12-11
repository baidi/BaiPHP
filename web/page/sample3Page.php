<!doctype html>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="/bai/css/bai.css" />
<link rel="stylesheet" type="text/css" href="<?php echo _STYLE; ?>/css/user.css" />
<!--[if lt IE 9]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script type="text/javascript" src="/bai/js/jquery-1.7.2.js"></script>
<script type="text/javascript" src="/bai/js/bai.js"></script>
<title>BaiPHP - 简单编程</title>
</head>
<body>
	<div>
		<h2>案例3</h2>
		<div class="box">
			<div class="t">输入验证</div>
			<div id="sampleCheck">
				<div class="row">
					<label class="required">数值（3-5位）:</label>
					<input type="number" value="<?php cWrite('sampleInt'); ?>" <?php cHint('sampleCheck', 'sampleInt'); ?> />
				</div>
				<div class="row">
					<label class="required">字母（3-10位）:</label>
					<input type="text" value="<?php cWrite('sampleLetter'); ?>" <?php cHint('sampleCheck', 'sampleLetter'); ?> />
				</div>
				<div class="row">
					<input type="button" value="验证" onclick="jss('sampleCheck', '#sampleCheck');" />
				</div>
			</div>
		</div>
	</div>
</body>
</html>
