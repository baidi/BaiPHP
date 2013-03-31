<?php
if (!empty($_SESSION['limit_count'])) echo $_SESSION['limit_count'],'<br/>';
if (!empty($_SESSION['limit_time'])) echo $_SESSION['limit_time'],'<br/>';
if (empty($_SESSION['hot_count'])) {
	$_SESSION['hot_count'] = 1;
} else {
	$_SESSION['hot_count'] ++;
}
echo $_SESSION['hot_count'];
?>
<table>
<tr>
	<td>a</td>
	<td>b</td>
</tr>
<tr>
	<td>cccccccc</td>
	<td>dddddddddd</td>
</tr>
<tr>
	<td>a</td>
	<td>b</td>
</tr>
</table>