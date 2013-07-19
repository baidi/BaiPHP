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
 * <b>流程处理流程</b>
 * <p>
 * </p>
 * @author 白晓阳
 */
class FlowAction extends Action
{
	protected function engage()
	{
		$aevent = $this->target['aevent'];
		$aservice = $this->target['aservice'];
		$this->mockConfig($aservice);
		$this->target[Flow::ACTION] = $this->flow($aevent, self::TARGET);
		$this->mockConfig($aservice, false);
	}

	private function mockConfig($service = null, $mock = true)
	{
		global $config;
		if (! $mock) {
			if ($this['config'] != null) {
				$config = $this['config'];
			}
			return;
		}
		$this['config'] = $config;
		$config = array(_DEF => $config[_DEF]);
		$config[_DEF][self::SERVICE] = $service._DIR;
		$target = new Target('config.php');
	}

	private function flow($event = null, $from = null)
	{
		if ($event == null || $from == null) {
			return null;
		}
		$event = ucfirst($event);
		$flow = $this->config(self::FLOW, $event.$from);
		if ($flow == null) {
			$flow = $this->config(self::FLOW, $from);
		}
		$class = $from;
		while (($flow == null || ! is_array($flow)) && ($class = get_parent_class($class))) {
			$flow = $this->config(self::FLOW, $class);
		}
		if (! is_array($flow)) {
			return null;
		}
		$class = class_exists($event.$from) ? $event.$from : $from;
		$result = array();
		foreach ($flow as $item => $mode) {
			if ($mode === self::NIL) {
				continue;
			}
			if (method_exists($class, $item)) {
				$result[$class][$item] = '方法';
			} else if (class_exists($item)) {
				$result[$class][$item] = '委托';
				$this->stuff($this->flow($event, $item), $result);
			}
			if (! $mode) {
				break;
			}
		}
		return $result;
	}
}
?>
