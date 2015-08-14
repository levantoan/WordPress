<?php
/*
Plugin Name: BxSlider
Plugin URI: http://levantoan.com/
Description: jQuery bx slider for WordPress.
Author: Le Van Toan
Version: 1.0
Author URI: http://levantoan.com/
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if( !class_exists('bxslider') ):
class bxslider{
	// vars
	var $settings;
	
	function __construct()
	{
		// helpers
		add_filter('svl/helpers/get_path', array($this, 'helpers_get_path'), 1, 1);
		add_filter('svl/helpers/get_dir', array($this, 'helpers_get_dir'), 1, 1);
		
		//vars
		$this->settings = array(
			'path'				=> apply_filters('svl/helpers/get_path', __FILE__),
			'dir'				=> apply_filters('svl/helpers/get_dir', __FILE__),
			'hook'				=> basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ),
			'version'			=> '1.0.0',
			'default_img'		=> 'images/default-placeholder.png',
		);
		
		// set text domain
		load_plugin_textdomain('devvn', false, $this->settings['path'] . '/languages' );
		
		// actions
		add_action('init', array($this, 'init'), 1);
		add_action( 'add_meta_boxes', array($this, 'bxslider_meta_box') );
		add_action( 'save_post', array($this,'bxslider_meta_save') );
		add_action( 'admin_enqueue_scripts', array($this,'svl_image_enqueue') );
		add_action( 'admin_print_styles', array($this,'svl_admin_styles') );
		
		// includes
		$this->include_before_theme();
	}
	
	function init(){
		
	}
	
	/*Thêm meta box*/
	function bxslider_meta_box() {
		add_meta_box( 
			'svl_bxslider_meta', 
			__( 'BxSlider Images', 'devvn' ), 
			array($this,'bxslider_meta_box_callback'), 
			'bx_slider',
			'normal',
			'high' 
		);
	}	
	/*File metabox*/
	function bxslider_meta_box_callback( $post ){
		wp_nonce_field( basename( __FILE__ ), 'bxslider_nonce' );
		$bxslider_meta = get_post_meta( $post->ID,'bxslider_data',true );
		?>
		<div class="bx_wrap">
		<?php if(!empty($bxslider_meta) && $bxslider_meta):$stt = 0;?>
		<?php foreach ($bxslider_meta as $slider):?>
			<div id="clonedInput<?php echo $stt;?>" class="slider_list clonedInput" slider-data="<?php echo $stt;?>">
				<div class="bx_left">
					<div class="js-image">
						<img class="has_image" src="<?php echo (!empty($slider['images'])) ? $slider['images'] : $this->settings['dir'].$this->settings['default_img'];?>">
					</div>
					<input type="hidden" name="bx_image[]" class="bx_image" id="bx_image<?php echo $stt;?>" value="<?php echo $slider['images']; ?>" />
					<input type="button" id="meta-image-button" class="button" value="<?php _e( 'Chọn Ảnh', 'devvn' )?>" />
					<input type="button" id="delete-image-button" class="button" value="<?php _e( 'Xóa Ảnh', 'devvn' )?>" />
					<input type="button" id="delete-slider-button" class="button" value="<?php _e( 'Xóa Slider', 'devvn' )?>" />
				</div>
				<div class="bx_right">
					<p>					
						<input type="text" name="bx_title[]" id="bx_title" placeholder="Tiêu đề" value="<?php echo $slider['title']; ?>" />
					</p>
					<p>					
						<textarea name="bx_desc[]" id="bx_desc" placeholder="Mô tả"/><?php echo $slider['desc']; ?></textarea>
					</p>
				</div>
			</div>
			<?php $stt++;endforeach;?>
		<?php else:?>
			<div id="clonedInput0" class="slider_list clonedInput" slider-data="0">
				<div class="bx_left">
					<div class="js-image">
						<img class="has_image" src="<?php echo $this->settings['dir'].$this->settings['default_img'];?>"/>
					</div>
					<input type="hidden" name="bx_image[]" id="bx_image0" class="bx_image" value="<?php if ( isset ( $bxslider_meta ) ) echo $bxslider_meta['images']; ?>" />
					<input type="button" id="meta-image-button" class="button" value="<?php _e( 'Chọn Ảnh', 'devvn' )?>" />
					<input type="button" id="delete-image-button" class="button" value="<?php _e( 'Xóa Ảnh', 'devvn' )?>" />
					<input type="button" id="delete-slider-button" class="button" value="<?php _e( 'Xóa Slider', 'devvn' )?>" />
				</div>
				<div class="bx_right">
					<p>					
						<input type="text" name="bx_title[]" id="bx_title" placeholder="Tiêu đề" />
					</p>
					<p>					
						<textarea name="bx_desc[]" id="bx_desc" placeholder="Mô tả"/></textarea>
					</p>
				</div>
			</div>
		<?php endif;?>
		</div>
		<?php $this->chuc_nang_func();?>
		<pre>
		<?php print_r($bxslider_meta)?>
		</pre>
		<?php 
	}
	/*Chức năng*/
	function chuc_nang_func(){
	?>
		<div class="them_img"><input type="button" id="them-slider-button" class="button" value="<?php _e( 'Thêm Slider', 'devvn' )?>" /></div>
	<?php
	}
	/*Lưu meta box*/
	function bxslider_meta_save( $post_id ){
		// Checks save status
		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST[ 'bxslider_nonce' ] ) && wp_verify_nonce( $_POST[ 'bxslider_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
	 
		// Exits script depending on save status
		if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
			return;
		}
		
		extract($_POST);
		$meta_array = array();
		if(is_array($bx_image)):
		foreach ($bx_image as $k=>$img){
			$meta_array[$k] = array(
				'images'	=>	$img,
				'title'		=>	$bx_title[$k],
				'desc'		=>	$bx_desc[$k]
			);
		}
		endif;
		update_post_meta($post_id,'bxslider_data',$meta_array);
	}
	/*Thêm jquery admin*/
	function svl_image_enqueue() {
		global $typenow;
		if( $typenow == 'bx_slider' ) {
			wp_enqueue_media();
	 
			// Registers and enqueues the required javascript.
			wp_register_script( 'meta-box-bximage', plugin_dir_url( __FILE__ ) . 'js/meta-box-image.js', array( 'jquery' ) );
			wp_localize_script( 'meta-box-bximage', 'meta_image',
				array(
					'title' 		=> __( 'Chọn hoặc tải ảnh', 'devvn' ),
					'button' 		=> __( 'Sử dụng ảnh này', 'devvn' ),
					'dir' 			=> $this->settings['dir'],
					'default_img' 	=> $this->settings['default_img'],
				)
			);
			wp_enqueue_script( 'meta-box-bximage' );
		}
	}
	/*Thêm style Admin*/
	function svl_admin_styles(){
		global $typenow;
		if( $typenow == 'bx_slider' ) {
			wp_enqueue_style( 'bx-admin-slider', plugin_dir_url( __FILE__ ) . 'css/bx-style.css' );
		}
	}
		
	function include_before_theme(){
		include_once('bx-inc/bx_slider_posttype.php');
	}
	
	function helpers_get_path( $file )
    {
        return trailingslashit(dirname($file));
    }
    /*
     * Return Url
     */
	function helpers_get_dir( $file )
    {
        $dir = trailingslashit(dirname($file));
        $count = 0;
        
        
        // sanitize for Win32 installs
        $dir = str_replace('\\' ,'/', $dir); 
        
        
        // if file is in plugins folder
        $wp_plugin_dir = str_replace('\\' ,'/', WP_PLUGIN_DIR); 
        $dir = str_replace($wp_plugin_dir, plugins_url(), $dir, $count);
        
        
        if( $count < 1 )
        {
	        // if file is in wp-content folder
	        $wp_content_dir = str_replace('\\' ,'/', WP_CONTENT_DIR); 
	        $dir = str_replace($wp_content_dir, content_url(), $dir, $count);
        }
        
        
        if( $count < 1 )
        {
	        // if file is in ??? folder
	        $wp_dir = str_replace('\\' ,'/', ABSPATH); 
	        $dir = str_replace($wp_dir, site_url('/'), $dir);
        }
        

        return $dir;
    }
}
function bxslider()
{
	global $bxslider;
	
	if( !isset($bxslider) )
	{
		$bxslider = new bxslider();
	}
	
	return $bxslider;
}
// initialize
bxslider();
endif;