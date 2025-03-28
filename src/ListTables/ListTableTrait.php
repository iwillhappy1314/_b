<?php

namespace WenpriseSpaceName\ListTables;


trait ListTableTrait
{
    /**
     * 数据模型实例
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * 默认每页显示数量
     * @var int
     */
    protected $per_page = 20;

    /**
     * 默认排序字段
     * @var string
     */
    protected $default_orderby = 'created_at';

    /**
     * 默认排序方向
     * @var string
     */
    protected $default_order = 'DESC';

    /**
     * 准备列表数据
     */
    public function prepare_items()
    {
        // 获取每页显示数量
        $per_page = $this->get_items_per_page($this->_args['singular'] . '_per_page', $this->per_page);

        // 获取当前页码
        $current_page = $this->get_pagenum();

        // 获取并应用排序
        $orderby = isset($_REQUEST['orderby']) ? $_REQUEST['orderby'] : $this->default_orderby;
        $order = isset($_REQUEST['order']) ? strtoupper($_REQUEST['order']) : $this->default_order;

        // 应用排序到查询
        $this->model->orderBy($orderby, $order);

        // 获取总数
        $total_items = $this->model->count();

        // 分页查询数据
        $this->items = $this->model
            ->limit($per_page)
            ->offset(($current_page - 1) * $per_page)
            ->get()
            ->toArray();

        // 设置列信息
        $columns = $this->get_columns();
        $hidden = get_hidden_columns($this->screen);
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = [$columns, $hidden, $sortable];

        // 设置分页参数
        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page' => $per_page,
            'total_pages' => ceil($total_items / $per_page),
        ]);
    }

    /**
     * 设置查询模型
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return $this
     */
    protected function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * 设置默认排序
     *
     * @param string $orderby
     * @param string $order
     * @return $this
     */
    protected function setDefaultOrder($orderby, $order = 'DESC')
    {
        $this->default_orderby = $orderby;
        $this->default_order = strtoupper($order);
        return $this;
    }

    /**
     * 设置每页显示数量
     *
     * @param int $per_page
     * @return $this
     */
    protected function setPerPage($per_page)
    {
        $this->per_page = (int)$per_page;
        return $this;
    }
}