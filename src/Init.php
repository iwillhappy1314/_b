<?php

namespace Wenprise\SpaceName;

use Wenprise\SpaceName\Admin\Pages\AddPage;

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