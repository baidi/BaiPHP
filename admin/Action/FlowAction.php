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
 * <b>事件处理流程</b>
 * <p>
 * </p>
 * @author 白晓阳
 */
class FlowAction extends Action
{
	/**
	 * 包含的目录
	 */
	protected $include = array();

	protected function engage()
	{
		$basin = _LOCAL.$this->event['abasin']._DIR;
		$result = array();
		foreach ($this->include as $item => $mode) {
			$files = scandir($basin.$item);
			if (! is_array($files)) {
				continue;
			}
			foreach ($files as $file) {
				if (! preg_match($mode, $file, $match) || ! is_file($basin.$item._DIR.$file)) {
					continue;
				}
				$event = lcfirst(self::pick(self::EVENT, $match));
				if (strpos($event, _DEF) === 0) {
					continue;
				}
				$result[$event][$item] = $file;
			}
		}
		if (! empty($result['home'])) {
			$result = array('home' => $result['home']) + $result;
		}
		$this->event[Flow::ACTION] = $result;
	}
}
?>
