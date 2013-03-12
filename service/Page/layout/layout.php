<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="keywords" content="<?php echo 'keywords'; ?>" />
	<meta name="description" content="<?php echo 'description'; ?>" />
	<title><?php echo 'title'; ?></title>
	<style>
	<?php
// 	$css = file_get_contents(_LOCAL.'bai/css/bai.css');
// 	foreach (c('CSS') as $item => $value)
// 	{
// 		$css = str_replace($item, $value, $css);
// 	}
// 	echo $css;
	?>
	</style>
	<link rel="stylesheet" type="text/css" href="<?php echo ''; ?>style/css/user.css" />
	<!--[if lt IE 9]>
	<script type="text/javascript" src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<script type="text/javascript" src="<?php echo ''; ?>style/js/jquery-1.8.0.js"></script>
	<script type="text/javascript">
	<?php
// 	$js = file_get_contents(_LOCAL.'bai/js/bai.js');
// 	$js = str_replace('$config$', json_encode(c('JS')), $js);
// 	echo $js;
	?>
	</script>
	<script type="text/javascript" src="<?php echo ''; ?>style/js/user.js"></script>
</head>
<body>
	<header><!-- 页首 -->
		<div class="box logo">BaiPHP - 简单PHP</div>
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
			<a href="/" class="sign" title="大乘网络支持中心，网络有限，支持无限……"></a>
			<span>
				Copyright © 2010 - <?php echo date('Y', time()); ?> 保留一切权力 
				<img src="<?php echo ''; ?>style/img/icon.png"></img> 大乘网络支持中心
			</span>
		</div-->
	</footer>
</body>
</html>
