<?php

namespace WenpriseSpaceName;

use WenpriseSpaceName\Admin\Pages\AddPage;
use WenpriseSpaceName\Controllers\AddressApiController;
use WenpriseSpaceName\Admin\Pages\AdminIndexPage;


class Init
{

    /**
     * constructor.
     */
    public function __construct()
    {
        $classes = [
            AddPage::class,
            AdminIndexPage::class,
            Frontend::class,
        ];

        foreach ($classes as $class) {
            new $class;
        }

        add_action('rest_api_init', [new AddressApiController, 'register_routes']);
    }

}