<?php
add_action( 'woocommerce_checkout_order_processed', 'devvn_pending_new_order_notification', 20, 3 );
function devvn_pending_new_order_notification( $order_id, $posted_data, $order ) {
    if( $order->has_status( 'pending' ) ){
        WC()->mailer()->emails['WC_Email_New_Order']->trigger( $order_id, $order, true );
    }
}
