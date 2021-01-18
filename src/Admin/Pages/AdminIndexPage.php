<?php

namespace WenpriseSpaceName\Admin\Pages;


class AdminIndexPage
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'admin_menu']);
    }

    /**
     * Register our menu page
     *
     * @return void
     */
    public function admin_menu()
    {
        global $submenu;

        $capability = 'manage_options';
        $slug       = '_b-admin';

        $hook = add_menu_page(__('Packages', '_b'), __('Packages', '_b'), $capability, $slug, [$this, 'render'], 'dashicons-text');

        if (current_user_can($capability)) {
            $submenu[ $slug ][] = [__('App', '_b'), $capability, 'admin.php?page=' . $slug . '#/'];
            $submenu[ $slug ][] = [__('Settings', '_b'), $capability, 'admin.php?page=' . $slug . '#/settings'];
        }

        add_action('load-' . $hook, [$this, 'init_hooks']);
    }

    /**
     * Initialize our hooks for the admin page
     *
     * @return void
     */
    public function init_hooks()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    /**
     * Load scripts and styles for the app
     *
     * @return void
     */
    public function enqueue_scripts()
    {
        wp_enqueue_style('_b-admin', \WenpriseSpaceName\Helpers::get_assets_url('admin', 'vendors~admin.css'), [], SPACENAME_VERSION, 'screen');

        wp_enqueue_script('_b-admin-runtime', \WenpriseSpaceName\Helpers::get_assets_url('admin', 'runtime.js'), [], SPACENAME_VERSION, true);

        wp_enqueue_script('_b-vendors-admin', \WenpriseSpaceName\Helpers::get_assets_url('admin', 'vendors~admin.js'), [], SPACENAME_VERSION, true);

        wp_enqueue_script('_b-admin', \WenpriseSpaceName\Helpers::get_assets_url('admin', 'admin.js'), ['_b-admin-runtime', '_b-vendors-admin'], SPACENAME_VERSION, true);

        wp_localize_script('_b-admin', 'wpTransshipSettings', [
            'root'  => esc_url_raw(rest_url()),
            'nonce' => wp_create_nonce('wp_rest'),
        ]);
    }

    /**
     * Render our admin page
     *
     * @return void
     */
    public function render()
    {
        echo '<div class="wrap"><div id="_b-admin"></div></div>';
    }
}