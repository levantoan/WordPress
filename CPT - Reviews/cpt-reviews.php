<?php
// Register Custom Post Type
function reviews_func() {

    $labels = array(
        'name'                  => _x( 'Reviews', 'Post Type General Name', 'devvn' ),
        'singular_name'         => _x( 'Reviews', 'Post Type Singular Name', 'devvn' ),
        'menu_name'             => __( 'Reviews', 'devvn' ),
        'name_admin_bar'        => __( 'Reviews', 'devvn' ),
        'archives'              => __( 'Reviews', 'devvn' ),
        'attributes'            => __( 'Item Attributes', 'devvn' ),
        'parent_item_colon'     => __( 'Parent Item:', 'devvn' ),
        'all_items'             => __( 'All Items', 'devvn' ),
        'add_new_item'          => __( 'Add New Item', 'devvn' ),
        'add_new'               => __( 'Add New', 'devvn' ),
        'new_item'              => __( 'New Item', 'devvn' ),
        'edit_item'             => __( 'Edit Item', 'devvn' ),
        'update_item'           => __( 'Update Item', 'devvn' ),
        'view_item'             => __( 'View Item', 'devvn' ),
        'view_items'            => __( 'View Items', 'devvn' ),
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
    $rewrite = array(
        'slug'                  => 'reviews',
        'with_front'            => true,
        'pages'                 => true,
        'feeds'                 => true,
    );
    $args = array(
        'label'                 => __( 'Reviews', 'devvn' ),
        'description'           => __( 'Cảm nhận học viên', 'devvn' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', ),
        'hierarchical'          => false,
        'public'                => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-money',
        'show_in_admin_bar'     => false,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'publicly_queryable'    => false,
        //'rewrite'               => $rewrite,
        'capability_type'       => 'page',
    );
    register_post_type( 'reviews', $args );

}
add_action( 'init', 'reviews_func', 0 );

if( function_exists('acf_add_local_field_group') ):
    acf_add_local_field_group(array (
        'key' => 'group_58ef4dc900036',
        'title' => 'Reviews Option',
        'fields' => array (
            array (
                'key' => 'field_58ef4dd55d6a7',
                'label' => 'Position',
                'name' => 'position',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array (
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
            ),
        ),
        'location' => array (
            array (
                array (
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'reviews',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => 1,
        'description' => '',
    ));
endif;

/*Remove column Yoast SEO*/
function devvn_reviews_columns_filter( $columns ) {
    unset($columns['wpseo-title']);
    unset($columns['wpseo-score']);
    unset($columns['wpseo-metadesc']);
    unset($columns['wpseo-focuskw']);
    unset($columns['wpseo-score-readability']);
    return $columns;
}
add_filter( 'manage_edit-reviews_columns', 'devvn_reviews_columns_filter',10, 1 );

add_action('add_meta_boxes_reviews', 'devvn_reviews_remove_taxonomies_metaboxes', 999);
function devvn_reviews_remove_taxonomies_metaboxes()
{
    remove_meta_box('bawpvc_meta_box', 'reviews', 'side');
    remove_meta_box('mymetabox_revslider_0', 'reviews', 'normal');
}
add_filter( 'manage_edit-reviews_columns', 'devvn_reviews_edit_orders_columns' ) ;
function devvn_reviews_edit_orders_columns( $columns ) {
    $columns = array(
        'cb' 			=> '<input type="checkbox" />',
        'title' 		=> __( 'Name','devvn' ),
        'thumbnail'     =>  __('Thumbnail','devvn'),
        'reviews'     =>  __('Reviews','devvn'),
        'date'          => __('Date','devvn')
    );
    return $columns;
}
add_action( 'manage_reviews_posts_custom_column', 'devvn_reviews_manage_orders_columns', 10, 2 );
function devvn_reviews_manage_orders_columns( $column, $post_id ) {
    switch( $column ) {
        case 'thumbnail' :
            if(has_post_thumbnail($post_id)){
                echo get_the_post_thumbnail($post_id,array(80,80));
            }
            break;
        case 'reviews' :
            echo get_the_excerpt();
            break;
        default :
            break;
    }
}
function devvn_reviews_admin_head() {
    $current_screen = get_current_screen();
    if ( isset( $current_screen->post_type ) && $current_screen->post_type == 'reviews' ) {?>
        <style>
        @media (min-width: 678px){
            th#thumbnail {width: 80px;}
            th#title {width: 220px;}
        }
        </style>
    <?php }
}
add_action( 'admin_head', 'devvn_reviews_admin_head' );