<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<meta name="author" content="<?php echo $this['author']; ?>" />
<meta name="keywords" content="<?php echo $this['keywords']; ?>" />
<meta name="description" content="<?php echo $this['description']; ?>" />
<link rel="icon" href="<?php echo Style::img('favicon.ico'); ?>" />
<title><?php echo $this['title']; ?></title>
<?php echo Style::css($this['css'], true); ?>
<?php echo Style::js($this['js'], true); ?>
</head>
<body>
	<header>
		<!-- 页眉 -->
		<?php echo $this->load('_header.php'); ?>
	</header>
	<div class="page">
		<!-- 页面 -->
		<?php if ($this['lside']) { ?>
		<aside class="lside">
			<!-- 左边栏 -->
			<?php echo $this['lside']; ?>
		</aside>
		<?php } ?>
		<?php if ($this['rside']) { ?>
		<aside class="rside">
			<!-- 右边栏 -->
			<?php echo $this['rside']; ?>
		</aside>
		<?php } ?>
		<div class="main">
			<!-- 主体 -->
			<?php echo $this->load("$this->target"); ?>
		</div>
	</div>
	<footer>
		<!-- 页脚 -->
		<?php echo $this->load('_footer.php'); ?>
	</footer>
</body>
</html>
