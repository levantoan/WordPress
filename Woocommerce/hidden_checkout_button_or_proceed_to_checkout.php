<?php
/*V2*/
add_action( 'woocommerce_checkout_process', 'wc_minimum_order_amount' );
add_action( 'woocommerce_before_cart' , 'wc_minimum_order_amount' );
function wc_minimum_order_amount() {
    $enable_checkout_minimum = get_field('enable_checkout_minimum','option');
    $minimum = get_field('checkout_minimum_price','option');
    $checkout_minimum_price_mess = get_field('checkout_minimum_price_mess','option');
    if ($minimum && $enable_checkout_minimum &&  WC()->cart->total < $minimum ) {
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



/*
V1 Hidden checkout button when amount under 3000000
*/
function disable_checkout_button_no_shipping() {
    $total = WC()->cart->get_total(false);
    if($total < 3000000)
        remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );
}
add_action( 'woocommerce_proceed_to_checkout', 'disable_checkout_button_no_shipping', 1 );

add_filter('woocommerce_order_button_html', 'devvn_woocommerce_order_button_html');
function devvn_woocommerce_order_button_html($button){
    $total = WC()->cart->get_total(false);
    if($total < 3000000)
        return false;
    else
        return $button;
}
