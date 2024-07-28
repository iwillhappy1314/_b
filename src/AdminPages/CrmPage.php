<?php
/**
 * 添加数据
 *
 * @package WenPrise
 */

namespace WenpriseSpaceName\AdminPages;

use WenpriseSpaceName\Helpers;

class CrmPage
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

        $hook = add_menu_page(__('CRM', '_b'), __('CRM', '_b'), $capability, $slug, [$this, 'render'], 'dashicons-text');

        if (current_user_can($capability)) {
            $submenu[ $slug ][] = [__('CRM', '_b'), $capability, 'admin.php?page=' . $slug . '#/'];
            $submenu[ $slug ][] = [__('设置 ', '_b'), $capability, 'admin.php?page=' . $slug . '#/settings'];
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
        wp_enqueue_script('_b-admin', Helpers::get_assets_url('/dist/scripts/admin.js'), ['jquery'], '1.0.0', true);

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
