<?php

namespace WenpriseSpaceName\Api;

trait PostApiTrait
{

    var $version = 1;
    var $namespace = '';
    var $base = '';

    /**
     * 更新自定义字段
     *
     * @param $data_id
     * @param $data
     */
    public function update_metadata($data_id, $data)
    {
        foreach ($data as $key => $value) {
            update_post_meta($data_id, $key, $value);
        }
    }


    /**
     * 获取自定义字段数据
     *
     * @param $data_id
     *
     * @return array
     */
    public function get_metadata($data_id): array
    {

        $items = array_keys($this->get_data_structure());

        $data = get_post($data_id, ARRAY_A);

        foreach ($items as $item) {
            $data[ 'id' ]     = $data[ 'ID' ];
            $data[ 'status' ] = get_post($data_id)->post_status;
            $data[ $item ]    = get_post_meta($data_id, $item, true);
        }

        return $data;
    }


    /**
     * Check if a given request has access to get items
     *
     * @param \WP_REST_Request $request Full data about the request.
     *
     * @return \WP_Error|bool
     */
    public function get_items_permissions_check($request)
    {
        //return true; <--use to make readable by all
        return current_user_can('update_option');
    }


    /**
     * Check if a given request has access to get a specific item
     *
     * @param \WP_REST_Request $request Full data about the request.
     *
     * @return \WP_Error|bool
     */
    public function get_item_permissions_check($request)
    {
        return $this->get_items_permissions_check($request);
    }


    /**
     * Check if a given request has access to create items
     *
     * @param \WP_REST_Request $request Full data about the request.
     *
     * @return \WP_Error|bool
     */
    public function create_item_permissions_check($request)
    {
        return current_user_can('update_option');
    }


    /**
     * Check if a given request has access to update a specific item
     *
     * @param \WP_REST_Request $request Full data about the request.
     *
     * @return \WP_Error|bool
     */
    public function update_item_permissions_check($request)
    {
        return $this->create_item_permissions_check($request);
    }


    /**
     * Check if a given request has access to delete a specific item
     *
     * @param \WP_REST_Request $request Full data about the request.
     *
     * @return \WP_Error|bool
     */
    public function delete_item_permissions_check($request)
    {
        return $this->create_item_permissions_check($request);
    }


    /**
     * 验证数据，设置默认值
     *
     * @param \WP_REST_Request $request Request object
     *
     * @return \WP_Error|array $prepared_item
     */
    protected function prepare_item_for_database($request)
    {
        $params = $request->get_params();

        $data = [];
        foreach ($this->get_data_structure() as $key => $rule) {
            if (in_array($key, $params)) {
                $sanitize_callback = $rule[ 'sanitize_callback' ];
                if ($sanitize_callback) {
                    $value = $rule[ 'sanitize_callback' ]($params[ $key ]);
                } else {
                    $value = $params[ $key ];
                }

                if ( ! $value) {
                    $value = $rule[ 'default' ];
                }

                $data[ $key ] = $value;
            }
        }

        return $data;
    }


    /**
     * 准备返回多条数据
     *
     * @param $items
     * @param $request
     *
     * @return array[]
     */
    public function prepare_items_for_response($items, $total, $request): array
    {
        return [
            'payload' => [
                'totalElements' => $total,
                'content'       => $items,
            ],
        ];
    }


    /**
     * Prepare the item for the REST response
     *
     * @param mixed            $item    WordPress representation of the item.
     * @param \WP_REST_Request $request Request object.
     *
     * @return mixed
     */
    public function prepare_item_for_response($item, $request): array
    {
        return [
            'description'       => 'Current page of the collection.',
            'type'              => 'integer',
            'default'           => 1,
            'sanitize_callback' => 'absint',
        ];
    }


    /**
     * Get the query params for collections
     *
     * @return array
     */
    public function get_collection_params(): array
    {
        return [
            'page'     => [
                'description'       => 'Current page of the collection.',
                'type'              => 'integer',
                'default'           => 1,
                'sanitize_callback' => 'absint',
            ],
            'per_page' => [
                'description'       => 'Maximum number of items to be returned in result set.',
                'type'              => 'integer',
                'default'           => 10,
                'sanitize_callback' => 'absint',
            ],
            'search'   => [
                'description'       => 'Limit results to those matching a string.',
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
            ],
        ];
    }
}