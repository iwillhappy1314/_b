<?php

namespace Wenprise\SpaceName;

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
}