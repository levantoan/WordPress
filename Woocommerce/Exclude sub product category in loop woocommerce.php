<?php
add_filter('pre_get_posts', 'devvn_exclude_sub_product_cat', 99);
function devvn_exclude_sub_product_cat($query){
    if(!is_admin() && $query->is_main_query() && is_product_category()) {
        $current_cat_id = get_queried_object_id();
        $child_cat = get_terms( array(
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
            'child_of' => $current_cat_id,
            'fields' => 'ids'
        ) );
        if($child_cat) {
            $tax_query = (array) $query->get('tax_query');
            $tax_query[] = array(
                'taxonomy' => 'product_cat',
                'field' => 'term_taxonomy_id',
                'terms' => $child_cat,
                'operator' => 'NOT IN',
            );
            $query->set('tax_query', $tax_query);
        }
    }
}

