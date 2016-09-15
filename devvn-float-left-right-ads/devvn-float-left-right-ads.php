<?php
/*
 * Plugin Name: DevVn Float Left Right Ads
 * Plugin URI: http://levantoan.com
 * Version: 1.0
 * Description: Float Advertising on Left and Right.
 * Author: Le Van Toan
 * Author URI: http://levantoan.com
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!class_exists('flra_options')){
class flra_options {
	public $_version = '1.0';
	public $_optionName = 'flra_options';
	public $_optionGroup = 'flra-options-group';
	public $_defaultOptions = array(
	    'flra_is_active' 	=>	'1',
		'screen_w'			=>	'1024',
		'content_w'			=>	'1000',
		'show_on_mobile'	=>	0,
		'banner_left_w'		=>	118,
		'banner_left_h'		=>	558,
		'banner_right_w'	=>	118,
		'banner_right_h'	=>	558,
		'margin_l'			=>	10,
		'margin_r'			=>	10,
		'margin_t'			=>	50,
		'margin_t_scroll'	=>	0,
		'z_index'			=>	999,
		'html_code_l'		=>	'<a href="http://yourwebsite.com" target="_blank"><img src="https://placeholdit.imgix.net/~text?txtsize=30&txt=ADS%20118x558&w=118&h=558" alt="" /></a>',
		'html_code_r'		=>	'<a href="http://yourwebsite.com" target="_blank"><img src="https://placeholdit.imgix.net/~text?txtsize=30&txt=ADS%20118x558&w=118&h=558" alt="" /></a>',
	);

	function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_mysettings') );
		add_action( 'wp_enqueue_scripts', array($this, 'flra_scripts') );
		add_action('wp_footer', array($this, 'flra_footer' ));
		
		register_activation_hook(__FILE__, array( $this, 'plugin_install' ));
		register_deactivation_hook( __FILE__, array( $this, 'plugin_deactive' ));
	}
	function admin_menu() {
		add_options_page(
			__('Float Left Right Ads','devvn'), 
			__('Float Left Right Ads','devvn'),
			'manage_options',
			'float-left-right-ads',
			array(
				$this,
				'svl_flra_setting'
			)
		);
	}
	
	function register_mysettings() {
		register_setting( $this->_optionGroup, $this->_optionName );
	}

	function  svl_flra_setting() {
		include 'inc/options-page.php';
	}
	
	function flra_scripts(){
		$flra_options = wp_parse_args(get_option($this->_optionName),$this->_defaultOptions);
		if($flra_options['flra_is_active'] == 1){
			if($flra_options['show_on_mobile'] == 0 && wp_is_mobile()) return;
	    	wp_enqueue_script( 'flra-script', plugins_url('/float-left-right.js',__FILE__), array(), $this->_version, true );	    	
	    	$array = array(
	    		'MainContentW' 		=> $flra_options['content_w'],
                'LeftBannerW' 		=> $flra_options['banner_left_w'],
                'RightBannerW' 		=> $flra_options['banner_right_w'],
                'LeftAdjust' 		=> $flra_options['margin_l'],
                'RightAdjust' 		=> $flra_options['margin_r'],
                'TopAdjust' 		=> $flra_options['margin_t'],
                'TopAdjustScroll' 	=> $flra_options['margin_t_scroll'],
	    	);
	    	wp_localize_script('flra-script', 'flra_array', $array);
		}
	}
	
	function flra_footer(){
		$flra_options = wp_parse_args(get_option($this->_optionName),$this->_defaultOptions);
		if($flra_options['flra_is_active'] == 1){
			if($flra_options['show_on_mobile'] == 0 && wp_is_mobile()) return;
			?>
			<div id="divFLRARight" style="display: none;position: absolute;top: 0px;width:<?=$flra_options['banner_right_w']?>px;<?=($flra_options['banner_right_h'])?'height:'.$flra_options['banner_right_h'].'px;':''?>overflow:hidden;z-index:<?=$flra_options['z_index']?>;"><?php echo html_entity_decode($flra_options['html_code_r']); ?></div>
			<div id="divFLRALeft" style="display: none;position: absolute;top: 0px;width:<?=$flra_options['banner_left_w']?>px;<?=($flra_options['banner_left_h'])?'height:'.$flra_options['banner_left_h'].'px;':''?>overflow:hidden;z-index:<?=$flra_options['z_index']?>;"><?php echo html_entity_decode($flra_options['html_code_l']); ?></div>			
			<?php
		}
	}
	
	function plugin_install(){
		add_option( $this->_optionName, $this->_defaultOptions );
	}
	
	function plugin_deactive(){
		//delete_option( $this->_optionName );
		register_uninstall_hook( __FILE__, array( $this,'plugin_remove') );
	}
	
	function plugin_remove(){
		delete_option( $this->_optionName );		
	}
}

new flra_options;
}