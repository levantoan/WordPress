<?php
/**
 * function userdevvn_register_scripts()
 * Dang ky toan bo jquery va css cho plugin
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
function userdevvn_register_scripts() {
	
	/* De chac chan da co jQuery */
	wp_enqueue_script( 'jquery' );
	
	/* make a filter to allow turning off styles */
	$style_output = apply_filters( 'userdevvn_frontend_styles', true );
	
	/* if we should output styles - enqueue them */
	if( $style_output == true )
		wp_enqueue_style( 'userdevvn_style', plugins_url( 'css/userdevvn-style.css', dirname( __FILE__ ) ),array(), pluginVersion ,'all' );
	
	/* make a filter to allow turning off tab js */
	$validate_output = apply_filters( 'userdevvn_frontend_validate', true );
	$script_output = apply_filters( 'userdevvn_frontend_script', true );
	
	if( $validate_output == true )
		wp_enqueue_script( 'userdevvn_validate', plugins_url( 'js/jquery.validate.min.js', dirname( __FILE__ ) ), array( 'jquery' ), pluginVersion, true );
	
	if( $script_output == true ){		
		wp_enqueue_script( 'userdevvn_script', plugins_url( 'js/userdevvn-script.js', dirname( __FILE__ ) ), array( 'jquery' ), pluginVersion, true );
		wp_localize_script( 'userdevvn_script', 'userdevvn_object', array( 
	        'ajaxurl' => admin_url( 'admin-ajax.php' ),
	        'redirecturl' => home_url(),
	        'loadingmessage' => __('Sending user info, please wait...')
	    ));
	}
	
}

add_action( 'wp_enqueue_scripts', 'userdevvn_register_scripts' );