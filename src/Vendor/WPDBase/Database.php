<?php

namespace Wenprise\SpaceName\Vendor\WPDBase;

// Exit if accessed directly
if ( ! defined('ABSPATH')) {
    exit;
}

/**
 * 创建需要的数据表
 *
 * @return  void
 * @since 0.1
 *
 */
abstract class Database
{

    public $wpdb;
    public $collate;

    public function __construct()
    {
        $this->setDB();
        $this->register();
    }


    /**
     * 设置数据库实例
     */
    public function setDB()
    {
        global $wpdb;
        $collate = '';

        if ($wpdb->has_cap('collation')) {
            if ( ! empty($wpdb->charset)) {
                $collate .= "DEFAULT CHARACTER SET $wpdb->charset";
            }
            if ( ! empty($wpdb->collate)) {
                $collate .= " COLLATE $wpdb->collate";
            }
        }

        $this->wpdb    = $wpdb;
        $this->collate = $collate;
    }


    /**
     * 注册数据表
     */
    public function register()
    {

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $table_schema = $this->setTables();
        foreach ($table_schema as $table) {
            dbDelta($table);
        }

    }


    /**
     * 设置数据表
     *
     * @return array
     */
    abstract function setTables();

}