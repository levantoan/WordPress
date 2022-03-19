<?php
add_filter('woocommerce_cart_crosssell_ids', 'devvn_crosssell_custom', 10, 2);
function devvn_crosssell_custom($cross_sells, $cart){
    add_filter('woocommerce_product_related_posts_relate_by_tag', '__return_false');
    $cross_sells = array();
    $in_cart     = array();
    if ( ! $cart->is_empty() ) {
        foreach ( $cart->get_cart() as $cart_item_key => $values ) {
            if ( $values['quantity'] > 0 ) {
                $related_products = array_filter( array_map( 'wc_get_product', wc_get_related_products( $values['product_id'], 10 ) ), 'wc_products_array_filter_visible' );
                $related_products = wp_list_pluck( $related_products, 'id' );
                $cross_sells = array_merge( $related_products, $cross_sells );
                $in_cart[]   = $values['product_id'];
            }
        }
    }
    $cross_sells = array_diff( $cross_sells, $in_cart );
    return apply_filters( 'devvn_woocommerce_cart_crosssell_ids', wp_parse_id_list( $cross_sells ), $cart );
}
