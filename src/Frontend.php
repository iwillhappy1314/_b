<?php

namespace WenpriseSpaceName;

class Frontend
{

    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
    }


    public function enqueue_scripts()
    {

        wp_enqueue_style('_b', Helpers::get_assets_url('/dist/index.css'));
        wp_enqueue_script('_b', Helpers::get_assets_url('/dist/main.js'), ['jquery'], SPACENAME_VERSION, true);

        wp_localize_script('_b', 'wenpriseSpaceNameFrontendSettings', [
            'root'     => esc_url_raw(rest_url()),
            'nonce'    => wp_create_nonce('wp_rest'),
            'ajax_url' => admin_url('admin-ajax.php'),
        ]);

    }


    public function admin_enqueue_scripts()
    {
        global $pagenow;

        // wp_enqueue_style('_b-admin', Helpers::get_assets_url('/dist/admin.css'));
        wp_enqueue_style('wp-tiktok-affiliate', Helpers::get_assets_url('/dist/index.css'));
        wp_enqueue_script('wp-tiktok-affiliate-alpine', Helpers::get_assets_url('/dist/alpine.js'), [], SPACENAME_VERSION, true);
        // wp_enqueue_script('_b-admin', Helpers::get_assets_url('/dist/admin.js'), ['jquery'], SPACENAME_VERSION, true);

        // wp_localize_script('_b-admin', 'wenpriseSpaceNameAdminSettings', [
        //     'root'  => esc_url_raw(rest_url()),
        //     'nonce' => wp_create_nonce('wp_rest'),
        // ]);
    }
}
