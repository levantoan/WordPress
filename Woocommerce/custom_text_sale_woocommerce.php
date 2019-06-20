<?php
/*
* Hiển thị dòng chữ 'Giảm ngay xx% giá chỉ còn xxx.xxx"
* Author: levantoan.com
* Thêm code sau vào functions.php của theme sau đó dùng shortcode [devvn_giamgia] vào nơi muốn hiển thị
*/

//Shortcode hiển thị Giảm ngay xx% giá chỉ còn xxx.xxx
add_shortcode('devvn_giamgia','devvn_giamgia_func');
function devvn_giamgia_func(){
    global $product;

    ob_start();

    if ( $product->is_on_sale() ) :
        $post_id = $product->get_id();

        if ( $product->is_type( 'simple' ) || $product->is_type( 'external' ) ) {
            $regular_price  = $product->get_regular_price();
            $sale_price     = $sale_price_out = $product->get_sale_price();
            $percentage_out = round( ( ( floatval( $regular_price ) - floatval( $sale_price ) ) / floatval( $regular_price ) ) * 100 );
        } elseif ( $product->is_type( 'variable' ) ) {
            $sale_price_out = $product->get_variation_price( 'min', true );
            if ( $bubble_content = flatsome_percentage_get_cache( $post_id ) ) {
                $percentage_out = $bubble_content;
            }else {
                $available_variations = $product->get_available_variations();
                $maximumper = 0;

                for ($i = 0; $i < count($available_variations); ++$i) {
                    $variation_id = $available_variations[$i]['variation_id'];
                    $variable_product = new WC_Product_Variation($variation_id);
                    if (!$variable_product->is_on_sale()) {
                        continue;
                    }
                    $regular_price = $variable_product->get_regular_price();
                    $sale_price = $variable_product->get_sale_price();
                    $percentage = round(((floatval($regular_price) - floatval($sale_price)) / floatval($regular_price)) * 100);
                    if ($percentage > $maximumper) {
                        $maximumper = $percentage;
                    }
                }
                $bubble_content = sprintf(__('%s', 'woocommerce'), $maximumper);
                // Cache percentage for variable products to reduce database queries.
                flatsome_percentage_set_cache($post_id, $bubble_content);
                $percentage_out = $bubble_content;
            }
        }
        if($percentage_out)
            echo 'Giảm ngay '.$percentage_out.'% giá chỉ còn ' . wc_price($sale_price_out);
    endif;
    return ob_get_clean();
}
