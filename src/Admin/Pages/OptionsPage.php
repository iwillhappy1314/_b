<?php
/**
 * 导入数据
 *
 * @package WenPrise
 */

namespace WenpriseSpaceName\Admin\Pages;

use TrademarkMonitor\Helpers;
use Wenprise\Forms\Datastores\OptionsDatastore;
use Wenprise\Forms\Form;
use Wenprise\Forms\Renders\AdminFormRender;

class OptionsPage
{
    public Form $form;

    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_page'], 25);

        add_action('wp_enqueue_scripts', function ()
        {
            wp_deregister_script('trademark-monitor-alpine');
        }, 999);

    }


    public function add_page()
    {
        $hook = add_submenu_page(
            'trademarks',
            __('系统设置', '_b'),
            __('系统设置', '_b'),
            'manage_options',
            'trademark-options',
            [$this, 'render_page']
        );

        add_action("load-$hook", [$this, 'set_form']);
    }


    public function set_form(): void
    {
        $form = new Form();
        $form->setRenderer(new AdminFormRender('option'));
        $form->setDatastore(new OptionsDatastore($form));

        $form->addGroup('数据选项');

        $form->addTableInput('trademark_type', '商标类型', [], [['name' => 'type', 'display' => '类型']])
            ->setDefaultValue(!empty(get_option('trademark_type')) ? get_option('trademark_type') : [['type' => '', ]]);

        $form->addTableInput('client_type', '客户类型', [], [['name' => 'type', 'display' => '类型']])
            ->setDefaultValue(!empty(get_option('client_type')) ? get_option('client_type') : [['type' => '', ]]);

        $form->addTableInput('source', '客户来源', [], [['name' => 'type', 'display' => '来源']])
            ->setDefaultValue(!empty(get_option('source')) ? get_option('source') : [['type' => '', ]]);

        $form->addGroup('提成比例');

        $form->addInquiryInput('commission', '提成设置', [], [['name' => 'role', 'type' => 'select', 'label' => '用户类型', 'options' => wp_list_pluck(get_option('client_type'), 'type', 'type')], ['name' => 'commission', 'label' => '提成比例']])
             ->setDefaultValue(get_option('commission'));

        $form->addGroup('');

        $form->addSubmit('submit', '保存');

        if ($form->isSuccess()) {
            $form->save();
            Helpers::flash('success', '保存成功', '', true);
        }

        $this->form = $form;
    }


    function render_page()
    {
        ?>

        <?= Helpers::show_messages(); ?>

        <div class="wrap">
            <h2><?php _e('数据设置', '_b'); ?></h2>
        </div>

        <div class="nav-tab-wrapper wp-clearfix nav nav-tabs tm-tabs"></div>

        <div class="tm-tab-forms">
            <?php $this->form->render();?>
        </div>

        <?php
    }

}



