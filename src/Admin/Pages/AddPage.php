<?php
/**
 * 添加数据
 *
 * @package WenPrise
 */

namespace WenpriseSpaceName\Admin\Pages;

use Wenprise\Mvc\Helpers as MvcHelpers;
use Wenprise\Forms\Form;
use Wenprise\Forms\Renders\AdminFormRender;
use WenpriseSpaceName\Helpers;
use WenpriseSpaceName\Models\OrderModel;

class AddPage
{
    public Form $form;

    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_page']);
    }


    public function add_page()
    {
        $hook = add_submenu_page(
            'options-general.php',
            __('Option Page', '_b'),
            __('Option', '_b'),
            'manage_options',
            '_b',
            [$this, 'render_page']
        );

        add_action("load-$hook", [$this, 'set_form']);
    }


    public function set_form(): void
    {
        $service = Helpers::input_get('stype', 'trademark');

        if (Helpers::input_get('order') && Helpers::input_get('action') === 'edit') {
            $model = OrderModel::query()->find(Helpers::input_get('order'));
        } else {
            $model = new OrderModel();
        }

        /**
         * 设置选项数据
         */
        $nations = ['' => '请选择国家/地区'] + [$model->nation => $model->nation] + Helpers::get_nation_names();

        $trademark_types = ['请选择商标类型'] + wp_list_pluck(get_option('trademark_type'), 'type', 'type');

        $primary_classes = ['' => '请选择商标类别'] + [$model->primary_class => $model->primary_class] + Helpers::get_primary_class_name();

        $sources = ['' => '请选择客户来源'] + [$model->source => $model->source] + wp_list_pluck(get_option('source'), 'type', 'type');

        /**
         * 开始构建表单
         */
        $form = new Form();
        $form->setRenderer(new AdminFormRender('option'));

        $form->addGroup('业务信息');

        $form->addHidden('user_id', get_current_user_id());

        $form->addChosen('nation', '国家/地区', $nations)
             ->setDefaultValue($model->nation);

        if ($form->isSuccess()) {
            $values = (array)$form->getValues();

            MvcHelpers::flash('success', '保存成功', '', true);
        }

        $this->form = $form;
    }


    function render_page()
    {
        ?>

        <div class="wrap">
            <h2><?php _e('Import Box Serial Number', '_b'); ?></h2>
        </div>

        <?php

    }

}



