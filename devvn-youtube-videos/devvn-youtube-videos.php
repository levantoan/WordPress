<?php
/*
Plugin Name: DevVn YouTube Videos
Plugin URI: http://levantoan.com/
Description: Get YouTube Videos list.
Author: Le Van Toan
Version: 1.0
Author URI: http://levantoan.com/
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

define('YTVIDEOS_URL', plugin_dir_url( __FILE__ ));
define('YTVIDEOS_PATH', plugin_dir_path( __FILE__ ));
define('YTVIDEOS_DEFAULT_OPTION', serialize(array(
	'number_col'			=>	'4',
	'posts_per_page'		=>	'16',
	'has_sidebar'			=>	0,
	'page_has_sidebar'		=>	'all',
	'sidebar_position'		=>	'right',
	'padding_left_right'	=>	15,
	'margin_bottom'			=>	30,
	'main_width'			=>	'66.67',
	'sidebar_width'			=>	'33.33',
	'respon_1200'			=>	4,
	'respon_992'			=>	3,
	'respon_768'			=>	2
)));
function get_ytvideos_option($nameoption = ''){
	if(!$nameoption) return false;
	$ytvideos_settings = wp_parse_args(get_option('ytvideos_settings'),unserialize(YTVIDEOS_DEFAULT_OPTION));
	return (isset($ytvideos_settings[$nameoption])) ? $ytvideos_settings[$nameoption] : '';
}
function get_ytvideos_thumb($link_to = '', $title = '',$target = '_self'){
	global $post;
	$vtvideos_data = get_post_meta($post->ID,'vtvideos_data',true);
	$title = ($title) ? $title : get_the_title($post->ID);
	$link_to = ($link_to) ? $link_to : get_permalink($post->ID);
	$target = ($target) ? $target : '_self';
	if(has_post_thumbnail()){
		$thumb_url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
	}else{
		$thumb_url = 'http://img.youtube.com/vi/'.$vtvideos_data['id'].'/hqdefault.jpg';
	}
	ob_start();
	echo '<div class="videoWrapper">';	
		echo '<a class="vtvideos-thumbnail" title="'.$title.'" rel="nofollow" href="'.$link_to.'" target="'.$target.'" style="background: url('.$thumb_url.') no-repeat center center;"><img src="'.$thumb_url.'" class="devvn-yt-videos"></a>';
	echo '</div>';
	return ob_get_clean();
}

function ytvideos_paginate() {
		global $wp_query;
		$big = 999999999; 
		$translated = "";
		$total = $wp_query->max_num_pages;
		if($total > 1) echo '<div class="ytvideos_paginate_links">';
		echo paginate_links( array(
			'base' 		=> str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format' 	=> '?paged=%#%',
			'current' 	=> max( 1, get_query_var('paged') ),
			'total' 	=> $wp_query->max_num_pages,
			'mid_size'	=> '10',
			'prev_text'    => __('Previous','devvn'),
			'next_text'    => __('Next','devvn'),
		) );
		if($total > 1) echo '</div>';
}

function ytvideos_pagesize( $query ) {
    if ( is_admin() || ! $query->is_main_query() )
        return;
        
    if ( is_post_type_archive( 'videos' ) ) {
        $query->set( 'posts_per_page', get_ytvideos_option('posts_per_page') );
        return;
    }
}
add_action( 'pre_get_posts', 'ytvideos_pagesize', 1 );

function class_ytvideos($class = ''){
	$class_ytvideos = array();
	if(get_ytvideos_option('has_sidebar')){
		$class_ytvideos[] = 'has_sidebar';
		if(get_ytvideos_option('sidebar_position') == 'right'){
			$class_ytvideos[] = 'ytvideos_right_sidebar';
		}else{
			$class_ytvideos[] = 'ytvideos_left_sidebar';
		}
	}
	if ( ! empty( $class ) ) {
		if ( !is_array( $class ) )
			$class = preg_split( '#\s+#', $class );
		$class_ytvideos = array_merge( $class_ytvideos, $class );
	} else {
		// Ensure that we always coerce class to being an array.
		$class = array();
	}
	$class_ytvideos = array_map( 'esc_attr', $class_ytvideos );
	$class_ytvideos = array_unique(apply_filters( 'ytvideos_class', $class_ytvideos, $class ));
	echo join(' ', $class_ytvideos);
}

function get_ytvideos_data($field = 'id'){
	global $post;
	$vtvideos_data = get_post_meta($post->ID,'vtvideos_data',true);	
	if(!is_array($vtvideos_data) && empty($vtvideos_data)) return;
	return (isset($vtvideos_data[$field]))?$vtvideos_data[$field]:'';
}

function add_breadcrums_ytvideos(){
	ob_start();
	?>
	<div class="breadcrumbs" typeof="BreadcrumbList" vocab="http://schema.org/">
	    <?php if(function_exists('bcn_display'))
	    {
	        bcn_display();
	    }?>
	</div>
	<?php
	echo ob_get_clean();
}
add_action('breadcrumbs_ytvideos', 'add_breadcrums_ytvideos');

include YTVIDEOS_PATH.'admin/inc/load-temp.php';								

// Register Custom Post Type
function videos_func() {

	$labels = array(
		'name'                  => _x( 'Videos', 'Post Type General Name', 'devvn' ),
		'singular_name'         => _x( 'Video', 'Post Type Singular Name', 'devvn' ),
		'menu_name'             => __( 'Videos', 'devvn' ),
		'name_admin_bar'        => __( 'Videos', 'devvn' ),
		'archives'              => __( 'Item Archives', 'devvn' ),
		'parent_item_colon'     => __( 'Parent Item:', 'devvn' ),
		'all_items'             => __( 'All Videos', 'devvn' ),
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

//Add new column
add_filter( 'manage_edit-videos_columns', 'my_edit_videos_columns' ) ;
function my_edit_videos_columns( $columns ) {

	$columns = array(
		'cb' 			=> '<input type="checkbox" />',
		'title' 		=> __( 'Title' ),	
		'thumnail' 		=> __( 'Thumbnail' ),
		'video_id' 		=> __( 'Video ID' ),
		'date' 			=> __( 'Date' ),
		'comments' 		=> __( 'Comments' ),	
	);

	return $columns;
}
//Add content to colum
add_action( 'manage_videos_posts_custom_column', 'my_manage_videos_columns', 10, 2 );
function my_manage_videos_columns( $column, $post_id ) {
	global $post;
	$vtvideos_data = get_post_meta($post->ID,'vtvideos_data',true);
	switch( $column ) {
		case 'thumnail' :
			echo get_ytvideos_thumb('','Watch Video','_blank');
			break;
		case 'video_id' :			
			echo $vtvideos_data['id'];
			break;		
		default :
			break;
	}
}
//Add admin style
function devvn_ytvideos_admin_styles(){
	global $typenow;
	if( $typenow == 'videos' ) {
		wp_enqueue_style( 'ytvideos-style', YTVIDEOS_URL . 'admin/css/devvn-youtube-videos.css' );
	}
}
add_action( 'admin_print_styles', 'devvn_ytvideos_admin_styles' );

//Add admin script
function devvn_ytvideos_admin_script() {
	global $typenow;
	if( $typenow == 'videos' ) {
		wp_register_script( 'ytvideos-script', YTVIDEOS_URL . 'js/maps_points.js', array( 'jquery' ) );
		wp_localize_script( 'ytvideos-script', 'ytvideos',
			array(
				'site_url'		=>	home_url()
			)
		);
		wp_enqueue_script( 'ytvideos-script' );
	}
}
add_action( 'admin_enqueue_scripts','devvn_ytvideos_admin_script' );

//Add frontend style and script
function devvn_ytvideos_frontend_styles(){
	wp_enqueue_style( 'ytvideos-style', YTVIDEOS_URL . 'assets/css/devvn-ytvideos.css' );
	$number_col = get_ytvideos_option('number_col');
	$width = round((100/$number_col),2);
	$padding_left_right = get_ytvideos_option('padding_left_right');
	$margin_bottom = get_ytvideos_option('margin_bottom');
	if(
		((is_single() && is_singular('videos') && get_post_type() == 'videos') &&
		(get_ytvideos_option('page_has_sidebar') == 'all' || get_ytvideos_option('page_has_sidebar') == 'single')) 
		||
		((is_post_type_archive( 'videos' )) &&
		(get_ytvideos_option('page_has_sidebar') == 'all' || get_ytvideos_option('page_has_sidebar') == 'archive'))
	){		
		$main_width = get_ytvideos_option('main_width');
		$sidebar_width = get_ytvideos_option('sidebar_width');
	}else{
		$main_width = '100';
		$sidebar_width = '100';
	}
	
	$respon_1200 = get_ytvideos_option('respon_1200');
	$respon_992 = get_ytvideos_option('respon_992');
	$respon_768 = get_ytvideos_option('respon_768');
	
	$width1200 = round((100/$respon_1200),2);
	$width992 = round((100/$respon_992),2);
	$width768 = round((100/$respon_768),2);
	
	$custom_css = "
                .ytvideos_row {
				    margin: 0 -{$padding_left_right}px;
				}
				.ytvideos_box {
				    padding: 0 {$padding_left_right}px;
				    width: {$width}%;
				    margin: 0 0 {$margin_bottom}px;
				    float: left;
				}
				.single_ytvideos_box{
					padding: 0 {$padding_left_right}px;
				}
				.ytvideos_list .ytvideos_box:nth-child({$number_col}n+1){
					clear: both;
				}
				.ytvideos_wrap_sidebar{
					margin: 0 0 {$margin_bottom}px;
				}
				.has_sidebar .ytvideos_wrap_main {
				    width: {$main_width}%;
				}
				.has_sidebar .ytvideos_wrap_sidebar {
				    width: {$sidebar_width}%;
				}
				.ytvideos_wrap_sidebar_box{
					padding: 0 {$padding_left_right}px;
				}
				@media (max-width: 1199px){					
					.ytvideos_box {
					    width: {$width1200}%;
					}
					.ytvideos_list .ytvideos_box:nth-child({$number_col}n+1){
						clear: none;
					}
					.ytvideos_list .ytvideos_box:nth-child({$respon_1200}n+1){
						clear: both;
					}
				}
				@media (max-width: 991px){					
					.ytvideos_box {
					    width: {$width992}%;
					}
					.ytvideos_list .ytvideos_box:nth-child({$respon_1200}n+1){
						clear: none;
					}
					.ytvideos_list .ytvideos_box:nth-child({$respon_992}n+1){
						clear: both;
					}
				}				
				@media (max-width: 767px){					
					.ytvideos_box {
					    width: {$width768}%;
					}
					.ytvideos_list .ytvideos_box:nth-child({$respon_992}n+1){
						clear: none;
					}
					.ytvideos_list .ytvideos_box:nth-child({$respon_768}n+1){
						clear: both;
					}
					.has_sidebar .ytvideos_wrap_main {
					    width: 100%;
					}
					.has_sidebar .ytvideos_wrap_sidebar {
					    width: 100%;
					}
				}
                ";
    wp_add_inline_style( 'ytvideos-style', $custom_css );
	/*
	wp_register_script( 'ytvideos-script', YTVIDEOS_URL . 'assets/js/devvn-ytvideos.js', array( 'jquery' ) );
	wp_localize_script( 'ytvideos-script', 'ytvideos',
		array(
			'site_url'		=>	home_url()
		)
	);
	wp_enqueue_script( 'ytvideos-script' );
	*/
}
add_action( 'wp_enqueue_scripts', 'devvn_ytvideos_frontend_styles' );

//Add meta box
function devvn_ytvideos_add_meta_box() {
	$screens = array( 'videos' );
	foreach ( $screens as $screen ) {		
		add_meta_box(
			'devvn-ytvideos-id',
			__( 'Youtube Video Option', 'devvn' ),
			'devvn_ytvideos_id_callback',
			$screen,
			'side',
			'high'
		);
	}
}
add_action( 'add_meta_boxes', 'devvn_ytvideos_add_meta_box' );

function devvn_ytvideos_id_callback($post){
	wp_nonce_field( 'youtube_video_id_save_meta_box_data', 'youtube_video_id_nonce' );
	$youtube_video_id = get_post_meta($post->ID,'vtvideos_data',true);	
	?>
	<p>
	<label for="videos-id">YouTube Video ID:</label>
	<input type="text" id="videos-id" class="widefat" name="vtvideos_data[id]" value="<?php echo isset($youtube_video_id['id'])?$youtube_video_id['id']:''?>" placeholder="Ex: qLb37K4jL14" maxlength="11">
	</p>
	<?php
}

function maps_points_save_meta_box_data( $post_id, $post ) {

	if ( ! isset( $_POST['vtvideos_data'] ) ) {
		return $post->ID;
	}
	if ( ! wp_verify_nonce( $_POST['youtube_video_id_nonce'], 'youtube_video_id_save_meta_box_data' ) ) {
		return $post->ID;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post->ID;
	}
	if ( isset( $_POST['post_type'] ) && 'videos' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return $post->ID;
		}
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post->ID;
		}
	}
	$vtvideos_data = wp_parse_args((isset($_POST['vtvideos_data']))?$_POST['vtvideos_data']:array(),array(
		'id'	=>	''
	));
	if(is_array($vtvideos_data)):
		if(get_post_meta($post->ID, 'vtvideos_data', FALSE)) {
			update_post_meta($post->ID, 'vtvideos_data', $vtvideos_data);
	    } else {
	    	add_post_meta($post->ID, 'vtvideos_data', $vtvideos_data);
	    }
    endif;
			
	
}
add_action( 'save_post', 'maps_points_save_meta_box_data', 1, 2 );

//Add Option page
add_action( 'admin_init', 'register_ytvideos_settings' );
function register_ytvideos_settings() {
	register_setting( 'ytvideos-settings-group', 'ytvideos_settings' );
}
add_action('admin_menu', 'devvn_vtvideos_submenu_page');
function devvn_vtvideos_submenu_page() {
	add_submenu_page( 
		'edit.php?post_type=videos', 
		__('YouTube Videos Setting','devvn'), 
		__('Setting','devvn'), 
		'manage_options', 
		'vtvideos-setting',
		'vtvideos_setting_callback'
	);
}
function vtvideos_setting_callback(){
	include_once YTVIDEOS_PATH.'admin/inc/options-page.php';
}

//install and uninstall plugin
function devvn_ytvideos_plugin_install(){
	if(!get_option( 'ytvideos_settings' )){
		add_option( 'ytvideos_settings', unserialize(YTVIDEOS_DEFAULT_OPTION));
	}
}
function devvn_ytvideos_plugin_remove(){
	delete_option( 'ytvideos_settings' );
}
register_activation_hook(__FILE__, 'devvn_ytvideos_plugin_install' );
register_deactivation_hook( __FILE__, 'devvn_ytvideos_plugin_remove' );
register_uninstall_hook( __FILE__, 'devvn_ytvideos_plugin_remove' );