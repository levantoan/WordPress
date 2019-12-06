<?php
/*
Author: levantoan.com
Ghim sản phẩm nổi bật lên đầu trang
Orderby feature product to first list
*/
add_filter('posts_orderby', 'devvn_order_by_featured_products',99,2 );
function devvn_order_by_featured_products( $order_by, $query ){
    global $wpdb;
    if(
        $query->is_main_query() && (is_product_taxonomy() || is_product_tag())
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
        $feture_product_id = wc_get_featured_product_ids();

        if( is_array( $feture_product_id ) && !empty($feture_product_id)  )
        {

            if( empty( $order_by ) ) {
                $order_by =  "FIELD(".$wpdb->posts.".ID,'".implode("','",$feture_product_id)."') DESC ";
            }
            else
            {
                $order_by =  "FIELD(".$wpdb->posts.".ID,'".implode("','",$feture_product_id)."') DESC, " . $order_by;
            }

        }
    }
    return $order_by;

}
