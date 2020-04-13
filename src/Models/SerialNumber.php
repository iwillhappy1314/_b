<?php

namespace Wenprise\SpaceName\Models;

use Wenprise\SpaceName\Vendor\WPDBase\DB;

/**
 * 序列号
 *
 * Class SerialNumber
 */
class SerialNumber extends DB
{

    public $table_name = 'wp_serial_numbers';
    public $primary_key = 'id';


    /**
     * Whitelist of columns
     *
     * @return  array
     * @since   2.1
     */
    public function get_columns()
    {
        return [
            'serial_number' => '%s',
            'serial_pass'   => '%s',
            'status'        => '%s',
        ];
    }

    /**
     * 默认列值
     *
     * @return  array
     * @since   2.1
     */
    public function get_column_defaults()
    {
        return [
            'status' => 'new',
        ];
    }


    /**
     * 获取全部数据
     *
     * @return array|object|null
     */
    public function getAll()
    {
        global $wpdb;

        return $wpdb->get_results($wpdb->prepare("SELECT * FROM $this->table_name WHERE $this->primary_key > %d", 0), ARRAY_A);
    }

}
