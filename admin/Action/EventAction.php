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
class EventAction extends Action
{
	/**
	 * 包含的目录
	 */
	protected $include = array();

	protected function engage()
	{
		$service = _LOCAL.$this->target['aservice']._DIR;
		$result = array();
		foreach ($this->include as $item => $mode) {
			$files = scandir($service.$item);
			if (! is_array($files)) {
				continue;
			}
			foreach ($files as $file) {
				if (! preg_match($mode, $file, $match) || ! is_file($service.$item._DIR.$file)) {
					continue;
				}
				$event = lcfirst($this->pick(self::EVENT, $match));
				$result[$event][$item] = $file;
			}
		}
		$this->target[Flow::ACTION] = $result;
	}
}
?>
