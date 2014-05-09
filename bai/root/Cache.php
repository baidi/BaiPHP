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
 * <h3>Cache work</h3>
 * <p>
 * Data cache based on APC.
 * </p>
 *
 * @copyright Copyright (C) 2011-2014 Xiao Yang, Bai
 * @author Xiao Yang, Bai
 */
class Cache extends Work
{
	/**
	 * Active status
	 */
	protected $active = false;
	/**
	 * Timeout(seconds)
	 */
	protected $timeout = 600;
	/**
	 * Excluded items
	 */
	protected $excluded = null;

	/**
	 * Static entrance
	 */
	private static $ENTRANCE = false;

	/**
	 * <h4>Read or write datas into cache</h4>
	 *
	 * @param string $item
	 *        item name
	 * @param mixed $data
	 *        item data
	 * @param bool $mem
	 *        true: into memory
	 *        false: into disk file
	 * @return mixed cached data
	 */
	public function entrust($item = null, $data = self::NIL, $mem = true)
	{
		if (!$this->active || $item == null || self::pick($item, $this->excluded))
		{
			return false;
		}

		### runtime datas
		$this['item'] = $this->rename($item);
		$this['data'] = $data;
		$this['mem'] = $mem;

		if ($item === Bai::FLUSH)
		{
			### flush cache
			return $this->result = $this->flush();
		}

		if ($data === self::NIL)
		{
			### read cache item
			return $this->result = $this->read();
		}

		return $this->result = $this->write();
	}

	/**
	 * <h4>Read a cache item</h4>
	 *
	 * @return mixed cached data
	 */
	protected function read()
	{
		$item = $this['item'];
		Log::logf(__FUNCTION__, $item, __CLASS__);

		### read cache item from APC
		$data = apc_fetch($item, $result);
		if (!$result)
		{
			return false;
		}

		### cache in memory
		if (substr($item, -strlen(__CLASS__) - 1) !== '.' . __CLASS__)
		{
			return $data;
		}

		### cache in a file
		if (!is_file($data))
		{
			return false;
		}
		return file_get_contents($data);
	}

	/**
	 * <h4>Write a cache item</h4>
	 *
	 * @return bool result
	 */
	protected function write()
	{
		$item = $this['item'];
		$data = $this['data'];
		$mem = $this['mem'];
		Log::logf(__FUNCTION__, $item, __CLASS__);

		if ($mem)
		{
			### write into memory
			return apc_store($item, $data, $this->timeout);
		}

		### write into file
		$filename = _LOCAL . $this->place . $item;
		if (file_put_contents($filename, $data) === false)
		{
			Log::logf('file', $filename, __CLASS__, Log::WARING);
			return false;
		}

		### cache file name
		return apc_store($item, $filename, $this->timeout);
	}

	/**
	 * <h4>Flush cache</h4>
	 */
	protected function flush()
	{
		### flush apc
		apc_clear_cache();

		$place = _LOCAL . $this->place;
		$files = scandir($place);
		if ($files == null)
		{
			return Bai::FLUSH;
		}

		### flush cached files
		foreach ($files as $file)
		{
			if (substr($file, -strlen(__CLASS__) - 1) !== '.' . __CLASS__)
			{
				continue;
			}
			if (!unlink($place . $file))
			{
				return false;
			}
		}
		return Bai::FLUSH;
	}

	/**
	 * <h4>Rename a cache item</h4>
	 *
	 * @param string $item
	 *        cache item
	 * @return string item name
	 */
	private function rename($item, $mem = true)
	{
		$item = $this->event['base'] . _DEF . $this->event['event'] . _DEF . $item;
		if (!$mem)
		{
			$item .= '.' . __CLASS__;
		}
		$item = urlencode($item);
		return $item;
	}
}
?>
