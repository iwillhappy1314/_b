<?php

namespace WenpriseSpaceName;

use Wenprise\TemplateHelper;

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
            $manifest_path = $manifest_directory . 'resources/mix-manifest.json';
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

        return SPACENAME_URL . 'resources/' . $path;
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

    public static function render_modal($id, $title, $content): void
    {
        $model_name_open = $id . 'Open';
        ?>

        <div class="inline-block" x-data="{<?=$model_name_open;?>: false}">

            <button class="page-title-action" @click="<?=$model_name_open;?> = !<?=$model_name_open;?>">
                <?=$title;?>
            </button>

            <?php
            static::render_modal_content($id, $title, $content); ?>
        </div>

        <?php
    }


    public static function render_modal_content($id, $title, $content): void
    {
        $model_name_open = $id . 'Open';
        ?>
        <div x-show="<?=$model_name_open;?>" :class="<?=$model_name_open;?> ? 'block' : ''" class="rs-fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen px-4 text-center md:items-center sm:block sm:p-0">
                <div x-cloak @click="<?=$model_name_open;?> = false" x-show="<?=$model_name_open;?>"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="rs-fixed inset-0 transition-opacity bg-gray-500 bg-opacity-40" aria-hidden="true"
                ></div>

                <div x-cloak x-show="<?=$model_name_open;?>"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative inline-block w-full max-w-xl p-8 my-20 overflow-hidden text-left transition-all transform bg-white rounded-lg shadow-xl 2xl:max-w-2xl"
                >
                    <div class="flex items-center justify-between space-x-4 mb-4">
                        <h1 class="text-xl font-medium text-gray-800"><?=$title;?></h1>

                        <div @click="<?=$model_name_open;?> = false" class="cursor-pointer text-gray-600 focus:outline-none hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>

                    <div>
                        <?=$content;?>
                    </div>
                </div>
            </div>
        </div>

        <?php
    }


    public static function add_ajax_listener($action, $callback, $logged = 'both')
    {
        // Front-end ajax for non-logged users
        // Set $logged to false
        if ($logged === false || $logged === 'no') {
            add_action('wp_ajax_nopriv_' . $action, $callback);
        }

        // Front-end and back-end ajax for logged users
        if ($logged === true || $logged === 'yes') {
            add_action('wp_ajax_' . $action, $callback);
        }

        // Front-end and back-end for both logged in or out users
        if ($logged === 'both') {
            add_action('wp_ajax_' . $action, $callback);
            add_action('wp_ajax_nopriv_' . $action, $callback);
        }
    }


    public static function add_admin_action_listener($action, $callback, $logged = 'both')
    {
        // Front-end ajax for non-logged users
        // Set $logged to false
        if ($logged === false || $logged === 'no') {
            add_action('admin_post_nopriv_' . $action, $callback);
        }

        // Front-end and back-end ajax for logged users
        if ($logged === true || $logged === 'yes') {
            add_action('admin_post_' . $action, $callback);
        }

        // Front-end and back-end for both logged in or out users
        if ($logged === 'both') {
            add_action('admin_post_' . $action, $callback);
            add_action('admin_post_nopriv_' . $action, $callback);
        }
    }


    public static function get_admin_action_url( $action ) {
		return admin_url( 'admin-post.php?action=' . $action );
	}


    public static function log( $message, $label = 'DEBUG', $log_file = null ) {
        // 指定默认日志路径
        if ( ! $log_file ) {
            $upload_dir = wp_upload_dir();
            $log_file = trailingslashit( $upload_dir['basedir'] ) . '_b-debug.log';
        }

        // 构造时间戳和标签
        $timestamp = date( 'Y-m-d H:i:s' );
        $output = "[$timestamp] [$label] ";

        // 支持数组或对象格式化
        if ( is_array( $message ) || is_object( $message ) ) {
            $output .= print_r( $message, true );
        } else {
            $output .= $message;
        }

        // 写入文件
        file_put_contents( $log_file, $output . PHP_EOL, FILE_APPEND );
    }

}
