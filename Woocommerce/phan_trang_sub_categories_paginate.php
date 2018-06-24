<?php

/*
Author: Le Van Toan
Author URI: levantoan.com
*/


add_action('woocommerce_after_shop_loop', 'devvn_woocommerce_after_shop_loop');
function devvn_woocommerce_after_shop_loop(){
    global $subcat_paged, $subcat_total_pages, $devvn_product_categories;
    if($devvn_product_categories) {
        if ($subcat_total_pages > 1) {
            echo '<div class="subcat_pagenavi">';
            echo paginate_links(array(
                'format' => '?pagedcat=%#%',
                'current' => max(1, $subcat_paged),
                'total' => $subcat_total_pages,
                'mid_size' => '10',
                'prev_text' => __('‹', 'devvn'),
                'next_text' => __('›', 'devvn'),
                'type'	=>	'list'
            ));
            echo '</div>';
        }
    }
}

function woocommerce_output_product_categories( $args = array() ) {

    global $subcat_paged, $subcat_total_pages, $devvn_product_categories;

    $args = wp_parse_args( $args, array(
        'before'    => apply_filters( 'woocommerce_before_output_product_categories', '' ),
        'after'     => apply_filters( 'woocommerce_after_output_product_categories', '' ),
        'parent_id' => 0,
    ) );

    $product_categories = woocommerce_get_product_subcategories( $args['parent_id'] );

    if ( ! $product_categories ) {
        return false;
    }

    $parent_id = $args['parent_id'];

    if( $parent_id == 0) {

        echo $args['before']; // WPCS: XSS ok.

        foreach ($product_categories as $category) {
            wc_get_template('content-product_cat.php', array(
                'category' => $category,
            ));
        }

        echo $args['after']; // WPCS: XSS ok.
    }else{

        $subcat_total = count($product_categories);
        $subcat_number = 1;
        $subcat_paged = isset($_GET['pagedcat']) ? intval($_GET['pagedcat']) : 1;
        $subcat_total_pages = ceil($subcat_total/$subcat_number);

        $devvn_product_categories = get_categories( apply_filters( 'devvn_woocommerce_product_subcategories_args', array(
            'parent'       => $parent_id,
            'menu_order'   => 'ASC',
            'hide_empty'   => 0,
            'hierarchical' => 1,
            'taxonomy'     => 'product_cat',
            'pad_counts'   => 1,
            'number'    =>  $subcat_number,
            'offset'    =>  ($subcat_paged - 1) * $subcat_number,
        ) ) );

        echo $args['before']; // WPCS: XSS ok.

        foreach ($devvn_product_categories as $category) {
            wc_get_template('content-product_cat.php', array(
                'category' => $category,
            ));
        }

        echo $args['after']; // WPCS: XSS ok.

    }

    return true;
}
