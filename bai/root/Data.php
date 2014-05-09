<?php
################################################################################
# BaiPHP Mobile Framework
# http://www.baiphp.com
# Copyright (C) 2011-2014 Xiao Yang, Bai
#
# Anyone obtaining a copy of BaiPHP gets permission to use, copy, modify, merge,
# publish, distribute, and/or sell it for non-profit purpose.
# Any contributor to BaiPHP gets for-profit permission for itself only, which
# can't be transferred or rent.
# Authors or copyright holders don't take any for all the consequences arising
# therefrom.
# By using BaiPHP, you are unconditionally agree to this notice and must keep it
# in the copy.
################################################################################


/**
 * <h2>BaiPHP Mobile Framework</h2>
 * <h3>Data work</h3>
 * <p>
 * Connect to database and CRUD records
 * </p>
 *
 * @copyright Copyright (C) 2011-2014 Xiao Yang, Bai
 * @author Xiao Yang, Bai
 */
class Data extends Work
{
	/**
	 * 数据库访问入口
	 */
	protected $pdo = null;

	/**
	 * 数据库连接串
	 */
	protected $dsn = null;
	/**
	 * 用户名
	 */
	protected $user = null;
	/**
	 * 密码
	 */
	protected $password = null;
	/**
	 * 字符集
	 */
	protected $charset = 'utf8';
	/**
	 * 是否保持连接
	 */
	protected $lasting = false;
	/**
	 * 重复前缀
	 */
	protected $pre = '_w_';

	/**
	 * Static entrance
	 */
	protected static $ENTRANCE = false;

	/**
	 * <h4>Get static entrance</h4>
	 *
	 * @param array $settings
	 *        runtime settings
	 * @return Data
	 */
	public static function access($settings = null)
	{
		if ($settings != null || !self::$ENTRANCE instanceof Data)
		{
			return new Data($settings);
		}
		return self::$ENTRANCE;
	}

	/**
	 * <h4>Count records</h4>
	 * <p>检索条件为列表，其中以表字段名作为键名，各条件默认以and链接 </p>
	 *
	 * @param string $table
	 *        table name
	 * @param array $where
	 *        conditions
	 * @return int record count
	 */
	public static function count($table = null, $where = null)
	{
		$data = Data::access();

		if ($table == null)
		{
			$data->message = Log::logs('table', __CLASS__, Log::EXCEPTION);
			return false;
		}
		if ($where != null && !is_array($where))
		{
			$data->message = Log::logs('where', __CLASS__, Log::EXCEPTION);
			return false;
		}

		### build sql
		$sql = 'SELECT COUNT(1) AS TOTAL FROM ' . $data->field($table);
		if ($where != null)
		{
			$sql .= ' WHERE ' . $data->join($where);
		}

		### execute sql
		$rows = $data->entrust($sql, $where);
		if ($rows !== false)
		{
			$count = (int) $rows[0]['TOTAL'];
			Log::logf(__FUNCTION__, $count, __CLASS__);
			return $count;
		}
		return $rows;
	}

	/**
	 * <h4>Read records</h4>
	 * <p>检索条件为列表，其中以表字段名作为键名，各条件默认以and链接 </p>
	 *
	 * @param string $table
	 *        table name
	 * @param array $where
	 *        conditions
	 * @param stirng $order
	 * @param integer $limit
	 * @param integer $offset
	 * @return array records
	 */
	public static function read($table = null, $where = null, $order = null, $limit = 0, $offset = 0)
	{
		$data = Data::access();

		if ($table == null)
		{
			$data->message = Log::logs('table', __CLASS__, Log::EXCEPTION);
			return false;
		}
		if ($where != null && !is_array($where))
		{
			$data->message = Log::logs('where', __CLASS__, Log::EXCEPTION);
			return false;
		}

		### build sql
		$sql = 'SELECT * FROM ' . $data->field($table);
		if ($where != null)
		{
			$sql .= ' WHERE ' . $data->join($where);
		}
		if ($order != null)
		{
			$sql .= ' ORDER BY ' . $data->join($order, ',', false);
		}
		if ($limit > 0)
		{
			$sql .= ' LIMIT ' . $limit;
			if ($offset > 0)
			{
				$sql .= ' OFFSET ' . $offset;
			}
		}

		### execute sql
		$rows = $data->entrust($sql, $where);
		if ($rows !== false)
		{
			Log::logf(__FUNCTION__, count($rows), __CLASS__);
		}
		return $rows;
	}

	/**
	 * <h4>Create a record</h4>
	 * <p>字段值为列表，其中以表字段名作为键名 </p>
	 *
	 * @param string $table
	 *        table name
	 * @param array $fields
	 * @return int record id
	 */
	public static function create($table = null, $fields = null)
	{
		$data = Data::access();

		if ($table == null)
		{
			$data->message = Log::logs('table', __CLASS__, Log::EXCEPTION);
			return false;
		}
		if ($fields == null || !is_array($fields))
		{
			$data->message = Log::logs('fields', __CLASS__, Log::EXCEPTION);
			return false;
		}

		### build sql
		$sql = 'INSERT INTO ' . $data->field($table) . ' SET ' . $data->join($fields, ',');

		### execute sql
		$data->pdo->beginTransaction();
		$count = $data->entrust($sql, $fields);
		if ($count === false)
		{
			$data->pdo->rollback();
			return $count;
		}
		Log::logf(__FUNCTION__, $count, __CLASS__);
		$id = $data->pdo->lastInsertId();
		$data->pdo->commit();
		return $id;
	}

	/**
	 * <h4>Update a record</h4>
	 * <p> 字段值为数列表，其中以表字段名作为键名。 检索条件为列表，其中以表字段名作为键名，各条件默认以and链接。 </p>
	 *
	 * @param string $table
	 *        table name
	 * @param array $fields
	 * @param array $where
	 *        conditions
	 * @return int result
	 */
	public static function update($table = null, $values = null, $where = null)
	{
		$data = Data::access();

		if ($table == null)
		{
			$data->message = Log::logs('table', __CLASS__, Log::EXCEPTION);
			return false;
		}
		if ($fields == null || !is_array($fields))
		{
			$data->message = Log::logs('values', __CLASS__, Log::EXCEPTION);
			return false;
		}
		if ($where == null || !is_array($where))
		{
			$data->message = Log::logs('where', __CLASS__, Log::EXCEPTION);
			return false;
		}

		### 排除重复键
		foreach ($where as $item => $value)
		{
			if (isset($fields[$item]))
			{
				$where[$data->pre . $item] = $where[$item];
				unset($where[$item]);
			}
		}

		### build sql
		$sql = 'UPDATE ' . $data->field($table) . ' SET ' . $data->join($fields, ',');
		$sql .= ' WHERE ' . $data->join($where);

		### execute sql
		$data->pdo->beginTransaction();
		$count = $data->entrust($sql, $fields + $where);
		if ($count === false)
		{
			$data->pdo->rollback();
			return $count;
		}
		Log::logf(__FUNCTION__, $count, __CLASS__);
		$data->pdo->commit();
		return $count;
	}

	/**
	 * <h4>Delete record(s)</h4>
	 * <p> 检索条件为列表，其中以表字段名作为键名，各条件默认以and链接。 </p>
	 *
	 * @param string $table
	 *        table name
	 * @param array $where
	 *        conditions
	 * @return integer result
	 */
	public static function delete($table = null, $where = null)
	{
		$data = Data::access();

		if ($table == null)
		{
			$data->message = Log::logs('table', __CLASS__, Log::EXCEPTION);
			return false;
		}
		if ($where == null || !is_array($where))
		{
			$data->message = Log::logs('where', __CLASS__, Log::EXCEPTION);
			return false;
		}

		### build sql
		$sql = 'DELETE FROM ' . $data->field($table) . ' WHERE ' . $data->join($where);

		### execute sql
		$data->pdo->beginTransaction();
		$count = $data->entrust($sql, $where);
		if ($count === false)
		{
			$data->pdo->rollback();
			return $count;
		}
		Log::logf(__FUNCTION__, $count, __CLASS__);
		$data->pdo->commit();
		return $count;
	}

	/**
	 * <h4>Show a table</h4>
	 * <p> 根据数据表名读取列定义。 </p>
	 *
	 * @param string $table
	 *        table name
	 * @return array rows
	 */
	public static function show($table = null)
	{
		$data = Data::access();

		if ($table == null)
		{
			$data->message = Log::logs('table', __CLASS__, Log::EXCEPTION);
			return false;
		}

		### build sql
		$sql = 'SHOW FULL COLUMNS FROM ' . $data->field($table);

		### execute sql
		$rows = $data->entrust($sql);
		if ($rows !== false)
		{
			Log::logf(__FUNCTION__, $table, __CLASS__);
		}
		return $rows;
	}

	/**
	 * <h4>Execute sql</h4>
	 * <p> SQL语句中以<:field>作为占位符，参数列表中以<field>作为键名。 </p>
	 *
	 * @param string $sql
	 * @param array $params
	 * @return mixed rows effected or records
	 */
	public function entrust($sql = null, $params = null)
	{
		if ($sql == null || $this->pdo == null)
		{
			return false;
		}
		Log::logf('sql', $sql, __CLASS__);

		### execute sql
		$stm = $this->pdo->prepare($sql, array(
			PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY
		));
		if (!$stm || !$stm->execute($params))
		{
			$this->message = Log::logs(__FUNCTION__, __CLASS__, Log::EXCEPTION);
			Log::logs(self::pick(2, $stm->errorInfo()), null, Log::EXCEPTION);
			return false;
		}

		### execute result
		$column = $stm->columnCount();
		if ($column > 0)
		{
			$rows = $stm->fetchAll(PDO::FETCH_ASSOC);
			foreach ($rows as &$row)
			{
				$row = array_change_key_case($row, CASE_UPPER);
			}
			return $rows;
		}
		return $stm->rowCount();
	}

	/**
	 * <h4>连结SQL参数</h4>
	 *
	 * @param array $params
	 *        SQL参数
	 * @param string $gap
	 *        间隔符
	 * @param bool $holder
	 *        占位符
	 * @return string
	 */
	protected function join($params = null, $gap = 'and', $holder = true)
	{
		$sql = '';
		if ($params == null || !is_array($params))
		{
			return $sql;
		}
		if ($holder)
		{
			foreach ($params as $item => $value)
			{
				$field = $this->field($item);
				$sql .= "$gap $field=:$item ";
			}
			return substr($sql, strlen($gap));
		}
		foreach ($params as $item)
		{
			$field = $this->field($item);
			$sql .= "$gap $field ";
		}
		return substr($sql, strlen($gap));
	}

	/**
	 * <h4>Escape field</h4>
	 *
	 * @param string $field
	 *        field name
	 * @return string
	 */
	protected function field($field = null)
	{
		if ($field == null || !is_string($field))
		{
			return '``';
		}
		if (strpos($field, $this->pre) === 0)
		{
			$field = substr($field, strlen($this->pre));
		}
		$field = '`' . str_replace('`', '', $field) . '`';
		$field = str_replace('.', '`.`', $field);
		return $field;
	}

	/**
	 * <h4>Connect to DB</h4>
	 *
	 * @return bool success or not
	 */
	protected function connect()
	{
		### 数据库连接串
		$template = self::pick($this->dbtype, $this->templates);
		$params = array(
			'dbtype' => $this->dbtype,
			'dbhost' => $this->dbhost,
			'dbport' => $this->dbport,
			'dbname' => $this->dbname
		);
		$this->dsn = Template::fetch($template, $params);
		### 检查连接信息
		if ($this->dsn == null || $this->user == null)
		{
			$this->message = Log::logs('config', __CLASS__, Log::EXCEPTION);
			return false;
		}
		### 连接数据库
		try
		{
			$this->pdo = new PDO($this->dsn, $this->user, $this->password);
			if ($this->charset != null)
			{
				$this->pdo->query('set character set ' . $this->field($this->charset));
			}
			if ($this->lasting)
			{
				self::$ENTRANCE = $this;
			} else
			{
				self::$ENTRANCE = $this->config;
			}
		} catch (PDOException $e)
		{
			$this->message = Log::logs(__FUNCTION__, __CLASS__, Log::EXCEPTION);
			Log::logs($e->getMessage(), null, Log::EXCEPTION);
			return false;
		}
		return true;
	}

	/**
	 * <h4>Build data work and connection</h4>
	 *
	 * @param array $settings
	 *        runtime settings
	 * @return void
	 */
	protected function __construct($settings = null)
	{
		parent::__construct($settings);
		$this->fit($settings, self::$ENTRANCE);
		$this->fit(self::$ENTRANCE);
		if (!$this->connect())
		{
			trigger_error($this->message, E_USER_ERROR);
		}
	}

	/**
	 * <h4>Release data work and connection</h4>
	 */
	public function __destruct()
	{
		$this->pdo = null;
	}
}
?>
