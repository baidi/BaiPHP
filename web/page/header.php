<?php
global $cTitle, $cMenu;
?>
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
<title><?php echo $cTitle; ?></title>
</head>
<body>
	<div><!-- 页首 -->
		<div class="logo fl"></div>
		<div class="text b">BaiPHP - 简单PHP开发框架</div>
		<div class="box borderb menu">
			<?php
			foreach ($cMenu as $item => $title) {
				echo '<a href="/', $item, '/"', ($item == $event) ? ' class="on"' : '', ' >', $title, '</a>';
			}
			?>
		</div>
	</div>
	<form id="bai" name="bai" action="/" method="post" enctype="multipart/form-data">
	<input id="event" name="event" type="hidden" value="<?php echo $event; ?>"></input>
