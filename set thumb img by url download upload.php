<?php
function devvn_download($imgurl) { 

	$url = $imgurl;
	$timeout_seconds = 5;

	// Download file to temp dir
	$temp_file = download_url( $url, $timeout_seconds );

	if ( !is_wp_error( $temp_file ) ) {

			$wp_file_type = wp_check_filetype($temp_file);

			$filemime = $wp_file_type['type'];

			$file = array(
				'name'     => basename($url), // ex: wp-header-logo.png
				'type'     => $filemime,
				'tmp_name' => $temp_file,
				'error'    => 0,
				'size'     => filesize($temp_file),
			);
			$overrides = array(
				'test_form' => false,
				'test_size' => true,
			);

			$results = media_handle_sideload( $file, $post->$id, NULL, $overrides );
		
			return $results;
	}
	return false;
}
