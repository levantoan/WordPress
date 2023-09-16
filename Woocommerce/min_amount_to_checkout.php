<?php
/*
Min Amount to checkout
Author levantoan.com
*/

add_action( 'woocommerce_checkout_process', 'devvn_wc_minimum_order_amount' );
add_action( 'woocommerce_before_cart' , 'devvn_wc_minimum_order_amount' );
function devvn_wc_minimum_order_amount() {

    $rules = array(
        'HANOI' => 2000000,
        'BACGIANG_223' => 1500000,
        'BACGIANG_223_07840' => 500000,
    );

    $checkout_minimum_price_mess = 'Bạn cần có hóa đơn >=%s để tiếp tục đặt hàng';

    $post_data = WC()->checkout()->get_posted_data();

    $ship_to_different_address = isset($post_data['ship_to_different_address']) ? boolval($post_data['ship_to_different_address']) : false;

    if($ship_to_different_address){
        $tinh = isset($post_data['shipping_state']) ? $post_data['shipping_state'] : '';
        $quan = isset($post_data['shipping_city']) ? $post_data['shipping_city'] : '';
        $xa = isset($post_data['shipping_address_2']) ? $post_data['shipping_address_2'] : '';
    }else{
        $tinh = isset($post_data['billing_state']) ? $post_data['billing_state'] : '';
        $quan = isset($post_data['billing_city']) ? $post_data['billing_city'] : '';
        $xa = isset($post_data['billing_address_2']) ? $post_data['billing_address_2'] : '';
    }

    if(isset($rules[$tinh . '_' . $quan . '_' . $xa])){
        $minimum = $rules[$tinh . '_' . $quan . '_' . $xa];
    }elseif(isset($rules[$tinh . '_' . $quan])){
        $minimum = $rules[$tinh . '_' . $quan];
    }elseif(isset($rules[$tinh])){
        $minimum = $rules[$tinh];
    }

    if ($minimum && WC()->cart->get_subtotal() < $minimum ) {
        if( is_cart() ) {
            wc_print_notice(
                sprintf( $checkout_minimum_price_mess ,
                    wc_price( $minimum ),
                    wc_price( WC()->cart->get_subtotal() )
                ), 'error'
            );
        } else {
            wc_add_notice(
                sprintf( $checkout_minimum_price_mess ,
                    wc_price( $minimum ),
                    wc_price( WC()->cart->get_subtotal() )
                ), 'error'
            );
        }
    }
}
