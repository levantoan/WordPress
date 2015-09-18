<?php
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