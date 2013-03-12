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
 * <h3>测试工场</h3>
 * <p>
 * 功能与代码覆盖率单元测试。
 * 基于Xdebug。
 * </p>
 * @author 白晓阳
 */
class Test extends Work
{
	/** 测试标识：测试文件 */
	const FILE  = 'FILE';
	/** 测试标识：测试场景 */
	const CASES = 'CASES';
	/** 测试标识：测试代码 */
	const CODES = 'CODES';

	/** 测试项标识：测试项目 */
	const ITEM = 'ITEM';
	/** 测试项标识：测试参数 */
	const PARAMS = 'PARAMS';
	/** 测试项标识：预期结果 */
	const EXPECTED = 'EXPECTED';
	/** 测试项标识：测试方式 */
	const MODE = 'MODE';
	/** 测试方式：构建 */
	const MODE_BUILD    = 'testBuild';
	/** 测试方式：行为 */
	const MODE_METHOD   = 'testMethod';
	/** 测试项方式：属性 */
	const MODE_PROPERTY = 'testProperty';
	/** 测试方式：模拟 */
	const MODE_MOCK     = 'testMock';
	/** 测试方式：引用 */
	const MODE_HOLDER   = '&&';

	protected $name = null;
	/** 测试结果：通过 */
	protected $success = '-';
	/** 测试结果：失败 */
	protected $failure = '/';
	/** 测试结果：略过 */
	protected $skip    = '.';
	/** 测试结果：出错 */
	protected $error   = '|';

	/**
	 * <h4>执行测试</h4>
	 * <p>
	 * 根据测试对象名和测试文件，执行相应的测试。
	 * </p>
	 * @param string $testee 测试对象名
	 * @param string $tester 测试文件
	 * @return array 测试结果
	 */
	public function entrust($testee = null, $tester = null)
	{
		if ($testee == null || ! class_exists($testee, true))
		{
			### 测试对象无效
			Log::logf('testee', $testee, __CLASS__);
			return $this->result;
		}
		if ($tester == null)
		{
			$tester = $testee;
		}
		$cases = $this->load($tester);
		if ($cases == null || ! is_array($cases))
		{
			### 测试文件无效
			Log::logf('tester', $tester, __CLASS__);
			return $this->result;
		}

		### 启动代码统计
		ini_set('xdebug.coverage_enable', 1);
		xdebug_start_code_coverage(XDEBUG_CC_UNUSED + XDEBUG_CC_DEAD_CODE);
		### 执行测试
		$this->result = array();
		$this->runtime['testee'] = $testee;
		foreach ($cases as $case)
		{
			$item = $this->pick(self::ITEM, $case);
			if ($case == null || ! is_array($case) || $item == null)
			{
				$this->result[self::CASES][] = $this->skip;
				Log::logf('testResult', $this->skip, __CLASS__);
				continue;
			}
			$this->runtime['case'] = $case;
			$result = $this->testCase();
			$this->result[self::CASES][] = $result;
			Log::logf('testResult', $result, __CLASS__);
		}
		### 关闭代码统计
		$codes = xdebug_get_code_coverage();
		xdebug_stop_code_coverage();

		### 测试结果
		foreach ($codes as $file => $code)
		{
			if (basename($file) === $testee._EXT)
			{
				$this->result[self::FILE] = $file;
				$this->result[self::CODES] = $code;
				break;
			}
		}
		return $this->result;
	}

	/**
	 * <h4>执行测试场景</h4>
	 * <p>
	 * 根据测试文件中的测试场景执行测试。
	 * </p>
	 * @return mixed 测试结果
	 */
	protected function testCase()
	{
		### 执行数据
		$testee = $this->pick('testee', $this->runtime);
		$case   = $this->pick('case',   $this->runtime);
		$item   = $this->pick(self::ITEM, $case);
		$mode   = $this->pick(self::MODE, $case);
		if ($mode == null)
		{
			$mode = self::MODE_METHOD;
		}
		try
		{
			### 执行测试场景
			Log::logf(__FUNCTION__, $item, __CLASS__);
			return $this->$mode();
		}
		catch (Exception $e)
		{
			Log::logf('error', $item, __CLASS__);
			Log::logs($e->getMessage(), null, Log::EXCEPTION);
			return $this->error;
		}
	}

	/**
	 * <h4>构建场景</h4>
	 * <p>
	 * 为后续测试构建测试对象。
	 * 场景内容：
	 * Test::ITEM： 构建对象
	 * Test::PARAMS： 构建参数
	 * Test::EXPECTED： 构建目标，如果省略则使用构建对象
	 * </p>
	 */
	protected function testBuild()
	{
		### 执行数据
		$case     = $this->pick('case', $this->runtime);
		$item     = $this->pick(self::ITEM,     $case);
		$params   = $this->pick(self::PARAMS,   $case);
		$expected = $this->pick(self::EXPECTED, $case);
		// $params   = $this->testHolder($params);
		### 构建对象
		$testee = $this->build($item, $params);
		if ($testee == null)
		{
			return $this->failure;
		}
		if ($expected == null)
		{
			$expected = $item;
		}
		$this->$expected = $testee;
		return $this->testResult($testee, $expected);
	}

	/**
	 * <h4>方法场景</h4>
	 * <p>
	 * 执行测试对象的方法测试。
	 * 场景内容：
	 * Test::ITEM： 测试方法
	 * Test::PARAMS： 测试参数
	 * Test::EXPECTED： 预期结果
	 * </p>
	 */
	protected function testMethod()
	{
		### 执行数据
		$testee   = $this->pick('testee', $this->runtime);
		$case     = $this->pick('case',   $this->runtime);
		$item     = $this->pick(self::ITEM,     $case);
		$params   = $this->pick(self::PARAMS,   $case);
		$expected = $this->pick(self::EXPECTED, $case);
// 		$params   = $this->testHolder($params);
		### 执行测试
		if ($this->$testee == null || ! method_exists($this->$testee, $item))
		{
			return $this->skip;
		}
		if ($params === null)
		{
			$actual = $this->$testee->$item();
		}
		else if (! is_array($params))
		{
			$actual = $this->$testee->$item($params);
		}
		else
		{
			$actual = call_user_func_array(array($this->$testee, $item), $params);
		}
		return $this->testResult($actual, $expected);
	}

	/**
	 * <h4>置换场景</h4>
	 * <p>
	 * 将引用参数置换成实际参数。
	 * </p>
	 * @param mixed $params 引用参数
	 */
// 	protected function testHolder($params = null)
// 	{
// 		if ($params == null)
// 		{
// 			return $params;
// 		}
// 		if (! is_array($params))
// 		{
// 			if (is_string($params) && strpos($params, self::MODE_HOLDER) === 0)
// 			{
// 				$params = substr($params, strlen(self::MODE_HOLDER));
// 				return $this->$params;
// 			}
// 			return $params;
// 		}
// 		foreach ($params as &$param)
// 		{
// 			if (is_string($param) && strpos($param, self::MODE_HOLDER) === 0)
// 			{
// 				$param = substr($param, strlen(self::MODE_HOLDER));
// 				$param = $this->$param;
// 				$param = &$param;
// 			}
// 		}
// 		return $params;
// 	}

	/**
	 * <h4>比较场景</h4>
	 * <p>
	 * 比较实际结果与预期结果是否相同。
	 * </p>
	 * @param mixed $actual 实际结果
	 * @param mixed $expected 预期结果
	 */
	protected function testResult($actual, $expected)
	{
		if (is_object($actual))
		{
			$result = ($actual instanceof $expected);
		}
		else
		{
			$result = ($actual === $expected) ||
			(is_array($actual) && is_array($expected) && $actual == $expected);
		}
		return $result ? $this->success : $this->failure;
	}

	/**
	 * <h4>模拟对象方法</h4>
	 * 模拟执行对象方法，并返回预置的模拟结果
	 *
	 * @see Bai::__call()
	 */
	public function __call($item, $params)
	{
		return $this->pick($item, $this->preset);
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
	 * <h4>构建测试工场</h4>
	 * @param array $setting 自定义配置文件
	 */
	public function __construct($setting = null)
	{
		parent::__construct($setting);
		$success = $this->pick('success', $this->preset);
		$failure = $this->pick('failure', $this->preset);
		$skip    = $this->pick('skip',    $this->preset);
		$error   = $this->pick('error',   $this->preset);
		if ($success != null)
		{
			$this->success = $success;
		}
		if ($failure != null)
		{
			$this->failure = $failure;
		}
		if ($skip != null)
		{
			$this->skip = $skip;
		}
		if ($error != null)
		{
			$this->error = $error;
		}
	}
}
?>
