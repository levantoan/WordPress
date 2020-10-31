<?php
//Sale Flash
add_filter('woocommerce_sale_flash', 'devvn_woocommerce_sale_flash', 10, 3);
function devvn_woocommerce_sale_flash($html, $post, $product){
	return '<span class="onsale"><span>' . devvn_presentage_bubble($product) . '</span></span>';
}

function devvn_presentage_bubble( $product ) {
	$post_id = $product->get_id();

	if ( $product->is_type( 'simple' ) || $product->is_type( 'external' ) ) {
		$regular_price  = $product->get_regular_price();
		$sale_price     = $product->get_sale_price();
		$bubble_content = round( ( ( floatval( $regular_price ) - floatval( $sale_price ) ) / floatval( $regular_price ) ) * 100 );
	} elseif ( $product->is_type( 'variable' ) ) {
		if ( $bubble_content = devvn_percentage_get_cache( $post_id ) ) {
			return devvn_percentage_format( $bubble_content );
		}

		$available_variations = $product->get_available_variations();
		$maximumper           = 0;

		for ( $i = 0; $i < count( $available_variations ); ++ $i ) {
			$variation_id     = $available_variations[ $i ]['variation_id'];
			$variable_product = new WC_Product_Variation( $variation_id );
			if ( ! $variable_product->is_on_sale() ) {
				continue;
			}
			$regular_price = $variable_product->get_regular_price();
			$sale_price    = $variable_product->get_sale_price();
			$percentage    = round( ( ( floatval( $regular_price ) - floatval( $sale_price ) ) / floatval( $regular_price ) ) * 100 );
			if ( $percentage > $maximumper ) {
				$maximumper = $percentage;
			}
		}

		$bubble_content = sprintf( __( '%s', 'woocommerce' ), $maximumper );

		// Cache percentage for variable products to reduce database queries.
		devvn_percentage_set_cache( $post_id, $bubble_content );
	} else {
		// Set default and return if the product type doesn't meet specification.
		$bubble_content = __( 'Sale!', 'woocommerce' );

		return $bubble_content;
	}

	return devvn_percentage_format( $bubble_content );
}

function devvn_percentage_get_cache( $post_id ) {
	return get_post_meta( $post_id, '_devvn_product_percentage', true );
}

function devvn_percentage_set_cache( $post_id, $bubble_content ) {
	update_post_meta( $post_id, '_devvn_product_percentage', $bubble_content );
}

// Process custom formatting. Keep mod value double check
// to process % for default parameter (See sprintf()).
function devvn_percentage_format( $value ) {
	return str_replace( '{value}', $value, '-{value}%' );
}

// Clear cached percentage whenever a product or variation is saved.
function devvn_percentage_clear( $object ) {
	$post_id = 'variation' === $object->get_type()
		? $object->get_parent_id()
		: $object->get_id();

	delete_post_meta( $post_id, '_devvn_product_percentage' );
}
add_action( 'woocommerce_before_product_object_save', 'devvn_percentage_clear' );
