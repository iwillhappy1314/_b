<?php

namespace Wenprise\SpaceName;

use Wenprise\SpaceName\Admin\Pages\AddPage;
use Wenprise\SpaceName\Databases\Member;

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