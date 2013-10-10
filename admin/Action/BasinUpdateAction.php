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
class BasinUpdateAction extends Action
{
	/**
	 * 子目录
	 */
	protected $exclude = null;

	protected function engage()
	{
		$this->end = true;
		$obasin = _LOCAL.$this->target['obasin']._DIR;
		$basin = _LOCAL.$this->target['abasin']._DIR;
		$result = array();
		if (! is_dir($obasin)) {
			$result['status'] = false;
			$result['notice'] = Log::logs('deleted', __CLASS__);
			return json_encode($result);
		}
		if (is_dir($basin)) {
			$result['status'] = false;
			$result['notice'] = Log::logs('existed', __CLASS__);
			return json_encode($result);
		}
		Log::logf('basin', array($obasin, $basin), __CLASS__);
		$status = rename($obasin, $basin);
		$result['status'] = $status;
		if (! $status) {
			$result['notice'] = Log::logs('fail', __CLASS__);
		}
		return json_encode($result);
	}
}
