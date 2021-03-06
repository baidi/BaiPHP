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
class FlowUpdateAction extends Action
{
	/**
	 * 相关文件
	 */
	protected $include = null;

	protected function engage()
	{
		$this->end = true;
		$basin = _LOCAL.$this->event['abasin']._DIR;
		$event = $this->event['aevent'];
		$oevent = $this->event['oevent'];
		Log::logf(__FUNCTION__, $this->event['abasin']._DIR.$event, __CLASS__);
		$status = true;
		foreach ($this->include as $item => $file) {
			$ofile = sprintf($file, $oevent);
			if ($item !== self::PAGE) {
				$ofile = ucfirst($ofile);
			}
			$ofile = $basin.$item._DIR.$ofile;
			if (is_file($ofile)) {
				$file = sprintf($file, $event);
				if ($item !== self::PAGE) {
					$file = ucfirst($file);
					$content = file_get_contents($ofile);
					$search =  '#(?<=\s)'.ucfirst($oevent).$item.'(?=\s|::)#';
					$content = preg_replace($search, ucfirst($event).$item, $content);
					file_put_contents($ofile, $content);
				}
				$file = $basin.$item._DIR.$file;
				$status = rename($ofile, $file) && $status;
			}
		}
		$result = array('status' => $status);
		if (! $status) {
			$result['notice'] = Log::logs('fail', __CLASS__);
		}
		return json_encode($result);
	}
}
