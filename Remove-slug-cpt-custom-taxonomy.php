<?php
/*
Author: levantoan.com
Remove Slug Custom post type thue_xe and custom taxonmy loai_xe
*/
/*Remove slug cpt*/
function devvn_remove_slug( $post_link, $post ) {
    if ( !in_array( get_post_type($post), array( 'cho_thue_xe' ) ) || 'publish' != $post->post_status ) {
        return $post_link;
    }
    if('cho_thue_xe' == $post->post_type){
        $post_link = str_replace( '/thue-xe/', '/', $post_link );
    }else{
        $post_link = str_replace( '/' . $post->post_type . '/', '/', $post_link );
    }
    return $post_link;
}
add_filter( 'post_type_link', 'devvn_remove_slug', 10, 2 );

function devvn_thue_xe_rewrite_rules($flash = false) {
    global $wp_post_types, $wpdb;
    $siteLink = esc_url(home_url('/'));
    foreach ($wp_post_types as $type=>$custom_post) {
        if($type == 'cho_thue_xe'){
            if ($custom_post->_builtin == false) {
                $transientName = 'devvn_cache_svl_removethuexeslug';
                if ( false === ( $posts = get_transient( $transientName ) ) ) {
                    $querystr = "SELECT {$wpdb->posts}.post_name, {$wpdb->posts}.ID
								FROM {$wpdb->posts} 
								WHERE {$wpdb->posts}.post_status = 'publish' 
								AND {$wpdb->posts}.post_type = '{$type}'";
                    $posts = $wpdb->get_results($querystr, OBJECT);
                    set_transient( $transientName, $posts );
                }
                foreach ($posts as $post) {
                    $current_slug = get_permalink($post->ID);
                    $base_product = str_replace($siteLink,'',$current_slug);
                    add_rewrite_rule($base_product.'?$', "index.php?{$custom_post->query_var}={$post->post_name}", 'top');
                    add_rewrite_rule($base_product.'comment-page-([0-9]{1,})/?$', 'index.php?'.$custom_post->query_var.'='.$post->post_name.'&cpage=$matches[1]', 'top');
                    add_rewrite_rule($base_product.'(?:feed/)?(feed|rdf|rss|rss2|atom)/?$', 'index.php?'.$custom_post->query_var.'='.$post->post_name.'&feed=$matches[1]','top');
                }
            }
        }
    }
    if ($flash == true)
        flush_rewrite_rules(false);
}
add_action('init', 'devvn_thue_xe_rewrite_rules');

function devvn_new_thuexe_post_save($post_id){
    global $wp_post_types;
    $post_type = get_post_type($post_id);
    foreach ($wp_post_types as $type=>$custom_post) {
        if ($custom_post->_builtin == false && $type == $post_type) {
            devvn_thue_xe_rewrite_rules(true);
        }
    }
}
add_action('wp_insert_post', 'devvn_new_thuexe_post_save');

/*Remove slug custom taxonomy*/
add_filter( 'term_link', 'devvn_loai_xe_permalink', 10, 3 );
function devvn_loai_xe_permalink( $url, $term, $taxonomy ){
    switch ($taxonomy):
        case 'loai_xe':
            $taxonomy_slug = 'loai-xe';
            if(strpos($url, $taxonomy_slug) === FALSE) break;
            $url = str_replace('/' . $taxonomy_slug, '', $url);
            break;
    endswitch;
    return $url;
}

function devvn_loaixe_rewrite_rules($flash = false) {
    $transientName = 'devvn_cache_svl_removeloaixeslug';
    if ( false === ( $terms = get_transient( $transientName ) ) ) {
        $terms = get_terms(array(
            'taxonomy' => 'loai_xe',
            'hide_empty' => false,
        ));
        if ($terms && !is_wp_error($terms)) {
            set_transient($transientName, $terms);
        }
    }
    if($terms && !is_wp_error($terms)){
        $siteurl = esc_url(home_url('/'));
        foreach ($terms as $term){
            $term_slug = $term->slug;
            $baseterm = str_replace($siteurl,'',get_term_link($term->term_id,'loai_xe'));
            add_rewrite_rule($baseterm.'?$','index.php?loai_xe='.$term_slug,'top');
            add_rewrite_rule($baseterm.'page/([0-9]{1,})/?$', 'index.php?loai_xe='.$term_slug.'&paged=$matches[1]','top');
            add_rewrite_rule($baseterm.'(?:feed/)?(feed|rdf|rss|rss2|atom)/?$', 'index.php?loai_xe='.$term_slug.'&feed=$matches[1]','top');
        }
    }
    if ($flash == true)
        flush_rewrite_rules(false);
}
add_action('init', 'devvn_loaixe_rewrite_rules');

add_action( 'create_term', 'devvn_new_loaixe_edit_success', 10, 2 );
function devvn_new_loaixe_edit_success( $term_id, $taxonomy ) {
    devvn_loaixe_rewrite_rules(true);
}

add_action( 'save_post', 'devvn_thuexe_delete_all_transient' );
add_action( 'wp_insert_post', 'devvn_thuexe_delete_all_transient' );
add_action( 'publish_post', 'devvn_thuexe_delete_all_transient' );
add_action( 'trash_post', 'devvn_thuexe_delete_all_transient' );
add_action( 'create_term', 'devvn_thuexe_delete_all_transient' );
add_action( 'edit_terms', 'devvn_thuexe_delete_all_transient' );
add_action( 'delete_term', 'devvn_thuexe_delete_all_transient' );
function devvn_thuexe_delete_all_transient() {
    global $wpdb;
    $menus = $wpdb->get_col( 'SELECT option_name FROM '.$wpdb->prefix.'options WHERE option_name LIKE "_transient_devvn_cache_svl_%" ' );
    foreach( $menus as $menu ) {
        $key = str_replace( '_transient_', '', $menu );
        delete_transient( $key );
    }
    wp_cache_flush();
}

