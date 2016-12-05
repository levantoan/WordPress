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
