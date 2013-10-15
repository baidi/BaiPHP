<?php
/**
 * <b>化简PHP（BaiPHP）开发框架</b>
 * @author		白晓阳
 * @copyright	Copyright (c) 2011 - 2012, 白晓阳
 * @link		http://dacbe.com
 * @version     V1.0.0 2012/03/31 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 */

/**
 * <b>化简PHP（BaiPHP）开发框架</b><br/>
 * <b>列表组件</b>
 * <p>
 * 组织形式：
 * 列表项目1，列表项目2，……
 * 或 列表项目1=>链接1，列表项目2=>链接2，……
 * </p>
 * @author 白晓阳
 */
if (! is_array($_param) || ! $_param)
{
	return;
}
echo '<dl class="', $_css, '">';
foreach ($_param as $item => $value)
{
	if (is_int($item))
	{
		echo '<dt>', $value, '</dt>';
		continue;
	}
	if (is_array($value))
	{
		echo '<dt>', $item;
		cLoad('box/list.php', $value);
		echo '</dt>';
		continue;
	}
	echo '<dt><a href="', $value, '">', $item, '</a></dt>';
}
echo '</dl>';
?>
