<?php
add_filter( 'post_thumbnail_html', 'remove_wps_width_attribute', 10 );
add_filter( 'image_send_to_editor', 'remove_wps_width_attribute', 10 );
add_filter( 'woocommerce_product_get_image', 'remove_wps_width_attribute', 10 );
add_filter( 'woocommerce_single_product_image_thumbnail_html', 'remove_wps_width_attribute', 10 );
function remove_wps_width_attribute( $html ) {
    $html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
    return $html;
}
