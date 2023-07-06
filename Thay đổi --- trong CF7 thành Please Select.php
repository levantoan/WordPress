<?php
/*new version*/
function devvn_replace_include_blank($name, $text, &$html) {
    $matches = false;
    preg_match('/<select(.*)name="' . $name . '"[^>]*>(.*)<\/select>/iU', $html, $matches);
    if ($matches) {
        $select = str_replace('<option value="">&#8212;Please choose an option&#8212;</option>', '<option value="">' . $text . '</option>', $matches[0]);
        $html = preg_replace('/<select(.*)name="' . $name . '"[^>]*>(.*)<\/select>/iU', $select, $html);
    }
}

function devvn_wpcf7_form_elements($html) {
    devvn_replace_include_blank('cus_gender', 'Title (Mr, Mrs)', $html);
    devvn_replace_include_blank('booking_adult', 'Adult (from 12 years)', $html);
    devvn_replace_include_blank('booking_children', 'Children ( 2 - 12 years )', $html);
    devvn_replace_include_blank('booking_infant', 'Infant ( <2 years )', $html);

    return $html;
}
add_filter('wpcf7_form_elements', 'devvn_wpcf7_form_elements');











/*For old version - đã loại bỏ không dùng nữa*/
function my_wpcf7_form_elements($html) {
	function ov3rfly_replace_include_blank($name, $text, &$html) {
		$matches = false;
		preg_match('/<select name="' . $name . '"[^>]*>(.*)<\/select>/iU', $html, $matches);
		if ($matches) {
			$select = str_replace('<option value="">---</option>', '<option value="">' . $text . '</option>', $matches[0]);
			$html = preg_replace('/<select name="' . $name . '"[^>]*>(.*)<\/select>/iU', $select, $html);
		}
	}
	ov3rfly_replace_include_blank('property', '- Please Select -', $html);
	ov3rfly_replace_include_blank('place', '- Please Select -', $html);
	ov3rfly_replace_include_blank('estimated', '- Please Select -', $html);
	
	return $html;
}
add_filter('wpcf7_form_elements', 'my_wpcf7_form_elements');
