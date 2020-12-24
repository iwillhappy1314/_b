<?php

namespace WenpriseSpaceName\Views\ListTables;

use WenpriseSpaceName\Models\SerialNumberModel;

if ( ! class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class SerialListTable extends \WP_List_Table
{

    /**
     * 列表數據
     *
     * @var array
     */
    public $datasets = [];
    public $model = null;


    /**
     * Serial_List_Table constructor.
     */
    public function __construct()
    {
        global $status, $page;

        //Set parent defaults
        parent::__construct([
            'singular' => 'serial',
            'plural'   => 'serials',
            'ajax'     => true,
        ]);

        $this->datasets = SerialNumberModel::all()->toArray();
    }


    /**
     * 設置數據列名稱
     *
     * @param object $item
     * @param string $column_name
     *
     * @return mixed
     */
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'serial_number':
                return $this->column_title($item);
            case 'status':
                return $item[ $column_name ];
            case 'user':
                return $item[ 'user_id' ] ? $item[ 'user_id' ] : 0;
            default:
                return print_r($item, true);
        }
    }


    /**
     * 设置标题列
     *
     * @param $item
     *
     * @return string
     */
    public function column_title($item)
    {

        // Build row actions
        $actions = [
            // 'edit'   => sprintf('<a href="?page=%s&action=%s&serial=%s">' . __('Edit', 'wenprise-serial-manager') . '</a>', $_REQUEST[ 'page' ], 'edit', $item[ 'id' ]),
            'delete' => sprintf('<a href="?page=%s&action=%s&serial=%s">' . __('Delete', 'wenprise-serial-manager') . '</a>', $_REQUEST[ 'page' ], 'delete', $item[ 'id' ]),
        ];

        // Return the title contents
        return sprintf('%1$s %2$s',
            $item[ 'serial_number' ],
            $this->row_actions($actions)
        );
    }


    /**
     * 批量操作多选框
     *
     * @param object $item
     *
     * @return string|mixed
     */
    public function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            $this->_args[ 'singular' ],
            $item[ 'id' ]
        );
    }


    /**
     * 获取数据列
     *
     * @return array
     */
    public function get_columns()
    {
        return [
            'cb'            => '<input type="checkbox" />',
            'serial_number' => __('Serial number', 'wenprise-serial-manager'),
            'user'          => __('User', 'wenprise-serial-manager'),
            'status'        => __('Status', 'wenprise-serial-manager'),
        ];
    }


    /**
     * 获取可排序数据列
     *
     * @return array
     */
    function get_sortable_columns()
    {
        return [
            'serial_number' => ['serial_number', false],
            'user'          => ['user', false],
            'status'        => ['status', false],
        ];
    }


    /**
     * 获取批量操作
     *
     * @return array
     */
    function get_bulk_actions()
    {
        return [
            'delete' => 'Delete',
        ];
    }


    /**
     * 执行批量操作
     */
    public function process_bulk_action()
    {
        $sendback = remove_query_arg(['trashed', 'untrashed', 'deleted', 'locked', 'ids'], wp_get_referer());
        $ids      = isset($_GET[ 'serial' ]) ? (array)$_GET[ 'serial' ] : [];

        if ('delete' === $this->current_action()) {
            foreach ($ids as $id) {
                $trashed = SerialNumberModel::destroy($id);
            }

            wp_redirect(add_query_arg(['trashed' => count($ids), 'ids' => join('_', (array)$ids), 'locked' => 1], $sendback));
        }

    }


    // 排序
    public function usort_reorder($a, $b)
    {
        $orderby = ( ! empty($_REQUEST[ 'orderby' ])) ? $_REQUEST[ 'orderby' ] : 'serial_number';
        $order   = ( ! empty($_REQUEST[ 'order' ])) ? $_REQUEST[ 'order' ] : 'asc';
        $result  = strcmp($a[ $orderby ], $b[ $orderby ]);

        return ($order === 'asc') ? $result : -$result;
    }


    /**
     * 准备数据
     */
    public function prepare_items()
    {

        // 每页显示数量
        $per_page = 20;

        // 必须设置
        $columns  = $this->get_columns();
        $hidden   = [];
        $sortable = $this->get_sortable_columns();


        // 列标题
        $this->_column_headers = [$columns, $hidden, $sortable];


        // 批量操作
        $this->process_bulk_action();


        // 列表数据
        $data = $this->datasets;

        usort($data, [$this, 'usort_reorder']);

        // 当前页数
        $current_page = $this->get_pagenum();

        // 总数
        $total_items = count($data);


        // 分页后的数据
        $data = array_slice($data, (($current_page - 1) * $per_page), $per_page);


        // 设置分页后的数据
        $this->items = $data;


        // 设置分页
        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil($total_items / $per_page),
        ]);
    }


}