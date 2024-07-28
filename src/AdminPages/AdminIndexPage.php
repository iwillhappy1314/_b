<?php

namespace WenpriseSpaceName\AdminPages;


use WenpriseSpaceName\Helpers;

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
        wp_enqueue_style('_b-admin', Helpers::get_assets_url('admin.css'), [], SPACENAME_VERSION, 'screen');
        wp_enqueue_script('_b-admin', Helpers::get_assets_url('admin.js'), [], SPACENAME_VERSION, true);

        wp_localize_script('_b-admin', 'wenpriseSpaceNameAdminSettings', [
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
