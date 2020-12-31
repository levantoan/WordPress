<?php
/*
Fixed by levantoan.com
*/
add_action( 'woocommerce_coupon_options_usage_limit', 'woocommerce_coupon_options_usage_limit', 10, 2 );
function woocommerce_coupon_options_usage_limit( $coupon_id, $coupon ){

    // max discount per coupons
    $max_discount =  get_post_meta( $coupon_id, '_max_discount', true );
    woocommerce_wp_text_input( array(
        'id'                => 'max_discount',
        'label'             => __( 'Usage max discount', 'woocommerce' ),
        'placeholder'       => esc_attr__( 'Unlimited discount', 'woocommerce' ),
        'description'       => __( 'The maximum discount this coupon can give.', 'woocommerce' ),
        'type'              => 'number',
        'desc_tip'          => true,
        'class'             => 'short',
        'custom_attributes' => array(
            'step' 	=> 1,
            'min'	=> 0,
        ),
        'value' => $max_discount ? $max_discount : '',
    ) );

}
add_action( 'woocommerce_coupon_options_save', 'woocommerce_coupon_options_save', 10, 2 );
function woocommerce_coupon_options_save( $coupon_id, $coupon ) {
    update_post_meta( $coupon_id, '_max_discount', wc_format_decimal( $_POST['max_discount'] ) );
}

add_filter( 'woocommerce_coupon_get_discount_amount', 'woocommerce_coupon_get_discount_amount', 20, 5 );
function woocommerce_coupon_get_discount_amount( $discount, $discounting_amount, $cart_item, $single, $coupon ) {

    $max_discount = get_post_meta( $coupon->get_id(), '_max_discount', true );

    if ( is_numeric($max_discount) && ! is_null( $cart_item ) && WC()->cart->subtotal_ex_tax ) {

        $cart_item_qty = is_null( $cart_item ) ? 1 : $cart_item['quantity'];
        if ( wc_prices_include_tax() ) {
            $discount_percent = ( wc_get_price_including_tax( $cart_item['data'] ) * $cart_item_qty ) / WC()->cart->subtotal;
        } else {
            $discount_percent = ( wc_get_price_excluding_tax( $cart_item['data'] ) * $cart_item_qty ) / WC()->cart->subtotal_ex_tax;
        }
        $_discount = ( $max_discount * $discount_percent );

        $discount = min( $_discount, $discount );
    }

    return $discount;
}
