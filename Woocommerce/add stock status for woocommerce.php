<?php
/*
* Add stock status for woocommerce. Add code to functions.php
* Author: levantoan.com
*/
add_filter('woocommerce_product_stock_status_options', 'devvn_woocommerce_product_stock_status_options');
function devvn_woocommerce_product_stock_status_options($stock_status){
    $stock_status = array_merge($stock_status, array(
        '7days'     => __( 'Đặt hàng 7 ngày', 'devvn' ),
        '15days'     => __( 'Đặt hàng 15 ngày', 'devvn' ),
        '30days'     => __( 'Đặt hàng 30 ngày', 'devvn' ),
        '45days'  => __( 'Đặt hàng 45 ngày', 'devvn' ),
        '60days'  => __( 'Đặt hàng 60 ngày', 'devvn' ),
    ));
    return $stock_status;
}

add_filter('woocommerce_get_availability_text', 'devvn_woocommerce_get_availability_text', 10, 2);
function devvn_woocommerce_get_availability_text($availability, $product){
    if(!$product->managing_stock() && !in_array($product->get_stock_status(), array('instock', 'outofstock', 'onbackorder'))){
        $stock_status = wc_get_product_stock_status_options();
        $availability = isset($stock_status[$product->get_stock_status()]) ? $stock_status[$product->get_stock_status()] : '';
    }
    if(!$availability) $availability = 'HÀNG CÓ SẴN';
    return $availability;
}

add_action('woocommerce_shop_loop_item_title', 'devvn_woocommerce_shop_loop_item_title');
function devvn_woocommerce_shop_loop_item_title(){
    global $product;
    echo wc_get_stock_html( $product );
}
