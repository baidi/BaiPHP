<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="author" content="<?php echo $this->author; ?>" />
	<meta name="keywords" content="<?php echo $this->keywords; ?>" />
	<meta name="description" content="<?php echo $this->description; ?>" />
	<link rel="icon" href="<?php echo _WEB; ?>favicon.ico" type="image/x-icon" />
	<title><?php echo $this->title; ?></title>
	<style type="text/css"><?php echo $this->css; ?></style>
	<script type="text/javascript" src="<?php echo _WEB; ?>service/js/sizzle.js"></script>
	<script type="text/javascript"><?php echo $this->js; ?></script>
</head>
<body>
	<div id="page">
		<header><!-- 页眉 -->
			<?php echo $this->load('_header.php'); ?>
		</header>
		<div><!-- 页面 -->
			<?php if ($lside = $this->lside) { ?>
			<aside id="lside"><!-- 左边栏 -->
				<?php echo $lside; ?>
			</aside>
			<?php } ?>
			<?php if ($rside = $this->rside) { ?>
			<aside id="rside"><!-- 右边栏 -->
				<?php echo $rside; ?>
			</aside>
			<?php } ?>
			<div id="main"><!-- 主体 -->
				<?php echo $this->main; ?>
			</div>
		</div>
		<footer><!-- 页脚 -->
			<?php echo $this->load('_footer.php'); ?>
		</footer>
	</div>
</body>
</html>
