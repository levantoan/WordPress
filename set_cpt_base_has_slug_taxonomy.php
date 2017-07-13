<?php
/*
Author: levantoan.com
Set CPT
'rewrite' = array(
    'slug'  => 'san-pham/%danhmuc_sanpham%'
);

SET Taxonomy
'rewrite' = array(
    'hierarchical'  => true, 
);
*/
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
        'slug'                  => 'san-pham/%danhmuc_sanpham%',
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
        'slug'                       => 'danh-muc',
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

/*Xóa san-pham và thêm base taxonomy vào custom post type*/
function devvn_filter_post_type_link($link, $post)
{
    if ($post->post_type != 'san_pham')
        return $link;
    if ($cats = get_the_terms($post->ID, 'danhmuc_sanpham')){
        $link = str_replace('san-pham/%danhmuc_sanpham%', devvn_get_term_base(array_pop($cats)->term_id, 'danhmuc_sanpham'), $link);
    }else{
        $link = str_replace('/%danhmuc_sanpham%', '', $link);
    }
    return $link;
}
add_filter('post_type_link', 'devvn_filter_post_type_link', 10, 2);

function devvn_get_term_base( $term, $taxonomy = '' ) {
    if ( !is_object($term) ) {
        if ( is_int( $term ) ) {
            $term = get_term( $term, $taxonomy );
        } else {
            $term = get_term_by( 'slug', $term, $taxonomy );
        }
    }
    if ( !is_object($term) )
        $term = new WP_Error('invalid_term', __('Empty Term'));
    if ( is_wp_error( $term ) )
        return $term;
    $taxonomy = $term->taxonomy;
    $slug = $term->slug;
    $t = get_taxonomy($taxonomy);
    if ( $t->rewrite['hierarchical'] ) {
        $hierarchical_slugs = array();
        $ancestors = get_ancestors( $term->term_id, $taxonomy, 'taxonomy' );
        foreach ( (array)$ancestors as $ancestor ) {
            $ancestor_term = get_term($ancestor, $taxonomy);
            $hierarchical_slugs[] = $ancestor_term->slug;
        }
        $hierarchical_slugs = array_reverse($hierarchical_slugs);
        $hierarchical_slugs[] = $slug;
        $term_slug = implode('/', $hierarchical_slugs);
    } else {
        $term_slug = $slug;
    }
    return $term_slug;
}

/*Sửa lỗi 404 sau khi đã remove slug product hoặc cua-hang*/
function devvn_woo_sanpham_rewrite_rules($flash = false) {
    global $wp_post_types, $wpdb;
    $siteLink = esc_url(home_url('/'));
    foreach ($wp_post_types as $type=>$custom_post) {
        if($type == 'san_pham'){
            if ($custom_post->_builtin == false) {
                $querystr = "SELECT {$wpdb->posts}.post_name, {$wpdb->posts}.ID
                            FROM {$wpdb->posts} 
                            WHERE {$wpdb->posts}.post_status = 'publish' 
                            AND {$wpdb->posts}.post_type = '{$type}'";
                $posts = $wpdb->get_results($querystr, OBJECT);
                foreach ($posts as $post) {
                    $current_slug = get_permalink($post->ID);
                    $base_product = str_replace($siteLink,'',$current_slug);
                    add_rewrite_rule($base_product.'?$', "index.php?{$custom_post->query_var}={$post->post_name}", 'top');
                }
            }
        }
    }
    if ($flash == true)
        flush_rewrite_rules(false);
}
add_action('init', 'devvn_woo_sanpham_rewrite_rules');
/*Fix lỗi khi tạo sản phẩm mới bị 404*/
function devvn_woo_new_sanpham_post_save($post_id){
    global $wp_post_types;
    $post_type = get_post_type($post_id);
    foreach ($wp_post_types as $type=>$custom_post) {
        if ($custom_post->_builtin == false && $type == $post_type) {
            devvn_woo_sanpham_rewrite_rules(true);
        }
    }
}
add_action('wp_insert_post', 'devvn_woo_new_sanpham_post_save');

/*
* Remove danh-muc in URL
*/
add_filter( 'term_link', 'devvn_product_cat_permalink', 10, 3 );
function devvn_product_cat_permalink( $url, $term, $taxonomy ){
    switch ($taxonomy):
        case 'danhmuc_sanpham':
            $taxonomy_slug = 'danh-muc';
            if(strpos($url, $taxonomy_slug) === FALSE) break;
            $url = str_replace('/' . $taxonomy_slug, '', $url);
            break;
    endswitch;
    return $url;
}
// Add our custom product cat rewrite rules
function devvn_product_category_rewrite_rules($flash = false) {
    $terms = get_terms( array(
        'taxonomy' => 'danhmuc_sanpham',
        'post_type' => 'san_pham',
        'hide_empty' => false,
    ));
    if($terms && !is_wp_error($terms)){
        $siteurl = esc_url(home_url('/'));
        foreach ($terms as $term){
            $term_slug = $term->slug;
            $baseterm = str_replace($siteurl,'',get_term_link($term->term_id,'danhmuc_sanpham'));
            add_rewrite_rule($baseterm.'?$','index.php?danhmuc_sanpham='.$term_slug,'top');
            add_rewrite_rule($baseterm.'page/([0-9]{1,})/?$', 'index.php?danhmuc_sanpham='.$term_slug.'&paged=$matches[1]','top');
            add_rewrite_rule($baseterm.'(?:feed/)?(feed|rdf|rss|rss2|atom)/?$', 'index.php?danhmuc_sanpham='.$term_slug.'&feed=$matches[1]','top');
        }
    }
    if ($flash == true)
        flush_rewrite_rules(false);
}
add_action('init', 'devvn_product_category_rewrite_rules');
/*Sửa lỗi khi tạo mới taxomony bị 404*/
add_action( 'created_danhmuc_sanpham', 'devvn_new_product_cat_edit_success', 10, 2 );
function devvn_new_product_cat_edit_success( $term_id, $taxonomy ) {
    devvn_product_category_rewrite_rules(true);
}
