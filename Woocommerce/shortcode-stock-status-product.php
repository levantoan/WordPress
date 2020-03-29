<?php
add_shortcode( 'devvn_woo_stock', 'display_product_stock_status' );
function display_product_stock_status( $atts) {

    $atts = shortcode_atts(
        array('id'  => get_the_ID() ),
        $atts, 'devvn_woo_stock'
    );
    $product = wc_get_product( $atts['id'] );
    $availabilitys = $product->get_availability();
    ob_start();
    if ( ! $product->is_in_stock() ) {
        $availability = __( 'Out of stock', 'woocommerce' );
    } elseif ( $product->managing_stock() && $product->is_on_backorder( 1 ) ) {
        $availability = $product->backorders_require_notification() ? __( 'Available on backorder', 'woocommerce' ) : '';
    } elseif ( $product->managing_stock() ) {
        $availability = wc_format_stock_for_display( $product );
    } else {
        if ( $product->is_on_backorder() ) {
            $stock_html = __( 'On backorder', 'woocommerce' );
        } elseif ( $product->is_in_stock() ) {
            $stock_html = __( 'In stock', 'woocommerce' );
        } else {
            $stock_html = __( 'Out of stock', 'woocommerce' );
        }
        $availability = apply_filters( 'devvn_woocommerce_stock_html', $stock_html, $product );
    }
    ?>
    <span class="devvn_stock_status"><strong><?php _e('Tình trạng:', 'devvn'); ?></strong>
        <span class="stock <?php echo esc_attr($availabilitys['class']); ?>"><?php echo $availability;?></span>
    </span>
    <?php
    return ob_get_clean();
}
