<?php
/*
Chuyển hướng tới trang khác khi post có shortcode nào đó
*/
function my_page_template_redirect(){
	global $post;
	if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'your_short_code') && ! is_user_logged_in() ) {
		wp_redirect(URL_IN_HERE);
        exit();
	}
}
add_action( 'template_redirect', 'my_page_template_redirect' );