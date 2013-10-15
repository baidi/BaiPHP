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
