<?php
/**
 * <b>BaiPHP（简单PHP）开发框架</b>
 * @author		白晓阳
 * @copyright	Copyright (c) 2011 - 2012, 白晓阳
 * @link		http://dacbe.com
 * @version    V1.0.0 2012/03/31 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 */

/**
 * <b>BaiPHP（简单PHP）开发框架</b><br/>
 * <b>扩展工场</b>
 * <p>
 * ！！！建议所有扩展工场以Extend结尾！！！
 * ！！！建议所有扩展方法以e开头！！！
 * ！！！不要把扩展做得过于臃肿和庞大，这是一个清新简单的系统！！！
 * </p>
 * @author 白晓阳
 */

/**
 * 生成检验码并输出图片
 * @param number $width 图片宽度
 * @param number $height 图片高度
 * @param number $length 检验码长度
 * @param string $type 检验码种类
 *         L：字母型； N：数字型； W：混合型；
 */
function eVImage($width = 60, $height = 20, $length = 4, $type = 'L')
{
	### 生成随机检验码
	$vcode = '';
	if (! $type || $type == 'L' || $type == 'l')
	{
		$text = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		for ($i = 0; $i < $length; $i++)
		{
			$vcode .= $text[rand(0, 25)];
		}
	}
	else if ($type == 'N' || $type == 'n')
	{
		$text = '0123456789';
		for ($i = 0; $i < $length; $i++)
		{
			$vcode .= $text[rand(0, 9)];
		}
	}
	else
	{
		$text = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		for ($i = 0; $i < $length; $i++)
		{
			$vcode .= $text[rand(0, 35)];
		}
	}
	$_SESSION['vcode'] = $vcode;

	### 生成检验码图片
	$png = imagecreate($width, $height);
	### 背景色
	$backColor   = imagecolorallocate($png, rand(100, 200), rand(100, 200), rand(100, 200));
	### 掩饰色
	$maskedColor = imagecolorallocate($png, rand(150, 220), rand(150, 220), rand(150, 220));
	### 文字色
	$textColor   = imagecolorallocate($png, rand(200, 255), rand(200, 255), rand(200, 255));
	imagefill($png, 0, 0, $backColor);
	for ($x = 0; $x <= $width; $x += 5) {
		for ($y = 0; $y <= $height; $y += 5) {
			imagefilledellipse($png, $x, $y, 4, 4, $maskedColor);
		}
	}
	$left = $width / ($length + 1);
	$top = $height / 3;
	#imagefilter($png, IMG_FILTER_GAUSSIAN_BLUR);
	for ($i = 0; $i < $length; $i++)
	{
		imagestring($png, rand(4, 6), rand(0, $left) + $left * $i, rand(0, $top), $vcode[$i], $textColor);
	}

	### 输出检验码图片
	ob_clean();
	header('Content-Type: image/png; charset=binary');
	imagepng($png);
	imagedestroy($png);
}

/**
 * 验证码检验
 * @param string $item 检验项目
 * @param array $params 检验参数
 */
function eVCode($item, array $params = null)
{
	if ($item && $params && strtoupper($item) == cRead($params[0]))
		return false;
	return Log::logs(__FUNCTION__, 'Event');
}
?>
