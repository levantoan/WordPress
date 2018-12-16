<?php
add_filter('woocommerce_get_image_size_gallery_thumbnail','devvn_woocommerce_get_image_size_gallery_thumbnail');
function devvn_woocommerce_get_image_size_gallery_thumbnail($size){
    $size['width'] = intval(get_option('thumbnail_size_w'));
    $size['height'] = intval(get_option('thumbnail_size_h'));
    return $size;
}
