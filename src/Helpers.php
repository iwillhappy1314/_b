<?php

namespace WenpriseSpaceName;

class Helpers
{

    /**
     * 获取资源 URL
     *
     * @param string $name      资源组名称
     * @param string $asset     静态资源名称
     * @param string $directory 相对插件根目录的 Url
     *
     * @return string
     */
    public static function get_assets_url($name, $asset, $directory = 'dist')
    {
        $filepath = realpath(SPACENAME_PATH . $directory . '/' . $name . '/manifest.json');

        if (file_exists($filepath)) {
            $assets = json_decode(file_get_contents($filepath), true);

            return esc_url(SPACENAME_URL . $directory . '/' . $assets[ $asset ]);
        }

        return false;
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
    public static function http_get($key = null, $default = null)
    {
        return static::data_get($_REQUEST, $key, $default);
    }

}