<?php
/*
Plugin Name:        _b
Plugin URI:         http://www.wpzhiku.com/
Description:        Display page`s subpage list and taxonomy terms list belongs to a post type
Version:            1.0.0
Author:             WordPress 智库
Author URI:         http://www.wpzhiku.com/
*/

define('SPACENAME_VERSION', '1.0.0');
define('SPACENAME_PATH', plugin_dir_path(__FILE__));
define('SPACENAME_URL', plugin_dir_url(__FILE__));

add_action('plugins_loaded', function ()
{
    require_once(SPACENAME_PATH . 'vendor/autoload.php');
    require_once(SPACENAME_PATH . 'src/routers.php');

    load_plugin_textdomain('_b', false, dirname(plugin_basename(__FILE__)) . '/languages/');

    register_activation_hook(__FILE__, function ()
    {
        new Wenprise\SpaceName\Actions\ActivatorAction();
    });


    register_deactivation_hook(__FILE__, function ()
    {
        new Wenprise\SpaceName\Actions\DeactivatorAction();
    });


    add_action('admin_init', function ()
    {

        global $pagenow;

        wp_enqueue_script('_b-runtime', Wenprise\SpaceName\Helpers::get_assets_url('app', 'runtime.js'), [], SPACENAME_VERSION, true);
        wp_enqueue_style('_b-vendors-style', \Wenprise\SpaceName\Helpers::get_assets_url('app', 'vendors~admin~frontend'), [], SPACENAME_VERSION, 'screen');
        wp_enqueue_script('_b-vendors-script', Wenprise\SpaceName\Helpers::get_assets_url('app', 'vendors~admin~frontend.js'), [], SPACENAME_VERSION, true);


        // 判断是否为可变商品
        if ($pagenow === 'post.php' && get_post_type($_GET[ 'post' ]) === 'product') {
            wp_enqueue_style('_b-admin-styles', \Wenprise\SpaceName\Helpers::get_assets_url('app', 'admin.css'), [], SPACENAME_VERSION, 'screen');
            wp_enqueue_script('_b-admin-scripts', Wenprise\SpaceName\Helpers::get_assets_url('app', 'scripts.js'), ['_b-runtime'], SPACENAME_VERSION, true);
        }

    });


    add_action('wp_enqueue_scripts', function ()
    {
        wp_enqueue_script('_b-runtime', Wenprise\SpaceName\Helpers::get_assets_url('app', 'runtime.js'), [], SPACENAME_VERSION, true);
        wp_enqueue_style('_b-vendors-style', \Wenprise\SpaceName\Helpers::get_assets_url('app', 'vendors~admin~frontend'), [], SPACENAME_VERSION, 'screen');
        wp_enqueue_script('_b-vendors-script', Wenprise\SpaceName\Helpers::get_assets_url('app', 'vendors~admin~frontend.js'), [], SPACENAME_VERSION, true);

        wp_enqueue_style('_b-frontend-styles', \Wenprise\SpaceName\Helpers::get_assets_url('app', 'frontend.css'), [], SPACENAME_VERSION, 'screen');
        wp_enqueue_script('_b-frontend-scripts', Wenprise\SpaceName\Helpers::get_assets_url('app', 'frontend.js'), ['_b-runtime'], SPACENAME_VERSION, false);
    });


    new \Wenprise\SpaceName\Init();
});