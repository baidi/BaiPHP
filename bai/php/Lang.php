<?php

/**
 * <h2>化简PHP（BaiPHP）开发框架</h2>
 *
 * @link http://www.baiphp.net
 * @copyright Copyright (c) 2011 - 2012, 白晓阳
 * @author 白晓阳
 * @version 1.0.0 2012/03/31 首版
 *          2.0.0 2012/07/01 首版
 *          <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 *          <p>欢迎提供各种形式的捐助。任何捐助者自动获得仅限于捐助者自身的商业使用（不包括再发行和再授权）授权。</p>
 */

/**
 * <h2>化简PHP（BaiPHP）开发框架</h2>
 * <h3>语言工场</h3>
 * <p>
 * 转换语言项目到当前语言。
 * </p>
 *
 * @author 白晓阳
 */
class Lang extends Work
{
	/**
	 * 语言标识：中文（中国）
	 */
	const ZH = 'zh_CN';
	/**
	 * 语言标识：英文（美国）
	 */
	const EN = 'en_US';

	/**
	 * 首选语言
	 */
	protected $primary = self::ZH;

	/**
	 * 语言工场静态入口
	 */
	private static $ACCESS = null;

	/**
	 * <h4>获取语言工场入口</h4>
	 *
	 * @param array $setting 即时配置
	 * @return Lang 语言工场
	 */
	public static function access ($setting = null)
	{
		if ($setting != null || self::$ACCESS == null) {
			self::$ACCESS = new Lang($setting);
		}
		return self::$ACCESS;
	}

	/**
	 * <h4>获取语言内容</h4>
	 * <p>
	 * 获取语言项目的当前内容，并即时输出。
	 * </p>
	 *
	 * @param string $item 语言项
	 * @param boolean $print 是否输出
	 * @return string
	 */
	public static function cut ($item = null, $print = true)
	{
		$lang = Lang::access();
		$result = $lang->entrust($item);
		if ($print) {
			echo $result;
		}
		return $result;
	}

	/**
	 * <h4>获取语言内容</h4>
	 * <p>
	 * 根据语言项目，获取当前语言的对应内容。
	 * </p>
	 *
	 * @param string $item 语言项
	 * @return string
	 */
	public function entrust ($item = null)
	{
		if ($item == null || ! is_string($item)) {
			return null;
		}
		### 预置语言内容
		$dic = $this->config(self::LANG, $this->primary);
		### 当前目标语言内容
		$event = $this->config(self::LANG, $this->primary, self::EVENT, "$this->target");
		$this->result = $this->pick($item, $event);
		if ($this->result === null) {
			$this->result = $this->pick($item, $dic);
		}
		$this[$item] = $this->result;
		return $this->result;
	}

	/**
	 * <h3>读取项语言目</h3>
	 *
	 * @param string $item 语言项
	 * @return mixed 语言内容
	 */
	public function offsetGet ($item)
	{
		if (! isset($this->runtime[$item])) {
			$this->runtime[$item] = $this->entrust($item);
		}
		return $this->runtime[$item];
	}

	/**
	 * <h4>构建语言工场</h4>
	 *
	 * @param array $setting 即时配置
	 */
	protected function __construct ($setting = null)
	{
		parent::__construct($setting);
		$this->load($this->primary, true);
	}
}
