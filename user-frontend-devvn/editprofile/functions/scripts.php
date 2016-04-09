<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
function userdevvn_profile_register_scripts() {
	
	/* make sure that jquery is enqueued */
	wp_enqueue_script( 'jquery' );
	
	/* make a filter to allow turning off styles */
	$style_output = apply_filters( 'userdevvn_frontend_styles', true );
	
	/* if we should output styles - enqueue them */
	if( $style_output == true )
		wp_enqueue_style( 'userdevvn_styles', plugins_url( 'css/userdevvn-style.css', dirname( __FILE__ ) ), array(), pluginVersion, 'all' );
	
	/* make a filter to allow turning off tab js */
	$tab_js_output = apply_filters( 'userdevvn_tabs_js', true );
	
	/* if we should output styles - enqueue them */
	if( $tab_js_output == true )
		wp_enqueue_script( 'userdevvn_tabs_js', plugins_url( 'js/tabs.js', dirname( __FILE__ ) ), array( 'jquery' ), pluginVersion, true );
	
}

add_action( 'wp_enqueue_scripts', 'userdevvn_profile_register_scripts' );