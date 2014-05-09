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
 * <h3>Reply process</h3>
 * <p>
 * 处理页面内容并输出最终页面
 * ·format：页面内容处理与缓存方法，如果需要对页面内容做特殊处理，应重写该方法。
 * ·assign：页面内容输出方法，无法重写该方法。
 * </p>
 *
 * @copyright Copyright (C) 2011-2014 Xiao Yang, Bai
 * @author Xiao Yang, Bai
 */
class Page extends Process
{
    const JS = 'JS';
    const CSS = 'CSS';

    /**
     * 页面布局
     */
    protected $layout = '_page.php';
    /**
     * 页面样式
     */
    protected $css = null;
    /**
     * 页面脚本
     */
    protected $js = null;
    /**
     * 页面版式
     */
    protected $formats = null;
    /**
     * 页面修整
     */
    protected $trims = null;

    /**
     * <h4>生成页面HTML</h4>
     *
     * @return void
     */
    protected function setup()
    {
        if ($this->event->message)
        {
            return $this->load('_error.php');
        }
        $this['css'] = $this->css;
        $this['js'] = $this->js;
        $this['lside'] = null;
        $this['rside'] = null;
        return $this->load($this->layout, null, 1);
    }

    /**
     * <h4>页面内容处理</h4>
     * <p>
     * 应用页面版式，页面修整，并缓存页面
     * </p>
     *
     * @param string $event 事件
     * @param string $page 页面内容
     */
    protected function format()
    {
        $page = $this->result;
        ### 应用页面版式
        $csses = self::config(self::CSS);
        if (is_array($csses))
        {
            $page = str_replace(array_keys($csses), array_values($csses), $page);
        }

        $jses = self::config(self::JS);
        if (is_array($jses))
        {
            $page = str_replace(array_keys($jses), array_values($jses), $page);
        }

        ### 应用页面修整
        if ($this->trims && is_array($this->trims))
        {
            #$page = preg_replace(array_keys($this->trims), array_values($this->trims), $page);
        }
        ### 应用页面缓存
        #$cache = Cache::access();
        #$cache->entrust($event, $page);
        return $page;
    }

    /**
     * <h3>Read runtime item</h3>
     *
     * @param string $name
     *        item name
     * @return mixed item value
     */
    public function offsetGet($item)
    {
        if (! isset($this->runtime[$item]))
        {
            $this->runtime[$item] = Lang::cut($item, false);
        }
        return $this->runtime[$item];
    }
}
