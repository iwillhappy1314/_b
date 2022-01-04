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

        $enqueue = new \WPackio\Enqueue( '_b', 'dist', '1.0.0', 'plugin', SPACENAME_MAIN_FILE );
        $assets = $enqueue->enqueue( 'frontend', 'main', [] );

        $entry_point = array_pop( $assets['js'] )['handle'];

        wp_localize_script($entry_point, 'WenpriseSpaceNameRealifeApiSettings', [
            'root'  => esc_url_raw(rest_url()),
            'nonce' => wp_create_nonce('wp_rest'),
        ]);
    }


    public function admin_enqueue_scripts()
    {
        global $pagenow;

        $enqueue = new \WPackio\Enqueue( '_b', 'dist', '1.0.0', 'plugin', SPACENAME_MAIN_FILE );
        $assets = $enqueue->enqueue( 'admin', 'main', [] );

        $entry_point = array_pop( $assets['js'] )['handle'];

        wp_localize_script($entry_point, 'wenpriseSpaceNameSettings', [
            'root'  => esc_url_raw(rest_url()),
            'nonce' => wp_create_nonce('wp_rest'),
        ]);

        // 判断是否为可变商品
        if ($pagenow === 'post.php' && get_post_type($_GET[ 'post' ]) === 'product') {
            wp_enqueue_style('_b-admin', Helpers::get_assets_url('admin', 'admin.css'), [], SPACENAME_VERSION, 'screen');
            wp_enqueue_script('_b-admin', Helpers::get_assets_url('admin', 'scripts.js'), ['_b-runtime'], SPACENAME_VERSION, true);
        }
    }
}
