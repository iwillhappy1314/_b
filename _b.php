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

if ( ! is_file(SPACENAME_PATH . 'vendor/autoload.php')) {
    spl_autoload_register(function ($class)
    {
        $prefix = 'WenpriseSpaceName';

        if (strpos($class, $prefix) === false) {
            return;
        }

        $class    = substr($class, strlen($prefix));
        $location = SPACENAME_PATH . 'src' . str_replace('\\', '/', $class) . '.php';

        if (is_file($location)) {
            require_once($location);
        }
    });
} else {
    require_once(SPACENAME_PATH . 'vendor/autoload.php');
}


register_activation_hook(__FILE__, '_s_activation');
register_deactivation_hook(__FILE__, '_s_deactivation');
register_uninstall_hook(__FILE__, '_s_uninstallation_action_action');

function _s_activation()
{
    new WenpriseSpaceName\Actions\ActivationAction();
}


function _s_deactivation()
{
    new WenpriseSpaceName\Actions\DeactivationAction();
}


function _s_uninstallation_action_action()
{
    new WenpriseSpaceName\Actions\DeactivationAction();
}


add_action('plugins_loaded', function ()
{
    require_once(SPACENAME_PATH . 'src/routers.php');

    load_plugin_textdomain('_b', false, dirname(plugin_basename(__FILE__)) . '/languages/');

    new \WenpriseSpaceName\Init();
});