<?php
add_action( 'wp_ajax_nopriv_devvn_user_login', 'devvn_user_login' );
add_action( 'wp_ajax_nopriv_devvn_user_register', 'devvn_user_register' );
add_action( 'wp_ajax_nopriv_devvn_user_lostpass', 'devvn_user_lostpass' );

function devvn_user_login(){
	
	check_ajax_referer( 'ajax-login-nonce', 'security' );
	
	$user_login = $_POST['username'];
	$password = $_POST['password'];
	$remember = ($_POST['rememberme'] == 'forever') ? true : false;
	
 	$info = array();
 	
    $info['user_login'] = $user_login;
    $info['user_password'] = $password;
    $info['remember'] = $remember;
 
	$user_signon = wp_signon( $info, false );
	if ( is_wp_error($user_signon) ){
		echo json_encode(array('loggedin'=>false, 'message'=>__('Wrong username or password.')));
	} else {
		wp_set_current_user($user_signon->ID); 
		echo json_encode(array('loggedin'=>true, 'message'=>__('Login successfully, redirecting...')));
    }
 	die();
}

function devvn_user_register(){
	
	check_ajax_referer( 'ajax-register-nonce', 'registersecurity' );
		
	$info = array();
    $info['user_nicename'] = $info['nickname'] = $info['display_name'] = $info['user_login'] = sanitize_user($_POST['username']) ;
    $info['user_pass'] = wp_generate_password();
 	$info['user_email'] = sanitize_email( $_POST['register_email']);
 	
 	if(!is_email($info['user_email'])){
 		echo json_encode(array('loggedin'=>false, 'message'=>__('This email address is wrong.')));
 		die();
 	}
 	
 	// Register the user
    $user_register = wp_insert_user( $info );
 	if ( is_wp_error($user_register) ){
 		$error  = $user_register->get_error_codes() ;
	 	if(in_array('empty_user_login', $error))
	 		echo json_encode(array('loggedin'=>false, 'message'=>__($user_register->get_error_message('empty_user_login'))));
	 	elseif(in_array('existing_user_login',$error))
	 		echo json_encode(array('loggedin'=>false, 'message'=>__('This username is already registered.')));
	 	elseif(in_array('existing_user_email',$error))
        	echo json_encode(array('loggedin'=>false, 'message'=>__('This email address is already registered.')));
    } else {
    	echo json_encode(array('loggedin'=>true,'message'=>__('Registration complete. Please check your e-mail.')));
   		//auth_user_login($info['nickname'], $info['user_pass'], 'Registration');
   		$message  = __('Hi there,') . "\r\n\r\n";
		$message .= sprintf( __("Welcome to %s! Here's how to log in:"), get_option('blogname')) . "\r\n\r\n";
		$message .= wp_login_url() . "\r\n";
		$message .= sprintf( __('Username: %s'), $info['user_nicename'] ) . "\r\n";
		$message .= sprintf( __('Password: %s'), $info['user_pass'] ) . "\r\n\r\n";
		$message .= sprintf( __('If you have any problems, please contact me at %s.'), get_option('admin_email') ) . "\r\n\r\n";
		$message .= __('Thanks!');
		wp_mail(
			$info['user_email'],
			sprintf( __('[%s] Your username and password'), get_option('blogname') ),
			$message
		); 
    }    
 	die();
}
function devvn_user_lostpass(){
	
	check_ajax_referer( 'ajax-lostpass-nonce', 'lostpasssecurity' );

	
	
 	die();
}

//Thay đổi URL lost pass
function my_lost_password_page( $lostpassword_url, $redirect ) {
    return home_url( '/user?action=lost_password&redirect_to=' . $redirect );
}
add_filter( 'lostpassword_url', 'my_lost_password_page', 20, 2 );

//Chuyển hướng khi vào wp-login.php?action=lostpassword
function redirect_to_custom_lostpassword() {
    if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
        if ( is_user_logged_in() ) {
            $this->redirect_logged_in_user();
            exit;
        } 
        wp_redirect( home_url( '/user?action=lost_password' ) );
        exit;
    }
}
add_action( 'login_form_lostpassword', 'redirect_to_custom_lostpassword' );

//Thay đổi URL register
function my_register_page( $register_url ) {
    return home_url( '/user?action=register' );
}
add_filter( 'register_url', 'my_register_page' );

//Chuyển hướng khi vào wp-login.php?action=register
function wpse45134_catch_register()
{
    wp_redirect( home_url( '/user?action=register' ) );
    exit();
}
add_action( 'login_form_register', 'wpse45134_catch_register' );

//Thay đổi URL login
function my_login_page( $login_url, $redirect ) {
    return home_url( '/user?action=login&redirect_to=' . $redirect );
}
add_filter( 'login_url', 'my_login_page', 20, 2 );

//Chuyển hướng khi đã login thành công
function my_login_redirect( $redirect_to, $request, $user ) {
	//is there a user to check?
	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
		//check for admins
		if ( in_array( 'administrator', $user->roles ) ) {
			// redirect them to the default place
			return $redirect_to;
		} else {
			return home_url();
		}
	} else {
		return $redirect_to;
	}
}
add_filter( 'login_redirect', 'my_login_redirect', 10, 3 );

