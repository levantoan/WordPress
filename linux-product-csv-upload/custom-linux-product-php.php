<?php 
// Register Custom Post Type
function custom_linux_product() {

	$labels = array(
		'name'                  => _x( 'Linux Product', 'Post Type General Name', 'text_domain' ),
		'singular_name'         => _x( 'Linux Product', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'             => __( 'Linux Product', 'text_domain' ),
		'name_admin_bar'        => __( 'Linux Product', 'text_domain' ),
		'archives'              => __( 'Item Archives', 'text_domain' ),
		'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
		'all_items'             => __( 'All Items', 'text_domain' ),
		'add_new_item'          => __( 'Add New Item', 'text_domain' ),
		'add_new'               => __( 'Add New', 'text_domain' ),
		'new_item'              => __( 'New Item', 'text_domain' ),
		'edit_item'             => __( 'Edit Item', 'text_domain' ),
		'update_item'           => __( 'Update Item', 'text_domain' ),
		'view_item'             => __( 'View Item', 'text_domain' ),
		'search_items'          => __( 'Search Item', 'text_domain' ),
		'not_found'             => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		'featured_image'        => __( 'Featured Image', 'text_domain' ),
		'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
		'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
		'items_list'            => __( 'Items list', 'text_domain' ),
		'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
		'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( 'Linux Product', 'text_domain' ),
		'description'           => __( 'Linux Product Description', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array('title'),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,		
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'menu_icon'				=>	'dashicons-align-left',
	);
	register_post_type( 'linux_product', $args );

}
add_action( 'init', 'custom_linux_product', 0 );
//Change folder Upload
function edd_set_upload_dir( $upload ) {
    $upload['subdir'] = '/csv-linux-product';
    $upload['path'] = $upload['basedir'] . $upload['subdir'];
    $upload['url']  = $upload['baseurl'] . $upload['subdir'];
    return $upload;
}
function edd_change_downloads_upload_dir() {
    global $pagenow;

    if ( ! empty( $_GET['page'] ) && 'edit.php' == $pagenow ) {
        if ( 'coupons-code-export' == $_GET['page'] ) {
            add_filter( 'upload_dir', 'edd_set_upload_dir' );
        }
    }
}
add_action( 'admin_init', 'edd_change_downloads_upload_dir', 999 );
//Import CSV to linux product
function linux_product_code_export_custom_menu_page() {
    add_submenu_page(
        'edit.php?post_type=linux_product',
        'Import CSV',
    	'Import CSV',
        'manage_options',
        'coupons-code-export',
        'linux_product_export_func'
    );
}
add_action( 'admin_menu', 'linux_product_code_export_custom_menu_page' );

function linux_product_export_func(){
	?>
	<div class="wrap">
		<h1>Import Linux Product by CSV</h1>
		<form action="" method="post" enctype="multipart/form-data">
			<?php wp_nonce_field('coupons_code_nonce','coupons_nonce');?>	
			<input type="file" name="filecsv_link" id="filecsv_link" accept=".csv"/>
			<input name="export" type="submit" class="button button-primary button-large export_coupons" id="import_csv" value="Import CSV">			
		</form>
		<div class="mess_process"></div>
		<?php
		$datas = array();	
		
		if(isset($_FILES['filecsv_link'])){
			$uploadedfile = $_FILES['filecsv_link'];
			if(is_array($uploadedfile)){ 
				$ext = pathinfo($uploadedfile['name'], PATHINFO_EXTENSION);
				if($ext == 'csv' && $uploadedfile['type'] == 'application/vnd.ms-excel'){					
					
					if ( ! function_exists( 'wp_handle_upload' ) ) {
					    require_once( ABSPATH . 'wp-admin/includes/file.php' );
					}
					
					$upload_overrides = array( 'test_form' => false );
					
					$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
					
					if ( $movefile && ! isset( $movefile['error'] ) ) {
						if (($handle = @fopen($movefile['file'], "r")) !== FALSE) {
							$row = 0;						
						    while (($data = fgetcsv($handle, 1000, "|")) !== FALSE) {
						    	
						    	if($row > 0){
						    		$postStatus = creat_post_by_data($data);
						    		if($postStatus){
						    			echo $row .' - '. $postStatus;						    			
						    		}else{
						    			echo $row . ' - NOT ARRAY <br>';
						    		}
						    	}						    	
						    	$row++;		
						    	
						    }
						    fclose($handle);
						}
						wp_delete_file($movefile['file']);
					} else {
					    echo $movefile['error'];
					}
				}
			}
		}
		?>
	</div>	
	<?php
}

function check_creat_category($catName = '', $cat_parent_id = false, $taxonomy = 'category_linux'){	
	if(!$catName || ($cat_parent_id && !is_numeric($cat_parent_id))) return false;
	if(!$cat_parent_id){
		$result = get_terms( $taxonomy, array(
        	'name' => $catName,
            'hide_empty' => false,
        ));
        if(empty($result) || is_wp_error($result)){
			$result = wp_insert_term(
			  $catName,
			  $taxonomy
			);
        }else{
        	$result = array(
        		'term_id'	=>	$result[0]->term_id,
        		'term_taxonomy_id'	=>	$result[0]->term_taxonomy_id
        	);
        }
	}else{
		$result = get_terms( $taxonomy, array(
        	'name' => $catName,
			'parent'	=>	$cat_parent_id,
            'hide_empty' => false,
        ));
        if(empty($result) || is_wp_error($result)){
			$result = wp_insert_term(
			  $catName,
			  $taxonomy,
			  array(
			    'parent'=> $cat_parent_id
			  )
			);
        }else{
        	$result = array(
        		'term_id'	=>	$result[0]->term_id,
        		'term_taxonomy_id'	=>	$result[0]->term_taxonomy_id
        	);
        }
	}
	if(is_array($result) && !is_wp_error($result)) return $result;
	return false;	
}

function check_creat_category_slug($catSlug = '', $cat_parent_id = false, $taxonomy = 'category_linux'){
	if(!$catSlug || ($cat_parent_id && !is_numeric($cat_parent_id))) return false;
	if(!$cat_parent_id){
		$result = get_terms( $taxonomy, array(
        	'slug' => $catSlug,
            'hide_empty' => false,
        ));
        if(empty($result) || is_wp_error($result)){
			$result = wp_insert_term(
			  $catSlug,
			  $taxonomy
			);
        }else{
        	$result = array(
        		'term_id'	=>	$result[0]->term_id,
        		'term_taxonomy_id'	=>	$result[0]->term_taxonomy_id
        	);
        }
	}else{
		$result = get_terms( $taxonomy, array(
        	'slug' => $catSlug,
			'parent'	=>	$cat_parent_id,
            'hide_empty' => false,
        ));
        if(empty($result) || is_wp_error($result)){
			$result = wp_insert_term(
			  $catSlug,
			  $taxonomy,
			  array(
			    'parent'=> $cat_parent_id
			  )
			);
        }else{
        	$result = array(
        		'term_id'	=>	$result[0]->term_id,
        		'term_taxonomy_id'	=>	$result[0]->term_taxonomy_id
        	);
        }
	}
	if(is_array($result) && !is_wp_error($result)) return $result;
	return false;
}
function check_posts_exits($data = array()){
	$category = ($data[0])?$data[0]:'';
	$manufacturer = ($data[1])?$data[1]:'';
	$type = ($data[2])?$data[2]:'';
	$DeviceID = ($data[3])?$data[3]:'';
	$Firmware = ($data[4])?$data[4]:'';
	
	$check_product = get_posts(array(
		'post_type'	=>	'linux_product',
		'title'		=>	$type,
		'tax_query'	=>	array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'category_linux',
				'field'    => 'term_id',
				'terms'    => array($category),
			),
			array(
				'taxonomy' => 'category_linux',
				'field'    => 'term_id',
				'terms'    => array($manufacturer)
			)
		),
		'meta_query'	=>	array(
			'relation' => 'AND',
			array(
				'key'     => 'firmware',
				'value'   => $Firmware,
			),
			array(
				'key'     => 'deviceid',
				'value'   => $DeviceID,
			)
		)
	)); 
	if(!empty($check_product) && !is_wp_error($check_product)) return true;
	return false;
}
function creat_post_by_data($data = array()){
	if(empty($data) || !is_array($data)) return false;	
	if(count($data) == 7){
		$category = ($data[0])?$data[0]:'';
		$manufacturer = ($data[1])?$data[1]:'';
		$type = ($data[2])?$data[2]:'';
		$DeviceID = ($data[3])?$data[3]:'';
		$TestSignal = ($data[4])?$data[4]:'';
		$Firmware = ($data[5])?$data[5]:'';
		
		if($category && $manufacturer){
			$category_parent = check_creat_category($category);
			if(!empty($category_parent) && is_array($category_parent) && !is_wp_error($category_parent)){
				$category_child = check_creat_category($manufacturer,$category_parent['term_id']);
				if(!empty($category_child) && is_array($category_child) && !is_wp_error($category_child)){
					$TestSignal_cat = check_creat_category_slug($TestSignal,false,'statut');
					if(!empty($TestSignal_cat) && is_array($TestSignal_cat) && !is_wp_error($TestSignal_cat)){
						if(!check_posts_exits(array($category_parent['term_id'],$category_child['term_id'],$type,$DeviceID,$Firmware))){
							$my_product = array(
								'post_title'    => wp_strip_all_tags( $type ),
								'post_status'   => 'publish',
								'post_type'		=>	'linux_product',
								'tax_input' => array( 
									'category_linux'	=>	array($category_parent['term_id'], $category_child['term_id']), 
									'statut'	=>	array($TestSignal_cat['term_id']) 
								)
							);
							$product_id = wp_insert_post( $my_product );
							if($product_id){
								update_post_meta($product_id, 'firmware', $Firmware);
								update_post_meta($product_id, 'deviceid', $DeviceID);															
								
								return implode('|', $data) .' ==> <span style="color: #4CAF50;">ADD Okie</span> <br>';
							}else{															
								return implode('|', $data) .' ==> <span style="color: red;">Add Post Error</span> <br>';
							}
						}else{
							return implode('|', $data) .' ==> <span style="color: red;">Post Exits</span> <br>';
						}
					}else{
						return implode('|', $data) .' ==> <span style="color: red;">Status Error</span> <br>';
					}
				}else{
					return implode('|', $data) .' ==> <span style="color: red;">Manufacturer Error</span> <br>';
				}
			}else{
				return implode('|', $data) .' ==> <span style="color: red;">Category Error</span> <br>';
			}								        	
		}else{
			return implode('|', $data) .' ==> <span style="color: red;">Category && manufacturer Not exits</span> <br>';
		}
	}
	return false;
}