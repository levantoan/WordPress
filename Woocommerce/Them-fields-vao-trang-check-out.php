<?php
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
function custom_override_checkout_fields( $fields ) {

	 $fields['shipping']['shipping_county'] = array(
	 	 'label'     => __('County', 'woocommerce'),
		 'placeholder' => _x('County', 'placeholder', 'woocommerce'),
		 'class' => array('form-row-last'),
	 	 'required'  => false,
	 );
	 
	 return $fields;
}
//Thêm field bất kỳ vào checkout
add_action( 'woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta' );
function my_custom_checkout_field_update_order_meta( $order_id ) {
	if ( ! empty( $_POST['shipping_county'] ) ) {
        update_post_meta( $order_id, 'County Shipping', sanitize_text_field( $_POST['shipping_county'] ) );
    }
}
/**
 * Display field value on the order edit page
 */
add_action( 'woocommerce_admin_order_data_after_billing_address', 'my_custom_checkout_field_display_admin_order_meta', 10, 1 );
function my_custom_checkout_field_display_admin_order_meta($order){
    echo '<p><strong>'.__('County').':</strong> ' . get_post_meta( $order->id, 'County', true ) . '</p>';
}
add_action( 'woocommerce_admin_order_data_after_shipping_address', 'shipping_checkout_field_display_admin_order_meta', 10, 1 );
function shipping_checkout_field_display_admin_order_meta($order){
    echo '<p><strong>'.__('County Shipping').':</strong> ' . get_post_meta( $order->id, 'County Shipping', true ) . '</p>';
}
