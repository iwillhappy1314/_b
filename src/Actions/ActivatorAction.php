<?php

namespace WenpriseSpaceName\Actions;

use WenpriseSpaceName\Databases\Member;

class ActivatorAction
{

    public function __construct()
    {
        $this->init_db();
    }


    public function init_db()
    {
        $databases = [
            Member::class,
        ];

        foreach ($databases as $database) {
            new $database;
        }
    }

}