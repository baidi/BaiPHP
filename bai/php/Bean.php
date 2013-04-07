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
 * <b>数据工场：</b>
 * <p>保存数据并提交更改</p>
 *
 * @author 白晓阳
 * @see Work
 */
class Record extends Work
{
	/**
	 * 数据表名
	 * @var string
	 */
	protected $table = null;
	/**
	 * 数据主键
	 * @var string
	 */
	protected $pk = null;
	/**
	 * 字段列表<br/>
	 * 格式：字段名=>标题
	 * @var array
	 */
	protected $columns = array();
	/**
	 * 规则列表<br/>
	 * 格式：字段名=>规则
	 * @var array
	 */
	protected $rules = array();

	/**
	 * 数据的增删改查<br/>
	 * @param string $action 数据操作
	 *     read： 查询； create： 插入； update： 更新； delete： 删除； 其他： 扩展；
	 */
	public function entrust($action)
	{
		if (! $this->table) {
			$message = Log::logs(__CLASS__, __CLASS__);
			return $message;
		}
		
		try {
			$this->$action();
		} catch (Exception $e) {
			### 数据操作异常
			$message = Log::logf(__FUNCTION__, $action, __CLASS__);
			Log::logs($e->getMessage());
			return $message;
		}
	}
}
?>
