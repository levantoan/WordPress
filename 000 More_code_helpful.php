<?php
// Make corp medium size image 
if(false === get_option("medium_crop"))
    add_option("medium_crop", "1");
else
    update_option("medium_crop", "1");


//Set posts_per_page in archive and more
function hwl_home_pagesize( $query ) {
    if ( is_admin() || ! $query->is_main_query() )
        return;

    if ( is_post_type_archive( 'thuong_hieu' ) ) {       
        $query->set( 'posts_per_page', -1 );
        return;
    }
}
add_action( 'pre_get_posts', 'hwl_home_pagesize', 1 );

//Do filter in ajax
if (defined( 'DOING_AJAX' ) && DOING_AJAX))
    
/******************************************************************/
// bật debug cho wordpress 
$check_local = $_SERVER['SERVER_NAME'];
if($check_local && $check_local == 'shop-devvn.local') {
    define('WP_DEBUG', true);
    ini_set('log_errors', TRUE);
    ini_set('error_reporting', E_ALL);
    ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
}else{
    define('WP_DEBUG', false);
}
/******************************************************************/

$url_thumb = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );

$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'thumbnail' );
$url = $thumb['0'];

echo get_the_post_thumbnail($post->ID,'blog_thumnail'); 

echo get_option('page_on_front');
echo get_option('page_for_posts');

//check reffer
function devvn_baogia_check_your_site(){
    $your_origin = isset($_SERVER['HTTP_ORIGIN']) ? parse_url(esc_url($_SERVER['HTTP_ORIGIN']), PHP_URL_HOST) : '';
    if(!$your_origin) {
        $your_origin = isset($_SERVER['HTTP_REFERER']) ? parse_url(esc_url($_SERVER['HTTP_REFERER']), PHP_URL_HOST) : '';
    }
    $home_url = parse_url(home_url(), PHP_URL_HOST);
    if ( $your_origin != $home_url ) {
        return false;
    }
    return true;
}
