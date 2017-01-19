<?php
/*
* Save base64 image to folder uploads/e-sign 
*/
function base64_images_to_folder($codeBase64 = ''){
	if(strrpos($codeBase64,'data:image/png;base64,') === false) wp_send_json_error();	
	$wp_upload_dir =  wp_upload_dir();	
	// The actual folder
	$custom_upload_folder = $wp_upload_dir['basedir'] .'/e-sign/';
	$baseurl = $wp_upload_dir['baseurl'] .'/e-sign/';
	//make the dir
	if(!file_exists($custom_upload_folder)) mkdir($custom_upload_folder);
	$img = $codeBase64;	
	$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$decoded = base64_decode($img) ;
	$filename = 'esign.png';
	$hashed_filename = md5( $filename . microtime() ) . '_' . $filename;	
	// @new
	if(false === ($fileUrl = file_put_contents( $custom_upload_folder . $hashed_filename, $decoded ))){
		wp_send_json_error();
	}else{
		return $baseurl . $hashed_filename;
	}
}
