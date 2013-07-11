<?php

/**
 * <b>化简PHP（BaiPHP）开发框架</b>
 *
 * @author 白晓阳
 * @copyright Copyright (c) 2011 - 2012, 白晓阳
 * @link http://dacbe.com
 * @version V1.0.0 2012/03/31 首版
 *          <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 */

/**
 * <b>化简PHP（BaiPHP）开发框架</b><br/>
 * <b>记录工场：</b>
 * <p>保存数据并提交更改</p>
 *
 * @author 白晓阳
 * @see Work
 */
class Record extends Work
{
	/**
	 * 记录标识：项目
	 */
	const ITEM = 'ITEM';
	/**
	 * 记录标识：选项
	 */
	const PARAM = 'PARAM';
	/**
	 * 记录标识：刷新记录
	 */
	const REFRESH = 'refresh';
	/**
	 * 记录标识：保存记录
	 */
	const SAVE = 'save';
	/**
	 * 记录标识：删除记录
	 */
	const DELETE = 'delete';

	/**
	 * 记录表名
	 */
	protected $table = null;
	/**
	 * 记录键值
	 */
	protected $id = null;
	/**
	 * 原始数据
	 */
	protected $data = array();
	/**
	 * 记录状态
	 */
	protected $status = null;
	/**
	 * 记录匹配式
	 */
	protected $mode = null;
	/**
	 * 记录类型转换
	 */
	protected $types = null;

	/**
	 * 记录主键
	 */
	private $pk = null;
	/**
	 * 记录字段
	 */
	private $columns = null;

	/**
	 * <h4>记录的增删改查</h4>
	 *
	 * @param string $action 数据操作
	 *        Record::REFRESH： 查询； Record::SAVE： 保存； Record::DELETE： 删除；
	 */
	public function entrust ($action = null)
	{
		try {
			return $this->$action();
		} catch (Exception $e) {
			### 数据操作异常
			$message = Log::logf(__FUNCTION__, $action, __CLASS__);
			Log::logs($e->getMessage());
			return $message;
		}
	}

	/**
	 * <h4>刷新数据</h4>
	 * <p>
	 * 根据主键查询数据库并刷新当前数据。
	 * </p>
	 *
	 * @return boolean
	 */
	protected function refresh ()
	{
		if ($this->id == null) {
			$this->notice = Log::logs('id', __CLASS__, Log::NOTICE);
			return false;
		}
		$data = Data::read($this->table, array($this->pk => $this->id));
		if (! $data) {
			return false;
		}
		$this->data = $data[0];
		$this->status = self::REFRESH;
		Log::logs(__FUNCTION__, __CLASS__);
		return true;
	}

	/**
	 * <h4>保存记录</h4>
	 * <p>
	 * 如果没有原始数据，则建立新数据，否则更新变更项。
	 * </p>
	 *
	 * @return boolean
	 */
	protected function save ()
	{
		if ($this->data == null) {
			if ($this->runtime == null) {
				return false;
			}
			### 新建记录
			if ($this->check($this->runtime)) {
				$id = Data::create($this->table, $this->runtime);
				if ($id) {
					$this->runtime[$this->pk] = $this->id = $id;
					$this->data = $this->runtime;
					$this->status = self::SAVE;
					Log::logs(__FUNCTION__, __CLASS__);
					return true;
				}
			}
			return false;
		}
		### 更新记录
		if ($this->id == null) {
			$this->notice = Log::logs('id', __CLASS__, Log::NOTICE);
			return false;
		}
		$values = array_diff_assoc($this->runtime, $this->data);
		if ($values == null) {
			return true;
		}
		if ($this->check($values)) {
			$result = Data::update($this->table, $values, array($this->pk => $this->id));
			if ($result > 0) {
				$this->stuff($this->runtime, $this->data);
				$this->status = self::SAVE;
				Log::logs(__FUNCTION__, __CLASS__);
				return true;
			}
		}
		return false;
	}

	/**
	 * <h4>删除数据</h4>
	 * <p>
	 * 根据主键删除当前数据。
	 * </p>
	 *
	 * @return boolean
	 */
	protected function delete ()
	{
		if ($this->id == null) {
			$this->notice = Log::logs('id', __CLASS__, Log::NOTICE);
			return false;
		}
		$result = Data::delete($this->table, array($this->pk => $this->id));
		if ($result > 0) {
			$this->status = self::DELETE;
			Log::logs(__FUNCTION__, __CLASS__);
			return true;
		}
		return false;
	}

	/**
	 * <h4>获取字段定义</h4>
	 * <p>
	 * 根据表名获取表字段定义。
	 * </p>
	 *
	 * @return boolean
	 */
	protected function show ()
	{
		if ($this->table == null) {
			$this->notice = Log::logs('table', __CLASS__, Log::EXCEPTION);
			return false;
		}
		$columns = Data::show($this->table);
		if (! $columns) {
			$this->notice = Log::logs(__FUNCTION__, __CLASS__, Log::EXCEPTION);
			return false;
		}
		foreach ($columns as $column) {
			$column['CHECK'] = $this->define($column);
			$this->columns[strtoupper($column['FIELD'])] = $column;
			if ($column['KEY'] == 'PRI') {
				$this->pk = $column['FIELD'];
			}
		}
		return true;
	}

	/**
	 * <h4>检验字段值</h4>
	 * <p>
	 * 检验字段内容。
	 * </p>
	 *
	 * @param array $columns 字段值
	 */
	protected function check ($columns = null)
	{
		$items = array();
		foreach ($columns as $item => $value) {
			$items[$item] = $this->columns[$item]['CHECK'];
		}
		$check = Check::access();
		$result = $check->entrust($items, $columns);
		if (! $result) {
			$this->notice = $check->notice;
		}
		return $result;
	}

	/**
	 * <h4>解析字段检验</h4>
	 * <p>
	 * 根据字段定义生成检验项目（用于检验工场）。
	 * </p>
	 *
	 * @param string $column 字段定义
	 * @return string
	 */
	protected function define ($column = null)
	{
		$result = array();
		### 必须检验
		if ($column['NULL'] == 'NO' && $column['DEFAULT'] === null &&
				 $column['EXTRA'] == null) {
			$result[] = 'required';
		}
		$defined = strtoupper($column['TYPE']);
		if (preg_match($this->mode, $defined, $matches)) {
			### 类型检验
			$item = $this->pick(self::ITEM, $matches);
			if (strpos($defined, 'UNSIGNED') !== false) {
				$item .= '+';
			}
			$result[] = $this->pick($item, $this->types);
			### 其他检验
			$param = $this->pick(self::PARAM, $matches);
			if ($item == 'ENUM' || $item == 'SET') {
				### 选项检验
				$result[] = strtolower($item) . '=' . $param;
			} else if ($param != null) {
				### 最大长度检验
				$result[] = 'max=' . $param;
			}
		}
		return implode(' ', $result);
	}

	/**
	 * <h3>读取项目</h3>
	 *
	 * @param string $item 项目名
	 * @return mixed 项目值
	 */
	public function offsetGet ($item)
	{
		$item = strtoupper($item);
		if (isset($this->runtime[$item])) {
			return $this->runtime[$item];
		}
		if (isset($this->data[$item])) {
			return $this->data[$item];
		}
		return null;
	}

	/**
	 * <h3>设定项目</h3>
	 *
	 * @param string $item 项目名
	 * @param mixed $value 项目值
	 * @return void
	 */
	public function offsetSet ($item, $value)
	{
		$item = strtoupper($item);
		$column = $this->pick($item, $this->columns);
		if ($column != null) {
			$this->runtime[$item] = $value;
		}
		return $this->runtime[$item];
	}

	/**
	 * <h3>构建记录工场</h3>
	 *
	 * @param array $setting 即时配置
	 *        table：表名
	 *        id：主键值
	 *        data：原始数据
	 */
	public function __construct ($setting = null)
	{
		parent::__construct($setting);
		if ($this->show()) {
			$this->refresh();
			return;
		}
		trigger_error($this->notice, E_USER_WARNING);
	}
}
?>
