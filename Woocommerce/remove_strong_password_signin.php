/*
Add to functions.php
*/
function remove_wc_password_meter() {
	wp_dequeue_script( 'wc-password-strength-meter' );
}
add_action( 'wp_print_scripts', 'remove_wc_password_meter', 100 );
