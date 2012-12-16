<?php
/**
 * <b>BaiPHP（简单PHP）开发框架</b>
 * @author		白晓阳
 * @copyright	Copyright (c) 2011 - 2012, 白晓阳
 * @link		http://dacbe.com
 * @version    V1.0.0 2012/03/31 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 */

/**
 * <b>BaiPHP（简单PHP）开发框架</b><br/>
 * <b>数据工场</b>
 * <p>连接数据库并访问数据</p>
 *
 * @author 白晓阳
 * @see Work
 */
class Data extends Work
{
	/** 数据标识：数据 */
	const DATA = 'Data';
	/** 数据标识：表头 */
	const TITLE = 'Title';
	/** 数据标识：记录 */
	const RECORD = 'Record';

	/** DB连接符：and */
	static protected $AND = 'and';
	/** DB连接符：, */
	static protected $COMMA = ',';
	/** 数据工场静态入口 */
	static protected $ACCESS = null;

	/** 数据库访问入口 */
	protected $pdo = null;

	/**
	 * <b>获取数据工场入口</b><br/>
	 * 该入口是静态单一入口<br/>
	 * ！！！长连接仅适用于数据访问特别频繁的情况！！！
	 *
	 * @param array $access 数据库连接信息
	 * @param boolean $p 长连接标识
	 *
	 * @return DB 数据工场
	 */
	static public function access(array $access = null, $p = false)
	{
		### 获取现有连接
		if (! $access)
		{
			if (is_object(self::$ACCESS))
			{
				return $ACCESS;
			}
			if (is_array(self::$ACCESS))
			{
				return new Data(self::$ACCESS);
			}
			Log::logs(__FUNCTION__, __CLASS__);
			return false;
		}
		if ($p)
		{
			### 建立长连接
			self::$ACCESS = new Data($access);
			return self::$ACCESS;
		}
		### 建立普通连接
		self::$ACCESS = $access;
		return true;
	}

	/**
	 * <b>执行SQL语句</b><br/>
	 * SQL语句中以〖:fieldName〗作为占位符，参数列表中以〖fieldName〗作为键值<br/>
	 *
	 * @param string $sql SQL语句
	 * @param array $params SQL参数列表
	 *
	 * @return mixed 执行结果：影响件数或检索结果
	 */
	public function entrust($sql = null, array $params = null)
	{
		if ( ! $sql || ! $this->pdo)
		{
			Log::logs('access', __CLASS__, Log::L_WARING);
			return false;
		}

		### SQL语句
		Log::logs($sql);

		### 执行SQL语句
		$stm = $this->pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		if (! $stm || ! $stm->execute($params))
		{
			Log::logs(__CLASS__, __CLASS__, Log::L_EXCEPTION);
			Log::logs($this->pdo->errorInfo(), null, Log::L_EXCEPTION);
			return false;
		}

		### 执行结果
		$rowCount = $stm->rowCount();
		$rows = $stm->fetchAll();
		Log::logf(__FUNCTION__, $rowCount, __CLASS__);
		if (! $rows)
		{
			return $rowCount;
		}
		return $rows;
	}

	/**
	 * <b>统计数据件数</b><br/>
	 * 检索条件为数组形式，其中以表字段名作为键值，各条件默认以and链接
	 *
	 * @param string $table 表名
	 * @param array $where 条件
	 *
	 * @return integer 执行结果
	 */
	static public function count($table, array $where = null)
	{
		if (! $table || ! ($data = Data::access()))
		{
			Log::logs('access', __CLASS__, Log::L_WARING);
			return false;
		}

		### 建立SQL语句
		$sql = 'select count(0) as rowcount from '.$data->field($table);
		if ($where)
		{
			$sql .= ' where '.$data->join($where);
		}

		### 执行SQL语句
		$rows = $data->entrust($sql, $where);
		if (! is_array($rows)) {
			return false;
		}
		$rowCount = $rows[0]['rowcount'];
		Log::logf(__FUNCTION__, $rowCount, __CLASS__);
		return (int)$rowCount;
	}

	/**
	 * <b>检索数据</b><br/>
	 * 检索条件为数组形式，其中以表字段名作为键值，各条件默认以and链接
	 *
	 * @param string $table 表名
	 * @param array $where 条件
	 * @param stirng $order 排序
	 * @param integer $limit 件数
	 * @param integer $offset 起始位置
	 *
	 * @return array 检索结果
	 */
	static public function read($table, array $where = null, array $order = null, $limit = 0, $offset = 0)
	{
		if (! $table || ! ($data = Data::access()))
		{
			Log::logs('access', __CLASS__, Log::L_WARING);
			return false;
		}

		### 建立SQL语句
		$sql = 'select * from '.$data->field($table);
		if ($where)
		{
			$sql .= ' where '.$data->join($where);
		}
		if ($order)
		{
			$sql .= ' order by '.$data->join($order, self::$COMMA, false);
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
		return $rows;
	}

	/**
	 * <b>追加数据</b><br/>
	 * 字段值为数组形式，其中以表字段名作为键值
	 *
	 * @param string $table 表名
	 * @param array $values 字段值
	 *
	 * @return integer 执行结果
	 */
	static public function create($table, array $values)
	{
		if (! $table || ! ($data = Data::access()))
		{
			Log::logs('access', __CLASS__, Log::L_WARING);
			return false;
		}
		### SQL字段值
		if (! $values)
		{
			Log::logs('value', __CLASS__);
			return false;
		}

		### 建立SQL语句
		$sql = 'insert into '.$data->field($table).' set '.$data->join($values, self::$COMMA);

		### 执行SQL语句
		$data->pdo->beginTransaction();
		$rowCount = $data->entrust($sql, $values);
		if ($rowCount > 0)
		{
			$data->pdo->commit();
			return $rowCount;
		}
		$data->pdo->rollback();
		return false;
	}

	/**
	 * <b>更新数据</b><br/>
	 * 字段值为数组形式，其中以表字段名作为键值
	 * 检索条件为数组形式，其中以表字段名作为键值，各条件默认以and链接<br/>
	 *
	 * @param string $table 表名
	 * @param array $values 字段值
	 * @param array $where 条件
	 *
	 * @return integer 执行结果
	 */
	static public function update($table, array $values, array $where)
	{
		if (! $table || ! ($data = Data::access()))
		{
			Log::logs('access', __CLASS__, Log::L_WARING);
			return false;
		}
		### SQL字段值
		if (! $values)
		{
			Log::logs('value', __CLASS__);
			return false;
		}
		### SQL条件
		if (! $where)
		{
			Log::logs('where', __CLASS__);
			return false;
		}

		### 建立SQL语句
		$sql = 'update '.$data->field($table).' set '.$data->join($values, self::$COMMA);
		$sql .= ' where '.$data->join($where);

		### 执行SQL语句
		$data->pdo->beginTransaction();
		$rowCount = $data->entrust($sql, $values + $where);
		if ($rowCount > 0)
		{
			$data->pdo->commit();
			return $rowCount;
		}
		$data->pdo->rollback();
		return false;
	}

	/**
	 * <b>删除数据</b><br/>
	 * 检索条件为数组形式，其中以表字段名作为键值，各条件默认以and链接<br/>
	 *
	 * @param string 表名 $table
	 * @param array 条件 $where
	 *
	 * @return integer 执行结果
	 */
	static public function delete($table, array $where)
	{
		if (! $table || ! ($data = Data::access())) {
			Log::logs('access', __CLASS__, Log::L_WARING);
			return false;
		}
		### SQL条件
		if (! $where)
		{
			Log::logs('where', __CLASS__);
			return false;
		}

		### 建立SQL语句
		$sql = 'delete from '.$data->field($table).' where '.$data->join($where);

		### 执行SQL语句
		$data->pdo->beginTransaction();
		$rowCount = $data->entrust($sql, $where);
		if ($rowCount > 0)
		{
			$data->pdo->commit();
			return $rowCount;
		}
		$data->pdo->rollback();
		return false;
	}

	/**
	 * <b>连结SQL参数</b><br/>
	 *
	 * @param array $params SQL参数
	 * @param string $gap 分割符
	 *
	 * @return string
	 */
	protected function join(array $params, $gap = 'and', $holder = true)
	{
		$sql = '';
		if ($holder) {
			foreach ($params as $item => $value)
			{
				$field = $data->field($item);
				$sql .= "$gap $field=:$item ";
			}
			return substr($sql, strlen($gap));
		}
		foreach ($params as $item => $value)
		{
			$field = $data->field($item);
			$sql .= "$gap $field ";
		}
		return substr($sql, strlen($gap));
	}
	
	protected function field($field)
	{
		if (! is_string($field) || ! $field)
		{
			return '``';
		}
		$field = '`'.str_replace('`', '``', $field).'`';
		return $field;
	}

	/**
	 * <b>连接数据库</b><br/>
	 *
	 * @param 数据库连接信息 $access
	 *
	 * @return bool
	 */
	private function connect(array $access)
	{
		if (! $access || empty($access['dsn']) || empty($access['user']))
		{
			return false;
		}
		try
		{
			$this->pdo = new PDO($access['dsn'], $access['user'], $access['password']);
			$this->pdo->query('set names `utf8`');
			return true;
		}
		catch (PDOException $e)
		{
			Log::logs(__FUNCTION__, __CLASS__, Log::L_EXCEPTION);
			Log::logs($e->getMessage(), null, Log::L_EXCEPTION);
			return false;
		}
	}

	/**
	 * <b>数据工场初始化</b><br/>
	 * 建立数据库连接
	 *
	 * @param array $access 数据库连接信息
	 */
	private function __construct(array $access)
	{
		$this->connect($access);
	}
}
?>
