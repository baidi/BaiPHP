<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="keywords" content="<?php echo $this->keywords; ?>" />
	<meta name="description" content="<?php echo $this->description; ?>" />
	<title><?php echo $this->title; ?></title>
	<style><?php echo $this->css; ?></style>
	<!--link rel="stylesheet" type="text/css" href="<?php echo ''; ?>style/css/user.css" /-->
	<!--[if lt IE 9]>
	<script type="text/javascript" src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<!--script type="text/javascript" src="<?php echo ''; ?>style/js/jquery-1.8.0.js"></script-->
	<script type="text/javascript"><?php echo $this->js; ?></script>
</head>
<body>
	<header><!-- 页首 -->
		<div class="box logo">BaiPHP - 化简PHP</div>
		<nav class="box">
			<?php
// 			foreach (c('menu') as $item => $title) {
// 				echo '<a href="/', $item, '/"', ($item == $event) ? ' class="on"' : '', ' >', $title, '</a>';
// 			}
			?>
		</nav>
	</header>

	<div><!-- 页面 -->
		<aside id="lside" class="fl w2">
			<?php echo $this->lside; ?>
		</aside><!-- 左边栏 -->
		<aside id="rside" class="fr w2">
			<?php echo $this->rside; ?>
		</aside><!-- 右边栏 -->
		<div id="content">
			<?php echo $this->content; ?>
		</div><!-- 主体 -->
	</div>

	<footer><!-- 页脚 -->
		<nav class="box">
			<?php
// 			foreach (c('link') as $item => $title) {
// 				echo '<a href="/', $item, '/"', ($item == $event) ? ' class="on"' : '', ' >', $title, '</a>';
// 			}
			?>
		</nav>
		<!--div class="box tr">
			<span>
				Copyright © 2011 - <?php echo date('Y', time()); ?> 保留一切权力
			</span>
		</div-->
	</footer>
</body>
</html>
