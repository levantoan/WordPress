<?php
/*
Add to functions.php
Author: https://levantoan.com
*/

function devvn_wpseo_breadcrumb_output( $output ){
    if( is_product() ){
        $from = '<a href="'.home_url('/shop/').'">Sản phẩm</a> <span class="divider">»</span>';
        $to     = '';
        $output = str_replace( $from, $to, $output );
    }
    return $output;
}
add_filter( 'wpseo_breadcrumb_output', 'devvn_wpseo_breadcrumb_output' );
