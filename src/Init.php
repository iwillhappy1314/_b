<?php

namespace WenpriseSpaceName;

use Wenprise\Dispatcher\Router;
use WenpriseSpaceName\Views\Metaboxes\PostMetabox;
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
            PostMetabox::class,
        ];

        foreach ($classes as $class) {
            new $class;
        }

        add_action('rest_api_init', [new AddressApiController, 'register_routes']);

        $this->setRouter();
        $this->setUpdateChecker();
    }


    public function setRouter()
    {
        $routers = [
            '_b' => ['\WenpriseSpaceName\Controllers\SerialsController', 'index'],
        ];

        Router::routes(apply_filters('_b_routers', $routers));
    }


    public function setUpdateChecker()
    {
        $theme_update_checker = \Puc_v4_Factory::buildUpdateChecker(
            'https://api.wpcio.com/api/plugin/info/_b',
            get_parent_theme_file_path('functions.php'),
            '_b'
        );
    }

}