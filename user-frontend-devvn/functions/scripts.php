<?php
/**
 * function userdevvn_register_scripts()
 * Dang ky toan bo jquery va css cho plugin
 */
function userdevvn_register_scripts() {
	
	/* De chac chan da co jQuery */
	wp_enqueue_script( 'jquery' );
	
	/* make a filter to allow turning off styles */
	$style_output = apply_filters( 'userdevvn_frontend_styles', true );
	
	/* if we should output styles - enqueue them */
	if( $style_output == true )
		wp_enqueue_style( 'userdevvn_style', plugins_url( 'css/userdevvn-style.css', dirname( __FILE__ ) ),array(), pluginVersion ,'all' );
	
	/* make a filter to allow turning off tab js */
	$tab_js_output = apply_filters( 'userdevvn_tabs_js', true );
	
	/* if we should output styles - enqueue them */
	if( $tab_js_output == true )
		wp_enqueue_script( 'userdevvn_script', plugins_url( 'js/userdevvn-script.js', dirname( __FILE__ ) ), array( 'jquery' ), pluginVersion, true );
	
}

add_action( 'wp_enqueue_scripts', 'userdevvn_register_scripts' );