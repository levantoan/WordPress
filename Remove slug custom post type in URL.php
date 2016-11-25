<?php
/*
* Remove slug custom post type in url
* http://levantoan.com
*/
function devvn_remove_slug( $post_link, $post ) {
    if ( !in_array( get_post_type($post), array( 'product','videos','short_url' ) ) || 'publish' != $post->post_status ) {
        return $post_link;
    }
	if('videos' == $post->post_type){
    	$post_link = str_replace( '/videos/', '/', $post_link );
    }elseif('product' == $post->post_type){
    	$post_link = str_replace( '/san-pham/', '/', $post_link );
    }elseif('short_url' == $post->post_type){
    	$post_link = str_replace( '/short-url/', '/', $post_link );
    }else{
    	$post_link = str_replace( '/' . $post->post_type . '/', '/', $post_link );
    }
    
    return $post_link;
}
add_filter( 'post_type_link', 'devvn_remove_slug', 10, 2 );

function devvn_parse_request( $query ) {
    if ( ! $query->is_main_query() || 2 != count( $query->query ) || ! isset( $query->query['page'] ) ) {
        return;
    }
    if ( ! empty( $query->query['name'] ) ) {
        $query->set( 'post_type', array( 'post', 'product', 'short_url', 'videos', 'page' ) );
    }
}
add_action( 'pre_get_posts', 'devvn_parse_request' );
