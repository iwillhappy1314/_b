<?php

namespace WenpriseSpaceName;

use WenpriseSpaceName\AdminPages\CrmAddPage;
use WenpriseSpaceName\AdminPages\CrmPage;
use WenpriseSpaceName\AdminPages\AddPage;
use WenpriseSpaceName\AdminPages\AdminIndexPage;
use WenpriseSpaceName\Providers\RoutingService;
use Wenprise\Dispatcher\Router;
use Wenprise\Mvc\App;
use WenpriseSpaceName\Metaboxes\PostMetabox;
use WenpriseSpaceName\Api\AddressApiController;
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;


class Init
{
   /**
    * 存储单例实例
    */
    private static ?Init $instance = null;

    
    /**
    * 私有克隆方法，防止克隆对象
    */
    private function __clone() {
    }

    /**
    * 私有反序列化方法，防止反序列化创建对象
    */
    private function __wakeup() {
    }

    /**
    * 获取单例实例的静态方法
    */
    public static function get_instance(): ?Init
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }


     /**
    * 私有构造函数，防止直接实例化
    */
    private function __construct() {
        add_action('init', array($this, 'init'));
    }
	

    /**
     * constructor.
     */
    public function init()
    {
        $classes = [
            AddPage::class,
            AdminIndexPage::class,
            Frontend::class,
            PostMetabox::class,
            CrmPage::class,
            CrmAddPage::class
        ];

        foreach ($classes as $class) {
            new $class;
        }

        add_action('rest_api_init', [new AddressApiController, 'register_routes']);

        $this->initMvc();
        $this->initForm();
        $this->setRouter();
        $this->setUpdateChecker();
    }


    public function initForm() {
		new \Wenprise\Forms\Init();
	}


    public function initMvc(){
        $GLOBALS[ '_b-app' ] = App::instance();

        /*
         * 获取服务容器
         */
        $container = $GLOBALS[ '_b-app' ]->container;

        /*
         * 注册主题视图路径
         */
        $container[ 'view.finder' ]->addLocation(SPACENAME_PATH . 'templates');


        /*
         * 加载配置文件
         */
        $container[ 'config.finder' ]->addPaths([
            SPACENAME_PATH . 'config/',
        ]);

        /**
         * 主题服务提供者
         */
        $providers = [
            RoutingService::class,
        ];

        foreach ($providers as $provider) {
            $container->register($provider);
        }
    }


    public function setRouter()
    {
        $routers = [
            '_b' => ['\WenpriseSpaceName\Controllers\SerialsController', 'index'],
        ];

        Router::routes(apply_filters('_b_routers', $routers));
    }


    public function setUpdateChecker()
    {
        $update_checker = PucFactory::buildUpdateChecker(
            'https://api.wpcio.com/api/plugin/info/_b',
            SPACENAME_MAIN_FILE,
            '_b'
        );
    }

}
