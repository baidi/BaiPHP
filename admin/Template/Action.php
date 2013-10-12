<?php
/**
 * <b>化简PHP（BaiPHP）开发框架</b>
 * @author		白晓阳
 * @copyright	Copyright (c) 2011 - 2012, 白晓阳
 * @link		http://www.dacbe.com
 * @version     V1.0.0 2012/03/31 首版
 * <p>版权所有，保留一切权力。未经许可，不得用于商业用途。</p>
 */

/**
 * <b>{$event}处理流程</b>
 * <p>
 * {$description}
 * </p>
 */
class {$event . ucfirst}Action extends Action
{
	# 这是由化简PHP（BaiPHP）自动生成的处理流程（Action）
	# 处理流程（Action）主要包括4个方法：prepare、check、data、和engage，BaiPHP会自动依次执行
	# 如果不需要进行处理，可直接删除该文件
	# 
	# 如需进行预处理（在输入检验和数据访问之前），请继承prepare方法并实现，主要用于：
	# 1、AJAX访问的GET返回
	# 2、缓存的读取与判断
	# 
	# 如需自定义输入检验，请继承check方法并实现
	# 常规检验可通过配饰$config[Work::CHECK][Work::EVENT]['{$event}']完成
	# 
	# 如需访问数据库，请继承data方法并通过数据工场（Data）进行访问
	# 
	# 如需输入检验和数据访问之外的常规处理，请继承并实现engage方法
	#
	# 如需加入自定义方法，可在上述4个方法中进行调用
	# 如果期望BaiPHP自动调用，请建立$config[Flow::FLOW]['{$event . ucfirst}Action']并进行配置

	protected function engage()
	{
		$result = null;

		# 在这里写入你需要执行的处理内容

		# 保存处理结果
		$this->target[Flow::ACTION] = $result;
	}
}
