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
		<h2>案例4</h2>
		<div class="box">
			<div class="t">数据访问</div>
			<?php 
			if (empty($_SESSION['sample4'])) {
				echo '<div>数据库访问出错或者没有数据……</div>';
			} else {
			?>
			<table>
				<tr>
					<th width="50">ID</th>
					<th width="50">姓名</th>
					<th width="50">性别</th>
					<th width="50">年龄</th>
				</tr>
				<?php 
				foreach ($_SESSION['sample4'] as $data) {
					echo '<tr>';
					echo '<td>', $data['id'], '</td>';	
					echo '<td>', $data['name'], '</td>';	
					echo '<td>', $data['sex']?'男':'女', '</td>';	
					echo '<td>', $data['age'], '</td>';	
					echo '</tr>';
				}
				?>
			</table>
			<?php } ?>
		</div>
	</div>
</body>
</html>
