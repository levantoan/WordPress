<?php
/*
* Author by levantoan.com
* Add to functions.php in your theme
*/
add_filter('woocommerce_structured_data_product_offer','devvn_woocommerce_structured_data_product_offer', 10, 2);
function devvn_woocommerce_structured_data_product_offer($markup_offer, $product){
    if ( '' !== $product->get_price() ) {
        if ( $product->is_type( 'variable' ) ) {
            $markup_offer['priceSpecification']['price'] = 0;
        } else {
            $markup_offer['price'] = 0;
        }
    }
    return $markup_offer;
}
