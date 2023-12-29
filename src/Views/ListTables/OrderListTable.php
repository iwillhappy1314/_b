<?php

namespace WenpriseSpaceName\Views\ListTables;

use WenpriseSpaceName\Helpers;
use WenpriseSpaceName\Models\OrderModel;
use Wenprise\Forms\Form;
use Wenprise\Forms\Renders\DefaultFormRender;

if ( ! class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class OrderListTable extends \WP_List_Table
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

        $model = OrderModel::query();

        // 搜索
        if (Helpers::input_get('s')) {
            $model->where('application_no', '=', Helpers::input_get('s'));
        }

        $this->datasets = $model->get()->toArray();
    }


    protected function get_views(): array
    {
        $model = OrderModel::query();
        if (current_user_can('administrator')) {
            $all_count = OrderModel::all()->count();

            $normal_count = $model->where('condition', '=', 'normal')
                                  ->get()
                                  ->count();
        } else {
            $all_count = OrderModel::query()
                                   ->where('user_id', '=', get_current_user_id())
                                   ->count();

            $normal_count = $model->where('condition', '=', 'normal')
                                  ->where('user_id', '=', get_current_user_id())
                                  ->get()
                                  ->count();
        }

        return [
            "all"      => __("<a href='" . remove_query_arg('condition') . "'>全部</a> (" . $all_count . ")", '_b'),
            "normal"   => __("<a href='" . add_query_arg('condition', 'normal') . "'>正常</a> (" . $normal_count . ")", '_b'),
            "abnormal" => __("<a href='" . add_query_arg('condition', 'abnormal') . "'>异常</a> (" . ($all_count - $normal_count) . ")", '_b'),
        ];
    }


    /**
     * 添加筛选项目
     *
     * @param $which
     *
     * @return void
     */
    function extra_tablenav($which)
    {
        if ($which == 'top') {
            $model = OrderModel::query();

            // 翻译国家
            $nations   = $model->distinct()->get('nation')->pluck('nation')->toArray();
            $countries = [];
            if ( ! empty($nations)) {
                foreach ($nations as $nation) {
                    $countries[ $nation ] = $nation;
                }
            }

            $countries = array_unique($countries);

            // 翻译类名
            $form = new Form();
            $form->setRenderer(new DefaultFormRender('vertical'));

            $form->addChosen('nation', '国家/地区', array_merge(['所有国家'], $countries))
                 ->setDefaultValue(Helpers::input_get('nation'));

            $form->addChosen('status', '状态', array_merge(['所有状态'], Helpers::get_config('general.status')));

            $form->addSubmit('submit', '筛选');

            echo '<div class="rs-filter-form">';
            $form->render('body');
            echo '</div>';
        }
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
            case 'user':
                return $item[ 'user_id' ] ? $item[ 'user_id' ] : 0;
            default:
                return $item[ $column_name ];
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
            $this->_args[ 'singular' ],
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
            'delete' => __('Delete', '_b'),
        ];
    }


    /**
     * 执行批量操作
     */
    public function process_bulk_action()
    {
        $send_back = remove_query_arg(['trashed', 'untrashed', 'deleted', 'locked', 'ids'], wp_get_referer());
        $ids       = (array)Helpers::input_get($this->_args[ 'singular' ]);

        if ('delete' === $this->current_action()) {
            foreach ($ids as $id) {
                $trashed = OrderModel::destroy($id);
            }

            Helpers::flash('success', '成功删除' . count($ids) . '条数据', add_query_arg(['trashed' => count($ids), 'ids' => join('_', $ids), 'locked' => 1], $send_back));
        }
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
        $hidden   = get_hidden_columns($this->screen);
        $sortable = $this->get_sortable_columns();


        // 列标题
        $this->_column_headers = [$columns, $hidden, $sortable];


        // 批量操作
        $this->process_bulk_action();


        // 列表数据
        $data = $this->datasets;

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