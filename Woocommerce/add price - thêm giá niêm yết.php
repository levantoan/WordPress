<?php

/*

Thêm 1 giá niêm yết vào giá sản phẩm. hiển thị như giá của biến thể

Css
p.price.price-loop > del {
    display: none !important;
}
p.price.price-loop, p.price.price-loop * {
    font-size: 13px;
}
*/

add_action('woocommerce_product_options_pricing', 'woocommerce_product_options_pricing_func');
function woocommerce_product_options_pricing_func(){
    global $product_object;
    $_niemyet_price = get_post_meta($product_object->get_id(), '_niemyet_price', true);
    woocommerce_wp_text_input(
        array(
            'id'          => '_niemyet_price',
            'value'       => $_niemyet_price,
            'data_type'   => 'price',
            'label'       => __( 'Giá niêm yết', 'woocommerce' ) . ' (' . get_woocommerce_currency_symbol() . ')',
            'description' => '',
        )
    );
}

add_action( 'save_post', 'save_product_niemyet_price', 10,3 );
function save_product_niemyet_price( $post_id, $post, $update ) {
    if ( 'product' !== $post->post_type ) {
        return;
    }
    $_niemyet_price = isset($_POST['_niemyet_price']) && $_POST['_niemyet_price'] ? floatval($_POST['_niemyet_price']) : '';
    update_post_meta($post_id, '_niemyet_price', $_niemyet_price);
}

function woocommerce_template_single_price() {
    global $product;
    $_niemyet_price = get_post_meta($product->get_id(), '_niemyet_price', true);
    ?>
    <p class="<?php echo esc_attr( apply_filters( 'woocommerce_product_price_class', 'price' ) ); ?>">
        <?php echo $product->get_price_html(); ?>
        <?php if($_niemyet_price): echo ' - ' . wc_price($_niemyet_price); endif;?>
    </p>
    <?php
}

function woocommerce_template_loop_price() {
	global $product;
	if ( $price_html = $product->get_price_html() ) :
	$_niemyet_price = get_post_meta($product->get_id(), '_niemyet_price', true);
	if($product->is_type( 'simple' ) && $_niemyet_price):
	?>
	<p class="<?php echo esc_attr( apply_filters( 'devvn_woocommerce_product_price_loop_class', 'price price-loop' ) ); ?>">
        <?php echo $product->get_price_html(); ?>
        <?php if($_niemyet_price): echo ' - ' . wc_price($_niemyet_price); endif;?>
    </p>
	<?php else:?>
	<span class="price"><?php echo $price_html; ?></span>
	<?php endif;?>
	<?php endif;
}
