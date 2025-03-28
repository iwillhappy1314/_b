<?php

namespace WenpriseSpaceName\AdminPages;

trait IndexPageTrait
{
    /**
     * 列表表格实例
     * @var \WP_List_Table
     */
    public \WP_List_Table $table;

    public string $table_class = '';

    /**
     * 添加显示选项分页设置
     *
     * @return void
     */
    public function set_display_options()
    {
        $this->table = new $this->table_class;

        $this->table->prepare_items();

        $option = 'per_page';
        $args = [
            'label'   => __('Items per page', 'wenprise-serial-manager'),
            'default' => $this->get_default_per_page(),
            'option'  => $this->get_per_page_option_name(),
        ];

        add_screen_option($option, $args);
    }

    /**
     * 获取默认的每页显示数量
     *
     * @return int
     */
    protected function get_default_per_page(): int
    {
        $user_id = get_current_user_id();
        $option_name = $this->table->args['singular'] . '_per_page';

        return (int) get_user_meta($user_id, $option_name, true) ?: 10;
    }

    /**
     * 获取每页显示数量的选项名称
     *
     * @return string
     */
    protected function get_per_page_option_name(): string
    {
        return $this->table->args['singular'] . '_per_page';
    }

    /**
     * 保存每页显示数量选项
     *
     * @param $status
     * @param $option
     * @param $value
     *
     * @return mixed
     */
    public function set_table_options($status, $option, $value)
    {
        return $value;
    }

}
