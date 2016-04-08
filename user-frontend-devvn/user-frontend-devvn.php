<?php
/*
Plugin Name: WP Front End Profile By DevVN
Plugin URI: http://devvn.com
Description: This plugin allows users to easily edit their profile information on the front end rather than having to go into the dashboard to make changes to password, email address and other user meta data.
Version:     0.1
Author:      Le Van Toan
Author URI:  http://levantoan.com
Text Domain: devvn
License:     GPL v1 or later
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

define( pluginVersion ,'0.1');

require_once dirname( __FILE__ ) . '/functions/scripts.php';
require_once dirname( __FILE__ ) . '/functions/func.php';

require_once dirname( __FILE__ ) . '/editprofile/devvn_editprofile.php';

//Tạo page User khi active plugin
function userdevvn_activate() {
	$page = get_page_by_path('user-dashboard');
	if (empty($page)) {
		$my_post = array(
			'post_title'    => 'User Dashboard',
			'post_content'  => '[userdevvn_login]',
			'post_status'   => 'publish',
			'post_author'   => 1,
			'post_type'     => 'page',
			'post_name'		=>	'user-dashboard'
		);
		$post_new_ID = wp_insert_post( $my_post );
	}else{
		$my_post = array(
			'ID'           	=> $page->ID,
			'post_status'	=>	'publish'
		);
		wp_update_post( $my_post );
	}
}
register_activation_hook( __FILE__, 'userdevvn_activate' );

//Pending page User khi uninstall plugin
function userdevvn_deactivation() {
	$page = get_page_by_path('user-dashboard');
	if (!empty($page) && $page->post_status == 'publish') {
		$my_post = array(
			'ID'           	=> $page->ID,
			'post_status'	=>	'pending'
		);
		wp_update_post( $my_post );
	}
}
register_deactivation_hook ( __FILE__, 'userdevvn_deactivation' );

//Xóa page User khi uninstall plugin
function userdevvn_uninstall() {
	$page = get_page_by_path('user-dashboard');
	if (!empty($page)) {
		wp_delete_post($page->ID,true);
	}
}
register_uninstall_hook ( __FILE__, 'userdevvn_uninstall' );

function userdevvn_login_func(){
	$action = $_GET['action'];
	ob_start();
	?>
	<div class="devvn-form">
		<?php if( $action == 'register' && !is_user_logged_in() ):?>
			<?php include_once dirname(__FILE__) . '/functions/register-form.php';?>			
		<?php elseif(($action == 'lost_password' || $action == 'rp') && !is_user_logged_in()):?>
			<?php include_once dirname(__FILE__) . '/functions/lostpass-form.php';?>
		<?php else:?>
			<?php if(!is_user_logged_in()):?>
				<?php include_once dirname(__FILE__) . '/functions/login-form.php';?>
			<?php else:?>
				<?php userdevvn_show_profile();?>
			<?php endif;?>
		<?php endif;?>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode('userdevvn_login', 'userdevvn_login_func');

add_action( 'init', 'blockusers_init' );
function blockusers_init() {
	if ( is_admin() && !current_user_can( 'administrator' ) && !( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
		wp_redirect( home_url('/user-dashboard') );
		exit;
	}
}

add_action('before_wrapper_userdevvn','before_wrapper_userdevvn_func');
function before_wrapper_userdevvn_func(){
	echo '<a href="'.wp_logout_url().'">Logout</a>';
}