<?php
/*
Min Amount to checkout
Author levantoan.com
*/
add_action( 'woocommerce_checkout_process', 'devvn_wc_minimum_order_amount' );
add_action( 'woocommerce_before_cart' , 'devvn_wc_minimum_order_amount' );
function devvn_wc_minimum_order_amount() {    
    $minimum = '100000';
    $checkout_minimum_price_mess = 'Bạn cần có hóa đơn >100k để tiếp tục đặt hàng';
    if ( WC()->cart->total < $minimum ) {
        if( is_cart() ) {
            wc_print_notice(
                sprintf( $checkout_minimum_price_mess ,
                    wc_price( $minimum ),
                    wc_price( WC()->cart->total )
                ), 'error'
            );
        } else {
            wc_add_notice(
                sprintf( $checkout_minimum_price_mess ,
                    wc_price( $minimum ),
                    wc_price( WC()->cart->total )
                ), 'error'
            );
        }
    }
}
