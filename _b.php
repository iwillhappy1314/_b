<?php
/*
Plugin Name:        _b
Plugin URI:         http://www.wpzhiku.com/
Description:        Display page`s subpage list and taxonomy terms list belongs to a post type
Version:            1.0.0
Author:             WordPress智库
Author URI:         http://www.wpzhiku.com/
*/

defined('WPINC') || die;

const SPACENAME_PLUGIN_SLUG = '_b';
const SPACENAME_VERSION = '1.0.0';
const SPACENAME_DEBUG = true;
const SPACENAME_MAIN_FILE = __FILE__;
define('SPACENAME_PATH', plugin_dir_path(__FILE__));
define('SPACENAME_URL', plugin_dir_url(__FILE__));

require_once(SPACENAME_PATH . 'vendor/autoload.php');

register_activation_hook(SPACENAME_MAIN_FILE, '_b_activation');
register_deactivation_hook(SPACENAME_MAIN_FILE, '_b_deactivation');
register_uninstall_hook(SPACENAME_MAIN_FILE, '_b_uninstallation_action_action');

function _b_activation()
{
    new WenpriseSpaceName\Actions\ActivationAction();
}


function _b_deactivation()
{
    new WenpriseSpaceName\Actions\DeactivationAction();
}


function _b_uninstallation_action_action()
{
    new WenpriseSpaceName\Actions\UninstallationAction();
}


if ( defined( 'WP_CLI' ) && WP_CLI ) {
    require_once __DIR__ . '/cli.php';
}


add_action('init', function ()
{
    load_plugin_textdomain('_b-', false, dirname(plugin_casename(__FILE__)) . '/languages/');
});


add_action('plugins_loaded', function ()
{
    if(!session_id()){
        session_start();
    }

    \WenpriseSpaceName\Init::get_instance();
});
