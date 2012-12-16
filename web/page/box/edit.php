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
 * <b>编辑组件</b>
 * <p>
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
	if (! is_array($value))
	{
		echo '<dt><label for="', $item, '">', $value, '</label>';
		echo '<input type="text" id="', $item, '" name="', $item, '" value="" /></dt>';
		continue;
	}
	echo '<dt><label for="', $item, '">', cRead('label', $value), '</label>';
	echo '<input type="', cRead('type', $value), '" id="', $item, '" name="', $item, '" value="', cRead('value', $value), '" /></dt>';
}
echo '</dl>';
?>
