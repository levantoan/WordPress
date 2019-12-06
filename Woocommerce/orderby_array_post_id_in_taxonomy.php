/*
Author: levantoan.com
Sắp xếp bài viết theo list ID cho trước
Sử dụng ACF để chọn list bài viết
ACF Field - Relationship - feature_product_cat
*/

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
