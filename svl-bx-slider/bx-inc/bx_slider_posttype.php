<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
// Register Custom Post Type
function bx_slider_fun() {

	$labels = array(
		'name'                => _x( 'BxSlider', 'Post Type General Name', 'devvn' ),
		'singular_name'       => _x( 'BxSlider', 'Post Type Singular Name', 'devvn' ),
		'menu_name'           => __( 'BxSlider', 'devvn' ),
		'name_admin_bar'      => __( 'BxSlider', 'devvn' ),
		'parent_item_colon'   => __( 'Parent Slider:', 'devvn' ),
		'all_items'           => __( 'All Sliders', 'devvn' ),
		'add_new_item'        => __( 'Add New Slider', 'devvn' ),
		'add_new'             => __( 'Add Slider', 'devvn' ),
		'new_item'            => __( 'New Slider', 'devvn' ),
		'edit_item'           => __( 'Edit Slider', 'devvn' ),
		'update_item'         => __( 'Update Slider', 'devvn' ),
		'view_item'           => __( 'View Slider', 'devvn' ),
		'search_items'        => __( 'Search Slider', 'devvn' ),
		'not_found'           => __( 'Not found', 'devvn' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'devvn' ),
	);
	$rewrite = array(
		'slug'                => 'bx_slider',
		'with_front'          => true,
		'pages'               => true,
		'feeds'               => true,
	);
	$args = array(
		'label'               => __( 'Bx Slider', 'devvn' ),
		'description'         => __( 'Bx Slider for WordPress', 'devvn' ),
		'labels'              => $labels,
		'supports'            => array( 'title' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-slides',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => false,
		'can_export'          => false,
		'has_archive'         => false,		
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'rewrite'             => $rewrite,
		'capability_type'     => 'page',
	);
	register_post_type( 'bx_slider', $args );

}
add_action( 'init', 'bx_slider_fun', 0 );