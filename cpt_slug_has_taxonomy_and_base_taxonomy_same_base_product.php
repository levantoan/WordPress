<?php
/*
 * Taxonomy: San-pham/{danh-muc-cha}/{danh-muc-con}
 * Single CPT base: san-pham/%danhmuc_sanpham%
 * Author: http://levantoan.com
 * */
// Register Custom Post Type
function san_pham_func() {

    $labels = array(
        'name'                  => _x( 'Sản phẩm', 'Post Type General Name', 'devvn' ),
        'singular_name'         => _x( 'Sản phẩm', 'Post Type Singular Name', 'devvn' ),
        'menu_name'             => __( 'Sản phẩm', 'devvn' ),
        'name_admin_bar'        => __( 'Sản phẩm', 'devvn' ),
        'archives'              => __( 'Item Archives', 'devvn' ),
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
        'slug'                  => _x('san-pham/%danhmuc_sanpham%','slug', 'devvn'),
        'with_front'            => true,
        'pages'                 => true,
        'feeds'                 => true,
    );
    $args = array(
        'label'                 => __( 'Sản phẩm', 'devvn' ),
        'description'           => __( 'Post Type Description', 'devvn' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', 'comments', ),
        'taxonomies'            => array( 'danhmuc_sanpham' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-cart',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'rewrite'               => $rewrite,
        'capability_type'       => 'page',
    );
    register_post_type( 'san_pham', $args );

}
add_action( 'init', 'san_pham_func', 0 );

// Register Custom Taxonomy
function danhmuc_sanpham_func() {

    $labels = array(
        'name'                       => _x( 'Danh mục', 'Taxonomy General Name', 'devvn' ),
        'singular_name'              => _x( 'Danh mục', 'Taxonomy Singular Name', 'devvn' ),
        'menu_name'                  => __( 'Danh mục', 'devvn' ),
        'all_items'                  => __( 'All Items', 'devvn' ),
        'parent_item'                => __( 'Parent Item', 'devvn' ),
        'parent_item_colon'          => __( 'Parent Item:', 'devvn' ),
        'new_item_name'              => __( 'New Item Name', 'devvn' ),
        'add_new_item'               => __( 'Add New Item', 'devvn' ),
        'edit_item'                  => __( 'Edit Item', 'devvn' ),
        'update_item'                => __( 'Update Item', 'devvn' ),
        'view_item'                  => __( 'View Item', 'devvn' ),
        'separate_items_with_commas' => __( 'Separate items with commas', 'devvn' ),
        'add_or_remove_items'        => __( 'Add or remove items', 'devvn' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'devvn' ),
        'popular_items'              => __( 'Popular Items', 'devvn' ),
        'search_items'               => __( 'Search Items', 'devvn' ),
        'not_found'                  => __( 'Not Found', 'devvn' ),
        'no_terms'                   => __( 'No items', 'devvn' ),
        'items_list'                 => __( 'Items list', 'devvn' ),
        'items_list_navigation'      => __( 'Items list navigation', 'devvn' ),
    );
    $rewrite = array(
        'slug'                       => _x('san-pham','slug', 'devvn'),
        'with_front'                 => true,
        'hierarchical'               => true,
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'rewrite'                    => $rewrite,
    );
    register_taxonomy( 'danhmuc_sanpham', array( 'san_pham' ), $args );

}
add_action( 'init', 'danhmuc_sanpham_func', 0 );

function devvn_cpt_sanpham_post_type_link( $permalink, $post ) {
    // Abort if post is not a san_pham.
    if ( 'san_pham' !== $post->post_type ) {
        return $permalink;
    }

    // Abort early if the placeholder rewrite tag isn't in the generated URL.
    if ( false === strpos( $permalink, '%' ) ) {
        return $permalink;
    }

    // Get the custom taxonomy terms in use by this post.
    $terms = get_the_terms( $post->ID, 'danhmuc_sanpham' );

    if ( ! empty( $terms ) ) {
        if ( function_exists( 'wp_list_sort' ) ) {
            $terms = wp_list_sort( $terms, 'term_id', 'ASC' );
        } else {
            usort( $terms, '_usort_terms_by_ID' );
        }
        $category_object = apply_filters( 'devvn_cpt_sanpham_post_type_link_product_cat', $terms[0], $terms, $post );
        $category_object = get_term( $category_object, 'danhmuc_sanpham' );
        $product_cat     = $category_object->slug;

        if ( $category_object->parent ) {
            $ancestors = get_ancestors( $category_object->term_id, 'danhmuc_sanpham' );
            foreach ( $ancestors as $ancestor ) {
                $ancestor_object = get_term( $ancestor, 'danhmuc_sanpham' );
                $product_cat     = $ancestor_object->slug . '/' . $product_cat;
            }
        }
    } else {
        // If no terms are assigned to this post, use a string instead (can't leave the placeholder there)
        $product_cat = _x( 'khong-phan-loai', 'slug', 'devvn' );
    }

    $find = array(
        '%year%',
        '%monthnum%',
        '%day%',
        '%hour%',
        '%minute%',
        '%second%',
        '%post_id%',
        '%category%',
        '%product_cat%',
        '%danhmuc_sanpham%',
    );

    $replace = array(
        date_i18n( 'Y', strtotime( $post->post_date ) ),
        date_i18n( 'm', strtotime( $post->post_date ) ),
        date_i18n( 'd', strtotime( $post->post_date ) ),
        date_i18n( 'H', strtotime( $post->post_date ) ),
        date_i18n( 'i', strtotime( $post->post_date ) ),
        date_i18n( 's', strtotime( $post->post_date ) ),
        $post->ID,
        $product_cat,
        $product_cat,
        $product_cat,
    );

    $permalink = str_replace( $find, $replace, $permalink );

    return $permalink;
}
add_filter( 'post_type_link', 'devvn_cpt_sanpham_post_type_link', 10, 2 );

/*rewrite danh muc san pham*/
function devvn_cpt_sanpham_category_base_same_shop_base( $flash = false ){
    $terms = get_terms(array(
        'taxonomy' => 'danhmuc_sanpham',
        'hide_empty' => false,
    ));
    if ($terms && !is_wp_error($terms)) {
        $siteurl = esc_url(home_url('/'));
        foreach ($terms as $term) {
            $term_slug = $term->slug;
            $baseterm = str_replace($siteurl, '', get_term_link($term->term_id, 'danhmuc_sanpham'));

            add_rewrite_rule($baseterm . '?$','index.php?danhmuc_sanpham=' . $term_slug,'top');
            add_rewrite_rule($baseterm . 'page/([0-9]{1,})/?$', 'index.php?danhmuc_sanpham=' . $term_slug . '&paged=$matches[1]','top');
            add_rewrite_rule($baseterm . '(?:feed/)?(feed|rdf|rss|rss2|atom)/?$', 'index.php?danhmuc_sanpham=' . $term_slug . '&feed=$matches[1]','top');

        }
    }
    if ($flash == true)
        flush_rewrite_rules(false);
}
add_filter( 'init', 'devvn_cpt_sanpham_category_base_same_shop_base');

/*Sửa lỗi khi tạo mới taxomony bị 404*/
add_action( 'created_term', 'devvn_cpt_sanpham_cat_same_shop_edit_success', 10, 2 );
function devvn_cpt_sanpham_cat_same_shop_edit_success( $term_id, $taxonomy ) {
    devvn_cpt_sanpham_category_base_same_shop_base(true);
}
