<?php
add_filter('woocommerce_format_sale_price', 'devvn_woocommerce_format_sale_price', 10, 3);
function devvn_woocommerce_format_sale_price($price, $regular_price, $sale_price){
    if(is_numeric($regular_price) && is_numeric($sale_price)) {
        $percent = round( ( ( floatval( $regular_price ) - floatval( $sale_price ) ) / floatval( $regular_price ) ) * 100 );
        $text = '<span class="sale_label">-'.$percent.'%</span>';
        return $price . $text;
    }
    return $price;
}

add_filter('woocommerce_variable_sale_price_html', 'shop_variable_product_price', 10, 2);
add_filter('woocommerce_variable_price_html','shop_variable_product_price', 10, 2 );
function shop_variable_product_price( $price, $product ){
    $variation_min_reg_price = $product->get_variation_regular_price('min', true);
    $variation_min_sale_price = $product->get_variation_sale_price('min', true);
    $text = '';
    if ( $product->is_on_sale() && !empty($variation_min_sale_price)){
        if ( !empty($variation_min_sale_price) )
            $price = '<del class="strike">' .  wc_price($variation_min_reg_price) . '</del>
                      <ins class="highlight">' .  wc_price($variation_min_sale_price) . '</ins>';
        $percent = round( ( ( floatval( $variation_min_reg_price ) - floatval( $variation_min_sale_price ) ) / floatval( $variation_min_reg_price ) ) * 100 );
        $text = '<span class="sale_label">-' . $percent . '%</span>';
    } else {
        if(!empty($variation_min_reg_price))
            $price = '<ins class="highlight">'.wc_price( $variation_min_reg_price ).'</ins>';
        else
            $price = '<ins class="highlight">'.wc_price( $product->regular_price ).'</ins>';
    }

    return $price.$text;
}

add_action('wp_head', 'devvn_custom_css');
function devvn_custom_css(){
    ?>
    <style type="text/css">
        span.sale_label {
            background: #ddd;
            border-radius: 3px;
            display: inline-block;
            padding: 3px;
            font-size: 12px;
            margin: 0 0 0 10px;
        }

        .badge-container .on-sale {
            display: none;
        }
    </style>
    <?php
}
