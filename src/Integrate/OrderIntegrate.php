<?php

namespace WenpriseSpaceName\Integrate;

class OrderIntegrate
{
    function __construct()
    {
        add_action('woocommerce_checkout_update_order_meta', [$this, 'add_woocommerce_order']);
        add_action('woocommerce_checkout_create_order_line_item', [$this, 'add_woocommerce_order_item'], 10, 4);
    }


    /**
     * 创建 WooCommerce 订单
     *
     * @param $order_id
     */
    public function add_woocommerce_order($order_id)
    {

        $cart_contents = wc()->cart->cart_contents;

        $create = false;
        $args   = [];
        foreach ($cart_contents as $cart_key => $cart_content) {
            if (get_post_type($cart_content[ 'product_id' ]) === 'post') {
                $create = true;
                $args   = [
                    'post_id' => $cart_content[ 'product_id' ],
                ];
                break;
            }
        }

        if ($create === true) {
            $order_model = new OrderModel();

            $order_model->insert([
                'user_id'      => get_current_user_id(),
                'post_id'      => $args[ 'post_id' ],
                'woo_order_id' => $order_id,
                'status'       => 'new',
            ]);
        }

    }


    function add_woocommerce_order_item($item, $cart_item_key, $values, $order)
    {
        $item->add_meta_data( '_post_id', absint( $values['product_id'] ) );
    }
}