function remove_output_structured_data() {
  remove_action( 'wp_footer', array( WC()->structured_data, 'output_structured_data' ), 10 ); // This removes structured data from all frontend pages
  remove_action( 'woocommerce_email_order_details', array( WC()->structured_data, 'output_email_structured_data' ), 30 ); // This removes structured data from all Emails sent by WooCommerce
}
add_action( 'init', 'remove_output_structured_data' );

add_filter( 'rank_math/snippet/rich_snippet_product_entity', function( $entity ) {
    global $product;
    if(!isset($entity['offers']['priceValidUntil']) || (isset($entity['offers']['priceValidUntil']) && $entity['offers']['priceValidUntil'] == '')){
        $entity['offers']['priceValidUntil'] = '2050-12-31';
    }
    if(!isset($entity['sku']) || (isset($entity['sku']) && $entity['sku'] == '')){
        $entity['sku'] = 'ORENYA-'.$product->get_id();
    }
    if(!isset($entity['mpn']) || (isset($entity['mpn']) && $entity['mpn'] == '')) {
        $entity['mpn'] = $product->get_sku() ? $product->get_sku() : 'ORENYA-'.$product->get_id();
    }
    if(!isset($entity['id']) || (isset($entity['id']) && $entity['id'] == '')) {
        $entity['id'] = $product->get_id() ? $product->get_id() : null;
    }
    return $entity;
}, 99);

function devvn_custom_woocommerce_structured_data_product ($data) {
    global $product;
 
    $data['brand'] = $product->get_attribute('pa_thuong-hieu') ? $product->get_attribute('pa_thuong-hieu') : null;
    $data['mpn'] = $product->get_sku() ? $product->get_sku() : null;
    $data['id'] = $product->get_id() ? $product->get_id() : null;
 
    return $data;
}
add_filter( 'woocommerce_structured_data_product', 'devvn_custom_woocommerce_structured_data_product' );
