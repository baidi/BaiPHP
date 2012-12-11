<?php

### 权限检查
if (! defined('_ISSUE'))
	exit('对不起！请走正门……');

/**
 * <b>Bai事件分派流程：</b>
 * <p>响应事件并分派至相应处理</p>
 *
 * @author    白晓阳
 * @copyright Copyright (c) 2011 - 2012, 白晓阳
 * @link       http://www.dacbe.com
 * @version    V1.0.0 2012/03/21 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 */
class Issue extends Flow
{
	/** 日志入口 */
	protected $log = null;

	/**
	 * 响应事件并分派相应处理
	 * @param 请求事件： $event
	 */
	public function assign($event)
	{
		$action = cRequest($event, __CLASS__);
		$message = $this->log->messagef(__CLASS__, $action);
		$this->log->assign($message, Log::L_DEBUG);

		$action = new $action();
		$action->assign($event);
	}

	public function __construct()
	{
		$this->log = Log::access();
	}
}
?>