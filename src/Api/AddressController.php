<?php

namespace WenpriseSpaceName\Api;

class AddressController extends \WP_REST_Controller
{
    var $version = 1;
    var $namespace = '';
    var $base = '';

    use PostApiTrait;

    public function __construct()
    {
        $this->version   = '1';
        $this->namespace = '_b/v' . $this->version;
        $this->base      = 'addresses';
    }

    /**
     * Register the routes for the objects of the controller.
     */
    public function register_routes()
    {

        register_rest_route($this->namespace, '/' . $this->base, [
            [
                'methods'             => \WP_REST_Server::READABLE,
                'callback'            => [$this, 'index'],
                'permission_callback' => '__return_true',
                'args'                => [

                ],
            ],
            [
                'methods'             => \WP_REST_Server::CREATABLE,
                'callback'            => [$this, 'create'],
                'permission_callback' => '__return_true',
                'args'                => $this->get_endpoint_args_for_item_schema(true),
            ],
        ]);

        register_rest_route($this->namespace, '/' . $this->base . '/(?P<id>[\s\S]+)', [
            [
                'methods'             => \WP_REST_Server::READABLE,
                'callback'            => [$this, 'get'],
                'permission_callback' => '__return_true',
                'args'                => [
                    'context' => [
                        'default' => 'view',
                    ],
                ],
            ],
            [
                'methods'             => \WP_REST_Server::EDITABLE,
                'callback'            => [$this, 'update'],
                'permission_callback' => '__return_true',
                'args'                => $this->get_endpoint_args_for_item_schema(false),
            ],
            [
                'methods'             => \WP_REST_Server::DELETABLE,
                'callback'            => [$this, 'delete'],
                'permission_callback' => '__return_true',
                'args'                => [
                    'force' => [
                        'default' => false,
                    ],
                ],
            ],
        ]);

        register_rest_route($this->namespace, '/' . $this->base . '/schema', [
            'methods'             => \WP_REST_Server::READABLE,
            'permission_callback' => '__return_true',
            'callback'            => [$this, 'get_public_item_schema'],
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
            'tel'           => [
                'type'    => 'string',
                'default' => '',
            ],
            'country'       => [
                'type'    => 'string',
                'default' => '',
            ],
            'province'      => [
                'type'    => 'string',
                'default' => '',
            ],
            'city'          => [
                'type'    => 'string',
                'default' => '',
            ],
            'area'        => [
                'type'    => 'string',
                'default' => '',
            ],
            'postalCode'    => [
                'type'    => 'string',
                'default' => '',
            ],
            'addressDetail' => [
                'type'    => 'string',
                'default' => '',
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
     * @param \WP_REST_Request $request Full data about the request.
     *
     * @return \WP_Error|\WP_REST_Response
     */
    public function index($request)
    {
        $params = $request->get_params();

        $meta_query = ['relation' => 'AND'];

        foreach ($params as $param => $value) {
            if (in_array($param, array_keys($this->get_data_structure()))) {
                $meta_query[] = [
                    'key'     => $param,
                    'value'   => $value,
                    'compare' => 'LIKE',
                ];
            }
        }

        $args = [
            'post_type'      => 'address',
            'posts_per_page' => Helpers::data_get($params, 'size', 20),
            'paged'          => Helpers::data_get($params, 'page', 1),
        ];

        if ( ! empty($meta_query)) {
            $args[ 'meta_query' ] = $meta_query;
        }

        $wp_query = new \WP_Query($args);
        $total = $wp_query->found_posts;

        $data = [];
        foreach ($wp_query->get_posts() as $post) {
            $data[] = $this->get_metadata($post->ID);
        }

        return new \WP_REST_Response($this->prepare_items_for_response($data, $total, $request), 200);
    }

    /**
     * 获取一条数据
     *
     * @param \WP_REST_Request $request Full data about the request.
     *
     * @return \WP_Error|\WP_REST_Response
     */
    public function get($request)
    {
        $params  = $request->get_params();
        $data_id = $params[ 'id' ];

        return new \WP_REST_Response($this->get_metadata($data_id), 200);
    }

    /**
     * 添加一条数据
     *
     * @param \WP_REST_Request $request Full data about the request.
     *
     * @return \WP_Error|\WP_REST_Response
     */
    public function create($request)
    {
        $post = $request->get_body_params();

        $post_data = [
            'post_type'   => 'address',
            'post_status' => 'publish',
            'post_author' => get_current_user_id(),
        ];

        if (isset($post[ 'id' ])) {
            $post_data[ 'ID' ] = $post[ 'id' ];
        }

        $data_id = wp_insert_post($post_data);

        $this->update_metadata($data_id, $post);

        return new \WP_REST_Response($data_id, 200);
    }

    /**
     * 更新数据
     *
     * @param \WP_REST_Request $request Full data about the request.
     *
     * @return \WP_Error|\WP_REST_Response
     */
    public function update($request)
    {
        $params  = $request->get_params();
        $data_id = $params[ 'id' ];

        $post = $request->get_body_params();

        if ( ! $data_id) {
            $data_id = $post[ 'id' ];
        }

        $this->update_metadata($data_id, $post);

        return new \WP_REST_Response($data_id, 200);
    }

    /**
     * 删除数据
     *
     * @param \WP_REST_Request $request Full data about the request.
     *
     * @return \WP_Error|\WP_REST_Response
     */
    public function delete($request)
    {
        $params   = $request->get_params();
        $data_ids = explode(',', $params[ 'id' ]);

        foreach ($data_ids as $data_id) {
            wp_delete_post($data_id);
        }

        return new \WP_REST_Response($data_ids, 200);
    }
}