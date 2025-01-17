<?php
/**
 * 导入数据
 *
 * @package WenPrise
 */

namespace WenpriseSpaceName\AdminPages;

use WenpriseSpaceName\Helpers;
use WenpriseSpaceName\ListTables\OrderListTable;
use WenpriseSpaceName\Models\OrderModel;
use Wenprise\Forms\Form;
use Wenprise\Forms\Renders\DefaultFormRender;

class OrderListPage
{
    public Form $form;

    use IndexPageTrait;

    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_page']);

        if (Helpers::input_get('page') === 'orders') {
            add_action('admin_init', [$this, 'set_form']);
        }

        $this->table_class = OrderListTable::class;

        add_filter('set-screen-option', [$this, 'set_table_options'], 10, 3);
    }


    public function add_page()
    {
        $hook = add_menu_page(
            __('Orders', '_b'),
            __('Orders', '_b'),
            'edit_posts',
            'trademarks',
            [$this, 'render_page'],
            'dashicons-clipboard'
        );

        // screen option
        add_action("load-$hook", [$this, 'set_display_options']);
    }


    public function set_form(): void
    {
        $nations = ['' => '请选择国家/地区'] + Helpers::get_nation_names();

        $form = new Form();
        $form->setRenderer(new DefaultFormRender('vertical'));

        $form->addGroup();

        $form->addHidden('user_id', get_current_user_id());
        $form->addChosen('nation', '国家/地区', $nations);
        $form->addText('user_email', '邮箱');
        $form->addText('application_no', '申请号');

        $form->addSubmit('submit', '添加监测');

        if ($form->isSuccess()) {
            $values = $form->getValues();

            if (Helpers::input_get('application_no')) {
                $trademark          = OrderModel::query()->create((array)$values);
                $trademark->save();

                // 添加抓取任务
                try {
                    wp_queue()->push(new MonitorJob($trademark->id), MINUTE_IN_SECONDS);

                    do_action('tm_monitor_job_added', $trademark);
                } catch (\Exception $e) {
                    error_log($e->getMessage());
                }

                Helpers::flash('success', '添加成功', '', true);
            }
        }

        $this->form = $form;
    }

    function render_page()
    {
        ?>

        <div class="wrap">
            <h1 class="wp-heading-inline"><?= __('全部监控', 'wenprise-serial-manager') ?></h1>

            <?php Helpers::render_modal('createTicket', '新增监控', $this->form); ?>

            <a href="<?= admin_url('admin.php?page=trademark-import'); ?>" class="page-title-action">导入数据</a>
            <a href="<?= add_query_arg('action', 'tm-monitor-export') ?>" class="page-title-action">导出数据</a>

            <?= Helpers::show_messages(); ?>

            <div>
                <?php $this->table->views(); ?>
            </div>

            <form id="movies-filter" method="get">
                <?php $this->table->search_box('搜索', 'search'); ?>

                <input type="hidden" name="page" value="<?php echo $_REQUEST[ 'page' ] ?>" />
                <?php $this->table->display() ?>
            </form>

        </div>
        <?php
    }

}
