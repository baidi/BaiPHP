<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="author" content="<?php echo $this->author; ?>" />
	<meta name="keywords" content="<?php echo $this->keywords; ?>" />
	<meta name="description" content="<?php echo $this->description; ?>" />
	<link rel="icon" type="image/x-icon" href="<?php echo Style::img('favicon.ico') ?>"/>
	<title><?php echo $this->title; ?></title>
	<?php echo Style::css($this->css, true); ?>
	<?php echo Style::js($this->js, true) ?>
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
				<?php echo $this->load("$this->target"); ?>
			</div>
		</div>
		<footer><!-- 页脚 -->
			<?php echo $this->load('_footer.php'); ?>
		</footer>
	</div>
</body>
</html>
