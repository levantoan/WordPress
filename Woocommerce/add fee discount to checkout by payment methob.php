<?php
/*
Add fee discount to checkout by payment methob
Author: levantoan.com
*/
add_action( 'woocommerce_cart_calculate_fees', 'devvn_add_checkout_discount_fee_for_gateway' );
function devvn_add_checkout_discount_fee_for_gateway() {
    $chosen_gateway = WC()->session->get( 'chosen_payment_method' );
    if ( $chosen_gateway != 'cod' ) {
        WC()->cart->add_fee( 'Chiết khấu', -5000 );
    }
}

add_action( 'woocommerce_after_checkout_form', 'devvn_refresh_checkout_on_payment_methods_change' );
function devvn_refresh_checkout_on_payment_methods_change(){
    wc_enqueue_js( "
       $( 'form.checkout' ).on( 'change', 'input[name^=\'payment_method\']', function() {
           $('body').trigger('update_checkout');
        });
   ");
}
