<?php
/*
Hidden checkout button when amount under 3000000
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
