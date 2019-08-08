<?php
/*
* Author: levantoan.com
*/
function devvn_get_all_post_by_type_escorts(){
    global $wpdb;

    $transient_name = 'devvn_all_prod_escorts';

    if ( false === ( $all_prods = get_transient($transient_name) ) ) {

        $all_prods = array();
        $i = 0;

        $taxonomy = "pa_escorts-type";
        $escorts_type = array('vip-escorts', 'premium-escorts', 'new-escorts', 'independent-escorts');

        if ($escorts_type) {
            foreach ($escorts_type as $escort) {
                $sql = "SELECT ID FROM $wpdb->posts WHERE post_type='product' AND post_status = 'publish' AND ID IN (
                            SELECT object_id FROM $wpdb->term_relationships WHERE term_taxonomy_id IN (
                                SELECT term_taxonomy_id FROM $wpdb->term_taxonomy WHERE taxonomy = '$taxonomy' AND term_id IN (
                                    SELECT t.term_id FROM $wpdb->terms t WHERE t.slug = '$escort'
                                )
                            )
                        )";
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

add_filter('posts_clauses', 'devvn_order_by_stock_status', 2000);
function devvn_order_by_stock_status($posts_clauses) {
    global $wpdb;
    if ( !is_admin() && is_main_query()
        && is_woocommerce() && (is_product_category() || is_product_tag())
        && (!isset($_GET['orderby']) || (isset($_GET['orderby']) && $_GET['orderby'] == 'menu_order'))
    ) {
        $all_prods = array_reverse(devvn_get_all_post_by_type_escorts());
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
            $posts_clauses['orderby'] = "FIELD( wp_posts.ID, ".$all_prod_id." ) DESC, $wpdb->posts.menu_order ASC";
        }
    }
    return $posts_clauses;
}

//Remove transient
add_action( 'save_post', 'devvn_delete_all_transient' );
add_action( 'wp_insert_post', 'devvn_delete_all_transient' );
add_action( 'publish_post', 'devvn_delete_all_transient' );
add_action( 'trash_post', 'devvn_delete_all_transient' );
add_action( 'created_pa_escorts-type', 'devvn_delete_all_transient' );
add_action( 'edited_pa_escorts-type', 'devvn_delete_all_transient' );
function devvn_delete_all_transient() {
    global $wpdb;
    $menus = $wpdb->get_col( 'SELECT option_name FROM '.$wpdb->prefix.'options WHERE option_name LIKE "_transient_devvn_all_prod_escorts" ' );
    foreach( $menus as $menu ) {
        $key = str_replace( '_transient_', '', $menu );
        delete_transient( $key );
    }
    wp_cache_flush();
}

function show_all_prod( $query ) {
    if ( !is_admin()  && is_woocommerce() && (is_product_category() || is_product_tag()) && $query->is_main_query() ) {
        $query->set( 'posts_per_page', -1 );
    }
}
add_action( 'pre_get_posts', 'show_all_prod' );
