<?php
/*
Search by SKU for woocommerce
Author: levantoan.com
Add this code to functions.php in your theme
*/
/*Search by SKU*/
function list_searcheable_acf()
{
    $list_searcheable_acf = array("_sku");
    return $list_searcheable_acf;
}
function advanced_custom_search($where, $wp_query)
{
    global $wpdb;
    if (empty($where))
        return $where;
    // get search expression
    $terms = $wp_query->query_vars['s'];
    // explode search expression to get search terms
    $exploded = explode(' ', $terms);
    if ($exploded === FALSE || count($exploded) == 0)
        $exploded = array(0 => $terms);
    // reset search in order to rebuilt it as we whish
    $where = '';
    $list_searcheable_acf = list_searcheable_acf();
    foreach ($exploded as $tag) :
        $where .= " 
          AND (
            ({$wpdb->posts}.post_title LIKE '%$tag%')
            OR EXISTS (
              SELECT * FROM {$wpdb->postmeta}
                  WHERE post_id = {$wpdb->posts}.ID
                    AND (";
        foreach ($list_searcheable_acf as $searcheable_acf) :
            if ($searcheable_acf == $list_searcheable_acf[0]):
                $where .= " (meta_key LIKE '%" . $searcheable_acf . "%' AND meta_value LIKE '%$tag%') ";
            else :
                $where .= " OR (meta_key LIKE '%" . $searcheable_acf . "%' AND meta_value LIKE '%$tag%') ";
            endif;
        endforeach;
        $where .= ")
            )
        )";
    endforeach;
    return $where;
}
add_filter('posts_search', 'advanced_custom_search', 500, 2);

function searchfilter($query) {
    if ($query->is_search && !is_admin() ) {
        if(isset($_GET['post_type'])) {
            $type = $_GET['post_type'];
            if($type == 'product') {
                $query->set('post_type',array('product', 'product_variation'));
            }
        }
    }
    return $query;
}
add_filter('pre_get_posts','searchfilter');
