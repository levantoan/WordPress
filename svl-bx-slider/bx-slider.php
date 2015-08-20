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
		
		add_action( 'add_meta_boxes', array($this,'bxslider_meta_box') );
		add_action( 'add_meta_boxes', array($this,'bxslider_shortcode_metabox') );
		add_action( 'add_meta_boxes', array($this,'bxslider_setting_metabox') );
		
		add_shortcode( 'bxslider', array($this,'shortcode_bxslider_func') );
		
		add_action( 'save_post', array($this,'bxslider_meta_save') );
		
		add_action( 'admin_enqueue_scripts', array($this,'svl_image_enqueue') );
		add_action( 'admin_print_styles', array($this,'svl_admin_styles') );
		
		add_action( 'wp_enqueue_scripts', array($this,'publish_scripts'), 1 );
		
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
					<input type="button" id="meta-image-button" class="button" value="<?php _e( 'Chọn Ảnh', 'devvn' )?>" />
					<input type="button" id="delete-image-button" class="button" value="<?php _e( 'Xóa Ảnh', 'devvn' )?>" />
					<div class="js-image">
						<img class="has_image" src="<?php echo (!empty($slider['images'])) ? $slider['images'] : $this->settings['dir'].$this->settings['default_img'];?>">
					</div>
					<input type="hidden" name="bx_image[]" class="bx_image" id="bx_image<?php echo $stt;?>" value="<?php echo $slider['images']; ?>" />
					
					<input type="button" id="delete-slider-button" class="button" value="<?php _e( 'Xóa Slider', 'devvn' )?>" />
				</div>
				<div class="bx_right">
					<p>					
						<input type="text" name="bx_title[]" id="bx_title" placeholder="Tiêu đề" value="<?php echo $slider['title']; ?>" />
					</p>
					<p>					
						<textarea name="bx_desc[]" id="bx_desc" placeholder="Mô tả"/><?php echo $slider['desc']; ?></textarea>
					</p>
					<p>					
						<input type="text" name="bx_link[]" id="bx_link" placeholder="Link" value="<?php echo $slider['link']; ?>" />
					</p>
					<p>					
						<label><input type="checkbox" name="bx_openwindow[]" <?php echo ($slider['openwindows'] == "on") ? 'checked="checked"' : '';?>> New windows</label>
					</p>
				</div>
			</div>
			<?php $stt++;endforeach;?>
		<?php else:?>
			<div id="clonedInput0" class="slider_list clonedInput" slider-data="0">
				<div class="bx_left">
					<input type="button" id="meta-image-button" class="button" value="<?php _e( 'Chọn Ảnh', 'devvn' )?>" />
					<input type="button" id="delete-image-button" class="button" value="<?php _e( 'Xóa Ảnh', 'devvn' )?>" />
					<div class="js-image">
						<img class="has_image" src="<?php echo $this->settings['dir'].$this->settings['default_img'];?>"/>
					</div>
					<input type="hidden" name="bx_image[]" id="bx_image0" class="bx_image" value="<?php if ( isset ( $bxslider_meta ) ) echo $bxslider_meta['images']; ?>" />
					
					<input type="button" id="delete-slider-button" class="button" value="<?php _e( 'Xóa Slider', 'devvn' )?>" />
				</div>
				<div class="bx_right">
					<p>					
						<input type="text" name="bx_title[]" id="bx_title" placeholder="Tiêu đề" />
					</p>
					<p>					
						<textarea name="bx_desc[]" id="bx_desc" placeholder="Mô tả"/></textarea>
					</p>
					<p>					
						<input type="text" name="bx_link[]" id="bx_link" placeholder="Link" value="" />
					</p>
					<p>					
						<p>					
							<label><input type="checkbox" name="bx_openwindow[]"> New windows</label>
						</p>
					</p>
				</div>
			</div>
		<?php endif;?>
		</div>
		<?php $this->chuc_nang_func();?>
		<?php 
	}
	/*Chức năng*/
	function chuc_nang_func(){
	?>
		<div class="them_img"><input type="button" id="them-slider-button" class="button" value="<?php _e( 'Thêm Slider', 'devvn' )?>" /></div>
	<?php
	}
	/*Thêm shortcode meta box*/
	function bxslider_shortcode_metabox() {
		add_meta_box( 
			'bxslider_shortcode_metabox', 
			__( 'BxSlider Shortcode', 'devvn' ), 
			array($this,'bxslider_shortcode_callback'), 
			'bx_slider',
			'side',
			'high' 
		);
	}
	function bxslider_shortcode_callback( $post ){
		$postID = $post->ID;
		?>
		<code>[bxslider id="<?php echo $postID;?>"]</code>
		<?php
	}
	/*Setting metabox*/
	function bxslider_setting_metabox(){
		add_meta_box( 
			'svl_bxslider_setting_meta', 
			__( 'BxSlider Images', 'devvn' ), 
			array($this,'bxslider_setting_metabox_callback'), 
			'bx_slider',
			'normal',
			'default' 
		);
	}
	function bxslider_setting_metabox_callback($post){
		wp_nonce_field( basename( __FILE__ ), 'bxslider_nonce' );
		$bxslider_setting = get_post_meta( $post->ID,'bxslider_setting',true );
		?>
		<?php if($bxslider_setting['mode'] == ""):?>
		<p class="setting_style">					
			<label><span class="label">Mode</span>
				<select name="bxsetting_mode">
					<option value="horizontal" selected="selected">Horizontal</option>
					<option value="vertical">Vertical</option>
					<option value="fade">Fade</option>
				</select>
			</label>
		</p>
		<p class="setting_style">					
			<label><span class="label">Responsive</span>
				<input type="checkbox" name="bxsetting_responsive" checked="checked"/>
			</label>
		</p>
		<p class="setting_style">					
			<label><span class="label">Auto Play</span>
				<input type="checkbox" name="bxsetting_auto"/>
			</label>
		</p>
		<p class="setting_style">					
			<label><span class="label">adaptiveHeight</span>
				<input type="checkbox" name="bxsetting_adaptiveHeight"/>
			</label>
		</p>
		<p class="setting_style">					
			<label><span class="label">Pager</span>
				<input type="checkbox" name="bxsetting_pager" checked="checked"/>
			</label>
		</p>
		<p class="setting_style">					
			<label><span class="label">Controls</span>
				<input type="checkbox" name="bxsetting_controls" checked="checked"/>
			</label>
		</p>
		<p class="setting_style">					
			<label><span class="label">nextText</span>
				<input type="text" name="bxsetting_nexttext" value="Next"/>
			</label>
		</p>
		<p class="setting_style">					
			<label><span class="label">prevText</span>
				<input type="text" name="bxsetting_prevtext" value="Prev"/>
			</label>
		</p>
		<?php else:?>
		<p class="setting_style">					
			<label><span class="label">Mode</span>
				<select name="bxsetting_mode">
					<option value="horizontal" <?php echo ($bxslider_setting['mode'] == "horizontal")?'selected="selected"':'';?>>Horizontal</option>
					<option value="vertical" <?php echo ($bxslider_setting['mode'] == "vertical")?'selected="selected"':'';?>>Vertical</option>
					<option value="fade" <?php echo ($bxslider_setting['mode'] == "fade")?'selected="selected"':'';?>>Fade</option>
				</select>
			</label>
		</p>
		<p class="setting_style">					
			<label><span class="label">Responsive</span>
				<input type="checkbox" name="bxsetting_responsive" <?php echo ($bxslider_setting['responsive'] == "on") ? 'checked="checked"' : '';?>/>
			</label>
		</p>
		<p class="setting_style">					
			<label><span class="label">Auto Play</span>
				<input type="checkbox" name="bxsetting_auto" <?php echo ($bxslider_setting['auto'] == "on") ? 'checked="checked"' : '';?>/>
			</label>
		</p>
		<p class="setting_style">					
			<label><span class="label">adaptiveHeight</span>
				<input type="checkbox" name="bxsetting_adaptiveHeight" <?php echo ($bxslider_setting['adaptiveHeight'] == "on") ? 'checked="checked"' : '';?>/>
			</label>
		</p>
		<p class="setting_style">					
			<label><span class="label">Pager</span>
				<input type="checkbox" name="bxsetting_pager" <?php echo ($bxslider_setting['pager'] == "on") ? 'checked="checked"' : '';?>/>
			</label>
		</p>
		<p class="setting_style">					
			<label><span class="label">Controls</span>
				<input type="checkbox" name="bxsetting_controls"  <?php echo ($bxslider_setting['controls'] == "on") ? 'checked="checked"' : '';?>/>
			</label>
		</p>
		<p class="setting_style">					
			<label><span class="label">nextText</span>
				<input type="text" name="bxsetting_nexttext" value="<?php echo ($bxslider_setting['nexttext'] == "on") ? $bxslider_setting['nexttext'] : 'Next';?>"/>
			</label>
		</p>
		<p class="setting_style">					
			<label><span class="label">prevText</span>
				<input type="text" name="bxsetting_prevtext" value="<?php echo ($bxslider_setting['prevtext'] == "on") ? $bxslider_setting['prevtext'] : 'Prev';?>"/>
			</label>
		</p>
		<?php endif;?>
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
		$setting = array();
		if(is_array($bx_image)):
			$setting = array(
				'mode'			=>	$bxsetting_mode,
				'responsive'	=>	$bxsetting_responsive,
				'pager'			=>	$bxsetting_pager,
				'controls'		=>	$bxsetting_controls,
				'nexttext'		=>	$bxsetting_nexttext,
				'prevtext'		=>	$bxsetting_prevtext,
				'adaptiveHeight'=>	$bxsetting_adaptiveHeight
			);
			foreach ($bx_image as $k=>$img){
				$meta_array[$k] = array(
					'images'		=>	$img,
					'title'			=>	$bx_title[$k],
					'desc'			=>	$bx_desc[$k],
					'link'			=>	$bx_link[$k],
					'openwindows'	=>	$bx_openwindow[$k]
				);
			}
		endif;
		update_post_meta($post_id,'bxslider_data',$meta_array);
		update_post_meta($post_id,'bxslider_setting',$setting);
	}
	/*Thêm jquery admin*/
	function svl_image_enqueue() {
		global $typenow;
		if( $typenow == 'bx_slider' ) {
			wp_enqueue_media();
	 
			// Registers and enqueues the required javascript.
			wp_register_script( 'meta-box-bximage', plugin_dir_url( __FILE__ ) . 'js/meta-box-image.js', array( 'jquery' ) );
			wp_register_script( 'bx-jquery-ui', plugin_dir_url( __FILE__ ) . 'js/jquery-ui.js', array( 'jquery' ) );
			
			wp_localize_script( 'meta-box-bximage', 'meta_image',
				array(
					'title' 		=> __( 'Chọn hoặc tải ảnh', 'devvn' ),
					'button' 		=> __( 'Sử dụng ảnh này', 'devvn' ),
					'dir' 			=> $this->settings['dir'],
					'default_img' 	=> $this->settings['default_img'],
				)
			);
			wp_enqueue_script( 'meta-box-bximage' );
			wp_enqueue_script( 'bx-jquery-ui' );
		}
	}
	/*Thêm style Admin*/
	function svl_admin_styles(){
		global $typenow;
		if( $typenow == 'bx_slider' ) {
			wp_enqueue_style( 'bx-admin-slider', plugin_dir_url( __FILE__ ) . 'css/bx-style.css' );
		}
	}
	/*Publist scripts*/
	function publish_scripts(){
		/** Register JavaScript Functions File */
		wp_register_script( 'bxslider-jquery', esc_url( $this->settings['dir'] . 'bxslider/jquery.bxslider.min.js'), array( 'jquery' ), $this->settings['version'] , true );
		$php_array = array( 
			'admin_ajax' => admin_url( 'admin-ajax.php' ) 
		);
		wp_localize_script( 'bxslider-jquery', 'bx_array', $php_array );		
 		wp_enqueue_script( 'bxslider-jquery' );
 		
 		//Style
 		wp_register_style( 'bxslider-css', esc_url($this->settings['dir'] . 'bxslider/jquery.bxslider.css' ), $this->settings['version'], true );		
 		wp_enqueue_style( 'bxslider-css' );
	}	
	function include_before_theme(){
		include_once('bx-inc/bx_slider_posttype.php');
		include_once('bx-inc/columns_for_bxslider.php');
	}
	/*Shortcode BXSLIDER*/
	function shortcode_bxslider_func( $atts ) {
		$atts = shortcode_atts( array(
			'id' => '',
		), $atts, 'bxslider' );
		
		$idSlider = intval($atts['id']);
		
		$bxslider_data = get_post_meta($idSlider,'bxslider_data',true);
		$bxslider_setting = get_post_meta($idSlider,'bxslider_setting',true);
		ob_start();
		include 'bx-inc/view.php';
		$html = ob_get_clean();
		return $html;
	}
	/*Return link folder*/
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