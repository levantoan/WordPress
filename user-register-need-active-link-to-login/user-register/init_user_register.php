<?php
function devvn_enqueue_style_script() {
	global $post;
	$tmp = get_page_template_slug($post->ID);
	if($tmp == 'template-user-register.php'){		
		wp_enqueue_style( 'css-register', get_stylesheet_directory_uri().'/user-register/user-register.css',array(), 1.0, 'all' ); 
		wp_enqueue_script( 'js-register', get_stylesheet_directory_uri().'/user-register/user-register.js', array('jquery'),'1.0', true);
		wp_localize_script( 'js-register', 'devvn', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'home_url' => home_url(),
		));        
	}
}
add_action( 'wp_enqueue_scripts', 'devvn_enqueue_style_script',99 );

add_action( 'wp_ajax_nopriv_devvn_user_register', 'devvn_user_register_func' );
function devvn_user_register_func() {
	if ( !wp_verify_nonce( $_REQUEST['nonce_register'], "nonce_register_action")) {
    	wp_send_json_error('nonce error');
	}   
	$info = array();
	$password = isset($_POST['password']) ? sanitize_text_field($_POST['password']) :'';
	$firstname = isset($_POST['firstname']) ? esc_attr($_POST['firstname']) :'';
	$lastname = isset($_POST['lastname']) ? esc_attr($_POST['lastname']) :'';
	$company = isset($_POST['company']) ? esc_attr($_POST['company']) :'';
	$address = isset($_POST['address']) ? esc_attr($_POST['address']) :'';
	$city = isset($_POST['city']) ? esc_attr($_POST['city']) :'';
	$state_province = isset($_POST['state_province']) ? esc_attr($_POST['state_province']) :'';
	$postcode = isset($_POST['postcode']) ? esc_attr($_POST['postcode']) :'';
	$country = isset($_POST['country']) ? esc_attr($_POST['country']) :'';
	$email = isset($_POST['email']) ? esc_attr(sanitize_email($_POST['email'],true)) :'';
	$tel = isset($_POST['tel']) ? esc_attr($_POST['tel']) :'';
	$fax = isset($_POST['fax']) ? esc_attr($_POST['fax']) :'';
	$industry = isset($_POST['industry']) ? esc_attr($_POST['industry']) :'';
	$preferred_language = isset($_POST['preferred_language']) ? esc_attr($_POST['preferred_language']) :'';
	$_wp_http_referer = isset($_POST['_wp_http_referer']) ? esc_attr($_POST['_wp_http_referer']) :'';
	$_wp_http_referer = esc_url(home_url($_wp_http_referer));
	
	if(!is_email($email) || $password == '') wp_send_json_error('Register error!');
	if(email_exists($email) || username_exists($email)) wp_send_json_error('Email is registered!');
	
	$info['user_nicename'] = $info['nickname'] = $info['user_login'] = $email ;	
	$info['first_name'] = $firstname;
	$info['last_name'] = $lastname;
    $info['user_pass'] = $password;
 	$info['user_email'] = $email;
	
 	$user_id = wp_insert_user( $info ) ;
 	if ( ! is_wp_error( $user_id ) ) {
 		update_user_meta($user_id,'company',$company);
 		update_user_meta($user_id,'address',$address);
 		update_user_meta($user_id,'city',$city);
 		update_user_meta($user_id,'state_province',$state_province);
 		update_user_meta($user_id,'post_code',$postcode);
 		update_user_meta($user_id,'country',$country);
 		update_user_meta($user_id,'telephone',$tel);
 		update_user_meta($user_id,'fax',$fax);
 		update_user_meta($user_id,'industry',$industry);
 		update_user_meta($user_id,'preferred_language',$preferred_language);
 		
 		$devvn_activekey = md5(wp_generate_password());
 		update_user_meta($user_id,'devvn_activekey',$devvn_activekey);
 		update_user_meta($user_id,'devvn_status',''); 		
 		
 		$subject = sprintf(__('[%s] Your username and password', 'devvn'), get_option('blogname'));
 		
     	$message  = sprintf(__('Username: %s', 'devvn'), $email) . "\r\n"; 
     	$message .= sprintf(__('Password: %s', 'devvn'), $password) . "\r\n\n";
     	$message .= sprintf(__('Activation Code: %s', 'devvn'), $devvn_activekey) . "\r\n\n"; 
     	$message .= "Click here to confirm your email: ".$_wp_http_referer.'?active-acc='.urlencode( $devvn_activekey ).'&email='.$email."\r\n"; 

		wp_mail($email, $subject, $message);
 		
 		wp_send_json_success('Register successfully. Check email to confirm, please! Redirecting ... ');
 	} 	
	wp_send_json_error('Register error!');
}

function devvn_check_login($user, $user_login, $password) {
	
	// get the error class ready
	$error = new \WP_Error();
	
	$activation_code = '';
	
	// first check if either of the two fields are empty
	if ( empty( $user_login ) || empty( $password ) ){
		// figure out which one
		if ( empty( $user_login ) )
			$error->add( 'empty_username', __( 'The username field is empty.', 'devvn' ) );

		if ( empty( $password ) )
			$error->add( 'empty_password', __( 'The password field is empty.', 'devvn' ) );			
		
		// return appropriate error
		return $error;
	}
	
	$user_info = get_user_by( 'login', $user_login );
	
	// if the object is empty, meaning an invalid username
	if( empty( $user_info ) ){
		// add the error message for invalid username
		$error->add( 'incorrect user', __( 'Username does not exist', 'devvn' ) );		
		
		// return appropriate error
		return $error;
	}else{
		// get the custom user meta defined during registration
		$activation_code = get_user_meta( $user_info->ID, 'devvn_status', true );
	}
	
	if( $activation_code == 'active' ){
		return $user;
		exit;
	}else{
		$error->add( 'access_denied', __( 'Confirm your email to login, Please.', 'devvn' ) );	
		return $error;
		
	}
}
add_filter('authenticate', 'devvn_check_login', 99, 3);

function send_email_after_active_ok($userID){
	
	$userinfo = get_user_by('id', $userID);
	
	if(empty($userinfo) || is_wp_error($userinfo)) return;
	
	$to = get_bloginfo('admin_email');		
	
	/* Create XML Document */
	$xmlDoc = new DOMDocument('1.0');
	
	/* Build Maximizer XML file */
	$xmlRoot = $xmlDoc->createElement('data');
	$xmlDoc->appendChild($xmlRoot);
	//user_login
	$xmlfirstname = $xmlDoc->createElement('UserLogin',$userinfo->user_login);
	$xmlRoot->appendChild($xmlfirstname);
	//firstname
	$xmlfirstname = $xmlDoc->createElement('FirstName',$userinfo->first_name);
	$xmlRoot->appendChild($xmlfirstname);
	//firstname
	$xmllastname = $xmlDoc->createElement('LastName',$userinfo->last_name);
	$xmlRoot->appendChild($xmllastname);
	//firstname
	$xmlemail = $xmlDoc->createElement('Email',$userinfo->user_email);
	$xmlRoot->appendChild($xmlemail);
	//Company
	$xmlcom = $xmlDoc->createElement('Company',get_user_meta($userinfo->ID,'company',true));
	$xmlRoot->appendChild($xmlcom);
	//address
	$xmlcountry = $xmlDoc->createElement('Address',get_user_meta($userinfo->ID,'address',true));
	$xmlRoot->appendChild($xmlcountry);
	//city
	$xmlcountry = $xmlDoc->createElement('City',get_user_meta($userinfo->ID,'city',true));
	$xmlRoot->appendChild($xmlcountry);
	//state_province
	$xmlcountry = $xmlDoc->createElement('StateProvince',get_user_meta($userinfo->ID,'state_province',true));
	$xmlRoot->appendChild($xmlcountry);
	//post_code
	$xmlcountry = $xmlDoc->createElement('PostCode',get_user_meta($userinfo->ID,'post_code',true));
	$xmlRoot->appendChild($xmlcountry);
	//Country
	$xmlcountry = $xmlDoc->createElement('Country',get_user_meta($userinfo->ID,'country',true));
	$xmlRoot->appendChild($xmlcountry);
	//phone
	$xmlphone = $xmlDoc->createElement('Phone',get_user_meta($userinfo->ID,'telephone',true));
	$xmlRoot->appendChild($xmlphone);
	//fax
	$xmlphone = $xmlDoc->createElement('Fax',get_user_meta($userinfo->ID,'fax',true));
	$xmlRoot->appendChild($xmlphone);
	//industry
	$xmlphone = $xmlDoc->createElement('Industry',get_user_meta($userinfo->ID,'industry',true));
	$xmlRoot->appendChild($xmlphone);
	//preferred_language
	$xmlphone = $xmlDoc->createElement('Preferred_Language',get_user_meta($userinfo->ID,'preferred_language',true));
	$xmlRoot->appendChild($xmlphone);
	//devvn_status
	$xmlphone = $xmlDoc->createElement('Status',get_user_meta($userinfo->ID,'devvn_status',true));
	$xmlRoot->appendChild($xmlphone);
	
	$content = chunk_split(base64_encode($xmlDoc->saveXML()));
	
	$filename = 'info_'.sanitize_file_name($userinfo->display_name).'.xml';
	$subject = 'User register active XML file';
	$message = 'Infor';
	// a random hash will be necessary to send mixed content
	$separator = md5(time());
	
	// carriage return type (RFC)
	$eol = "\r\n";
	$fname = $userinfo->display_name;
	$femail = $userinfo->user_email;
	// main header (multipart mandatory)
	$headers = "From: {$fname} <{$femail}>" . $eol;
	//$headers .= "Cc: test@gmail.com" . $eol;
	$headers .= "MIME-Version: 1.0" . $eol;
	$headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
	$headers .= "Content-Transfer-Encoding: 7bit" . $eol;
	$headers .= "This is a MIME encoded message." . $eol;
	
	// message
	$body = "--" . $separator . $eol;
	$body .= "Content-Type: text/plain; charset=\"iso-8859-1\"" . $eol;
	$body .= "Content-Transfer-Encoding: 8bit" . $eol;
	$body .= $message . $eol;
	
	// attachment
	$body .= "--" . $separator . $eol;
	$body .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . $eol;
	$body .= "Content-Transfer-Encoding: base64" . $eol;
	$body .= "Content-Disposition: attachment" . $eol;
	$body .= $content . $eol;
	$body .= "--" . $separator . "--";
	wp_mail($to, $subject, $body,$headers);	
	
}
add_action('after_active_ok', 'send_email_after_active_ok');