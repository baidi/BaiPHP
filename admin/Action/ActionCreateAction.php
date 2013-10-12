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
class ActionCreateAction extends Action
{
	/**
	 * 相关文件
	 */
	protected $include = null;

	protected function engage()
	{
		$this->end = true;
		$basin = _LOCAL.$this->target['abasin']._DIR;
		$event = $this->target['aevent'];
		$file = $basin.sprintf($this->include, ucfirst($event));
		if (is_file($file)) {
			$result = array(
				'status' => false,
				'notice' => Log::logs('existed', __CLASS__),
			);
			return json_encode($result);
		}

		Log::logf(__FUNCTION__, $this->target['abasin']._DIR.$event, __CLASS__);
		$template = Template::file(self::ACTION._EXT, array('event' => $event));
		$status = file_put_contents($file, $template);
		$result = array('status' => $status);
		if (! $status) {
			$result['notice'] = Log::logs('fail', __CLASS__);
		} else {
			$result['file'] = basename($file);
		}
		return json_encode($result);
	}
}
