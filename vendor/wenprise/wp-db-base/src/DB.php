<?php

namespace WPDBase;

// Exit if accessed directly
if ( ! defined('ABSPATH')) {
    exit;
}

/**
 * DB base class
 *
 * @package     EDD
 * @subpackage  Classes/EDD DB
 * @copyright   Copyright (c) 2015, Pippin Williamson
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       2.1
 */
abstract class DB
{

    /**
     * 数据表名称
     *
     * @since   2.1
     */
    public $table_name;

    /**
     * 数据表版本
     *
     * @since   2.1
     */
    public $version;

    /**
     * 主键名
     *
     * @since   2.1
     */
    public $primary_key;

    /**
     * Get things started
     *
     * @since   2.1
     */
    public function __construct() { }

    /**
     * Whitelist of columns
     *
     * @return  array
     * @since   2.1
     */
    public function get_columns()
    {
        return [];
    }

    /**
     * 默认列值
     *
     * @return  array
     * @since   2.1
     */
    public function get_column_defaults()
    {
        return [];
    }

    /**
     * 根据主键获取一行数据
     *
     * @param int $row_id 主键
     *
     * @return  object
     * @since   2.1
     */
    public function get_row($row_id)
    {
        global $wpdb;

        return $wpdb->get_row($wpdb->prepare("SELECT * FROM $this->table_name WHERE $this->primary_key = %s LIMIT 1;", $row_id));
    }

    /**
     * 根据指定的列/值获取一行数据
     *
     * @param string $column 列名
     * @param int    $row_id 主键
     *
     * @return  object
     * @since   2.1
     */
    public function get_row_by($column, $row_id)
    {
        global $wpdb;
        $column = esc_sql($column);

        return $wpdb->get_row($wpdb->prepare("SELECT * FROM $this->table_name WHERE $column = %s LIMIT 1;", $row_id));
    }

    /**
     * 根据主键获取指定的列值
     *
     * @param string $column 列名
     * @param int    $row_id 主键
     *
     * @return  string
     * @since   2.1
     */
    public function get_column($column, $row_id)
    {
        global $wpdb;
        $column = esc_sql($column);

        return $wpdb->get_var($wpdb->prepare("SELECT $column FROM $this->table_name WHERE $this->primary_key = %s LIMIT 1;", $row_id));
    }

    /**
     * 根据指定的列/值获取一列数据
     *
     * @param string $column       列名
     * @param string $column_where 列名
     * @param string $column_value 列值
     *
     * @return  string
     * @since   2.1
     */
    public function get_column_by($column, $column_where, $column_value)
    {
        global $wpdb;
        $column_where = esc_sql($column_where);
        $column       = esc_sql($column);

        return $wpdb->get_var($wpdb->prepare("SELECT $column FROM $this->table_name WHERE $column_where = %s LIMIT 1;", $column_value));
    }

    /**
     * 插入一个新行
     *
     * @param array  $data 需要插入的关联数组
     * @param string $type 插入数据的类型
     *
     * @return  int
     * @since   2.1
     */
    public function insert($data, $type = '')
    {
        global $wpdb;

        // 设置默认值
        $data = wp_parse_args($data, $this->get_column_defaults());

        do_action('wprs_pre_insert_' . $type, $data);

        // 初始化数据列格式
        $column_formats = $this->get_columns();

        // 转换数据 key 到小些字符
        $data = array_change_key_case($data);

        // 列白名单
        $data = array_intersect_key($data, $column_formats);

        // 重新排序 $column_formats 以符合 $data 中的数据顺序
        $data_keys      = array_keys($data);
        $column_formats = array_merge(array_flip($data_keys), $column_formats);

        $wpdb->insert($this->table_name, $data, $column_formats);
        $wpdb_insert_id = $wpdb->insert_id;

        do_action('wprs_post_insert_' . $type, $wpdb_insert_id, $data);

        return $wpdb_insert_id;
    }

    /**
     * 更新数据行
     *
     * @param int    $row_id 主键
     * @param array  $data   需要更新的关联数组
     * @param string $where  主键名称
     *
     * @return  bool
     * @since   2.1
     */
    public function update($row_id, $data = [], $where = '')
    {

        global $wpdb;

        // Row ID 必须是正整数
        $row_id = absint($row_id);

        if (empty($row_id)) {
            return false;
        }

        if (empty($where)) {
            $where = $this->primary_key;
        }

        // 初始化数据列格式
        $column_formats = $this->get_columns();

        // 转换数据 key 到小些字符
        $data = array_change_key_case($data);

        // 列白名单
        $data = array_intersect_key($data, $column_formats);

        // 重新排序 $column_formats 以符合 $data 中的数据顺序
        $data_keys      = array_keys($data);
        $column_formats = array_merge(array_flip($data_keys), $column_formats);

        return ! (false === $wpdb->update($this->table_name, $data, [$where => $row_id], $column_formats));
    }

    /**
     * 删除指定的数据表
     *
     * @param int $row_id 主键 ID
     *
     * @return  bool
     * @since   2.1
     */
    public function delete($row_id = 0)
    {

        global $wpdb;

        // Row ID must be positive integer
        $row_id = absint($row_id);

        if (empty($row_id)) {
            return false;
        }

        if (false === $wpdb->query($wpdb->prepare("DELETE FROM $this->table_name WHERE $this->primary_key = %d", $row_id))) {
            return false;
        }

        return true;
    }

    /**
     * 检查数据表是否存在
     *
     * @param string $table 数据表名称
     *
     * @return bool
     * @since  2.4
     */
    public function table_exists($table)
    {
        global $wpdb;
        $table = sanitize_text_field($table);

        return $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE '%s'", $table)) === $table;
    }

    /**
     * 检查数据表是否已安装
     *
     * @return bool
     * @since  2.4
     */
    public function installed()
    {
        return $this->table_exists($this->table_name);
    }

}
