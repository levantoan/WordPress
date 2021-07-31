<?php
add_action( 'wpseo_opengraph', 'wpseo_opengraph_add_id', 99 );
function wpseo_opengraph_add_id(){
	$object = $GLOBALS['wp_query']->get_queried_object();
	if ( isset($object->ID) && $object->ID && get_post_type($object->ID) == 'product' ) {
		$product = wc_get_product($object->ID);
		$sku = ($product->get_sku()) ? $product->get_sku() : $product->get_id();
		echo '<meta property="product:retailer_item_id" content="'.$sku.'" />', "\n";		
		echo '<meta property="product:price:amount" content="'.$product->get_price(false).'" />', "\n";					
		echo '<meta property="product:price:currency" content="VND" />', "\n";		
		echo '<meta property="og:availability" content="in stock" />', "\n";			
		echo '<meta property="product:availability" content="in stock" />', "\n";			
		echo '<meta property="product:condition" content="new" />', "\n";		
	}
	
}
