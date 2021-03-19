<?php
/*
* Order by stock status
* Author: levantoan.com
*/
function devvn_get_all_post_by_stock_status(){
    global $wpdb;

    $transient_name = 'devvn_all_prod_by_stock';

    if ( false === ( $all_prods = get_transient($transient_name) ) ) {

        $all_prods = array();
        $i = 0;

        $stock_status = array('7days', '15days', '30days', '45days', '60days', 'onbackorder', 'outofstock');

        if ($stock_status) {
            foreach ($stock_status as $stock) {
                $sql = "SELECT SQL_CALC_FOUND_ROWS  p.ID FROM $wpdb->posts p INNER JOIN $wpdb->postmeta t ON ( p.ID = t.post_id ) WHERE 1=1  AND (
                            t.meta_key = '_stock_status' AND t.meta_value IN ('$stock')
                        ) AND p.post_type = 'product' AND p.post_status = 'publish' GROUP BY p.ID ORDER BY p.post_date DESC";
                $products = $wpdb->get_results($sql);
                if ($products):
                    foreach ($products as $prod):
                        $all_prods[$i][] = $prod->ID;
                    endforeach;
                endif;
                $i++;
            }
        }
        if($all_prods) set_transient( $transient_name, $all_prods );
    }
    return $all_prods;
}

add_action( 'save_post', 'devvn_delete_all_transient_by_stock' );
add_action( 'wp_insert_post', 'devvn_delete_all_transient_by_stock' );
add_action( 'publish_post', 'devvn_delete_all_transient_by_stock' );
add_action( 'trash_post', 'devvn_delete_all_transient_by_stock' );
function devvn_delete_all_transient_by_stock() {
    global $wpdb;
    $menus = $wpdb->get_col( 'SELECT option_name FROM '.$wpdb->prefix.'options WHERE option_name LIKE "_transient_devvn_all_prod_by_stock" ' );
    foreach( $menus as $menu ) {
        $key = str_replace( '_transient_', '', $menu );
        delete_transient( $key );
    }
    wp_cache_flush();
}

add_filter('posts_clauses', 'devvn_order_by_stock_status', 2000);
function devvn_order_by_stock_status($posts_clauses) {
    global $wpdb;
    if ( !is_admin() && is_main_query()
        && is_woocommerce() && (is_shop() || is_product_category() || is_product_tag())
        && (!isset($_GET['orderby']) || (isset($_GET['orderby']) && $_GET['orderby'] == 'menu_order'))
    ) {
        $all_prods = devvn_get_all_post_by_stock_status();
        $all_prod_arg = array();
        if($all_prods && !empty($all_prods)) {
            $new_args = array();
            foreach ($all_prods as $key=>$escorts_post){
                shuffle($escorts_post);
                $new_args[$key] = $escorts_post;
            }
            foreach ($new_args as $prod_id){
                $all_prod_arg[] = implode(',',$prod_id);
            }
            $all_prod_id = implode(',', $all_prod_arg);
            $posts_clauses['orderby'] = "FIELD( $wpdb->posts.ID, ".$all_prod_id." ) ASC, $wpdb->posts.menu_order ASC";
        }
    }
    return $posts_clauses;
}
