<?php
/**
 * Bai事件处理流程：vimage
 * 刷新验证码图片
 *
 * @author 白晓阳
 */
class VimageAction extends Action
{
	public function entrust($event)
	{
		eVImage();
	}
}
?>
