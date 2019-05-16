<?php
/*
* Query product like post_name
*/
function cross_sell_posts_where( $where ) {
    if( !is_admin() ) {
        global $wpdb, $new_slug;
        $where .= " AND {$wpdb->posts}.post_name LIKE '{$new_slug}%'";
    }
    return $where;
}

add_action('woocommerce_after_single_product_summary','devvn_crosssell_product');
function devvn_crosssell_product(){
    global $product, $new_slug;

    $args_slug = array('-men','-women');

    $old_slug = $product->get_slug();
    $new_slug = str_replace($args_slug, '', $old_slug);
    add_filter( 'posts_where' , 'cross_sell_posts_where' );
    $cross_sell = new WP_Query(array(
        'post_type' => 'product',
        'posts_per_page'    => -1
    ));
    remove_filter( 'posts_where' , 'cross_sell_posts_where' );

    if($cross_sell->have_posts()):
        $type = get_theme_mod('related_products','slider');

        if($type == 'grid') $type = 'row';

        $repater['type'] = $type;
        $repater['columns'] = get_theme_mod('related_products_pr_row','4');
        $repater['slider_style'] = 'reveal';
        $repater['row_spacing'] = 'small';

        if(count($cross_sell) < $repater['columns']){
            $repater['type'] = 'row';
        }
        ?>
        <div class="up-sells upsells upsells-wrapper product-section">
            <h3 class="product-section-title product-section-title-upsell pt-half pb-half uppercase">
                <?php _e( 'You may also like&hellip;', 'woocommerce' ) ?>
            </h3>
            <?php get_flatsome_repeater_start($repater); ?>
            <?php while($cross_sell->have_posts()):$cross_sell->the_post();
                wc_get_template_part( 'content', 'product' ); ?>
            <?php endwhile; ?>
            <?php get_flatsome_repeater_end($repater); ?>
        </div>
        <?php
    endif; wp_reset_query();
}
