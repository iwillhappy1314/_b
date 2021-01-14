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

require_once(SPACENAME_PATH . 'vendor/autoload.php');

register_activation_hook(__FILE__, function ()
{
    new WenpriseSpaceName\Actions\ActivationAction();
});


register_deactivation_hook(__FILE__, function ()
{
    new WenpriseSpaceName\Actions\DeactivationAction();
});


register_uninstall_hook(__FILE__, '_b_install');

function _b_install()
{
    new WenpriseSpaceName\Actions\UninstallationAction();
}


add_action('plugins_loaded', function ()
{

    require_once(SPACENAME_PATH . 'src/routers.php');

    load_plugin_textdomain('_b', false, dirname(plugin_basename(__FILE__)) . '/languages/');

    new \WenpriseSpaceName\Init();
});