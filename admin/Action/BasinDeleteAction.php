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
 * <b>新建流域处理流程</b>
 * <p>
 * </p>
 * @author 白晓阳
 */
class BasinDeleteAction extends Action
{
	/**
	 * 子目录
	 */
	protected $exclude = null;

	protected function engage()
	{
		$this->end = true;
		$basin = $this->target['abasin'];
		$result = array('status' => false);
		if (! empty($this->exclude[$basin]) && is_dir(_LOCAL.$basin._DIR)) {
			Log::logf(__FUNCTION__, $basin, __CLASS__);
			$result['status'] = rmdir($basin);
		}
		if (empty($result['status'])) {
			$result['notice'] = Log::logf('fail', $basin, __CLASS__);
		}
		return json_encode($result);
	}
}
