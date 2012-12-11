<?php

### 权限检查
if (! defined('_ISSUE'))
	exit('对不起！请走正门……');

/**
 * <b>Bai数据访问工场：</b>
 * <p>连接数据库并访问数据</p>
 *
 * @author    白晓阳
 * @copyright Copyright (c) 2011 - 2012, 白晓阳
 * @link       http://www.dacbe.com
 * @version    V1.0.0 2012/03/21 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 */
class Data extends Work
{
	/** 数据库入口 */
	protected $db = null;
	/** 日志入口 */
	protected $log = null;
	/** 数据静态入口 */
	protected static $ACCESS = null;

	/**
	 * 获取数据入口
	 * @param 连接信息： $access
	 */
	public static function access($access = null)
	{
		if ($access)
		{
			self::$ACCESS = $access;
			return null;
		}
		return new Data(self::$ACCESS);
	}

	/**
	 * 执行SQL语句
	 * @param SQL语句： $sql
	 * @param SQL参数： $params
	 */
	public function sql($sql, $params = null)
	{
		if (! $this->db || ! $sql)
			return;

		$message = $this->log->message(__CLASS__);
		$this->log->assign($message, Log::L_DEBUG);

		### SQL语句
		$this->log->assign($sql, Log::L_DEBUG);

		### 执行SQL语句
		$stm = $this->db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		if (! $stm || ! $stm->execute($params))
		{
			$message = $this->log->message(__CLASS__, __CLASS__);
			$this->log->assign($message, Log::L_EXCEPTION);
			$this->log->assign($this->db->errorInfo(), Log::L_EXCEPTION);
			return null;
		}

		### 执行结果
		$amount = $stm->rowCount();
		$message = $this->log->messagef(__FUNCTION__, $amount, __CLASS__);
		$this->log->assign($message, Log::L_DEBUG);
		$result = $stm->fetchAll();
		if (! $result)
			return $amount;
		return $result;
	}

	/**
	 * 统计数据
	 * @param 表名： $table
	 * @param 条件： $where
	 */
	public function count($table, $where = null)
	{
		if (! $this->db || ! $table)
			return;

		$message = $this->log->message(__CLASS__);
		$this->log->assign($message, Log::L_DEBUG);

		### SQL语句
		$sql = 'select count(0) as amount from $table';
		if ($where)
			$sql .= ' where '.$this->join($where, 'and');
		$this->log->assign($sql, Log::L_DEBUG);

		### 执行SQL语句
		$stm = $this->db->query($sql);
		if (! $stm)
		{
			$message = $this->log->message(__CLASS__, __CLASS__);
			$this->log->assign($message, Log::L_EXCEPTION);
			$this->log->assign($this->db->errorInfo(), Log::L_EXCEPTION);
			return null;
		}

		### 执行结果
		$result = $stm->fetch();
		$amount = $result['amount'];
		$message = $this->log->messagef(__FUNCTION__, $amount, __CLASS__);
		$this->log->assign($message, Log::L_DEBUG);
		return $amount;
	}

	/**
	 * 检索数据
	 * @param 表名： $table
	 * @param 条件： $where
	 * @param 排序： $order
	 * @param 件数： $limit
	 */
	public function read($table, $where = null, $order = null, $limit = 0)
	{
		if (! $this->db || ! $table)
			return;

		$message = $this->log->message(__CLASS__);
		$this->log->assign($message, Log::L_DEBUG);

		### SQL语句
		$sql = "select * from $table";
		if ($where) $sql .= ' where '.$this->join($where, 'and');
		if ($order) $sql .= ' order by '.$this->join($order);
		if ($limit) $sql .= " limit $limit";
		$this->log->assign($sql, Log::L_DEBUG);

		### 执行SQL语句
		$stm = $this->db->query($sql);
		if (! $stm)
		{
			$message = $this->log->message(__CLASS__, __CLASS__);
			$this->log->assign($message, Log::L_EXCEPTION);
			$this->log->assign($this->db->errorInfo(), Log::L_EXCEPTION);
			return null;
		}

		### 执行结果
		$amount = $stm->columnCount();
		$message = $this->log->messagef(__FUNCTION__, $amount, __CLASS__);
		$this->log->assign($message, Log::L_DEBUG);
		$result = $stm->fetchAll();
		return $result;
	}

	/**
	 * 追加数据
	 * @param 表名：   $table
	 * @param 字段值： $values
	 */
	public function add($table, $values)
	{
		if (! $this->db || ! $table)
			return;

		$message = $this->log->message(__CLASS__);
		$this->log->assign($message, Log::L_DEBUG);

		### 字段值
		if (! $values)
		{
			$message = $this->log->message('value', __CLASS__);
			$this->log->assign($message);
			$this->log->assign($this->db->errorInfo());
			return null;
		}

		### SQL语句
		$sql = "insert into $table set ".$this->join($values);
		$this->log->assign($sql, Log::L_DEBUG);

		### 执行SQL语句
		$this->db->beginTransaction();
		$stm = $this->db->exec($sql);
		if ($stm === false)
		{
			$this->db->rollBack();
			$message = $this->log->message(__CLASS__, __CLASS__);
			$this->log->assign($message, Log::L_EXCEPTION);
			$this->log->assign($this->db->errorInfo());
			return null;
		}
		$this->db->commit();

		### 执行结果
		$amount = $stm->rowCount();
		$message = $this->log->messagef(__FUNCTION__, $amount, __CLASS__);
		$this->log->assign($message, Log::L_DEBUG);
		return $amount;
	}

	/**
	 * 更新数据
	 * @param 表名：   $table
	 * @param 字段值： $values
	 * @param 条件：   $where
	 */
	public function update($table, $values, $where)
	{
		if (! $this->db || ! $table)
			return;

		$message = $this->log->message(__CLASS__);
		$this->log->assign($message, Log::L_DEBUG);

		### 字段值
		if (! $values)
		{
			$message = $this->log->message('value', __CLASS__);
			$this->log->assign($message);
			return null;
		}
		### 条件
		if (! $where)
		{
			$message = $this->log->message('where', __CLASS__);
			$this->log->assign($message);
			return null;
		}

		### SQL语句
		$sql = "update $table set ".$this->join($values);
		$sql .= ' where '.$this->join($where, 'and');
		$this->log->assign($sql, Log::L_DEBUG);

		### 执行SQL语句
		$this->db->beginTransaction();
		$stm = $this->db->exec($sql);
		if ($stm === false)
		{
			$this->db->rollBack();
			$message = $this->log->message(__CLASS__, __CLASS__);
			$this->log->assign($message, Log::L_EXCEPTION);
			$this->log->assign($this->db->errorInfo());
			return null;
		}
		$this->db->commit();

		### 执行结果
		$amount = $stm->rowCount();
		$message = $this->log->messagef(__FUNCTION__, $amount, __CLASS__);
		$this->log->assign($message, Log::L_DEBUG);
		return $amount;
	}

	/**
	 * 删除数据
	 * @param 表名： $table
	 * @param 条件： $where
	 */
	public function delete($table, $where)
	{
		if (! $this->db || ! $table)
			return;

		$message = $this->log->message(__CLASS__);
		$this->log->assign($message, Log::L_DEBUG);

		### 条件
		if (! $where)
		{
			$message = $this->log->message('where', __CLASS__);
			$this->log->assign($message);
			return null;
		}

		### SQL语句
		$sql = "delete from $table where ".$this->join($where, "and");
		$this->log->assign($sql, Log::L_DEBUG);

		### 执行SQL语句
		$this->db->beginTransaction();
		$stm = $this->db->exec($sql);
		if ($stm === false)
		{
			$this->db->rollBack();
			$message = $this->log->message(__CLASS__, __CLASS__);
			$this->log->assign($message, Log::L_EXCEPTION);
			$this->log->assign($this->db->errorInfo());
			return null;
		}
		$this->db->commit();

		### 执行结果
		$amount = $stm->rowCount();
		$message = $this->log->messagef(__FUNCTION__, $amount, __CLASS__);
		$this->log->assign($message, Log::L_DEBUG);
		return $amount;
	}

	/**
	 * 输入值转换成SQL值
	 * @param 输入值： $value
	 */
	protected function convert($value)
	{
		if (is_string($value))
			if (trim($value) == '')
				return 'null';
			return $this->db->quote($value);
		if (is_bool($value))
			return ($value ? 1 : 0);
		if (is_null($value))
			return 'null';
		return $value;
	}

	/**
	 * 连结SQL参数
	 * @param SQL参数： $params
	 * @param 连接符：  $glue
	 */
	protected function join($params, $glue = ',')
	{
		if (! is_array($params))
			return $params;
		$sql = '';
		foreach ($params as $key => $value)
		{
			$value = $this->convert($value);
			$sql .= "$glue $key = $value ";
		}
		return substr($sql, strlen($glue));
	}

	/**
	 * 连接数据库
	 * @param 连接信息 $access
	 */
	private function connect($access)
	{
		if (! $access)
			return;
		try
		{
			$this->db = new PDO($access['dsn'], $access['user'],
					$access['password']);
			$this->db->query("set names 'utf8'");
		}
		catch (PDOException $e)
		{
			$message = $this->log->message(__FUNCTION__, __CLASS__);
			$this->log->assign($message, Log::L_EXCEPTION);
			$this->log->assign($e->getMessage(), Log::L_EXCEPTION);
		}
	}

	private function __construct($access)
	{
		$this->log = Log::access();
		$this->connect($access);
	}

	//	function __destruct()
	//	{
	//		$this->db = null;
	//	}
}
?>