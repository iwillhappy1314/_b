<?php

namespace WenpriseSpaceName\Views\Shortcodes;

use WenpriseSpaceName\Helpers;

class QueryShortcode
{
    public function __construct()
    {
        add_shortcode('_b_app', [$this, 'render']);
    }


    public function render($attrs)
    {
        ob_start();

        Helpers::get_template_part('my-affiliates', '', ['attrs' => $attrs]);

        return ob_get_clean();
    }
}