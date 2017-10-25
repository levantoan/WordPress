<?php
add_action('woocommerce_checkout_process', 'devvn_validate_phone_field_process');
function devvn_validate_phone_field_process() {
    $billing_phone = filter_input(INPUT_POST, 'billing_phone');
    /*if ( ! (preg_match('/^(09|08)([0-9]{8})+$|^01([0-9]{9})+$/D', $billing_phone )) ){*/
    if ( ! (preg_match('/^0([0-9]{9,10})+$/D', $billing_phone )) ){
        wc_add_notice( "Xin nhập đúng <strong>số điện thoại</strong> của bạn"  ,'error' );
    }
}
