<?php

namespace WenpriseSpaceName\Views\Shortcodes;

class QueryShortcode
{
    public function __construct()
    {
        add_shortcode('_b_serial_validator', [$this, 'render']);
    }


    public function render()
    {
        $template = SPACENAME_PATH . 'resources/templates/validator.php';

        include $template;
    }
}