<?php
add_filter('posts_orderby', 'devvn_order_by_featured_products_cat', 99, 2 );
function devvn_order_by_featured_products_cat( $order_by, $query ){
    global $wpdb;
    if(
        $query->is_main_query() && (is_product_taxonomy() && function_exists('get_field'))
        &&
        (
            !empty( $query->query_vars[ 'orderby' ] )
            &&
            $query->query_vars[ 'orderby' ] == 'menu_order title'
            &&
            !empty( $query->query_vars[ 'order' ] )
            &&
            $query->query_vars[ 'order' ]== 'ASC'
        )
    ) {
        $term_id = $query->get_queried_object_id();
        $feture_product_id = get_field('feature_product_cat', 'product_cat_'.$term_id);
        if( is_array( $feture_product_id ) && !empty($feture_product_id)  )
        {
            $feture_product_id = array_reverse($feture_product_id);
            if( empty( $order_by ) ) {
                $order_by =  "FIELD(".$wpdb->posts.".ID, ".implode(",",$feture_product_id).") DESC ";
            }
            else
            {
                $order_by =  "FIELD(".$wpdb->posts.".ID, ".implode(",",$feture_product_id).") DESC, " . $order_by;
            }
        }
    }
    
    return $order_by;
}
add_filter('acf/fields/relationship/query/name=feature_product_cat','devvn_acf_fields_relationship_query', 10, 3);
function devvn_acf_fields_relationship_query($args, $field, $term_id){
    $term_id = explode('_', $term_id);
    if(isset($term_id[1]) && is_numeric($term_id[1]) && empty($field['taxonomy'])){
        $tag_ID = $term_id[1];
        $args['tax_query'] = array(
            'relation' => 'OR',
        );
        $args['tax_query'][] = array(
            'taxonomy'	=> 'product_cat',
            'field'		=> 'term_id',
            'terms'		=> array($tag_ID),
        );
    }
    return $args;
}
//ACF field
if( function_exists('acf_add_local_field_group') ):
    acf_add_local_field_group(array(
        'key' => 'group_5dea06175d708',
        'title' => 'Sản phẩm nổi bật',
        'fields' => array(
            array(
                'key' => 'field_5dea062596c47',
                'label' => 'Danh sách sản phẩm nổi bật',
                'name' => 'feature_product_cat',
                'type' => 'relationship',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'post_type' => array(
                    0 => 'product',
                ),
                'taxonomy' => '',
                'filters' => array(
                    0 => 'search',
                    1 => 'post_type',
                    2 => 'taxonomy',
                ),
                'elements' => array(
                    0 => 'featured_image',
                ),
                'min' => '',
                'max' => '',
                'return_format' => 'id',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'taxonomy',
                    'operator' => '==',
                    'value' => 'product_cat',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));
endif;
