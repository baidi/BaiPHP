<?php
/**
 * <h2>化简PHP（BaiPHP）开发框架</h2>
 * @link      http://www.baiphp.net
 * @copyright Copyright (c) 2011 - 2012, 白晓阳
 * @author    白晓阳
 * @version   1.0.0 2012/03/31 首版
 *            2.0.0 2012/07/01 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 * <p>欢迎提供各种形式的捐助。任何捐助者自动获得仅限于捐助者自身的商业使用（不包括再发行和再授权）授权。</p>
 */

/**
 * <h2>化简PHP（BaiPHP）开发框架</h2>
 * <h3>数据工场</h3>
 * <p>连接数据库并访问数据</p>
 * @author 白晓阳
 */
class Data extends Work
{
	/** 数据库访问入口 */
	protected $pdo = null;

	/** 数据库连接串 */
	protected $dsn = null;
	/** 用户名 */
	protected $user = null;
	/** 密码 */
	protected $password = null;
	/** 字符集 */
	protected $charset = 'utf8';
	/** 是否保持连接 */
	protected $lasting = false;
	/** 重复前缀 */
	protected $pre = '_w_';

	/** 数据工场静态入口 */
	private static $ACCESS = null;

	/**
	 * <h4>获取数据工场入口</h4>
	 * @param array $setting 即时配置
	 * @return Data 数据工场
	 */
	public static function access($setting = null)
	{
		if ($setting != null || ! self::$ACCESS instanceof Data) {
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
	public static function count($table = null, $where = null)
	{
		### 数据连接
		$data = Data::access();
		### 数据表
		if ($table == null) {
			$data->notice = Log::logs('table', __CLASS__, Log::EXCEPTION);
			return false;
		}
		### 条件
		if ($where != null && ! is_array($where)) {
			$data->notice = Log::logs('where', __CLASS__, Log::EXCEPTION);
			return false;
		}

		### 建立SQL语句
		$sql = 'SELECT COUNT(1) AS TOTAL FROM '.$data->field($table);
		if ($where != null) {
			$sql .= ' WHERE '.$data->join($where);
		}

		### 执行SQL语句
		$rows = $data->entrust($sql, $where);
		if ($rows !== false) {
			$count = (int) $rows[0]['TOTAL'];
			Log::logf(__FUNCTION__, $count, __CLASS__);
			return $count;
		}
		return $rows;
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
	public static function read($table = null, $where = null, $order = null, $limit = 0, $offset = 0)
	{
		### 数据连接
		$data = Data::access();
		### 数据表
		if ($table == null) {
			$data->notice = Log::logs('table', __CLASS__, Log::EXCEPTION);
			return false;
		}
		### 条件
		if ($where != null && ! is_array($where)) {
			$data->notice = Log::logs('where', __CLASS__, Log::EXCEPTION);
			return false;
		}

		### 建立SQL语句
		$sql = 'SELECT * FROM '.$data->field($table);
		if ($where != null) {
			$sql .= ' WHERE '.$data->join($where);
		}
		if ($order != null) {
			if (is_array($order)) {
				$sql .= ' ORDER BY '.$data->join($order, ',', false);
			} else if (is_string($order)) {
				$sql .= ' ORDER BY '.$order;
			}
		}
		if ($limit > 0) {
			$sql .= ' LIMIT '.$limit;
			if ($offset > 0) {
				$sql .= ' OFFSET '.$offset;
			}
		}

		### 执行SQL语句
		$rows = $data->entrust($sql, $where);
		if ($rows !== false) {
			Log::logf(__FUNCTION__, count($rows), __CLASS__);
		}
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
	public static function create($table = null, $values = null)
	{
		### 数据连接
		$data = Data::access();
		### 数据表
		if ($table == null) {
			$data->notice = Log::logs('table', __CLASS__, Log::EXCEPTION);
			return false;
		}
		### 字段值
		if ($values == null || ! is_array($values)) {
			$data->notice = Log::logs('values', __CLASS__, Log::EXCEPTION);
			return false;
		}

		### 建立SQL语句
		$sql = 'INSERT INTO '.$data->field($table).' SET '.$data->join($values, ',');

		### 执行SQL语句
		$data->pdo->beginTransaction();
		$count = $data->entrust($sql, $values);
		if ($count === false) {
			$data->pdo->rollback();
			return $count;
		}
		Log::logf(__FUNCTION__, $count, __CLASS__);
		$id = $data->pdo->lastInsertId();
		$data->pdo->commit();
		return $id;
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
	public static function update($table = null, $values = null, $where = null)
	{
		### 数据连接
		$data = Data::access();
		### 数据表
		if ($table == null) {
			$data->notice = Log::logs('table', __CLASS__, Log::EXCEPTION);
			return false;
		}
		### 字段值
		if ($values == null || ! is_array($values)) {
			$data->notice = Log::logs('values', __CLASS__, Log::EXCEPTION);
			return false;
		}
		### 条件
		if ($where == null || ! is_array($where)) {
			$data->notice = Log::logs('where', __CLASS__, Log::EXCEPTION);
			return false;
		}
		### 排除重复键
		foreach ($where as $item => $value) {
			if (isset($values[$item])) {
				$where[$data->pre.$item] = $where[$item];
				unset($where[$item]);
			}
		}

		### 建立SQL语句
		$sql = 'UPDATE '.$data->field($table).' SET '.$data->join($values, ',');
		$sql .= ' WHERE '.$data->join($where);

		### 执行SQL语句
		$data->pdo->beginTransaction();
		$count = $data->entrust($sql, $values + $where);
		if ($count === false) {
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
	public static function delete($table = null, $where = null)
	{
		### 数据连接
		$data = Data::access();
		### 数据表
		if ($table == null) {
			$data->notice = Log::logs('table', __CLASS__, Log::EXCEPTION);
			return false;
		}
		### 条件
		if ($where == null || ! is_array($where)) {
			$data->notice = Log::logs('where', __CLASS__, Log::EXCEPTION);
			return false;
		}

		### 建立SQL语句
		$sql = 'DELETE FROM '.$data->field($table).' WHERE '.$data->join($where);

		### 执行SQL语句
		$data->pdo->beginTransaction();
		$count = $data->entrust($sql, $where);
		if ($count === false) {
			$data->pdo->rollback();
			return $count;
		}
		Log::logf(__FUNCTION__, $count, __CLASS__);
		$data->pdo->commit();
		return $count;
	}

	/**
	 * <h4>描述数据</h4>
	 * <p>
	 * 根据数据表名读取列定义。
	 * </p>
	 * @param string 表名 $table
	 * @return array 数据列
	 */
	public static function show($table = null)
	{
		### 数据连接
		$data = Data::access();
		### 数据表
		if ($table == null) {
			$data->notice = Log::logs('table', __CLASS__, Log::EXCEPTION);
			return false;
		}

		### 建立SQL语句
		$sql = 'SHOW FULL COLUMNS FROM '.$data->field($table);

		### 执行SQL语句
		$rows = $data->entrust($sql);
		if ($rows !== false) {
			Log::logf(__FUNCTION__, $table, __CLASS__);
		}
		return $rows;
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
		if ($sql == null || $this->pdo == null) {
			return false;
		}

		### SQL语句
		Log::logf('sql', $sql, __CLASS__);

		### 执行SQL语句
		$stm = $this->pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
		if (! $stm || ! $stm->execute($params)) {
			$this->notice = Log::logs(__FUNCTION__, __CLASS__, Log::EXCEPTION);
			Log::logs($this->pick(2, $stm->errorInfo()), null, Log::EXCEPTION);
			return false;
		}

		### 执行结果
		$column = $stm->columnCount();
		if ($column > 0) {
			$rows = $stm->fetchAll(PDO::FETCH_ASSOC);
			### 字段名转为大写
			foreach ($rows as &$row) {
				$row = array_change_key_case($row, CASE_UPPER);
			}
			return $rows;
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
		if ($params == null || ! is_array($params)) {
			return $sql;
		}
		if ($holder) {
			foreach ($params as $item => $value) {
				$field = $this->field($item);
				$sql .= "$gap $field=:$item ";
			}
			return substr($sql, strlen($gap));
		}
		foreach ($params as $item) {
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
		if ($field == null || ! is_string($field)) {
			return '``';
		}
		if (strpos($field, $this->pre) === 0) {
			$field = substr($field, strlen($this->pre));
		}
		$field = '`'.str_replace('`', '', $field).'`';
		$field = str_replace('.', '`.`', $field);
		return $field;
	}

	/**
	 * <h4>连接数据库</h4>
	 * @return bool 是否成功
	 */
	protected function connect()
	{
		### 数据库连接串
		$template = $this->pick($this->dbtype, $this->templates);
		$params = array(
			'dbtype' => $this->dbtype,
			'dbhost' => $this->dbhost,
			'dbport' => $this->dbport,
			'dbname' => $this->dbname,
		);
		$this->dsn = Template::fetch($template, $params);
		### 检查连接信息
		if ($this->dsn == null || $this->user == null) {
			$this->notice = Log::logs('config', __CLASS__, Log::EXCEPTION);
			return false;
		}
		### 连接数据库
		try {
			$this->pdo = new PDO($this->dsn, $this->user, $this->password);
			if ($this->charset != null) {
				$this->pdo->query('set character set '.$this->field($this->charset));
			}
			if ($this->lasting) {
				self::$ACCESS = $this;
			} else {
				self::$ACCESS = $this->preset;
			}
		} catch (PDOException $e) {
			$this->notice = Log::logs(__FUNCTION__, __CLASS__, Log::EXCEPTION);
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
		if (! $this->connect()) {
			$this->target->notice = $this->notice;
			trigger_error($this->notice, E_USER_ERROR);
		}
	}

	/**
	 * <h4>撤销数据工场</h4>
	 * <p>
	 * 关闭数据库。
	 * </p>
	 */
	public function __destruct()
	{
		$this->pdo = null;
	}
}
?>
