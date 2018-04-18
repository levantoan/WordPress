<?php
/*
Author: levantoan.com
Thay đổi định dạng ngày đăng đơn hàng h:i d/m/Y
*/
add_filter( 'manage_shop_order_posts_columns', 'devvn_shop_order_columns', 999 );
add_action( 'manage_shop_order_posts_custom_column', 'devvn_render_shop_order_columns' , 999 );
function devvn_shop_order_columns($posts_columns){
    unset($posts_columns['order_date']);
    $posts_columns['devvn_order_date'] = 'Ngày đặt hàng';
    return $posts_columns;
}
function devvn_render_shop_order_columns($column){
    global $post, $the_order, $wp_query;
    if ( empty( $the_order ) || $the_order->get_id() !== $post->ID ) {
        $the_order = wc_get_order( $post->ID );
    }
    switch ( $column ) {
        case 'devvn_order_date' :
            echo $the_order->get_date_created()->date_i18n( apply_filters( 'woocommerce_admin_order_date_format', __( 'M j, Y', 'woocommerce' ) ) );;
            break;
    }
    return $column;
}
