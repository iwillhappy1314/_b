<?php

namespace WenpriseSpaceName;

use Wenprise\TemplateHelper;
use Plasticbrain\FlashMessages\FlashMessages;

class Helpers extends \Wenprise\Mvc\Helpers
{

    /**
     * 获取资源 URL
     *
     * @param string $path 资源组名称
     *
     * @return string
     */
    public static function get_assets_url($path, string $manifest_directory = SPACENAME_PATH)
    {
        static $manifest;
        static $manifest_path;

        if ( ! $manifest_path) {
            $manifest_path = $manifest_directory . 'frontend/mix-manifest.json';
        }

        if ( ! $manifest) {
            // @codingStandardsIgnoreLine
            $manifest = json_decode(file_get_contents($manifest_path), true);
        }

        // Remove manifest directory from path
        $path = str_replace($manifest_directory, '', $path);
        // Make sure there’s a leading slash
        $path = '/' . ltrim($path, '/');

        // Get file URL from manifest file
        $path = $manifest[ $path ];
        // Make sure there’s no leading slash
        $path = ltrim($path, '/');

        return SPACENAME_URL . 'frontend/' . $path;
    }


    /**
     * 获取指定值的默认值
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public static function value($value)
    {
        return $value instanceof \Closure ? $value() : $value;
    }


    /**
     * 使用点注释获取数据
     *
     * @param array  $array
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public static function data_get($array, $key, $default = null)
    {
        if (is_null($key)) {
            return $array;
        }

        if (isset($array[ $key ])) {
            return $array[ $key ];
        }

        foreach (explode('.', $key) as $segment) {
            if ( ! is_array($array) || ! array_key_exists($segment, $array)) {
                return static::value($default);
            }

            $array = $array[ $segment ];
        }

        return $array;
    }


    /**
     * Get request var, if no value return default value.
     *
     * @param null $key
     * @param null $default
     *
     * @return mixed|null
     */
    public static function input_get($key = null, $default = null)
    {
        return static::data_get($_REQUEST, $key, $default);
    }


    /**
     * 生成通知消息
     *
     * @param $type    string 通知消息类型
     * @param $message string 通知消息内容
     * @param $url     string|null 跳转 URL
     * @param $sticky  bool 是否固定
     */
    public static function flash(string $type, string $message, string $url = null, bool $sticky = false)
    {
        $msg = new FlashMessages();
        $msg->$type($message, $url, $sticky);
    }

    /**
     * 显示通知消息
     */
    public static function show_messages()
    {
        $msg = new FlashMessages();

        return $msg->display(null, false);
    }


    /**
     * @param       $slug
     * @param       $name
     * @param array $args
     *
     * @return void
     */
    public static function get_template_part($slug, $name = null, array $args = [])
    {
        $helper = new TemplateHelper('_b', SPACENAME_PATH . 'templates/');

        $name = (string)$name;

        if ('' !== $name) {
            $template = "{$slug}-{$name}.php";
        } else {
            $template = "{$slug}.php";
        }

        $helper->get_template($template, $args);
    }


    /**
     * Add debug message to error log
     *
     * @param        $message
     * @param string $note
     *
     * @return void
     */
    public static function info_log($message, $note = '')
    {
        if (WP_DEBUG && SPACENAME_DEBUG) {
            error_log($note . var_export($message, true), 3, WP_CONTENT_DIR . '/' . SPACENAME_PLUGIN_SLUG . '-info.log');
        }
    }

}