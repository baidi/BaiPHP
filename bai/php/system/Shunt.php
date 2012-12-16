<?php
/**
 * <b>BaiPHP（简单PHP）开发框架</b>
 * @author		白晓阳
 * @copyright	Copyright (c) 2011 - 2012, 白晓阳
 * @link		http://dacbe.com
 * @version     V1.0.0 2012/03/31 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 */

/**
 * <b>BaiPHP（简单PHP）开发框架</b><br/>
 * <b>事件分流流程：</b>
 * <p>
 * 对事件进行分流并分派至相应处理
 * ！！！一般不建议重写分流流程，只需指定不同的分流名（子目录名）即可完成分流！！！
 * </p>
 * @author 白晓阳
 * @see Flow
 */
class Shunt extends Flow
{
	/**
	 * <b>分流事件并分派至相应处理</b><br/>
	 * 
	 * @param Event $event 事件
	 * 
	 * @return void
	 */
	final public function entrust(Event $event = null)
	{
		$this->checkin($event);
		$this->filter($event);
		$action = cFlow($event, __CLASS__);
		Log::logf(__CLASS__, _ISSUE.$action, 'Event');

		### 事件处理
		$action = new $action();
		$event->$action = $action;
		$action->entrust($event);
	}

	/**
	 * <b>访问登记</b>
	 * <p>
	 * 记录访问来源、访问目标和实际响应。
	 * </p>
	 * 
	 * @param Event $event
	 */
	protected function checkin(Event &$event)
	{
		### 访问来源
		$client = array(
				### 客户端主机名
				$_SERVER['REMOTE_HOST'],
				### 客户端IP地址
				$_SERVER['REMOTE_ADDR'],
				### 客户端端口
				$_SERVER['REMOTE_PORT'],
				### 访问起始页（空为URL直连）
				cRead('HTTP_REFERER', $_SERVER),
				### 客户端环境
				$_SERVER['HTTP_USER_AGENT'],
		);
		Log::logf('client', $client, __CLASS__);
		$event->client = $_SERVER['REMOTE_ADDR'];
		### 访问目标
		$server = array(
				### 服务器主机名
				$_SERVER['SERVER_NAME'],
				### 服务器IP地址
				$_SERVER['SERVER_ADDR'],
				### 服务器端口
				$_SERVER['SERVER_PORT'],
				### 访问方式
				$_SERVER['REQUEST_METHOD'],
				### 访问地址
				$_SERVER['REQUEST_URI'],
				### 服务器环境
				$_SERVER['SERVER_SOFTWARE'],
		);
		Log::logf('server', $server, __CLASS__);
		$event->server = $_SERVER['SERVER_ADDR'].':'.$_SERVER['SERVER_PORT'];
		### 实际响应
		$response = array(
				### 响应文件
				$_SERVER['SCRIPT_FILENAME'],
				### 访问参数
				$_SERVER['QUERY_STRING'],
		);
		$event->response = Log::logf('response', $response, __CLASS__);
	}

	/**
	 * <b>访问过滤</b>
	 * <p>
	 * 过滤黑名单地址、非法事件及其他无效访问。
	 * </p>
	 * 
	 * @param Event $event
	 */
	protected function filter(Event &$event)
	{
		
	}
	
	protected function identify(Event &$event)
	{
		
	}
}
?>
