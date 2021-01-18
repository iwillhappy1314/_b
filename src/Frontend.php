<?php

namespace WenpriseSpaceName;


class Frontend
{

    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('admin_init', [$this, 'enqueue_admin_scripts']);
    }


    public function enqueue_scripts()
    {
        wp_enqueue_script('_b-runtime', Helpers::get_assets_url('app', 'runtime.js'), [], SPACENAME_VERSION, true);
        wp_enqueue_style('_b-vendors', Helpers::get_assets_url('app', 'vendors~frontend.css'), [], SPACENAME_VERSION, 'screen');
        wp_enqueue_script('_b-vendors', Helpers::get_assets_url('app', 'vendors~frontend.js'), [], SPACENAME_VERSION, true);

        wp_enqueue_style('_b-frontend', Helpers::get_assets_url('app', 'frontend.css'), [], SPACENAME_VERSION, 'screen');
        wp_enqueue_script('_b-frontend', Helpers::get_assets_url('app', 'frontend.js'), ['_b-runtime'], SPACENAME_VERSION, false);

        wp_localize_script('_b-frontend', '_bApiSettings', [
            'root'  => esc_url_raw(rest_url()),
            'nonce' => wp_create_nonce('wp_rest'),
        ]);
    }


    public function enqueue_admin_scripts()
    {
        global $pagenow;

        wp_enqueue_script('_b-runtime', Helpers::get_assets_url('app', 'runtime.js'), [], SPACENAME_VERSION, true);
        wp_enqueue_style('_b-vendors', Helpers::get_assets_url('app', 'vendors~admin~frontend.css'), [], SPACENAME_VERSION, 'screen');
        wp_enqueue_script('_b-admin', Helpers::get_assets_url('app', 'vendors~admin~frontend.js'), [], SPACENAME_VERSION, true);

        wp_localize_script('_b-admin', '_bAdminSettings', [
            'root'  => esc_url_raw(rest_url()),
            'nonce' => wp_create_nonce('wp_rest'),
        ]);

        // 判断是否为可变商品
        if ($pagenow === 'post.php' && get_post_type($_GET[ 'post' ]) === 'product') {
            wp_enqueue_style('_b-admin', Helpers::get_assets_url('app', 'admin.css'), [], SPACENAME_VERSION, 'screen');
            wp_enqueue_script('_b-admin', Helpers::get_assets_url('app', 'scripts.js'), ['_b-runtime'], SPACENAME_VERSION, true);
        }
    }

}