<?php
/*
* Add Reply-to to customer email when using other email SMTP
* Author: https://levantoan.com
*/
add_filter( 'woocommerce_email_headers', 'add_reply_to_wc_customer_order', 10, 3 );
function add_reply_to_wc_customer_order( $headers = '', $id = '', $order ) {
    $send_list = array(
        'customer_note',
        'customer_completed_order',
        'customer_invoice',
        'customer_processing_order',
        'customer_on_hold_order',
        'customer_reset_password',
        'customer_refunded_order',
        'customer_new_account',
    );
    if ( in_array($id, $send_list)) {
        $headers .= "Reply-to: Lê Văn Toản <hotro.levantoan@gmail.com>\r\n";
    }
    return $headers;
}
