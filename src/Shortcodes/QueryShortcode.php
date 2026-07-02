<?php

namespace WenpriseSpaceName\Shortcodes;

use WenpriseSpaceName\Helpers;

/**
 * 查询页短代码
 *
 * 负责演示独立前台页面的 Shortcode 入口写法。
 */
class QueryShortcode
{
    /**
     * 注册短代码
     */
    public function __construct()
    {
        add_shortcode('_b_app', [$this, 'render']);
    }

    /**
     * 渲染短代码页面
     *
     * @param array $attrs
     * @return string
     */
    public function render($attrs)
    {
        ob_start();

        Helpers::get_template_part('my-affiliates', '', ['attrs' => $attrs]);

        return ob_get_clean();
    }
}
