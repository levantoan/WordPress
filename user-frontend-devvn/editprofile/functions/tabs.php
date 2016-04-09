<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
function userdevvn_add_profile_tab( $tabs ) {
	
	$tabs[] = array(
		'id' => 'profile',
		'label' => 'Profile',
		'tab_class' => 'profile-tab',
		'content_class' => 'profile-content',
		'callback' => 'userdevvn_profile_tab_content'
	);
	
	return $tabs;
	
}

add_filter( 'userdevvn_tabs', 'userdevvn_add_profile_tab', 10 );

function userdevvn_add_password_tab( $tabs ) {
	
	$tabs[] = array(
		'id' => 'password',
		'label' => 'Password',
		'tab_class' => 'password-tab',
		'content_class' => 'password-content',
	);
	
	return $tabs;
	
}

add_filter( 'userdevvn_tabs', 'userdevvn_add_password_tab', 20 );