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
 * <b>首页处理流程</b>
 * <p>
 * </p>
 * @author 白晓阳
 */
class HomeAction extends Action
{
	/**
	 * 排除的目录
	 */
	protected $exclude = array();

	protected function engage()
	{
		header('Location: '.$this->url('basin', $this->target['service']));
	}
}
?>
