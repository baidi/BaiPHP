<?php
/**
 * <h2>BaiPHP（简单PHP）开发框架</h2>
 * @author		白晓阳
 * @copyright	Copyright (c) 2011 - 2012, 白晓阳
 * @link		http://dacbe.com
 * @version     V1.0.0 2012/03/31 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 */

/**
 * <h2>BaiPHP（简单PHP）开发框架</h2>
 * <h3>用户工场</h3>
 * <p>记录访问信息，并控制访客权限</p>
 */
class User extends Work
{
	static protected $ID = 0;
	static protected $NAME = '_GUEST';
	static protected $RANK = 0;
	public function entrust($username = null, $password = null)
	{
		$this->checkin();
	}

	/**
	 * 访问登记
	 */
	protected function checkin()
	{
		### 访问来源
		$client = array(
				$_SERVER['REMOTE_HOST'],
				$_SERVER['REMOTE_ADDR'],
				$_SERVER['REMOTE_PORT'],
				$_SERVER['HTTP_USER_AGENT'],
				date('Y-m-d H:i:s', $_SERVER['REQUEST_TIME']),
				cRead('HTTP_REFERER', $_SERVER),
		);
		Log::logf('client', $client, __CLASS__);
		$this->client = $_SERVER['REMOTE_ADDR'].':'.$_SERVER['REMOTE_PORT'];
		### 访问目标
		$server = array(
				$_SERVER['SERVER_NAME'],
				$_SERVER['SERVER_ADDR'],
				$_SERVER['SERVER_PORT'],
				$_SERVER['SERVER_SOFTWARE'],
				$_SERVER['REQUEST_METHOD'],
				$_SERVER['REQUEST_URI'],
		);
		Log::logf('server', $server, __CLASS__);
		$this->server = $_SERVER['SERVER_ADDR'].':'.$_SERVER['SERVER_PORT'];
		### 真实响应
		$script = array(
				$_SERVER['SCRIPT_FILENAME'],
				$_SERVER['QUERY_STRING'],
		);
		$this->script = Log::logf('script', $script, __CLASS__);
	}

	/**
	 * 用户登录
	 *
	 * @param string $username
	 * @param string $password
	 */
	protected function login($username, $password = null)
	{
		$params = array('username' => $username, 'password' => $password);
		$result = Data::read('user', $params);
		if ($result)
		{
			$this->id = $result['id'];
			$this->name = $result['name'];
			$this->rank = $result['rank'];
			$this->cipher = md5($this->id.$this->name.time());
			$_SESSION[$this->name] = $this->cipher;
			return true;
		}
		$this->id = $this->ID;
		$this->name = $this->NAME;
		$this->rank = $this->RANK;
		$this->cipher = md5($this->id.$this->name.time());
		$_SESSION[$this->name] = $this->cipher;
		return false;
	}

	protected function logsession($username, $cipher)
	{
		if (empty($_SESSION[$username]) || $_SESSION[$username] !== $cipher)
		{
			return false;
		}
		return true;
	}

	protected function logout($username)
	{
		session_destroy();
	}
}
?>
