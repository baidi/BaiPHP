<?php
/**
 * <h2>BaiPHP（简单PHP）开发框架</h2>
 * @author		白晓阳
 * @copyright	Copyright (c) 2011 - 2012, 白晓阳
 * @link		http://dacbe.com
 * @version     V1.0.0 2012/03/31 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 */

/**
 * <h2>BaiPHP（简单PHP）开发框架</h2>
 * <h3>工场</h3>
 * <p>工场具备下述特点：</p>
 * <ol>
 * <li>工场是专一的实干家，（理论上）只做一件事情或者一类事情。</li>
 * <li>工场（理论上）是独立的、封闭的，不随入口数据的变化而变化。</li>
 * <li>工场只开放一个常规公开入口（如果必须开放其他入口，也应是静态入口）。</li>
 * <li>工场具备动态执行自身方法的能力。</li>
 * <li>工场能够按照预置程序（像一道流水线一样）智能运作。</li>
 * </ol>
 */
abstract class Work extends Bai
{
	/** 标识：日志工场 */
	const LOG = 'Log';
	/** 标识：检验工场 */
	const CHECK = 'Check';
	/** 标识：数据工场 */
	const DATA = 'Data';
	/** 标识：缓存工场 */
	const CACHE = 'Cache';
	/** 标识：样式工场 */
	const STYLE = 'Style';
	/** 标识：输入工场 */
	const INPUT = 'Input';
	/** 标识：测试工场 */
	const TEST = 'Test';
	/** 标识：语言工场 */
	const LANG = 'Lang';
	/** 标识：过滤工场 */
	const FILTER = 'Filter';
}
?>
