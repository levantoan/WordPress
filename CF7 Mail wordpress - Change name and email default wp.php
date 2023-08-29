<?php
add_filter('wp_mail_from', 'new_mail_from');
add_filter('wp_mail_from_name', 'new_mail_from_name');
function new_mail_from($old) {
	//get_option('admin_email')
	return 'your-email@your-domain.com';
}
function new_mail_from_name($old) {
 return 'TÊN MONG MUỐN';
}
