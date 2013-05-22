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
 * <b>记录工场：</b>
 * <p>保存数据并提交更改</p>
 *
 * @author 白晓阳
 * @see Work
 */
class Record extends Work
{
	/** 刷新数据 */
	const REFRESH = 'refresh';
	/** 保存数据 */
	const SAVE = 'save';
	/** 删除数据 */
	const DELETE = 'delete';

	/** 数据表名 */
	private $table = null;
	/** 数据主键 */
	private $pk = null;
	/** 数据键值 */
	private $id = null;
	/** 数据状态 */
	private $status = null;
	/** 数据字段 */
	private $columns = null;
	/** 原始数据 */
	private $origin = array();
	/** 更新数据 */
	private $update = array();

	/**
	 * 数据的增删改查<br/>
	 * @param string $action 数据操作
	 *     create： 插入； update： 更新； delete： 删除； 其他： 扩展；
	 */
	public function entrust($action = null)
	{
		try {
			$this->$action();
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
	 * @return boolean
	 */
	protected function refresh()
	{
		if ($this->id != null)
		{
			$data = Data::read($this->table, array($this->pk => $this->id));
			$this->origin = $data[0];
			$this->status = self::REFRESH;
			return true;
		}
		return false;
	}

	/**
	 * <h4>保存数据</h4>
	 * <p>
	 * 如果没有原始数据，则建立新数据，否则更新变更项。
	 * </p>
	 * @return boolean
	 */
	protected function save()
	{
		if ($this->origin == null)
		{
			$id = Data::create($this->table, $this->update);
			$this->origin = $this->update;
			$this->origin[$this->pk] = $id;
			return true;
		}
		$values = array();
		foreach ($this->origin as $item => $value)
		{
			if (isset($this->update[$item]) && $this->update[$item] !== $value)
			{
				$update[$item] = $this->update[$item];
			}
		}
		if ($values == null)
		{
			return true;
		}
		$where = array($this->pk => $this->id);
		Data::update($this->table, $values, $where);
		$this->status = self::SAVE;
		return true;
	}

	protected function check()
	{
		foreach ($this->update as $item => $value)
		{
			
		}
	}

	/**
	 * <h4>删除数据</h4>
	 * <p>
	 * 根据主键删除当前数据。
	 * </p>
	 * @return boolean
	 */
	protected function delete()
	{
		Data::delete($this->table, array($this->pk => $this->id));
		$this->status = self::DELETE;
	}

	/**
	 * <h4>获取数据字段</h4>
	 * <p>
	 * 根据数据表名获取数据表字段定义。
	 * </p>
	 * @return boolean
	 */
	protected function show()
	{
		if ($this->table == null)
		{
			$this->error = Log::logs(__FUNCTION__, __CLASS__, Log::EXCEPTION);
			return false;
		}
		$columns = Data::show($this->table);
		if (! $columns)
		{
			return false;
		}
		foreach ($columns as $column)
		{
			$this->columns[$column['field']] = $column;
			if ($column['field'] == 'PRI')
			{
				$this->pk = $column['field'];
			}
		}
	}

	/**
	 * <h3>读取项目</h3>
	 * @param string $item 项目名
	 * @return mixed 项目值
	 */
	public function offsetGet($item)
	{
		if (isset($this->update[$item]))
		{
			return $this->update[$item];
		}
		if (isset($this->origin[$item]))
		{
			return $this->origin[$item];
		}
		return null;
	}

	/**
	 * <h3>设定项目</h3>
	 * @param string $item 项目名
	 * @param mixed $value 项目值
	 * @return void
	 */
	public function offsetSet($item, $value)
	{
		$column = $this->pick($item, $this->columns);
		if ($column != null)
		{
			$this->update[$item] = $value;
		}
	}

	/**
	 * 属性未知时，返回空（null）。
	 */
	public function __get($item)
	{
		return $this->offsetGet($item);
	}

	/**
	 * 属性未知时，返回空（null）。
	 */
	public function __set($item, $value)
	{
		$this->offsetSet($item, $value);
	}

	/**
	 * <h3>构建记录工场</h3>
	 * @param array $setting 即时配置
	 *   table：表名
	 *   id：主键值
	 *   origin：原始数据
	 */
	public function __construct($setting = null)
	{
		parent::__construct($setting);
		if (! $this->show())
		{
			$this->target->error = $this->error;
			trigger_error($this->error, E_USER_ERROR);
		}
		$this->refresh();
	}
}
?>
