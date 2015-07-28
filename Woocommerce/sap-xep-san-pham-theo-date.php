<?php
/*sắp xếp sản phẩm theo date*/
add_filter('woocommerce_get_catalog_ordering_args', 'am_woocommerce_catalog_orderby');
function am_woocommerce_catalog_orderby( $args ) {
	$args['orderby'] = 'date';
	$args['order'] = 'desc';
	$args['meta_key'] = '';
    return $args;
}
?>