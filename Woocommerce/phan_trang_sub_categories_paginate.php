<?php

/*
Author: Le Van Toan
Author URI: levantoan.com
*/

function woocommerce_output_product_categories( $args = array() ) {
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

        $total = count($product_categories);
        $number = 12;
        $paged = isset($_GET['pagedcat']) ? intval($_GET['pagedcat']) : 1;
        $total_pages = ceil($total/$number);

        $product_categories = get_categories( apply_filters( 'devvn_woocommerce_product_subcategories_args', array(
            'parent'       => $parent_id,
            'menu_order'   => 'ASC',
            'hide_empty'   => 0,
            'hierarchical' => 1,
            'taxonomy'     => 'product_cat',
            'pad_counts'   => 1,
            'number'    =>  $number,
            'offset'    =>  ($paged - 1) * $number,
        ) ) );

        echo $args['before']; // WPCS: XSS ok.

        foreach ($product_categories as $category) {
            wc_get_template('content-product_cat.php', array(
                'category' => $category,
            ));
        }

        echo $args['after']; // WPCS: XSS ok.

        if($total > $number) {
            echo '<div class="subcat_pagenavi">';
            echo paginate_links(array(
                'format' => '?pagedcat=%#%',
                'current' => max(1, $paged),
                'total' => $total_pages,
                'mid_size' => '10',
                'prev_text' => __('Prev', 'devvn'),
                'next_text' => __('Next', 'devvn'),
            ));
            echo '</div>';
        }

    }

    return true;
}
