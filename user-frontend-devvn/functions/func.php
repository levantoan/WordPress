<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
add_action( 'wp_ajax_nopriv_devvn_user_login', 'devvn_user_login' );
add_action( 'wp_ajax_nopriv_devvn_user_register', 'devvn_user_register' );
add_action( 'wp_ajax_nopriv_devvn_user_lostpass', 'devvn_user_lostpass' );

function devvn_user_login(){
	
	check_ajax_referer( 'ajax-login-nonce', 'security' );
	
	$user_login = esc_attr(sanitize_user($_POST['username'],true));
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
	
	if (!empty($_POST['username']) && !validate_username($_POST['username'])){
		echo json_encode(array('loggedin'=>false, 'message'=>__('This username is wrong format.')));
		die();
	}
			
	$info = array();
    $info['user_nicename'] = $info['nickname'] = $info['display_name'] = $info['user_login'] = esc_attr(sanitize_user($_POST['username'],true)) ;
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
 		$lostPassLink = '<a class="text-link" href="<'.wp_lostpassword_url().'">Lost password?</a>';
	 	if(in_array('empty_user_login', $error))
	 		echo json_encode(array('loggedin'=>false, 'message'=>__($user_register->get_error_message('empty_user_login'))));
	 	elseif(in_array('existing_user_login',$error))
	 		echo json_encode(array('loggedin'=>false, 'message'=>__('This username is already registered. '.$lostPassLink)));
	 	elseif(in_array('existing_user_email',$error))
        	echo json_encode(array('loggedin'=>false, 'message'=>__('This email address is already registered. '.$lostPassLink)));
    } else {
    	echo json_encode(array('loggedin'=>true,'message'=>__('Registration complete. Please check your e-mail.')));
   		
   		$message  = __('Hi there,') . "\r\n\r\n";
		$message .= sprintf( __("Welcome to %s! Here's how to log in:"), get_option('blogname')) . "\r\n\r\n";
		$message .= wp_login_url() . "\r\n\r\n";
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
	
	if ( empty( $_POST['user_login'] ) ) {
		echo json_encode(array('loggedin'=>false, 'message'=>__('Enter e-mail address.','devvn')));
		die();
	}
	
	$email_lostpass = sanitize_email( $_POST['user_login']);
	
	if( !is_email( $email_lostpass) ) {
    	echo json_encode(array('loggedin'=>false, 'message'=>__('Invalid e-mail.','devvn')));
    	die();
   	}
   	
   	if( !email_exists( $email_lostpass )) {
    	echo json_encode(array('loggedin'=>false, 'message'=>__('There is no user registered with that email address.','devvn')));
    	die();
   	}
   	
	echo json_encode(array('loggedin'=>true, 'message'=>__($email_lostpass)));
		
 	die();
}

//Thay đổi URL lost pass
function my_lost_password_page( $lostpassword_url, $redirect ) {
    return home_url( '/user-dashboard?action=lost_password&redirect_to=' . $redirect );
}
add_filter( 'lostpassword_url', 'my_lost_password_page', 20, 2 );

//Chuyển hướng khi vào wp-login.php?action=lostpassword
function redirect_to_custom_lostpassword() {
    if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
        if ( is_user_logged_in() ) {
            wp_redirect( home_url( '/user-dashboard' ) );
            exit;
        } 
        wp_redirect( home_url( '/user-dashboard?action=lost_password' ) );
        exit;
    }
}
add_action( 'login_form_lostpassword', 'redirect_to_custom_lostpassword' );

function checkPassword($pwd) {
    $errors = array();

    if (strlen($pwd) < 8) {
        $errors[] = "Password too short!";
    }

    if (!preg_match("#[0-9]+#", $pwd)) {
        $errors[] = "Password must include at least one number!";
    }

    if (!preg_match("#[a-zA-Z]+#", $pwd)) {
        $errors[] = "Password must include at least one letter!";
    }     

    return $errors;
}

function devvn_retrieve_password() {
	global $wpdb, $wp_hasher;

	$login = trim( $_POST['user_login'] );

	if ( empty( $login ) ) {

		_e('<p class="devvn-error">Enter a username or e-mail address.</p>', 'devvn' );
		return false;

	} else {
		// Check on username first, as customers can use emails as usernames.
		$user_data = get_user_by( 'login', $login );
	}

	// If no user found, check if it login is email and lookup user based on email.
	if ( ! $user_data && is_email( $login ) ) {
		$user_data = get_user_by( 'email', $login );
	}

	do_action( 'lostpassword_post' );

	if ( ! $user_data ) {
		_e('<p class="devvn-error">Invalid username or e-mail.</p>', 'devvn');
		return false;
	}

	if ( is_multisite() && ! is_user_member_of_blog( $user_data->ID, get_current_blog_id() ) ) {
		_e( '<p class="devvn-error">Invalid username or e-mail.</p>', 'devvn' );
		return false;
	}

	// redefining user_login ensures we return the right case in the email
	$user_login = $user_data->user_login;

	do_action( 'retrieve_password', $user_login );

	$allow = apply_filters( 'allow_password_reset', true, $user_data->ID );

	if ( ! $allow ) {

		_e( '<p class="devvn-info">Password reset is not allowed for this user</p>', 'devvn' );
		return false;

	} elseif ( is_wp_error( $allow ) ) {

		_e($allow->get_error_message());
		return false;
	}

	$key = wp_generate_password( 20, false );

	do_action( 'retrieve_password_key', $user_login, $key );

	// Now insert the key, hashed, into the DB.
	if ( empty( $wp_hasher ) ) {
		require_once ABSPATH . 'wp-includes/class-phpass.php';
		$wp_hasher = new PasswordHash( 8, true );
	}

	$hashed = $wp_hasher->HashPassword( $key );

	$wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user_login ) );	
	
	$message  = __('Hello!,') . "\r\n\r\n";
	$message .= sprintf( __("You asked us to reset your password for your account using the email address %s."), $user_login) . "\r\n\r\n";
	$message .= __( "If this was a mistake, or you didn't ask for a password reset, just ignore this email and nothing will happen.", 'devvn' ) . "\r\n\r\n";
	$message .= __( 'To reset your password, visit the following address:', 'devvn' ) . "\r\n\r\n";	
	$message .= site_url( "user-dashboard?action=rp&key=".$key."&login=" . rawurlencode( $user_login ), 'devvn' ) . "\r\n\r\n";	
	$message .= __('Thanks!','devvn');
	wp_mail(
		$user_data->user_email,
		sprintf( __('[%s] Reset your password'), get_option('blogname') ),
		$message
	); 
	_e( '<p class="devvn-success">Check your e-mail for the confirmation link.</p>', 'devvn' );
	return true;
}

function userdevvn_process_reset_password() {
	$posted_fields = array( 'userdevvn_reset_password', 'password_1', 'password_2', 'reset_key', 'reset_login', '_wpnonce' );

	foreach ( $posted_fields as $field ) {
		if ( ! isset( $_POST[ $field ] ) ) {
			return;
		}
		$posted_fields[ $field ] = $_POST[ $field ];
	}

	if ( ! wp_verify_nonce( $posted_fields['_wpnonce'], 'reset_password' ) ) {
		return;
	}

	$user = userdevvn_check_password_reset_key( $posted_fields['reset_key'], $posted_fields['reset_login'] );

	if ( $user instanceof WP_User ) {
		if ( empty( $posted_fields['password_1'] ) ) {
			_e( '<p class="devvn-error">Please enter your password.</p>', 'devvn' );
			return;
		}

		if ( $posted_fields[ 'password_1' ] !== $posted_fields[ 'password_2' ] ) {
			_e( '<p class="devvn-error">Passwords do not match.', 'devvn' );		
			return;	
		}
		$errorCheckPass = checkPassword($posted_fields['password_1']);
		if($errorCheckPass && is_array($errorCheckPass)){
			foreach ($errorCheckPass as $error) {
				_e( '<p class="devvn-error">'.$error.'</p>' );
			}
			return;
		}		
		userdevvn_reset_password( $user, $posted_fields['password_1'] );			
		echo '<script>
		(function($){
				var url = window.location.href.split("?")[0];		 
				window.location.assign(url+"?reset=true");
		})(jQuery)		
		</script>';
	}
}

function userdevvn_check_password_reset_key( $key, $login ) {
	global $wpdb, $wp_hasher;

	$key = preg_replace( '/[^a-z0-9]/i', '', $key );

	if ( empty( $key ) || ! is_string( $key ) ) {
		_e( '<p class="devvn-error">Invalid key</p>', 'devvn' );
		return false;
	}

	if ( empty( $login ) || ! is_string( $login ) ) {
		_e( '<p class="devvn-error">Invalid key</p>', 'devvn' );
		return false;
	}

	$user = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->users WHERE user_login = %s", $login ) );

	if ( ! empty( $user ) ) {
		if ( empty( $wp_hasher ) ) {
			require_once ABSPATH . 'wp-includes/class-phpass.php';
			$wp_hasher = new PasswordHash( 8, true );
		}

		$valid = $wp_hasher->CheckPassword( $key, $user->user_activation_key );
	}

	if ( empty( $user ) || empty( $valid ) ) {
		_e( '<p class="devvn-error">Invalid key</p>', 'devvn' );
		return false;
	}

	return get_userdata( $user->ID );
}
function userdevvn_reset_password( $user, $new_pass ) {
	do_action( 'password_reset', $user, $new_pass );

	wp_set_password( $new_pass, $user->ID );

	wp_password_change_notification( $user );
}

//Thay đổi URL register
function my_register_page( $register_url ) {
    return home_url( '/user-dashboard?action=register' );
}
add_filter( 'register_url', 'my_register_page' );

//Chuyển hướng khi vào wp-login.php?action=register
function wpse45134_catch_register()
{
	if ( is_user_logged_in() ) {
    	wp_redirect( home_url( '/user-dashboard' ) );
        exit;
    } 
    wp_redirect( home_url( '/user-dashboard?action=register' ) );
    exit();
}
add_action( 'login_form_register', 'wpse45134_catch_register' );

//Thay đổi URL login
function my_login_page( $login_url, $redirect ) {
    return home_url( '/user-dashboard?action=login&redirect_to=' . $redirect );
}
add_filter( 'login_url', 'my_login_page', 20, 2 );

//Chuyển hướng khi vào wp-login.php
add_action('init', 'prevent_wp_login');
function prevent_wp_login() {
    global $pagenow;
    $action = (isset($_GET['action'])) ? $_GET['action'] : '';
    if( $pagenow == 'wp-login.php' && ( ! $action || ( $action && in_array($action, array('login'))))) {
        $page = home_url( '/user-dashboard' );
        wp_redirect($page);
        exit();
    }
}

//Chuyển hướng khi đã login thành công
function my_login_redirect( $redirect_to, $request, $user ) {
	//is there a user to check?
	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
		//check for admins
		if ( in_array( 'administrator', $user->roles ) ) {
			// redirect them to the default place
			return $redirect_to;
		} else {
			return home_url('/user-dashboard');
		}
	} else {
		return $redirect_to;
	}
}
add_filter( 'login_redirect', 'my_login_redirect', 20, 3 );