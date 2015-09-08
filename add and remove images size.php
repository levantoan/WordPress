<?php
//Remove Default WordPress Image Sizes
function svl_remove_default_image_sizes( $sizes) {
	//unset( $sizes['thumbnail']);
	unset( $sizes['medium']);
	unset( $sizes['large']);
	 
	return $sizes;
}
add_filter('intermediate_image_sizes_advanced', 'svl_remove_default_image_sizes');

//Add images size
add_image_size('bxslider_large',1020,550,true);
add_image_size('bxslider_thumnail',165,125,true);

//Thêm images vào danh sách thumbnail
add_filter( 'image_size_names_choose', 'custom_image_sizes_choose' );
function custom_image_sizes_choose( $sizes ) {
	$custom_sizes = array(
		'bxslider_large' => 'Bxslider large 1020x550 crop',
		'bxslider_thumnail' => 'Bxslider thumbnail 165x125 crop'
	);
	return array_merge( $sizes, $custom_sizes );
}