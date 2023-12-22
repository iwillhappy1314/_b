<?php
/**
 * 添加数据
 *
 * @package WenPrise
 */

namespace WenpriseSpaceName\Admin\Pages;

class CrmAddPage
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_page']);
    }


    public function add_page()
    {
        add_submenu_page(
            'options-general.php',
            __('Option Page', '_b'),
            __('Option', '_b'),
            'manage_options',
            '_b',
            [$this, 'render_page']
        );
    }


    function render_page()
    {
        ?>

        <div class="wrap">
            <h2><?php _e('Import Box Serial Number', '_b'); ?></h2>
        </div>

        <?php

    }

}



