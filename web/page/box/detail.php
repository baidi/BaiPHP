<?php
### 单列列表
echo '<ul class="', $_css, '">';
foreach ($_param as $item => $value) {
	$type = substr($value, -4);
	if (is_int($item))
	{
		### 图片
		if ($type == '.png' || $type == '.jpg')
		{
			echo '<li><img src="', $value, '" /></li>';
			continue;
		}
		### 文本
		echo '<li>', $value, '</li>';
		continue;
	}
	### 外部文件
	if ($type == '.php')
	{
		if (substr($value, 0, 5) == 'load=')
		{
			
			echo '<li id="/', substr($value, 5, -4), '/', urlencode($item), '/"><a>', $item, '</a></li>';
			continue;
		}
		echo '<li><a>', $item, '</a><div>';
		cLoad($value, $item);
		echo '</div></li>';
		continue;
	}
	### 图片
	if ($type == '.png' || $type == '.jpg')
	{
		echo '<li><a href="', $item, '"><img src="', $value, '" /></a></li>';
		continue;
	}
	### 链接
	echo '<li><a href="', $value, '">', $item, '</a></li>';
	continue;
}
echo '</ul>';
?>
