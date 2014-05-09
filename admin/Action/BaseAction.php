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
class BaseAction extends Action
{
	/**
	 * 排除的目录
	 */
	protected $exclude = null;

	protected function engage()
	{
		$files = scandir(_LOCAL);
		if (! is_array($files)) {
			return false;
		}
		$result = array();
		foreach ($files as $file) {
			if (self::pick($file, $this->exclude) == null && is_dir($file)) {
				$result[$file] = true;
			}
		}
		$primary = array(
			'bai' => false,
			'base' => false,
			$this->event['base'] => false,
		);
		$this->event[Flow::ACTION] = $primary + $result;
	}
}
?>
