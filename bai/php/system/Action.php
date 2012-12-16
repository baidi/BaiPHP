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
 * <b>事件处理流程：</b>
 * <p>
 * 检验输入、更新缓存、访问数据库并分派至相应页面
 * ！！！服务器端响应客户端请求的核心流程，根据需要继承该类以实现不同的处理！！！
 * ·check：输入检验方法，系统检验无法满足需求时应重写该方法。
 *          如果仍需要系统检验，可使用parent::check($event)。
 * ·data：数据访问方法，需要访问数据库时应重写该方法，调用Data工场实现。
 * ·cache：提取缓存方法，需要操作缓存数据时应重写该方法。
 * ·page：分派页面方法，一般不建议重写该方法。
 * ·assign：事件处理方法，一般不建议重写该方法，除非需要打乱现有处理流程。
 *           ！！！打乱现有流程可能引发不可预料的结果，谨慎处置！！！
 * </p>
 * @author 白晓阳
 * @see Flow
 */
class Action extends Flow
{
	/**
	 * <b>检验输入、更新缓存、访问数据库并分派至相应页面</b><br/>
	 *
	 * @param Event $event 事件
	 *
	 * @return void
	 */
	final public function entrust(Event $event = null)
	{
		Log::logs(__CLASS__, 'Event');
		### 检验输入内容
		if (! $this->check($event))
		{
			### 读取缓存
			if ($cache = $this->cache($event))
			{
				echo $cache;
				return;
			}
			### 访问数据库
			$this->data($event);
		}
		if (defined(_CIPHER) && _CIPHER)
		{
			cCipher($event);
		}
		### 输出页面
		$this->page($event);
	}

	/**
	 * <b>检验输入内容</b><br/>
	 *
	 * @param string $event 事件
	 *
	 * @return mixed 提示信息或false（检验通过）
	 */
	protected function check($event)
	{
		$check = Check::access();
		return $check->entrust($event);
	}

	/**
	 * <b>提取缓存数据</b><br/>
	 *
	 * @param string $event 事件
	 *
	 * @return mixed
	 */
	protected function cache($event)
	{
		if (! defined('_CACHE')) {
			return false;
		}
		$cache = Cache::access();
		return $cache->entrust($event);
	}

	/**
	 * <b>访问数据库</b><br/>
	 *
	 * @param string $event 事件
	 */
	protected function data($event)
	{
		return null;
	}

	/**
	 * <b>分派相应页面</b><br/>
	 *
	 * @param string $event 事件
	 */
	protected function page($event)
	{
		$page = cFlow($event, __CLASS__);
		$page = new $page();
		$event->$page = $page;
		$page->entrust($event);
	}
}
?>
