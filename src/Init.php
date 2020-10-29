<?php

namespace WenpriseSpaceName;

use WenpriseSpaceName\Admin\Pages\AddPage;
use WenpriseSpaceName\Databases\Member;

class Init
{

    /**
     * constructor.
     */
    public function __construct()
    {

        $databases = [
            Member::class,
        ];

        foreach ($databases as $database) {
            new $database;
        }

        $classes = [
            AddPage::class,
        ];

        foreach ($classes as $class) {
            new $class;
        }

    }

}