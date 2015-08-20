<?php
defined('ABSPATH') or die("No script kiddies please!");
//Add new column
add_filter( 'manage_edit-bx_slider_columns', 'my_edit_bx_slider_columns' ) ;
function my_edit_bx_slider_columns( $columns ) {

	$columns = array(
		'cb' 			=> '<input type="checkbox" />',
		'title' 		=> __( 'Tên Slider' ),
		'shortcode'	=> __( 'Shortcode'),	
	);

	return $columns;
}
//Add content to colum
add_action( 'manage_bx_slider_posts_custom_column', 'my_manage_bx_slider_columns', 10, 2 );
function my_manage_bx_slider_columns( $column, $post_id ) {
	global $post;

	switch( $column ) {
		case 'shortcode' :
			_e('<code>[bxslider id="'.$post_id.'"]</code>');
			break;
		default :
			break;
	}
}
?>