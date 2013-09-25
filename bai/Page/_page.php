<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<meta name="author" content="<?php Lang::cut('author'); ?>" />
<meta name="keywords" content="<?php Lang::cut('keywords'); ?>" />
<meta name="description" content="<?php Lang::cut('description'); ?>" />
<link rel="icon" href="<?php echo Style::img('favicon.ico'); ?>" />
<title><?php Lang::cut('title'); ?></title>
<?php echo Style::css($this['css'], true); ?>
<?php echo Style::js($this['js'], true); ?>
</head>
<body>
	<!-- 页眉 -->
	<?php //echo $this->load('_header.php'); ?>
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
	<!-- 页脚 -->
	<?php //echo $this->load('_footer.php'); ?>
	<?php echo $this->load('_dialog.php'); ?>
</body>
</html>
