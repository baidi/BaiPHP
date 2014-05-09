<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<meta name="author" content="<?php Lang::cut('author'); ?>" />
<meta name="keywords" content="<?php Lang::cut('keywords'); ?>" />
<meta name="description" content="<?php Lang::cut('description'); ?>" />
<link rel="icon" href="<?php echo Style::img('favicon.ico'); ?>" />
<title><?php Lang::cut('title'); ?></title>
<?php echo Style::css($this['css']); ?>
<?php echo Style::js($this['js']); ?>
</head>
<body>
	<?php //echo $this->load('_header.php'); ?>
	<div class="page">
		<?php if ($this['lside']) { ?>
		<aside class="lside">
			<?php echo $this['lside']; ?>
		</aside>
		<?php } ?>
		<?php if ($this['rside']) { ?>
		<aside class="rside">
			<?php echo $this['rside']; ?>
		</aside>
		<?php } ?>
		<div class="main">
			<?php echo $this->load("$this->event", null, 1); ?>
		</div>
	</div>
	<?php //echo $this->load('_footer.php'); ?>
</body>
</html>
