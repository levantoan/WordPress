<?php
// Register Custom Post Type
function resources_func() {

	$labels = array(
		'name'                  => 'Resources',
		'singular_name'         => 'Resources',
		'menu_name'             => 'Resources',
		'name_admin_bar'        => 'Resources',
		'archives'              => 'Item Archives',
		'parent_item_colon'     => 'Parent Item:',
		'all_items'             => 'All Items',
		'add_new_item'          => 'Add New Item',
		'add_new'               => 'Add New',
		'new_item'              => 'New Item',
		'edit_item'             => 'Edit Item',
		'update_item'           => 'Update Item',
		'view_item'             => 'View Item',
		'search_items'          => 'Search Item',
		'not_found'             => 'Not found',
		'not_found_in_trash'    => 'Not found in Trash',
		'featured_image'        => 'Featured Image',
		'set_featured_image'    => 'Set featured image',
		'remove_featured_image' => 'Remove featured image',
		'use_featured_image'    => 'Use as featured image',
		'insert_into_item'      => 'Insert into item',
		'uploaded_to_this_item' => 'Uploaded to this item',
		'items_list'            => 'Items list',
		'items_list_navigation' => 'Items list navigation',
		'filter_items_list'     => 'Filter items list',
	);
	$args = array(
		'label'                 => 'Resources',
		'description'           => 'Post Type Description',
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'excerpt', 'thumbnail', ),
		'taxonomies'            => array( 'resources-category' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-tablet',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,		
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'all-resources', $args );

}
add_action( 'init', 'resources_func', 0 );

// Register Custom Taxonomy
function resources_taxonomy_func() {

	$labels = array(
		'name'                       => _x( 'Resources Taxonomy', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Resources Taxonomy', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Resources Taxonomy', 'text_domain' ),
		'all_items'                  => __( 'All Items', 'text_domain' ),
		'parent_item'                => __( 'Parent Item', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
		'new_item_name'              => __( 'New Item Name', 'text_domain' ),
		'add_new_item'               => __( 'Add New Item', 'text_domain' ),
		'edit_item'                  => __( 'Edit Item', 'text_domain' ),
		'update_item'                => __( 'Update Item', 'text_domain' ),
		'view_item'                  => __( 'View Item', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
		'popular_items'              => __( 'Popular Items', 'text_domain' ),
		'search_items'               => __( 'Search Items', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
		'no_terms'                   => __( 'No items', 'text_domain' ),
		'items_list'                 => __( 'Items list', 'text_domain' ),
		'items_list_navigation'      => __( 'Items list navigation', 'text_domain' ),
	);
	$rewrite = array(
		'slug'                       => 'all-resources',
		'with_front'                 => true,
		'hierarchical'               => false,
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
	register_taxonomy( 'resources-category', array( 'all-resources' ), $args );

}
add_action( 'init', 'resources_taxonomy_func', 0 );

// rewrite urls
function taxonomy_slug_rewrite($wp_rewrite) {
    $rules = array();
    $taxonomies = get_taxonomies(array('_builtin' => false), 'objects');
    $post_types = get_post_types(array('public' => true, '_builtin' => false), 'objects');    
    foreach ($post_types as $post_type) {
        foreach ($taxonomies as $taxonomy) {
            foreach ($taxonomy->object_type as $object_type) {
                if ($object_type == $post_type->rewrite['slug']) {
                    $terms = get_categories(array('type' => $object_type, 'taxonomy' => $taxonomy->name, 'hide_empty' => 0));
                    foreach ($terms as $term) {
                        $rules[$object_type . '/' . $term->slug . '/?$'] = 'index.php?' . $term->taxonomy . '=' . $term->slug;
                    }
                }
            }
        }
    }
    $wp_rewrite->rules = $rules + $wp_rewrite->rules;
}
add_filter('generate_rewrite_rules', 'taxonomy_slug_rewrite');

function resources_display_count_metabox() {
    add_meta_box( 
	    'resources-display-count',
	    __( 'Resources Download', 'devvn' ),
	    'resources_display_count_callback',
	    'all-resources',
	    'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'resources_display_count_metabox' );
function resources_display_count_callback( $post ) {
    $userDownload = get_download_resources('file_id',$post->ID);
    if(is_array($userDownload)):
    	echo '<table class="og_table">';
    	echo '<tr>
    			<th>ID</th>
    			<th>User Email</th>
    			<th>Time Download</th>
    		  </tr>
    	';
    	$stt = 1; foreach ($userDownload as $user):
    		if(!check_exits_post_by_id($user->user_id)) continue;
    		echo '<tr>
	    			<td>'.$stt.'</td>
	    			<td><a href="'.get_edit_post_link($user->user_id).'">'.get_user_email_register($user->user_id).'</a></td>
	    			<td>'.get_date_from_gmt($user->time_down, 'F j, Y H:i:s').'</td>
	    		  </tr>
	    	';
    	$stt++; endforeach;
    	echo '</table>';
    	echo '<style>table.og_table {
		    margin: 0 0 1.5em;
		    width: 100%;
		    border-spacing: 0;
			border-collapse: collapse;
		}
		
		table.og_table th, table.og_table td {
		    font-weight: normal;
		    text-align: left;
		}
		table.og_table th, table.og_table td {
		    border: 1px solid #eaeaea;
		    padding: 6px 10px;
		}
		table.og_table th {
		    font-weight: bold;
		}
		table.og_table tr {
		    border-bottom: 1px solid #ddd;
		}
		table.og_table tr:nth-child(even) {
		    background-color: #f1f1f1;
		}</style>';
    else :
    	echo 'File no download!';
    endif;
}