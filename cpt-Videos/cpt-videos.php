<?php
// Register Custom Post Type
function videos_func() {

	$labels = array(
		'name'                  => _x( 'Videos', 'Post Type General Name', 'devvn' ),
		'singular_name'         => _x( 'Video', 'Post Type Singular Name', 'devvn' ),
		'menu_name'             => __( 'Videos', 'devvn' ),
		'name_admin_bar'        => __( 'Videos', 'devvn' ),
		'archives'              => __( 'Item Archives', 'devvn' ),
		'parent_item_colon'     => __( 'Parent Item:', 'devvn' ),
		'all_items'             => __( 'All Items', 'devvn' ),
		'add_new_item'          => __( 'Add New Item', 'devvn' ),
		'add_new'               => __( 'Add Video', 'devvn' ),
		'new_item'              => __( 'New Item', 'devvn' ),
		'edit_item'             => __( 'Edit Item', 'devvn' ),
		'update_item'           => __( 'Update Item', 'devvn' ),
		'view_item'             => __( 'View Item', 'devvn' ),
		'search_items'          => __( 'Search Item', 'devvn' ),
		'not_found'             => __( 'Not found', 'devvn' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'devvn' ),
		'featured_image'        => __( 'Featured Image', 'devvn' ),
		'set_featured_image'    => __( 'Set featured image', 'devvn' ),
		'remove_featured_image' => __( 'Remove featured image', 'devvn' ),
		'use_featured_image'    => __( 'Use as featured image', 'devvn' ),
		'insert_into_item'      => __( 'Insert into item', 'devvn' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'devvn' ),
		'items_list'            => __( 'Items list', 'devvn' ),
		'items_list_navigation' => __( 'Items list navigation', 'devvn' ),
		'filter_items_list'     => __( 'Filter items list', 'devvn' ),
	);
	$args = array(
		'label'                 => __( 'Video', 'devvn' ),
		'description'           => __( 'Post Type Description', 'devvn' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'comments', ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-video-alt3',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,		
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'videos', $args );

}
add_action( 'init', 'videos_func', 0 );