<?php
/*
Add SKU below title in Woocommerce
Author: levantoan.com

Add this code to functions.php in your theme
*/
//Remove sku default
add_filter('wc_product_sku_enabled','__return_false');

//Add sku to below title - levantoan.com
add_action('woocommerce_single_product_summary','devvn_sku_below_title', 6);
function devvn_sku_below_title(){
    global $product;
    if ( $product->get_sku() || $product->is_type( 'variable' ) ) : ?>
		<div class="product_meta"><span class="sku_wrapper"><?php esc_html_e( 'SKU:', 'woocommerce' ); ?> <span class="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'woocommerce' ); ?></span></span></div>
	<?php endif;
}
