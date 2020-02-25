<?php
/*
Author levantoan.com
Code xóa số 0 ở đầu số điện thoại trước khi gửi mail
*/
add_filter( 'wpcf7_posted_data', 'remove_zero_first_value_cf7', 10, 1 );
function remove_zero_first_value_cf7($array){
	if(isset($array['number-phone']) && $array['number-phone']){
		$old_data = $array['number-phone'];
		$array['number-phone'] = ltrim($old_data, '0');
	}
	return $array;
}
