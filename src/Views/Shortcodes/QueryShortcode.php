<?php

namespace WenpriseSpaceName\Views\Shortcodes;

class QueryShortcode
{
    public function __construct()
    {
        add_shortcode('_b_app', [$this, 'render']);
    }


    public function render()
    {
        $template = SPACENAME_PATH . 'templates/app.php';

        include $template;
    }
}