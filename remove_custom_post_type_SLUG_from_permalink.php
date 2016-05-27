<?php
/*
Ví dụ cho post_type là race
Thêm code dưới vào file functions.php
Chú ý nếu post type là "race" nhưng slug lại là "race-slug" thì cần thay lại dòng 17 như sau
$post_link = str_replace( '/race-slug/', '/', $post_link );
*/
/**
 * Remove the slug from published post permalinks. Only affect our custom post type, though.
 */
function gp_remove_cpt_slug( $post_link, $post, $leavename ) {
 
    if ( 'race' != $post->post_type || 'publish' != $post->post_status ) {
        return $post_link;
    }
 
    $post_link = str_replace( '/' . $post->post_type . '/', '/', $post_link );
 
    return $post_link;
}
add_filter( 'post_type_link', 'gp_remove_cpt_slug', 10, 3 );


/**
 * Have WordPress match postname to any of our public post types (post, page, race)
 * All of our public post types can have /post-name/ as the slug, so they better be unique across all posts
 * By default, core only accounts for posts and pages where the slug is /post-name/
 */
function gp_parse_request_trick( $query ) {
 
    // Only noop the main query
    if ( ! $query->is_main_query() )
        return;
 
    // Only noop our very specific rewrite rule match
    if ( 2 != count( $query->query ) || ! isset( $query->query['page'] ) ) {
        return;
    }
 
    // 'name' will be set if post permalinks are just post_name, otherwise the page rule will match
    if ( ! empty( $query->query['name'] ) ) {
        $query->set( 'post_type', array( 'post', 'page', 'race' ) );
    }
}
add_action( 'pre_get_posts', 'gp_parse_request_trick' );
