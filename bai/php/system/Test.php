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
 * <b>测试工场</b><br/>
 * <p>
 * 功能性单元测试。
 * </p>
 * @author 白晓阳
 * @see Work
 */
class Test extends Work
{
	/** 测试文件标识：测试项目 */
	const ITEM = '_ITEM';
	/** 测试文件标识：传入参数 */
	const PARAMS = '_PARAMS';
	/** 测试文件标识：预期结果 */
	const EXPECT = '_EXPECT';
	/** 测试文件标识：说明标签 */
	const TAB = '_TAB';
	/** 测试文件标识：测试类型 */
	const TYPE = '_TYPE';
	/** 测试文件标识：测试类型 - 属性 */
	const TYPE_PROPERTY = '::';
	/** 测试文件标识：测试类型 - 子项 */
	const TYPE_SUBITEM = '[]';
	/** 测试文件标识：测试类型 - 创建 */
	const TYPE_BUILD = '()';
	/** 测试文件标识：测试类型 - 跳过 */
	const TYPE_SKIP = '||';
	/** 测试文件标识：测试类型 - 语句 */
	const TYPE_EVAL = '{}';
	/** 测试文件标识：测试类型 - 模拟 */
	const TYPE_MOCK = '==';
	/** 测试文件标识：参数类型 - 引用 */
	const TYPE_PARAM = '&&';

	/** 测试结果：通过（与预期一致） */
	const SUCCESS = '通过';
	/** 测试结果：失败（与预期不符） */
	const FAILURE = '失败';
	/** 测试结果：出错（未能执行） */
	const ERROR = '出错';
	/** 测试结果：覆盖率 */
	const COVERAGE = '覆盖率';

	/** 通过的测试 */
	protected $success = array();
	/** 失败的测试 */
	protected $failure = array();
	/** 出错的测试 */
	protected $error = array();
	/** 跳过的测试 */
	protected $skip = array();
	
	protected $name = null;

	/**
	 * <b>执行测试</b><br/>
	 * 根据传人的测试类名和测试文件，执行相应的测试<br/>
	 *
	 * @param string $testee 测试对象（类名）
	 * @param string $file 测试文件
	 */
	public function entrust($testee = null, $file = null)
	{
		if (! $testee || ! class_exists($testee, true))
		{
			### 测试对象无效
			Log::logf('testee', $testee, __CLASS__);
			return false;
		}
		if (! $file || ! is_file($file) || ! ($cases = include $file))
		{
			### 测试文件无效
			Log::logf('file', $file, __CLASS__);
			return false;
		}
		### 执行测试
		$coverages = $this->testAll($testee, $cases);
		foreach ($coverages as $filename => $coverage)
		{
			if (basename($filename) === $testee.'.php')
			{
				break;
			}
		}
		return array
		(
				self::SUCCESS => $this->success,
				self::FAILURE => $this->failure,
				self::ERROR   => $this->error,
				self::COVERAGE => $coverage,
		);
	}

	/**
	 * <b>执行测试</b><br/>
	 * 逐条执行根据测试文件中的测试情景
	 *
	 * @param object $testee 测试对象
	 * @param array $cases 测试情景
	 *
	 * @return mixed 测试结果
	 */
	protected function testAll($testee, array $cases)
	{
		### 打开代码覆盖率统计
		xdebug_start_code_coverage();

		### 执行测试
		foreach ($cases as $case)
		{
			if (! is_array($case))
			{
				continue;
			}
			### 测试情景
			$this->testCase($testee, $case);
		}

		### 关闭代码覆盖率统计
		xdebug_start_code_coverage();
		return xdebug_get_code_coverage();
	}

	/**
	 * <b>执行测试情景</b><br/>
	 * 根据测试文件中的测试情景执行测试
	 *
	 * @param object $testee 测试对象
	 * @param array $case 测试情景
	 * 
	 * @return mixed 测试结果
	 */
	protected function testCase($testee, array $case)
	{
		### 说明标签
		$tab = cRead(self::TAB, $case);
		### 测试类型
		$type = cRead(self::TYPE, $case);
		### 构建测试对象
		if ($type === self::TYPE_BUILD)
		{
			return $this->buildCase($testee, $case);
		}
		### 测试对象构建失败
		if ($type === self::TYPE_SKIP || ! $this->$testee)
		{
			$this->skip[] = $tab;
			Log::logf(self::TYPE_SKIP, $tab, __CLASS__);
			return false;
		}
		### 测试项目
		$item = cRead(self::ITEM, $case);
		### 传入参数
		$params = cRead(self::PARAMS, $case);
		### 参数置换（以&&开头）
		if (is_array($params))
		{
			foreach ($params as $param)
			{
				if (is_string($params) && strpos($params, self::TYPE_PARAM) === 0)
				{
					$params = $this->{substr($params, strlen(self::TYPE_PARAM))};
				}
			}
		}
		### 预期结果
		$expect = cRead(self::EXPECT, $case);

		try
		{
			if ($type === self::TYPE_PROPERTY)
			{
				### 测试属性
				if ($params !== null)
				{
					$this->$testee->$item = $params;
				}
				$result = $this->$testee->$item;
			}
			else if ($type === self::TYPE_SUBITEM)
			{
				### 测试子项
				$result = $this->$testee;
				if ($params !== null)
				{
					$result[$item] = $params;
				}
				$result = $result[$item];
			}
			else if ($type == self::TYPE_EVAL)
			{
				### 测试语句
				$result = $this->evalCase($testee, $case);
			}
			else
			{
				### 测试方法
				if (is_array($params))
				{
					$result = call_user_func_array(array($this->$testee, $item), $params);
				}
				else
				{
					$result = call_user_func(array($this->$testee, $item), $params);
				}
			}
		}
		catch (Exception $e)
		{
			$this->error[] = $tab;
			Log::logf(__FUNCTION__, array($tab, self::ERROR), __CLASS__);
			return false;
		}
		### 比较实际结果和预期结果
		if ($result === $expect
				|| (is_array($result) && is_array($expect) && $result == $expect)
				|| (is_object($result) && is_object($expect) && $result == $expect))
		{
			$this->success[] = $tab;
			Log::logf(__FUNCTION__, array($tab, self::SUCCESS), __CLASS__);
			return true;
		}
		$this->failure[] = $tab;
		Log::logf(__FUNCTION__, array($tab, self::FAILURE), __CLASS__);
		return false;
	}

	/**
	 * <b>构建测试对象</b><br/>
	 * 根据测试文件中的构建情景构建测试对象
	 *
	 * @param string $testee 测试对象
	 * @param array $case 构建情景
	 *
	 * @return object 测试对象
	 */
	protected function buildCase($testee, array $case)
	{
		### 构建方法
		$item = cRead(self::ITEM, $case);
		### 构建参数
		$params = cRead(self::PARAMS, $case);
		### 构建对象
		$expect = cRead(self::EXPECT, $case);
		### 说明标签
		$tab = cRead(self::TAB, $case);
		if (! $item)
		{
			### 使用默认构建方法（new）
			if (! is_array($params))
			{
				$this->$expect = new $expect($params);
				return $this->$expect;
			}
			$reflector = new ReflectionClass($expect);
			$this->$expect = $reflector->newInstanceArgs($params);
			return $this->$expect;
		}
		### 使用指定构建方法
		if (! is_array($params))
		{
			$this->$expect = call_user_func($expect.'::'.$item, $params);
			return $this->$expect;
		}
		$this->$expect = call_user_func_array($expect.'::'.$item, $params);
		return $this->$expect;
	}

	/**
	 * <b>模拟测试对象</b><br/>
	 * 根据测试文件中的模拟情景模拟测试对象
	 *
	 * @param object $testee 测试对象
	 * @param array $case 测试情景
	 *
	 * @return mixed 测试结果
	 */
	protected function mockCase($testee, array $case)
	{
		### 模拟参数
		$params = cRead(self::PARAMS, $case);
		### 模拟对象
		$expect = cRead(self::EXPECT, $case);
		### 说明标签
		$tab = cRead(self::TAB, $case);
		### 构建模拟对象
		$this->$expect = new Test($expect, $params);
		return $this->$expect;
	}

	/**
	 * <b>模拟测试对象</b><br/>
	 * 根据测试文件中的模拟情景模拟测试对象
	 *
	 * @param object $testee 测试对象
	 * @param array $case 测试情景
	 *
	 * @return mixed 测试结果
	 */
	protected function evalCase($testee, array $case)
	{
		### 测试项目
		$item = cRead(self::ITEM, $case);
		$result = eval($item);
		return $result;
	}

	/**
	 * <b>模拟对象方法</b>
	 * 模拟执行对象方法，并返回预置的模拟结果
	 * 
	 * @see Bai::__call()
	 */
	public function __call($item, $params)
	{
		return cRead($item, $this->preset);
	}
	
	public function __toString()
	{
		if (! $this->name)
		{
			$this->name = __CLASS__;
		}
		return $this->name;
	}

	/**
	 * <b>测试工场初始化</b><br/>
	 * 初始化模拟方法及结果
	 * 
	 * @param array $preset
	 */
	public function __construct($name = null, array $preset = null)
	{
		if ($name)
		{
			$this->name = $name;
		}
		if ($preset)
		{
			$this->preset = $preset;
		}
	}
}
?>
