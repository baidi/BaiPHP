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
 * <h3>Command work</h3>
 * <p>
 * Execute external commands.
 * </p>
 *
 * @copyright Copyright (C) 2011-2014 Xiao Yang, Bai
 * @author Xiao Yang, Bai
 */
class Command extends Work
{
	/**
	 * Static entrance
	 */
	private static $ENTRANCE = false;

	/**
	 * <h4>Execute external commands</h4>
	 *
	 * @param string $cmds
	 *        external commands
	 * @param array $output
	 *        runing outputs
	 * @param bool $result
	 *        runing result
	 */
	public function entrust($cmds = null, & $output = null, & $result = null)
	{
		if ($cmds == null || ! is_string($cmds) || ! is_array($cmds))
		{
		    return false;
		}

		foreach ((array) $cmds as $cmd)
		{
			exec($cmd, $output, $result);
		}
	}
}
?>
