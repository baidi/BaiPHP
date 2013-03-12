<?php
### 多列表格
### 数据
$data = cRead('data', $_param);
if (! $data)
	return;
unset($_param['data']);
### 数据列格式：宽度（1） a=链接（2） img=图片（3） div=块内容（4）
$pattern = '/^([^\s]+)?(?i:\sa=([^\s]+))?(?i:\simg=([^\s]+))?(?i:\sdiv=([^\s]+))?(?i:\sload=([^\s]+))?$/';
$size = 1;
$a    = 2;
$img  = 3;
$div  = 4;
$load = 5;
echo '<div class="m borderb open l"><ul class="', $_css, ' table">';
foreach ($data as $row)
{
	echo '<li>';
	foreach ($_param as $column => $layout)
	{
		preg_match($pattern, $layout, $matches);
		if (! empty($matches[$load]))
		{
			if (stripos($matches[$load], '.php') !== false)
			{
				echo '<div id="/', substr($matches[$load], 0, -4), '/', cRead('id', $row), '/" style="width:', $matches[$size], ';">';
			}
			else
			{
				echo '<div id="/', $matches[$load], '/', cRead('id', $row), '/" style="width:', $matches[$size], ';">';
			}
		}
		else
		{
			echo '<div style="width:', $matches[$size], ';">';
		}
		if (! empty($matches[$a]))
			if (stripos($matches[$a], '.php') !== false)
			{
				echo '<a href="/', substr($matches[$a], 0, -4), '/', cRead('id', $row), '/">', cRead($column, $row), '</a>';
			}
			else if (empty($row[$column]))
			{
				echo '<a href="', cRead($matches[$a], $row), '">', $column, '</a>';
			}
			else
			{
				echo '<a href="', cRead($matches[$a], $row), '">', cRead($column, $row), '</a>';
			}
		else if (! empty($matches[$load]))
			echo '<a>', cRead($column, $row), '</a>';
		else
			cWrite($column, $row);
		if (! empty($matches[$img]))
			echo '<img src="', cRead($matches[$img], $row), '"></img>';
		if (! empty($matches[$div]))
		{
			if (stripos($matches[$div], '.php') !== false)
			{
				echo '<div class="h">';
				cLoad($matches[$div], $row);
				echo '</div></div>';
				continue;
			}
			echo '<div>', cRead($matches[$div], $row), '</div>';
		}
		echo '</div>';
	}
	echo '</li>';
}
echo '</ul></div>';
?>
