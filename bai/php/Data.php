<?php
/**
 * <h2>BaiPHP（简单PHP）开发框架</h2>
 * @link      http://www.baiphp.com
 * @copyright Copyright (c) 2011 - 2012, 白晓阳
 * @author    白晓阳
 * @version   1.0.0 2012/03/31 首版
 *            2.0.0 2012/07/01 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 * <p>欢迎提供各种形式的捐助。任何捐助者自动获得仅限于捐助者自身的商业使用（不包括再发行和再授权）授权。</p>
 */

/**
 * <h2>BaiPHP（简单PHP）开发框架</h2>
 * <h3>数据工场</h3>
 * <p>连接数据库并访问数据</p>
 * @author 白晓阳
 */
class Data extends Work
{
	/** 数据库访问入口 */
	protected $pdo = null;
	/** 数据库 */
	protected $dsn = null;
	/** 用户 */
	protected $user = null;
	/** 密码 */
	protected $password = null;
	/** 字符集 */
	protected $charset = 'utf8';
	/** 是否保持 */
	protected $lasting = false;

	/** 数据工场静态入口 */
	static private $ACCESS = null;

	/**
	 * <h4>获取数据工场入口</h4>
	 * @param array $setting 即时配置
	 * @return Data 数据工场
	 */
	static public function access($setting = null)
	{
	    if ($setting != null || ! self::$ACCESS instanceof Data)
	    {
	    	return new Data($setting);
	    }
	    return self::$ACCESS;
	}

	/**
	 * <h4>统计数据</h4>
	 * <p>
	 * 检索条件为列表，其中以表字段名作为键名，各条件默认以and链接
	 * </p>
	 * @param string $table 表名
	 * @param array $where 条件
	 * @return int 数据件数
	 */
	static public function count($table = null, $where = null)
	{
		### 数据连接
		$data = Data::access();
		if ($data == null)
		{
			return false;
		}
		### 数据表
		if ($table == null)
		{
			$data->error = Log::logs('table', __CLASS__, Log::EXCEPTION);
			return false;
		}
		### 条件
		if ($where != null && ! is_array($where))
		{
			$data->error = Log::logs('where', __CLASS__, Log::EXCEPTION);
			return false;
		}

		### 建立SQL语句
		$sql = 'select count(1) as count from '.$data->field($table);
		if ($where != null)
		{
			$sql .= ' where '.$data->join($where);
		}

		### 执行SQL语句
		$rows = $data->entrust($sql, $where);
		if ($rows === false)
		{
			return $rows;
		}
		$count = (int) $rows[0]['count'];
		Log::logf(__FUNCTION__, $count, __CLASS__);
		return $count;
	}

	/**
	 * <h4>检索数据</h4>
	 * <p>
	 * 检索条件为列表，其中以表字段名作为键名，各条件默认以and链接
	 * </p>
	 * @param string $table 表名
	 * @param array $where 条件
	 * @param stirng $order 排序
	 * @param integer $limit 件数
	 * @param integer $offset 起始位置
	 * @return array 检索结果
	 */
	static public function read($table = null, $where = null, $order = null, $limit = 0, $offset = 0)
	{
		### 数据连接
		$data = Data::access();
		if ($data == null)
		{
			return false;
		}
		### 数据表
		if ($table == null)
		{
			$data->error = Log::logs('table', __CLASS__, Log::EXCEPTION);
			return false;
		}
		### 条件
		if ($where != null && ! is_array($where))
		{
			$data->error = Log::logs('where', __CLASS__, Log::EXCEPTION);
			return false;
		}

		### 建立SQL语句
		$sql = 'select * from '.$data->field($table);
		if ($where != null)
		{
			$sql .= ' where '.$data->join($where);
		}
		if ($order != null)
		{
			if (is_array($order))
			{
				$sql .= ' order by '.$data->join($order, ',', false);
			}
			else if (is_string($order))
			{
				$sql .= ' order by '.$order;
			}
		}
		if ($limit > 0)
		{
			$sql .= ' limit '.$limit;
			if ($offset > 0) {
				$sql .= ' offset '.$offset;
			}
		}

		### 执行SQL语句
		$rows = $data->entrust($sql, $where);
		if ($rows === false)
		{
			return $rows;
		}
		Log::logf(__FUNCTION__, count($rows), __CLASS__);
		return $rows;
	}

	/**
	 * <h4>新建数据</h4>
	 * <p>
	 * 字段值为列表，其中以表字段名作为键名
	 * </p>
	 * @param string $table 数据表
	 * @param array $values 字段值
	 * @return int 执行结果
	 */
	static public function create($table = null, $values = null)
	{
		### 数据连接
		$data = Data::access();
		if ($data == null)
		{
			return false;
		}
		### 数据表
		if ($table == null)
		{
			$data->error = Log::logs('table', __CLASS__, Log::EXCEPTION);
			return false;
		}
		### 字段值
		if ($values == null || ! is_array($values))
		{
			$data->error = Log::logs('values', __CLASS__, Log::EXCEPTION);
			return false;
		}

		### 建立SQL语句
		$sql = 'insert into '.$data->field($table).' set '.$data->join($values, ',');

		### 执行SQL语句
		$data->pdo->beginTransaction();
		$count = $data->entrust($sql, $values);
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
	 * <h4>更新数据</h4>
	 * <p>
	 * 字段值为数列表，其中以表字段名作为键名。
	 * 检索条件为列表，其中以表字段名作为键名，各条件默认以and链接。
	 * </p>
	 * @param string $table 数据表
	 * @param array $values 字段值
	 * @param array $where 条件
	 * @return int 执行结果
	 */
	static public function update($table = null, $values = null, $where = null)
	{
		### 数据连接
		$data = Data::access();
		if ($data == null)
		{
			return false;
		}
		### 数据表
		if ($table == null)
		{
			$data->error = Log::logs('table', __CLASS__, Log::EXCEPTION);
			return false;
		}
		### 字段值
		if ($values == null || ! is_array($values))
		{
			$data->error = Log::logs('values', __CLASS__, Log::EXCEPTION);
			return false;
		}
		### 条件
		if ($where == null || ! is_array($where))
		{
			$data->error = Log::logs('where', __CLASS__, Log::EXCEPTION);
			return false;
		}

		### 建立SQL语句
		$sql = 'update '.$data->field($table).' set '.$data->join($values, ',');
		$sql .= ' where '.$data->join($where);

		### 执行SQL语句
		$data->pdo->beginTransaction();
		$count = $data->entrust($sql, $values + $where);
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
	 * <h4>删除数据</h4>
	 * <p>
	 * 检索条件为列表，其中以表字段名作为键名，各条件默认以and链接。
	 * </p>
	 * @param string 表名 $table
	 * @param array 条件 $where
	 * @return integer 执行结果
	 */
	static public function delete($table = null, $where = null)
	{
		### 数据连接
		$data = Data::access();
		if ($data == null)
		{
			return false;
		}
		### 数据表
		if ($table == null)
		{
			$data->error = Log::logs('table', __CLASS__, Log::EXCEPTION);
			return false;
		}
		### 条件
		if ($where == null || ! is_array($where))
		{
			$data->error = Log::logs('where', __CLASS__, Log::EXCEPTION);
			return false;
		}

		### 建立SQL语句
		$sql = 'delete from '.$data->field($table).' where '.$data->join($where);

		### 执行SQL语句
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
	 * <h4>执行SQL语句</h4>
	 * <p>
	 * SQL语句中以<:field>作为占位符，参数列表中以<field>作为键名。
	 * </p>
	 * @param string $sql SQL语句
	 * @param array $params SQL参数
	 * @return mixed 执行结果：影响件数或检索结果
	 */
	public function entrust($sql = null, $params = null)
	{
		if ($sql == null || $this->pdo == null)
		{
			return false;
		}

		### SQL语句
		Log::logs($sql);

		### 执行SQL语句
		$stm = $this->pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		if (! $stm || ! $stm->execute($params))
		{
			$this->error = Log::logs(__FUNCTION__, __CLASS__, Log::EXCEPTION);
			Log::logs($this->pick(2, $stm->errorInfo()), null, Log::EXCEPTION);
			return false;
		}

		### 执行结果
		$column = $stm->columnCount();
		if ($column > 0)
		{
			return $stm->fetchAll(PDO::FETCH_ASSOC);
		}
		return $stm->rowCount();
	}

	/**
	 * <h4>连结SQL参数</h4>
	 * @param array $params SQL参数
	 * @param string $gap 间隔符
	 * @param bool $holder 占位符
	 * @return string
	 */
	protected function join($params = null, $gap = 'and', $holder = true)
	{
		$sql = '';
		if ($params == null || ! is_array($params))
		{
			return $sql;
		}
		if ($holder) {
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
	 * <h4>转义字段名</h4>
	 * @param string $field
	 * @return string
	 */
	protected function field($field = null)
	{
		if ($field == null || ! is_string($field))
		{
			return '``';
		}
		$field = '`'.str_replace('`', '``', $field).'`';
		$field = str_replace('.', '`.`', $field);
		return $field;
	}

	/**
	 * <h4>连接数据库</h4>
	 * @return bool 是否成功
	 */
	protected function connect()
	{
		### 连接信息
		if ($this->dsn == null || $this->user == null)
		{
			$this->error = Log::logs(__FUNCTION__, __CLASS__, Log::EXCEPTION);
			return false;
		}
		### 连接数据库
		try
		{
			$this->pdo = new PDO($this->dsn, $this->user, $this->password);
			if ($this->charset != null)
			{
				$this->pdo->query('set character set '.$this->field($this->charset));
			}
			if ($this->lasting)
			{
				self::$ACCESS = $this;
			}
			else
			{
				self::$ACCESS = $this->preset;
			}
		}
		catch (PDOException $e)
		{
			$this->error = Log::logs(__FUNCTION__, __CLASS__, Log::EXCEPTION);
			Log::logs($e->getMessage(), null, Log::EXCEPTION);
			return false;
		}
		return true;
	}

	/**
	 * <h4>构建数据工场</h4>
	 * @param array $setting 即时配置
	 */
	protected function __construct($setting = null)
	{
		parent::__construct($setting);
		### 连接数据库
		$this->stuff(self::$ACCESS, $this->preset);
		$this->stuff($setting, $this->preset);
		$this->stuff($this->pick(_DEFAULT, $this->preset));
		if (! $this->connect())
		{
			$this->target->error = $this->error;
			trigger_error($this->error, E_USER_ERROR);
		}
	}
}
?>
