<?php
################################################################################
# BaiPHP Mobile Framework
# http://www.baiphp.com
# Copyright (C) 2011-2014 Xiao Yang, Bai
#
# Anyone obtaining a copy of BaiPHP gets permission to use, copy, modify, merge,
# publish, distribute, and/or sell it for non-profit purpose.
# Any contributor to BaiPHP gets for-profit permission for itself only, which
# can't be transferred or rent.
# Authors or copyright holders don't take any for all the consequences arising
# therefrom.
# By using BaiPHP, you are unconditionally agree to this notice and must keep it
# in the copy.
################################################################################


/**
 * <h2>BaiPHP Mobile Framework</h2>
 * <h3>App work</h3>
 * <p>工场具备下述特点：</p>
 * <ol>
 * <li>工场是专一的实干家，（理论上）只做一件事情或者一类事情。</li>
 * <li>工场（理论上）是独立的、封闭的，不随入口数据的变化而变化。</li>
 * <li>工场只开放一个常规公开入口（如果必须开放其他入口，也应是静态入口）。</li>
 * <li>工场具备动态执行自身方法的能力。</li>
 * <li>工场能够按照预置程序（像一道流水线一样）智能运作。</li>
 * </ol>
 *
 * @copyright Copyright (C) 2011-2014 Xiao Yang, Bai
 * @author Xiao Yang, Bai
 */
abstract class Work extends Bai
{
	/**
	 * Work place
	 */
	protected $place = null;
	/**
	 * Depending extensions
	 */
	protected $dependences = null;

	/**
	 * <h4>Initialize work</h4>
	 * <p>
	 * Set up depending extensions and work place.
	 * </p>
	 *
	 * @param array $settings
	 *        runtime settings
	 * @return void
	 */
	protected function __construct($settings = null)
	{
		parent::__construct($settings);

		### check depending extensions
		if (!empty($this->dependences))
		{
			foreach ((array) $this->dependences as $item)
			{
				if ($item != null && !extension_loaded($item))
				{
					$this->message = Log::logf('dependences', $item, __CLASS__, Log::ERROR);
					trigger_error($this->message, E_USER_ERROR);
				}
			}
		}

		### check work place
		if (!empty($this->place))
		{
			$path = _LOCAL;
			foreach (explode(_DIR, $this->place) as $item)
			{
				if ($item == null)
				{
					continue;
				}
				if (!is_dir($path . $item . _DIR) && !mkdir($path . $item . _DIR))
				{
					$this->message = Log::logf('place', $this->place, __CLASS__, Log::ERROR);
					trigger_error($this->message, E_USER_ERROR);
				}
				$path .= $item . _DIR;
			}
			$this->place = substr($path, strlen(_LOCAL));
		}
	}
}
?>
