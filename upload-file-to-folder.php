<?php
function upload_file_to_folder($files = array()){
	if(!$files || !is_array($files) || empty($files)) return false;
	add_filter( 'upload_dir', 'edd_set_upload_dir' );
	$ext = pathinfo($files['name'], PATHINFO_EXTENSION);
	$ext_include = array('pdf','csv','doc','docx','xls','xlsx','png','jpg','gif','jpeg');
	$respond = array(
		'error'=>false,
		'message'=>''
	);
	if(in_array(strtolower($ext), $ext_include) && $files['error'] == 0){		
		if ( $files['sizes'] <= (6*1024*1024) ) {
			if ( ! function_exists( 'wp_handle_upload' ) ) {
			    require_once( ABSPATH . 'wp-admin/includes/file.php' );
			}
			
			$upload_overrides = array( 'test_form' => false );
			
			$movefile = wp_handle_upload( $files, $upload_overrides );
			
			if ( $movefile && ! isset( $movefile['error'] ) ) {
				$respond['error'] = false;
				$respond['message']=$movefile['url'];
				
			} else {
			    $respond['error'] = true;
				$respond['message']=$movefile['error'];
			}
		}else{
			$respond['error'] = true;
			$respond['message']='Your file upload is too large';
		}
	}else{
		$respond['error'] = true;
		$respond['message']='Your file upload is not correct extension!,your ext:'.$ext;
	}
	return $respond;
}
