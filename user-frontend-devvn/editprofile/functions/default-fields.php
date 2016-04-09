<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
function userdevvn_add_profile_tab_meta_fields( $fields ) {	
	$fields[] = array(
		'id' => 'user_email', 
		'label' => 'Email Address',
		'desc' => 'Edit your email address - used for resetting your password etc.',
		'type' => 'email', 
		'classes' => 'user_email',
	);
	$fields[] = array(
		'id' => 'first_name', 
		'label' => 'First Name',
		'desc' => 'Edit your first name.',
		'type' => 'text', 
		'classes' => 'first_name',
	);	
	$fields[] = array(
		'id' => 'last_name',
		'label' => 'Last Name',
		'desc' => 'Edit your last name.',
		'type' => 'text',
		'classes' => 'last_name',
	);	
	$fields[] = array(
		'id' => 'user_url', 
		'label' => 'URL',
		'desc' => 'Edit your profile associated URL.',
		'type' => 'text', 
		'classes' => 'user_url',
	);		
	$fields[] = array(
		'id' => 'description',
		'label' => 'Description/Bio',
		'desc' => 'Edit your description/bio.',
		'type' => 'wysiwyg',
		'classes' => 'description',
	);	
	return $fields;	
}
add_filter( 'userdevvn_fields_profile', 'userdevvn_add_profile_tab_meta_fields', 10 );

function userdevvn_add_password_tab_fields( $fields ) {	
	$fields[] = array(
		'id' => 'user_pass',
		'label' => 'Password',
		'desc' => 'New Password.',
		'type' => 'password',
		'classes' => 'user_pass',
	);	
	return $fields;
}
add_filter( 'userdevvn_fields_password', 'userdevvn_add_password_tab_fields', 10 );