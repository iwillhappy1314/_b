<?php

if ( ! defined('WP_CLI')) {
    return;
}

/**
 * Generates files for the WordPress plugin.
 */
class WP_Mvc_Generator_Command
{

    private $namespace;

    private $base_dir;

    private $model;

    private $slug_singular;

    private $slug_plural;

    private $plugin_name;


    /**
     * Generates the necessary files for the WordPress plugin.
     *
     * ## OPTIONS
     *
     * [--model=<model>]
     * : The model name to use for the generated files. Defaults to 'Keywords'.
     *
     * [--slug=<slug>]
     * : The slug to use instead of 'keyword/keywords' in the generated files. Provide in format 'singular,plural'. Defaults to 'book,books'.
     *
     * ## EXAMPLES
     *
     *     wp mvc generate
     *     wp mvc generate --model=MyModel
     *     wp mvc generate --slug=tag,tags
     *
     * @when after_wp_load
     */
    public function generate($args, $assoc_args)
    {
        $this->base_dir = isset($assoc_args[ 'dir' ]) ? $assoc_args[ 'dir' ] : getcwd();

        if ( ! is_dir($this->base_dir)) {
            WP_CLI::error("The specified directory does not exist.");

            return;
        }

        $this->plugin_name = basename($this->base_dir);

        $this->namespace = isset($assoc_args[ 'namespace' ]) ? $assoc_args[ 'namespace' ] : $this->get_psr4_namespace();
        if ( ! $this->namespace) {
            WP_CLI::error("Could not determine PSR-4 namespace from composer.json.");

            return;
        }

        $this->model = isset($assoc_args[ 'model' ]) ? $assoc_args[ 'model' ] : 'Books';

        if (isset($assoc_args[ 'slug' ])) {
            $slugs = explode(',', $assoc_args[ 'slug' ]);
            if (count($slugs) !== 2) {
                WP_CLI::error("Slug must be provided in format 'singular,plural'.");

                return;
            }
            $this->slug_singular = trim($slugs[ 0 ]);
            $this->slug_plural   = trim($slugs[ 1 ]);
        } else {
            $this->slug_singular = 'book';
            $this->slug_plural   = 'books';
        }

        $files = [
            'AdminPages/' . $this->model . 'ListPage.php'  => $this->get_list_page_content(),
            'Databases/' . $this->model . '.php'           => $this->get_database_content(),
            'ListTables/' . $this->model . 'ListTable.php' => $this->get_list_table_content(),
            'Models/' . $this->model . 'Model.php'         => $this->get_model_content(),
            'Api/' . $this->model . 'Api.php'              => $this->get_api_content(),
            'Controllers/' . $this->model . 'Controller.php' => $this->get_controller_content(),
        ];

        foreach ($files as $filepath => $content) {
            $full_path = $this->base_dir . '/src/' . $filepath;
            $dir       = dirname($full_path);

            // Check if directory exists, if not, create it
            if (!is_dir($dir)) {
                if (!mkdir($dir, 0755, true)) {
                    WP_CLI::error("Failed to create directory: $dir");
                    continue;
                }
            }

            // Check if file already exists
            if (file_exists($full_path)) {
                WP_CLI::warning("File already exists: $full_path");
                exit; // Skip to the next file
            }

            // Attempt to create the file
            if (file_put_contents($full_path, $content)) {
                WP_CLI::success("Created file: $full_path");
            } else {
                WP_CLI::error("Failed to create file: $full_path");
            }
        }

        $this->add_route();

        WP_CLI::success("All files have been generated successfully.");
    }


    private function get_psr4_namespace()
    {
        $composer_path = $this->base_dir . '/composer.json';
        if ( ! file_exists($composer_path)) {
            WP_CLI::warning("composer.json not found in the specified directory.");

            return false;
        }

        $composer_content = file_get_contents($composer_path);
        $composer_json    = json_decode($composer_content, true);

        if ( ! isset($composer_json[ 'autoload' ][ 'psr-4' ])) {
            WP_CLI::warning("PSR-4 autoload configuration not found in composer.json.");

            return false;
        }

        $psr4      = $composer_json[ 'autoload' ][ 'psr-4' ];
        $namespace = key($psr4);

        return rtrim($namespace, '\\');
    }

    private function add_route()
    {
        $routes_file = $this->base_dir . '/src/routes.php';
        $new_route = "Route::match(['get', 'post'],'{$this->slug_plural}/log/{id}', '{$this->model}Controller@log')->middleware(AuthMiddleware::class);\n";

        if (file_exists($routes_file)) {
            $content = file_get_contents($routes_file);
            $content .= "\r\n\r\n" . $new_route;
            file_put_contents($routes_file, $content);
            WP_CLI::success("Added new route to routes.php");
        } else {
            WP_CLI::warning("routes.php not found. Please add the following route manually:\n" . $new_route);
        }
    }

    private function get_controller_content()
    {
        return "<?php

namespace {$this->namespace}\Controllers;

use Nette\Forms\Form;
use Valitron\Validator;
use Wenprise\Eloquent\Facades\DB;
use Wenprise\Forms\Renders\DefaultFormRender;
use Wenprise\Mvc\Facades\Input;
use {$this->namespace}\Helpers;
use {$this->namespace}\Models\\{$this->model}Model;

class {$this->model}Controller
{
    public function index()
    {
        \$per_page = 10;
        \$paged = Helpers::input_get('paged', 1);
        \$status = Helpers::input_get('status', '');

        \$page = Input::get('page', 1);

        \${$this->slug_plural} = {$this->model}Model::select('*')
            ->where('id', '>', 0)
            ->when(\$status, function (\$query, \$status) {
                return \$query->where('status', \$status);
            })
            ->paginate(\$per_page, ['*'], 'page', \$page);

        if (\$_SERVER['REQUEST_METHOD'] == 'POST') {
            return Helpers::render_view('{$this->slug_singular}.details-part', compact(['paged', '{$this->slug_plural}', 'status']));
        } else {
            return Helpers::render_view('{$this->slug_singular}.details', compact(['paged', '{$this->slug_plural}', 'status']));
        }
    }

    public function detail(\$id)
    {
        \${$this->slug_singular} = {$this->model}Model::query()->find(\$id);

        return Helpers::render_view('{$this->slug_singular}.detail', compact(['{$this->slug_singular}']));
    }

    public function add()
    {
        if (\$_SERVER['REQUEST_METHOD'] === 'POST') {
            \$v = new Validator(\$_POST);
            \$v->rule('required', ['name']);

            if (!\$v->validate()) {
                wp_send_json_error([
                    'message' => 'Please correct your input.',
                    'errors'  => \$v->errors(),
                ]);
                exit();
            }

            \$model = {$this->model}Model::query()->create([
                'name' => Helpers::input_get('name'),
                'status' => 'draft',
            ]);

            if (\$model) {
                wp_send_json_success([
                    'message' => 'You have successfully added a new {$this->slug_singular}!',
                    'url' => '/{$this->slug_plural}/',
                ]);
            }
        } else {
            return Helpers::render_view('{$this->slug_plural}.add');
        }
    }

    public function log(\$id)
    {
        \$form = new Form();
        \$form->setMethod('POST');
        \$form->setAction('/{$this->slug_plural}/edit/' . \$id);
        \$form->setRenderer(new DefaultFormRender('vertical'));

        \$form->addGroup();

        \$form->addHidden('id', \$id);
        \$form->addText('name', 'Name')->setDefaultValue(\${$this->slug_singular}->name);
        \$form->addSelect('status', 'Status', ['draft' => 'Draft', 'published' => 'Published'])->setDefaultValue(\${$this->slug_singular}->status);

        \$form->addSubmit('submit', 'Update');

        if (\$_SERVER['REQUEST_METHOD'] === 'POST') {
            \${$this->slug_singular} = {$this->model}Model::query()->find(\$id);
            \${$this->slug_singular}->name = Input::get('name');
            \${$this->slug_singular}->status = Input::get('status');
            \${$this->slug_singular}->save();

            Helpers::flash('success', '{$this->model} updated successfully.', admin_url('admin.php?page={$this->slug_singular}'));
        }

        return Helpers::render_view('{$this->slug_plural}.log', compact('form', '{$this->slug_singular}'));
    }

    public function delete(\$id)
    {
        {$this->model}Model::query()->where('id', \$id)->delete();

        Helpers::flash('success', '{$this->model} deleted successfully.', admin_url('admin.php?page={$this->slug_singular}'));
    }
}
";
    }



    private function get_list_table_content()
    {
        return "<?php
namespace {$this->namespace}\ListTables;

use Wenprise\Forms\Form;
use Wenprise\Forms\Renders\DefaultFormRender;
use {$this->namespace}\Helpers;
use {$this->namespace}\Models\\{$this->model}Model;

class {$this->model}ListTable extends \WP_List_Table {

    /**
     * 列表數據
     *
     * @var array
     */
    public \$model = null;

    /**
     * Serial_List_Table constructor.
     */
    public function __construct() {
        global \$status, \$page;

        //Set parent defaults
        parent::__construct([
            'singular' => '$this->slug_singular',
            'plural'   => '$this->slug_plural',
            'ajax'     => true,
        ]);

        \$model = {$this->model}Model::query();

        // 搜索
        if ( Helpers::input_get( 's' ) ) {
            \$model->where( 'name', 'LIKE', Helpers::input_get( 's' ) );
        }

        \$this->model = \$model;
    }

    protected function get_views(): array {
        \$model = {$this->model}Model::query();
        \$all_count = {$this->model}Model::all()->count();

        \$normal_count = \$model->where('status', '=', 'normal')
                               ->get()
                               ->count();

        return [
            'all'      => __('<a href=\"' . remove_query_arg('condition') . '\">全部</a> (' . \$all_count . ')', '$this->plugin_name'),
            'normal'   => __('<a href=\"' . add_query_arg('condition', 'normal') . '\">正常</a> (' . \$normal_count . ')', '$this->plugin_name'),
            'abnormal' => __('<a href=\"' . add_query_arg('condition', 'abnormal') . '\">异常</a> (' . (\$all_count - \$normal_count) . ')', '$this->plugin_name'),
        ];
    }

    /**
     * 添加筛选项目
     *
     * @param \$which
     *
     * @return void
     */
    function extra_tablenav(\$which) {
        if (\$which == 'top') {
            // 翻译类名
            \$form = new Form();
            \$form->setRenderer(new DefaultFormRender('vertical'));

            \$form->addChosen('status', '状态', array_merge(['所有状态'], Helpers::get_config('general.status')));

            \$form->addSubmit('submit', '筛选');

            echo '<div class=\"rs-filter-form\">';
            \$form->render('body');
            echo '</div>';
        }
    }

    /**
     * 设置数据列数据
     *
     * @param object \$item
     * @param string \$column_name
     *
     * @return mixed
     */
    public function column_default( \$item, \$column_name ) {
        switch ( \$column_name ) {
            case 'user_id':
                \$user_id = \$item[ \$column_name ];

                return '<a target=\"_blank\" href=\"' . admin_url('user-edit.php?user_id=' . \$user_id . '&wp_http_referer=%2Fwp-admin%2Fusers.php') . '\">' . get_userdata(\$user_id)->display_name . \$this->column_title(\$item);

            case 'name':
                return \$item[ \$column_name ] . \$this->column_title( \$item );

            case 'action':
                return '<button class=\"button primary\" hx-get=\"/tasks/log/'. \$item['id'] .'\" hx-target=\"body\" hx-swap=\"beforeend\">添加记录</button>';

            default:
                return \$item[ \$column_name ];
        }
    }

    /**
     * 设置标题列
     *
     * @param \$item
     *
     * @return string
     */
    public function column_title( \$item ) {
        // Build row actions
        \$actions = [
            'edit'   => sprintf('<a href=\"?page=%s&action=%s&%s=%s\">' . __('编辑', 'wprs') . '</a>', 'add-service-order', 'edit', \$this->_args['singular'], \$item['id']),
            'delete' => sprintf('<a onclick=\"return confirm(\'确定要删除吗？\')\" href=\"?page=%s&action=%s&%s=%s\">' . __('删除', 'wprs') . '</a>', \$_REQUEST['page'], 'delete', \$this->_args['singular'], \$item['id']),
        ];

        // Return the title contents
        return sprintf('%1\$s %2\$s', '', \$this->row_actions( \$actions ));
    }

    /**
     * 批量操作多选框
     *
     * @param object \$item
     *
     * @return string|mixed
     */
    public function column_cb( \$item ) {
        return sprintf(
            '<input type=\"checkbox\" name=\"%1\$s[]\" value=\"%2\$s\" />',
            \$this->_args['singular'],
            \$item['id']
        );
    }

    /**
     * 获取数据列
     *
     * @return array
     */
    public function get_columns() {
        return [
            'cb'           => '<input type=\"checkbox\" />',
            'user_id'      => __('User ID', 'wprs'),
            'name'         => __('名称', 'wprs'),
            'status'       => __('状态', 'wprs'),
        ];
    }

    /**
     * 获取可排序数据列
     *
     * @return array
     */
    function get_sortable_columns() {
        return [
            'name' => ['name', false],
            'status'  => ['status', false],
        ];
    }

    /**
     * 获取批量操作
     *
     * @return array
     */
    function get_bulk_actions() {
        return [
            'delete' => __('Delete', '$this->plugin_name'),
        ];
    }

    /**
     * 执行批量操作
     */
    public function process_bulk_action() {
        \$send_back = remove_query_arg(['trashed', 'untrashed', 'deleted', 'locked', 'ids'], wp_get_referer());
        \$ids = (array) Helpers::input_get(\$this->_args['singular']);

        if ('delete' === \$this->current_action()) {
            foreach (\$ids as \$id) {
                \$trashed = {$this->model}Model::destroy(\$id);
            }

            Helpers::flash('success', '成功删除' . count(\$ids) . '条数据', add_query_arg(['trashed' => count(\$ids), 'ids' => join('_', \$ids), 'locked' => 1], \$send_back));
        }
    }

    /**
     * 准备数据
     */
    public function prepare_items() {

        // 每页显示数量
        \$per_page =  \$this->get_items_per_page(\$this->_args[ 'singular' ] . '_per_page', 20);

        // 当前页数
        \$current_page = \$this->get_pagenum();

        // 总数
        \$total_items = \$this->model->count();

        // 分页后的数据
        \$this->items  = \$this->model
            ->limit(\$per_page)
            ->offset((\$current_page - 1) * \$per_page)
            ->get()
            ->toArray();

        // 必须设置
        \$columns  = \$this->get_columns();
        \$hidden   = get_hidden_columns(\$this->screen);
        \$sortable = \$this->get_sortable_columns();

        // 列标题
        \$this->_column_headers = [\$columns, \$hidden, \$sortable];

        // 批量操作
        \$this->process_bulk_action();

        // 设置分页
        \$this->set_pagination_args([
            'total_items' => \$total_items,
            'per_page'    => \$per_page,
            'total_pages' => ceil(\$total_items / \$per_page),
        ]);
    }
}
";
    }


    private function get_database_content()
    {
        return "<?php
namespace {$this->namespace}\Databases;

use WPDBase\Database;

class {$this->model} extends Database {

    /**
     * 定义主题路径命名空间
     */
    public function setTables() {

        return [

            \"CREATE TABLE `{\$this->wpdb->prefix}{$this->slug_plural}` (
                `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `user_id` bigint(20) unsigned DEFAULT NULL,
                `name` varchar(255) DEFAULT NULL,
                `amount` decimal(26,8) DEFAULT NULL,
                `status` varchar(20) DEFAULT NULL,
                `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `deleted_at` timestamp DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY (`user_id`)
            ) {\$this->collate};\",

        ];
    }
}
";
    }


    private function get_list_page_content()
    {
        return "<?php
namespace {$this->namespace}\AdminPages;

use {$this->namespace}\Helpers;
use {$this->namespace}\ListTables\\{$this->model}ListTable;
use {$this->namespace}\Models\\{$this->model}Model;
use Nette\Forms\Form;
use Wenprise\Forms\Renders\DefaultFormRender;

class {$this->model}ListPage {
    public Form \$form;

    public {$this->model}ListTable \$table;

    public function __construct() {
        add_action( 'admin_menu', [ \$this, 'add_page' ] );

        if ( Helpers::input_get( 'page' ) === '$this->slug_singular' ) {
            add_action( 'admin_init', [ \$this, 'set_form' ] );
        }

        add_filter( 'set-screen-option', [ \$this, 'set_table_options' ], 10, 3 );
    }

    public function add_page() {
        \$hook = add_menu_page(
            __( ucfirst('$this->slug_singular'), '$this->plugin_name' ),
            __( ucfirst('$this->slug_plural'), '$this->plugin_name' ),
            'edit_posts',
            '$this->slug_singular',
            [ \$this, 'render_page' ],
            'dashicons-admin-network'
        );

        // screen option
        add_action( \"load-\$hook\", [ \$this, 'set_display_options' ] );
    }

    /**
     * 添加显示选项分页设置
     *
     * @return void
     */
    function set_display_options() {
        \$option = 'per_page';

        \$args = [
            'label'   => '每页显示的项目数',
            'default' => get_user_meta( get_current_user_id(), \$this->slug_singular . '_per_page', true ) ? get_user_meta( get_current_user_id(), \$this->slug_singular . '_per_page', true ) : 10,
            'option'  => 'wprs_per_page',
        ];

        add_screen_option( \$option, \$args );

        // 必须在这里实例化 table 才能有显示选项里面的 columns
        \$this->table = new {$this->model}ListTable();
        \$this->table->prepare_items();
    }

    /**
     * 保存 _per_page 的值
     *
     * @param \$status
     * @param \$option
     * @param \$value
     *
     * @return mixed
     */
    function set_table_options( \$status, \$option, \$value ) {
        return \$value;
    }

    public function set_form(): void {
        \$form = new Form();
        \$form->setRenderer( new DefaultFormRender( 'vertical' ) );

        \$form->addGroup();

        \$form->addText( 'name', '名称' );

        \$form->addSubmit( 'submit', '添加' );

        if ( \$form->isSuccess() ) {
            \$values = \$form->getValues();

            if ( Helpers::input_get( 'name' ) ) {
                \$model = {$this->model}Model::query()->firstOrCreate( (array) \$values );
                \$model->save();

                Helpers::flash( 'success', '添加成功', '', true );
            }
        }

        \$this->form = \$form;
    }

    function render_page() {
        ?>

        <div class=\"wrap\">
            <h1 class=\"wp-heading-inline\"><?= __( '全部图书', 'wprs' ) ?></h1>

            <?php Helpers::render_modal( 'createTicket', '新增图书', \$this->form ); ?>

            <a href=\"<?= admin_url( 'admin.php?page=book-import' ); ?>\" class=\"page-title-action\">导入数据</a>
            <a href=\"<?= add_query_arg( 'action', 'wprs-book-export' ) ?>\" class=\"page-title-action\">导出数据</a>

            <?= Helpers::show_messages(); ?>

            <div>
                <?php \$this->table->views(); ?>
            </div>

            <form id=\"wprs-data-filter\" method=\"get\">
                <?php \$this->table->search_box( '搜索', 'search' ); ?>

                <input type=\"hidden\" name=\"page\" value=\"<?php echo \$_REQUEST[ 'page' ] ?>\" />
                <?php \$this->table->display() ?>
            </form>

        </div>
        <?php
    }

}
";
    }


    private function get_model_content()
    {
        return "<?php
namespace {$this->namespace}\Models;

use Wenprise\Eloquent\Model;

class {$this->model}Model extends Model {
    /**
     * @var string
     */
    protected \$table = '$this->slug_plural';

    /**
     * @var string
     */
    protected \$primaryKey = 'id';

    /**
     * @var bool
     */
    public \$timestamps = false;

    /**
     * @var array
     */
    protected \$guarded = [ 'id' ];

}
";
    }


    private function get_api_content()
    {
        return "<?php
namespace {$this->namespace}\Api;

use {$this->namespace}\Helpers;

class {$this->model}Api extends \WP_REST_Controller
{
    var \$version = 1;
    var \$namespace = '';
    var \$base = '';

    use PostApiTrait;

    public function __construct()
    {
        \$this->version   = '1';
        \$this->namespace = '$this->plugin_name' . '/v' . \$this->version;
        \$this->base      = '$this->slug_plural';
    }

    /**
     * Register the routes for the objects of the controller.
     */
    public function register_routes()
    {

        register_rest_route(\$this->namespace, '/' . \$this->base, [
            [
                'methods'             => \WP_REST_Server::READABLE,
                'callback'            => [\$this, 'index'],
                'permission_callback' => '__return_true',
                'args'                => [

                ],
            ],
            [
                'methods'             => \WP_REST_Server::CREATABLE,
                'callback'            => [\$this, 'create'],
                'permission_callback' => '__return_true',
                'args'                => \$this->get_endpoint_args_for_item_schema(true),
            ],
        ]);

        register_rest_route(\$this->namespace, '/' . \$this->base . '/(?P<id>[\\s\\S]+)', [
            [
                'methods'             => \WP_REST_Server::READABLE,
                'callback'            => [\$this, 'get'],
                'permission_callback' => '__return_true',
                'args'                => [
                    'context' => [
                        'default' => 'view',
                    ],
                ],
            ],
            [
                'methods'             => \WP_REST_Server::EDITABLE,
                'callback'            => [\$this, 'update'],
                'permission_callback' => '__return_true',
                'args'                => \$this->get_endpoint_args_for_item_schema(false),
            ],
            [
                'methods'             => \WP_REST_Server::DELETABLE,
                'callback'            => [\$this, 'delete'],
                'permission_callback' => '__return_true',
                'args'                => [
                    'force' => [
                        'default' => false,
                    ],
                ],
            ],
        ]);

        register_rest_route(\$this->namespace, '/' . \$this->base . '/schema', [
            'methods'             => \WP_REST_Server::READABLE,
            'permission_callback' => '__return_true',
            'callback'            => [\$this, 'get_public_item_schema'],
        ]);
    }

    /**
     * 获取需要保存的数据
     *
     * @return array
     */
    public function get_data_structure(): array
    {
        return [
            'name'          => [
                'type'    => 'string',
                'default' => '',
            ],
            'user_id'           => [
                'type'    => 'int',
                'default' => 0,
            ],
            'isDefault'     => [
                'type'    => 'boolean',
                'default' => false,
            ],
        ];
    }

    /**
     * 获取数据列表
     *
     * @param \WP_REST_Request \$request Full data about the request.
     *
     * @return \WP_Error|\WP_REST_Response
     */
    public function index(\$request)
    {
        \$params = \$request->get_params();

        \$meta_query = ['relation' => 'AND'];

        foreach (\$params as \$param => \$value) {
            if (in_array(\$param, array_keys(\$this->get_data_structure()))) {
                \$meta_query[] = [
                    'key'     => \$param,
                    'value'   => \$value,
                    'compare' => 'LIKE',
                ];
            }
        }

        \$args = [
            'post_type'      => 'address',
            'posts_per_page' => Helpers::data_get(\$params, 'size', 20),
            'paged'          => Helpers::data_get(\$params, 'page', 1),
        ];

        if ( ! empty(\$meta_query)) {
            \$args[ 'meta_query' ] = \$meta_query;
        }

        \$wp_query = new \WP_Query(\$args);
        \$total = \$wp_query->found_posts;

        \$data = [];
        foreach (\$wp_query->get_posts() as \$post) {
            \$data[] = \$this->get_metadata(\$post->ID);
        }

        return new \WP_REST_Response(\$this->prepare_items_for_response(\$data, \$total, \$request), 200);
    }

    /**
     * 获取一条数据
     *
     * @param \WP_REST_Request \$request Full data about the request.
     *
     * @return \WP_Error|\WP_REST_Response
     */
    public function get(\$request)
    {
        \$params  = \$request->get_params();
        \$data_id = \$params[ 'id' ];

        return new \WP_REST_Response(\$this->get_metadata(\$data_id), 200);
    }

    /**
     * 添加一条数据
     *
     * @param \WP_REST_Request \$request Full data about the request.
     *
     * @return \WP_Error|\WP_REST_Response
     */
    public function create(\$request)
    {
        \$post = \$request->get_body_params();

        \$post_data = [
            'post_type'   => 'address',
            'post_status' => 'publish',
            'post_author' => get_current_user_id(),
        ];

        if (isset(\$post[ 'id' ])) {
            \$post_data[ 'ID' ] = \$post[ 'id' ];
        }

        \$data_id = wp_insert_post(\$post_data);

        \$this->update_metadata(\$data_id, \$post);

        return new \WP_REST_Response(\$data_id, 200);
    }

    /**
     * 更新数据
     *
     * @param \WP_REST_Request \$request Full data about the request.
     *
     * @return \WP_Error|\WP_REST_Response
     */
    public function update(\$request)
    {
        \$params  = \$request->get_params();
        \$data_id = \$params[ 'id' ];

        \$post = \$request->get_body_params();

        if ( ! \$data_id) {
            \$data_id = \$post[ 'id' ];
        }

        \$this->update_metadata(\$data_id, \$post);

        return new \WP_REST_Response(\$data_id, 200);
    }

    /**
     * 删除数据
     *
     * @param \WP_REST_Request \$request Full data about the request.
     *
     * @return \WP_Error|\WP_REST_Response
     */
    public function delete(\$request)
    {
        \$params   = \$request->get_params();
        \$data_ids = explode(',', \$params[ 'id' ]);

        foreach (\$data_ids as \$data_id) {
            wp_delete_post(\$data_id);
        }

        return new \WP_REST_Response(\$data_ids, 200);
    }
}
";
    }
}


/**
 * Migrates the custom databases by scanning a fixed directory.
 */
class Database_Migrate_Command {

    /**
     * The directory to scan for database classes.
     *
     * @var string
     */
    private $dir = 'src/Databases';

    /**
     * Migrates the custom databases.
     *
     * ## EXAMPLE
     *
     *     wp database migrate
     *
     * @when after_wp_load
     */
    public function __invoke( $args, $assoc_args ) {
        $path = plugin_dir_path(__FILE__) . $this->dir;

        if (!is_dir($path)) {
            WP_CLI::error("Directory not found: $path");
            return;
        }

        $databases = $this->scan_directory($path);

        foreach ($databases as $database) {
            new $database();
            WP_CLI::success("Migrated database: " . $database);
        }

        WP_CLI::success("All databases migrated successfully.");
    }

    /**
     * Scans the given directory for PHP files and returns class names.
     *
     * @param string $dir The directory to scan.
     * @return array An array of fully qualified class names.
     */
    private function scan_directory($dir) {
        $databases = [];
        $files = glob($dir . '/*.php');

        foreach ($files as $file) {
            $content = file_get_contents($file);
            if (preg_match('/class\s+(\w+)/i', $content, $matches)) {
                $class_name = $matches[1];
                // Assuming PSR-4 autoloading
                $namespace = $this->get_namespace($content);
                $fully_qualified_class_name = $namespace ? "$namespace\\$class_name" : $class_name;
                $databases[] = $fully_qualified_class_name;
            }
        }

        return $databases;
    }

    /**
     * Extracts the namespace from the file content.
     *
     * @param string $content The content of the PHP file.
     * @return string|null The namespace if found, null otherwise.
     */
    private function get_namespace($content) {
        if (preg_match('/namespace\s+(.*?);/i', $content, $matches)) {
            return $matches[1];
        }
        return null;
    }
}

WP_CLI::add_command('mvc', 'WP_Mvc_Generator_Command');
WP_CLI::add_command('mvc migrate', 'Database_Migrate_Command' );
