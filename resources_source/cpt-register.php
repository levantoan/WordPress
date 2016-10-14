<?php
// Register Custom Post Type
function register_func() {

	$labels = array(
		'name'                  => 'Register',
		'singular_name'         => 'Register',
		'menu_name'             => 'Register',
		'name_admin_bar'        => 'Register',
		'archives'              => 'Item Archives',
		'parent_item_colon'     => 'Parent Item:',
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
		'label'                 => 'Register',
		'description'           => 'Post Type Description',
		'labels'                => $labels,
		'supports'              => array( 'title', ),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => 'edit.php?post_type=all-resources',
		'menu_position'         => 5,
		'show_in_admin_bar'     => false,
		'show_in_nav_menus'     => true,
		'can_export'            => false,
		'has_archive'           => false,		
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'register', $args );

}
add_action( 'init', 'register_func', 0 );

//Add admin inline style
function devvn_ihotspot_admin_css() {
	global $post_type;
	$post_types = array(
		'register'
	);
	if(in_array($post_type, $post_types))
		echo '<style type="text/css">#post-preview, #view-post-btn,#message.notice-success a{display: none;}</style>';
}
add_action( 'admin_head-post-new.php', 'devvn_ihotspot_admin_css' );
add_action( 'admin_head-post.php', 'devvn_ihotspot_admin_css' );
//Add row to admin column
add_filter( 'page_row_actions', 'devvn_ihotspot_row_actions', 10, 2 );
add_filter( 'post_row_actions', 'devvn_ihotspot_row_actions', 10, 2 );
function devvn_ihotspot_row_actions( $actions, $post ) {
	if($post->post_type == 'register'){
	    unset( $actions['inline hide-if-no-js'] );
	    unset( $actions['view'] );
	}
    return $actions;
}
//Add new column
function devvn_ihotspot_cpt_admin_columns( $columns ) {
	$columns = array(
		'cb' 			=> '<input type="checkbox" />',
		'title' 		=> __( 'Title','devvn' ),	
		'email' 		=> __( 'Email','devvn' ),
		'company' 		=> __( 'Company/institution','devvn' ),
		'date' 			=> __( 'Date','devvn' ),	
	);
	return $columns;
}
add_filter( 'manage_edit-register_columns', 'devvn_ihotspot_cpt_admin_columns' ) ;
//Add content to colum
function devvn_ihotspot_manage_points_image_columns( $column, $post_id ) {
	global $post;
	switch( $column ) {
		case 'email' :
			the_field('email_user',$post->ID);
			break;
		case 'company' :
			the_field('company_user',$post->ID);
			break;	
		default :
			break;
	}
}
add_action( 'manage_register_posts_custom_column', 'devvn_ihotspot_manage_points_image_columns', 10, 2 );

function user_display_count_metabox() {
    add_meta_box( 
	    'file-display-count',
	    __( 'File Download', 'devvn' ),
	    'user_display_count_callback',
	    'register',
	    'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'user_display_count_metabox' );
function user_display_count_callback( $post ) {
    $userDownload = get_download_resources('user_id',$post->ID);
    if(is_array($userDownload)):
    	echo '<table class="og_table">';
    	echo '<tr>
    			<th>ID</th>
    			<th>User Email</th>
    			<th>Time Download</th>
    		  </tr>
    	';
    	$stt = 1; foreach ($userDownload as $user):
    		if(!check_exits_post_by_id($user->file_id,'all-resources')) continue;
    		echo '<tr>
	    			<td>'.$stt.'</td>
	    			<td><a href="'.get_edit_post_link($user->file_id).'">'.get_the_title($user->file_id).'</a></td>
	    			<td>'.get_date_from_gmt($user->time_down, 'F j, Y H:i:s').'</td>
	    		  </tr>
	    	';
    	$stt++;endforeach;
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
    	echo 'User no download!';
    endif;
}