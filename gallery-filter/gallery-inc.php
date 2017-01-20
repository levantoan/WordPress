<?php
define('GALLERY_NUMBER_POSTS_PERPAGE', 50);
// Register Custom Post Type
function svl_gallery_func() {

	$labels = array(
		'name'                  => _x( 'Gallery Filter', 'Post Type General Name', 'devvn' ),
		'singular_name'         => _x( 'Gallery Filter', 'Post Type Singular Name', 'devvn' ),
		'menu_name'             => __( 'Gallery Filter', 'devvn' ),
		'name_admin_bar'        => __( 'Gallery Filter', 'devvn' ),
		'archives'              => __( 'Gallery', 'devvn' ),
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
		'slug'                  => 'exhibitor-catalogue',
		'with_front'            => true,
		'pages'                 => true,
		'feeds'                 => false,
	);
	$args = array(
		'label'                 => __( 'Gallery Filter', 'devvn' ),
		'description'           => __( 'Post Type Description', 'devvn' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'thumbnail', ),
		'taxonomies'            => array( 'gallery-country', ' gallery-company' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-images-alt',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,		
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'rewrite'               => $rewrite,
		'capability_type'       => 'page',
	);
	register_post_type( 'gallery', $args );

}
add_action( 'init', 'svl_gallery_func', 0 );

// Register Custom Taxonomy
function gallery_company_func() {

	$labels = array(
		'name'                       => _x( 'Company', 'Taxonomy General Name', 'devvn' ),
		'singular_name'              => _x( 'Company', 'Taxonomy Singular Name', 'devvn' ),
		'menu_name'                  => __( 'Company', 'devvn' ),
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
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'gallery-company', array( 'gallery' ), $args );

}
add_action( 'init', 'gallery_company_func', 0 );

// Register Custom Taxonomy
function gallery_country_func() {

	$labels = array(
		'name'                       => _x( 'Country', 'Taxonomy General Name', 'devvn' ),
		'singular_name'              => _x( 'Country', 'Taxonomy Singular Name', 'devvn' ),
		'menu_name'                  => __( 'Country', 'devvn' ),
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
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'gallery-country', array( 'gallery' ), $args );

}
add_action( 'init', 'gallery_country_func', 0 );

/*Country & company metabox*/
function devvn_gallery_meta_box() {
	add_meta_box(
		'devvn-gallery-metabox',
		__( 'Gallery Option', 'devvn' ),
		'devvn_gallery_meta_box_callback',
		'gallery',
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', 'devvn_gallery_meta_box' );
function devvn_gallery_meta_box_callback($post, $metabox){
	wp_nonce_field( 'gallery_save_meta_box_data', 'gallery_meta_box_nonce' );
	$gallery_title_post = get_post_meta( $post->ID, 'gallery_title_post', true );
	$gallery_website = get_post_meta( $post->ID, 'gallery_website', true );
	?>
	<p>
	    <label for="gallery-title-post"><strong><?php _e( "Add title", 'devvn' ); ?></strong></label>
	</p>
	<table class="gallery_title">
		<?php if(is_array($gallery_title_post) && !empty($gallery_title_post)):?>
			<?php foreach ($gallery_title_post as $fiels):?>
			<tr class="clone">
				<td><input placeholder="<?php _e('Type a title','devvn')?>" class="widefat" type="text" name="gallery_title_post[]" id="gallery-title-post" value="<?php echo $fiels;?>" size="30" /></td>
				<td><a href="#" class="gallery-remove-field"><?php _e('Delete','devvn')?></a></td>
			</tr>		
			<?php endforeach;?>
		<?php else:?>
		<tr class="clone">
			<td><input placeholder="<?php _e('Type a title','devvn')?>" class="widefat" type="text" name="gallery_title_post[]" id="gallery-title-post" value="" size="30" /></td>
			<td><a href="#" class="gallery-remove-field"><?php _e('Delete','devvn')?></a></td>
		</tr>
		<?php endif;?>
	</table>
	<p><button class="add_gallery_title button" type="button"><?php _e( "Add title", 'devvn' ); ?></button></p>
	<hr>
	<p>
	    <label for="gallery-website-link"><strong><?php _e( "Website(s)", 'devvn' ); ?></strong></label>
	</p>
	<table class="gallery_website_list">
		<?php if(is_array($gallery_website) && !empty($gallery_website)):?>
			<?php foreach ($gallery_website as $field):?>
			<tr class="clone">
				<td>
					<input placeholder="<?php _e('Type a link title','devvn');?>" class="widefat" type="text" name="gallery_website[title][]" id="gallery-website-title" value="<?php echo (isset($field['title']))?$field['title']:'';?>" size="30" /><br>
					<input placeholder="<?php _e('Type a link url','devvn');?>" class="widefat" type="text" name="gallery_website[link][]" id="gallery-website-link" value="<?php echo (isset($field['link']))?esc_url($field['link']):'';?>" size="30" />
				</td>
				<td><a href="#" class="gallery-remove-field"><?php _e('Delete','devvn')?></a></td>
			</tr>
			<?php endforeach;?>
		<?php else:?>
		<tr class="clone">
			<td>
				<input placeholder="<?php _e('Type a link title','devvn')?>" class="widefat" type="text" name="gallery_website[title][]" id="gallery-website-title" value="" size="30" /><br>
				<input placeholder="<?php _e('Type a link url','devvn')?>" class="widefat" type="text" name="gallery_website[link][]" id="gallery-website-link" value="" size="30" />
			</td>
			<td><a href="#" class="gallery-remove-field"><?php _e('Delete','devvn')?></a></td>
		</tr>
		<?php endif;?>		
	</table>
	<p><button class="add_gallery_website button" type="button"><?php _e( "Add website", 'devvn' ); ?></button></p>
	<script type="text/javascript">
	(function($){
		$(document).ready(function(){
			$('.add_gallery_title').click(function(){
				var cloneElement = $('.gallery_title .clone').eq(0).clone()				
			    //.removeClass('clone').addClass('cloned')
			    .find(':input').each(function(){
					$(this).val('');
			    }).end()
			    .appendTo('.gallery_title');
				return false;
			});
			$('.add_gallery_website').click(function(){
				var cloneElement = $('.gallery_website_list .clone').eq(0).clone()				
			    //.removeClass('clone').addClass('cloned')
			    .find(':input').each(function(){
					$(this).val('');
			    }).end()
			    .appendTo('.gallery_website_list');
				return false;
			});
			$('.gallery-remove-field').live('click',function(){				
				var parentThis = $(this).parents('tr');
				var tableThis = parentThis.parents('table');
				if(tableThis.find('tr').length > 1){
					parentThis.remove();
				}else{
					parentThis.find(':input').each(function(){
						$(this).val('');
					});
				}
				return false;
			});
		})
	})(jQuery);
	</script>	
	<?php
	/*echo '<pre>';
	print_r($post);
	print_r($metabox);*/
}
function devvn_gallery_save_meta_box_data( $post_id ) {

	if ( ! isset( $_POST['gallery_meta_box_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( $_POST['gallery_meta_box_nonce'], 'gallery_save_meta_box_data' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( isset( $_POST['post_type'] ) && 'gallery' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}
	/*do something*/	
	/* Get the posted data and sanitize it for use as an HTML class. */
  	$gallery_title_post = isset( $_POST['gallery_title_post'] ) ? devvn_sanitize_array($_POST['gallery_title_post']) : array();    	
    $gallery_website = devvn_array_to_array(isset( $_POST['gallery_website'] ) ? (array) $_POST['gallery_website'] : array());
    
  	$allFields = array(
  		'gallery_title_post'	=>	$gallery_title_post,
  		'gallery_website'		=>	$gallery_website
  	);
  	
  	foreach ($allFields as $k => $fielMeta){
  		$meta_key = $k;
  		$new_meta_value = $fielMeta;
  			
	  	$meta_value = get_post_meta( $post_id, $meta_key, true );
	  	if ( $new_meta_value && '' == $meta_value )
	    	add_post_meta( $post_id, $meta_key, $new_meta_value, true );
	  	elseif ( $new_meta_value && $new_meta_value != $meta_value )
	    	update_post_meta( $post_id, $meta_key, $new_meta_value );
	  	elseif ( '' == $new_meta_value && $meta_value )
	    	delete_post_meta( $post_id, $meta_key, $meta_value );
  	}    
    	
    	
}
add_action( 'save_post', 'devvn_gallery_save_meta_box_data' );

function devvn_array_to_array($aInput = array()){	
	$aOutput =  array();		
	$firstKey;
	foreach ($aInput as $key => $value){
		$firstKey = $key;
		break;
	}
	$nCountKey = count($aInput[$firstKey]);
	for ($i =0; $i<$nCountKey;$i++){
		$element;
		foreach ($aInput as $key => $value){
			$element[$key] = sanitize_text_field($value[$i]);
		}
		array_push($aOutput,$element);
	}
	return wp_unslash($aOutput);
}

function devvn_sanitize_array($array = array()){
	if(!is_array($array)) return false;
	$array = array_map( 'sanitize_text_field', wp_unslash( $array ) );
	return $array;
}

function devvn_get_featured_image($post_ID) {
    $post_thumbnail_id = get_post_thumbnail_id($post_ID);
    if ($post_thumbnail_id) {
        $post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'thumbnail');
        return $post_thumbnail_img[0];
    }
    return false;
}

// ADD thumbnail COLUMN
function devvn_columns_head($defaults) {
    $defaults['featured_image'] = 'Featured Image';
    return $defaults;
}
function devvn_columns_content($column_name, $post_ID) {
    if ($column_name == 'featured_image') {
        $post_featured_image = devvn_get_featured_image($post_ID);
        if ($post_featured_image) {
            echo '<img style="max-width: 50px; height: auto;" src="' . $post_featured_image . '" />';
        }
    }
}
add_filter('manage_gallery_posts_columns', 'devvn_columns_head');
add_action('manage_gallery_posts_custom_column', 'devvn_columns_content', 10, 2);

function all_tax_to_class($postID = '', $listTax = array()){
	if(!$postID || !is_array($listTax) || empty($listTax)) return false;	
	$outPut = array();
	foreach ($listTax as $termSlug){
		$terms = get_the_terms($postID,$termSlug);
		if($terms){
			foreach ($terms as $term){
				$outPut[] = $term->slug;
			}			
		}
	}
	return array_unique($outPut);	
}

function devvn_shortcode_gallery_views_func(){
	wp_enqueue_style( 'gallery-style' );
	wp_enqueue_script( 'isotope-js' );
	wp_enqueue_script( 'gallery-script' );
	ob_start();
	?>
	<div class="gallery_filter_wrap">
		<div class="gallery_filter_nav">
			<div id="filters">
				<?php 
				$company = get_terms(array(
					'taxonomy' => 'gallery-company',
    				'hide_empty' => false,
				));
				if($company && !is_wp_error($company)):
				?>
				<ul class="filter_group" data-filter-group="company">
					<li class="all_company equalheight"><a href="#" data-filter="" class="filter_a selected"><?php _e('All','devvn')?></a></li>
					<?php foreach ($company as $company):?>
						<li class="equalheight"><a class="filter_a" href="#<?php echo sanitize_html_class($company->slug);?>" data-count="<?php echo $company->count;?>" data-filter=".<?php echo sanitize_html_class($company->slug)?>" title="<?php printf(__('Filter by %s','devvn'),$company->name);?>"><?php echo $company->name?></a></li>
					<?php endforeach;?>
				</ul>
				<?php endif;//End company?>
				<?php 
				$company = get_terms(array(
					'taxonomy' => 'gallery-country',
    				'hide_empty' => false,
				));
				if($company && !is_wp_error($company)):
				?>
				<ul class="filter_group" data-filter-group="country">
					<li class="all_country equalheight"><a href="#" data-filter="" class="filter_a selected"><?php _e('All','devvn')?></a></li>
					<?php foreach ($company as $company):?>
						<li class="equalheight"><a class="filter_a" href="#<?php echo sanitize_html_class($company->slug);?>" data-count="<?php echo $company->count;?>" data-filter=".<?php echo sanitize_html_class($company->slug)?>" title="<?php printf(__('Filter by %s','devvn'),$company->name);?>"><?php echo $company->name?></a></li>
					<?php endforeach;?>
				</ul>
				<?php endif;//End company?>
			</div>
		</div>
		<div class="gallery_filter_items">
			<?php
			$images = new WP_Query(array(
				'post_type'			=>	'gallery',
				'posts_per_page'	=>	GALLERY_NUMBER_POSTS_PERPAGE
			));
			$maxPages = $images->max_num_pages;
			if($images->have_posts()):
			?>
			<div class="gallery_filter_container">
			<div class="gallery_filter_sizer"></div>
			<?php while ($images->have_posts()):$images->the_post();?>		
			<?php include 'content-galleryItem.php';?>
			<?php endwhile;?>
			</div>
			<?php 
			if($maxPages > 1){
			$nonce = wp_create_nonce('gallery_action_nonce');
			?>
			<div class="gallery_filter_loadmore"><a href="#" data-page="1" data-nonce="<?php echo $nonce;?>"><?php _e('Load more','devvn');?></a></div>
			<?php }?>
			<?php else:?>
			<div class="no-gallery"><?php _e('No photo to load!');?></div>
			<?php endif;?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode('gallery_filter', 'devvn_shortcode_gallery_views_func');

function devvn_gallery_scripts() {
	wp_register_style( 'gallery-style', esc_url( trailingslashit( get_template_directory_uri() ) . 'gallery-filter/css/gallery-style.css' ),array(), '1.0', 'all' );
	
	wp_register_script( 'isotope-js', esc_url( trailingslashit( get_template_directory_uri() ) . 'gallery-filter/js/isotope.pkgd.min.js' ), array( 'jquery' ), '1.0', true );
	wp_register_script( 'gallery-script', esc_url( trailingslashit( get_template_directory_uri() ) . 'gallery-filter/js/gallery-mainjs.js' ), array( 'jquery' ), '1.0', true );
	$php_array = array( 
		'ajaxurl'	=>	admin_url( 'admin-ajax.php'),
		'home_url'	=>	home_url(),
		'loadmoreText'	=>	__('Load more','devvn'),
		'loading'	=>	__('Loading ...','devvn'),
		'noloading'	=>	__('No more images to load!','devvn'),
	);
	wp_localize_script( 'gallery-script', 'gallery_array', $php_array );	
}
add_action( 'wp_enqueue_scripts', 'devvn_gallery_scripts', 1 );

add_action( 'wp_ajax_gallery_load_more', 'gallery_load_more_func' );
add_action( 'wp_ajax_nopriv_gallery_load_more', 'gallery_load_more_func' );

function gallery_load_more_func() {
	if ( !wp_verify_nonce( $_REQUEST['nonce'], "gallery_action_nonce")) {
    	wp_send_json_error('Nonce Error');
   	}
   	$paged = (isset($_POST['page']))?intval($_POST['page']):1;
   	$pageMore = true;
	$images = new WP_Query(array(
		'post_type'			=>	'gallery',
		'posts_per_page'	=>	GALLERY_NUMBER_POSTS_PERPAGE,
		'paged'				=>	$paged
	));
	$maxPage = $images->max_num_pages;
	if($paged >= $maxPage) $pageMore = false;
   	ob_start();
	if($images->have_posts()):
		while ($images->have_posts()):$images->the_post();	
			include 'content-galleryItem.php';
		endwhile;
		
		$content = ob_get_clean();
		$result = array(
			'content'	=>	$content,
			'pagemore'	=>	$pageMore
		);
		wp_send_json_success($result);
	else:	
		wp_send_json_error();
	endif;
	die();
}
