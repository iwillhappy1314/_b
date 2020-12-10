<?php

namespace WenpriseSpaceName;

use WenpriseSpaceName\Admin\Pages\AddPage;


class Init
{

    /**
     * constructor.
     */
    public function __construct()
    {
        $classes = [
            AddPage::class,
        ];

        foreach ($classes as $class) {
            new $class;
        }

    }

}