<?php
/*
* set and get transient
*/
$transientName = 'dvls_cache_query_allpost';
if ( false === ( $near_store = get_transient( $transientName ) ) ) {
    $args = array(
        'post_type' => 'local-store',
        'posts_per_page' => -1,
    );
    $near_store = get_posts($args);
    set_transient( $transientName, $near_store );
}

/*
* Delete all transient
*/
add_action( 'save_post', 'devvn_delete_all_transient_khoahoc' );
add_action( 'wp_insert_post', 'devvn_delete_all_transient_khoahoc' );
add_action( 'publish_post', 'devvn_delete_all_transient_khoahoc' );
function devvn_delete_all_transient_khoahoc() {
    global $wpdb;
    $menus = $wpdb->get_col( 'SELECT option_name FROM '.$wpdb->prefix.'options WHERE option_name LIKE "_transient_dvls_cache_query_%" ' );
    foreach( $menus as $menu ) {
        $key = str_replace( '_transient_', '', $menu );
        delete_transient( $key );
    }
    wp_cache_flush();
}
