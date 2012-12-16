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
 * <b>模板解析工场：</b>
 * <p>解析模板并格式化数据输出</p>
 * <p>
 * {$param->action:var1,var2,……} 参数->动作:变量1,变量2,……<br/>
 * {$param} 参量输出<br/>
 * {$param->case[:var]} 条件体开始<br/>
 * {$param->loop} 循环体开始<br/>
 * {$param->end} 解析体结束<br/>
 * {$param->call[:var1,var2]} 调用方法<br/>
 * {$param->page[:var1,var2]} 引用模板
 * </p>
 * @author 白晓阳
 * @see Work
 */
class Parser extends Work
{
	### 解析进行时
	protected $parsing = false;
	### 匹配符
	protected $pattern;
	### 替换符
	protected $replacement;
	### 解析起止符
	protected $begin, $end;
	### 匹配项：参量，行为，条件
	protected $param = false, $event = false, $vars = false;
	### 循环体变量
	protected $_loop;

	/**
	 * 解析模板并格式化参数
	 * {$param->action:var1,var2,……} 参数->动作:变量1,变量2,……
	 * {$param} 参量输出
	 * {$param->case[:var]} 条件体开始
	 * {$param->loop} 循环体开始
	 * {$param->end} 解析体结束
	 * {$param->call[:var1,var2]} 调用方法
	 * {$param->page[:var1,var2]} 引用模板
	 * @param $page 模板
	 * @return 输出页面
	 */
	public function entrust($page)
	{
		$page = preg_replace('/^\s*/m', '', $page);
		$page = preg_replace_callback($this->pattern, $this->replacement, $page);
		return $page;
	}

	/**
	 * 获取解析结果
	 * @return 解析结果
	 */
	protected function fetch()
	{
		$matches = func_get_args();
		### 获取匹配项的值
		$this->value($matches[0]);
		### 参量行为
		if ($this->event) {
			return $this->{'_'.$this->event}($this->param, $this->vars);
		}
		### 参量输出
		if ($this->param[0] != '_') {
			return $this->param;
		}
		if (!$this->parsing || !array_key_exists($this->param, $this->_loop)) {
			return '';
		}
		return sprintf($this->_loop[$this->param],
				$this->vars?"[\"".$this->vars[0]."\"]":"");

	}

	/**
	 * 条件体开始
	 * @param $param 匹配参量
	 * @param $vars 条件
	 * @return 解析结果
	 */
	protected function _case($param, $vars)
	{
		if ((!$vars && !$param)
				|| (array_search($param, $vars) === false)) {
			$this->parsing = __FUNCTION__;
			return $this->begin[__FUNCTION__];
		}
		return '';
	}

	/**
	 * 循环体开始
	 * @param $param 匹配参量
	 * @param $vars 条件
	 * @return 解析结果
	 */
	protected function _loop($param, $vars)
	{
		if ($param[0] != '$') {
			return $this->begin['_'.__CLASS__];
		}
		$this->parsing = __FUNCTION__;
		if (!$vars) {
			return sprintf($this->begin[__FUNCTION__], $param);
		} else {
			return sprintf($this->begin[__FUNCTION__], $param);
		}
		return $this->begin['_'.__CLASS__];
	}

	/**
	 * 结束
	 * @param $param 匹配参量
	 * @param $vars 条件
	 * @return 解析结果
	 */
	protected function _end($param, $vars)
	{
		if (current($param) == '_') {
			return $this->end['_'.__CLASS__];
		}
		if ($this->parsing) {
			$_end = $this->end[$this->parsing];
			$this->parsing = false;
			return $_end;
		}
		return '';
	}

	/**
	 * 引入文件
	 * @param $param 匹配参量
	 * @param $vars 条件
	 * @return 解析结果
	 */
	protected function _page($param, $vars)
	{
		return $this->parse(file_get_contents('page/'.substr($param, 1)));
	}

	/**
	 * 调用方法
	 * @param $param 匹配参量
	 * @param $vars 条件
	 * @return 解析结果
	 */
	protected function _call($param, $vars)
	{
		if (current($param) == '_' || !$vars) {
			return '';
		}
		if ($p = array_search('@', $vars)) {
			$vars[$p] = $param;
		}
		return call_user_func_array($vars[0], array_slice($vars, 1));
	}

	/**
	 * 获取匹配项的值
	 * @param $matches 匹配项
	 */
	protected function value($matches)
	{
		$this->param = $matches[1];
		$this->event = isset($matches[2])?$matches[2]:false;
		$this->vars = isset($matches[3])?explode(',', $matches[3]):false;
		if (array_key_exists($this->param, $this->_loop)) {
			return;
		}
		if (!empty($_POST[$this->param])) {
			if ($this->event == 'loop') {
				$this->param = '$_POST["'.$this->param.'"]';
				return;
			}
			$this->param = $_POST[$this->param];
		} else if (!empty($_SESSION[$this->param])) {
			if ($this->event == 'loop') {
				$this->param = '$_SESSION["'.$this->param.'"]';
				return;
			}
			$this->param = $_SESSION[$this->param];
		} else if (!empty($_SERVER[$this->param])) {
			if ($this->event == 'loop') {
				$this->param = '$_SERVER["'.$this->param.'"]';
				return;
			}
			$this->param = $_SESSION[$this->param];
		} else {
			$this->param = '_'.$this->param;
		}
	}

	public function __construct()
	{
		### 匹配符
		### {$param[->action[:var1[,var2]]]} 参量[->动作[:变量1[,变量2]]]
		### {$param} 变量输出
		### {$param->case[:var]} 变量条件开始
		### {$param->loop} 变量循环开始
		### {$param->end} 变量解析结束
		### {$param->call[:var,var]} 变量方法调用
		### {$param->page[:var1,var2]} 引用模板
		$this->pattern = '/(?:<!--)?{'.
		'\$([a-zA-Z0-9_\.]+)'.
		'(?:->([a-zA-Z0-9_]+))?'.
		'(?::([a-zA-Z0-9_,@\|]+))?'.
		'}(?:-->)?/';
		### 替换符
		$this->replacement = 'self::fetch';
		### 解析起始符
		$this->begin = array(
				'_'.__CLASS__=>'<!--',
				'_case'=>'<!--',
				'_loop'=>'<?php foreach (%s as $_key=>$_loop) { ?>');
		### 解析终止符
		$this->end = array(
				'_'.__CLASS__=>'-->',
				'_case'=>'-->',
				'_loop'=>'<?php } ?>');
		$this->_loop = array(
				'_key'=>'<?php echo $_key; ?>',
				'_loop'=>'<?php echo $_loop%s; ?>');
	}

	public function __call($method, $vars)
	{
		if ($this->parsing) {
			return '<?php echo $_loop["'.substr($method, 1).'"]; ?>';
		}
		return '';
	}
}
?>
