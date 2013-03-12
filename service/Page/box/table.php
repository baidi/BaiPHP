<?php
/**
 * <b>BaiPHP（简单PHP）开发框架</b>
 * @author		白晓阳
 * @copyright	Copyright (c) 2011 - 2012, 白晓阳
 * @link		http://dacbe.com
 * @version     V1.0.0 2012/03/31 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 */

/**
 * <b>BaiPHP（简单PHP）开发框架</b><br/>
 * <b>数据一览组件</b>
 * <p>显示数据一览</p>
 * @author 白晓阳
 */

if (! is_array($_param) || ! $_param)
{
	return;
}
$title = cRead(Data::TITLE, $_param);
$records = cRead(Data::RECORD, $_param);
if ($title == null && $records == null)
{
	$records = $_param;
}
echo '<table class="', $_css, '">';
### 表头
if (is_array($title) && $title)
{
	echo '<thead><tr>';
	foreach ($title as $item => $value)
	{
		if (is_int($item))
		{
			echo '<th>', $value, '</th>';
			continue;
		}
		echo '<th style="width:', $value, '">', $item, '</th>';
	}
	echo '</tr></thead>';
}
### 数据
echo '<tbody>';
foreach ($records as $record)
{
	if (! is_array($record) || ! $record)
	{
		continue;
	}
	echo '<tr>';
	foreach ($record as $item => $value)
	{
		if (is_int($item))
		{
			echo '<td>', $value, '</td>';
			continue;
		}
		echo '<td><a href="', $value, '">', $item, '</a></td>';
	}
	echo '</tr>';
}
echo '</tbody>';
echo '</table>';
?>
