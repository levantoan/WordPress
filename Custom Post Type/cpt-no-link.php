<?php

add_action( 'init', 'solar_register_func', 0 );
function solar_register_func() {

    $labels = array(
        'name'                  => _x( 'Đăng ký tư vấn', 'Post Type General Name', 'devvn-solar' ),
        'singular_name'         => _x( 'Đăng ký tư vấn', 'Post Type Singular Name', 'devvn-solar' ),
        'menu_name'             => __( 'Đăng ký tư vấn', 'devvn-solar' ),
        'name_admin_bar'        => __( 'Đăng ký tư vấn', 'devvn-solar' ),
        'archives'              => __( 'Item Archives', 'devvn-solar' ),
        'attributes'            => __( 'Item Attributes', 'devvn-solar' ),
        'parent_item_colon'     => __( 'Parent Item:', 'devvn-solar' ),
        'all_items'             => __( 'All Items', 'devvn-solar' ),
        'add_new_item'          => __( 'Add New Item', 'devvn-solar' ),
        'add_new'               => __( 'Add New', 'devvn-solar' ),
        'new_item'              => __( 'New Item', 'devvn-solar' ),
        'edit_item'             => __( 'Edit Item', 'devvn-solar' ),
        'update_item'           => __( 'Update Item', 'devvn-solar' ),
        'view_item'             => __( 'View Item', 'devvn-solar' ),
        'view_items'            => __( 'View Items', 'devvn-solar' ),
        'search_items'          => __( 'Search Item', 'devvn-solar' ),
        'not_found'             => __( 'Not found', 'devvn-solar' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'devvn-solar' ),
        'featured_image'        => __( 'Featured Image', 'devvn-solar' ),
        'set_featured_image'    => __( 'Set featured image', 'devvn-solar' ),
        'remove_featured_image' => __( 'Remove featured image', 'devvn-solar' ),
        'use_featured_image'    => __( 'Use as featured image', 'devvn-solar' ),
        'insert_into_item'      => __( 'Insert into item', 'devvn-solar' ),
        'uploaded_to_this_item' => __( 'Uploaded to this item', 'devvn-solar' ),
        'items_list'            => __( 'Items list', 'devvn-solar' ),
        'items_list_navigation' => __( 'Items list navigation', 'devvn-solar' ),
        'filter_items_list'     => __( 'Filter items list', 'devvn-solar' ),
    );
    $args = array(
        'label'                 => __( 'Đăng ký tư vấn', 'devvn-solar' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor' ),
        'hierarchical'          => true,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-feedback',
        'show_in_admin_bar' => false,
        'show_in_nav_menus' => false,
        'can_export' => true,
        'has_archive' => false,
        'exclude_from_search' => true,
        'publicly_queryable' => false,
        'capability_type' => 'page',
        'show_in_rest'          => false,
    );
    register_post_type( 'solar_register', $args );

}
