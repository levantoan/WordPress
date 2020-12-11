<?php
/*
Add exclude_cat attribute in shortcode [products exclude_cat=""]
Add to functions.php
*/
add_filter('shortcode_atts_products', 'devvn_add_exclude_cat_to_shortcode_products', 10, 4);
function devvn_add_exclude_cat_to_shortcode_products($out, $pairs, $atts, $shortcode){
    if(!isset($out['exclude_cat']) && isset($atts['exclude_cat'])){
        $out['exclude_cat'] = $atts['exclude_cat'];
    }
    return $out;
}

add_filter('woocommerce_shortcode_products_query', 'devvn_exclude_cat', 10, 3);
function devvn_exclude_cat($query_args, $attributes, $type){
    if ( ! empty( $attributes['exclude_cat'] ) ) {
        $categories = array_map( 'sanitize_title', explode( ',', $attributes['exclude_cat'] ) );
        $field      = 'slug';

        if ( is_numeric( $categories[0] ) ) {
            $field      = 'term_id';
            $categories = array_map( 'absint', $categories );
            // Check numeric slugs.
            foreach ( $categories as $cat ) {
                $the_cat = get_term_by( 'slug', $cat, 'product_cat' );
                if ( false !== $the_cat ) {
                    $categories[] = $the_cat->term_id;
                }
            }
        }

        $query_args['tax_query'][] = array(
            'taxonomy'         => 'product_cat',
            'terms'            => $categories,
            'field'            => $field,
            'operator'         => 'NOT IN',
        );
    }
    return $query_args;
}
