<?php
define('TEMP_URL_OG',get_stylesheet_directory_uri());
define('API_MAILCHIMP_OG','086bae59723532254c6f19a9537e0929-us12');
define('IDLIST_MAILCHIMP_OG','22946e787c');
define('RESOURCES_TABLE', $wpdb->prefix . "resources");

include 'cpt-resources.php';
include 'cpt-register.php';

function delete_resources_cookie($name = 'resources'){
	if(!isset($_COOKIE[$name])) {
		unset( $_COOKIE[$name] );
		setcookie( $name, '', time() - ( 15 * 60 ) );
	}
}

function set_resources_cookie($name = 'resources', $data = '', $expire = 30){	
	setcookie( $name, $data, $expire * DAYS_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
}

function get_resources_cookie($name = 'resources'){	
	if(!isset($_COOKIE[$name])) {
		return false;
	} else {
		return $_COOKIE[$name];
	}
}

function is_resources_logged_in(){
	if(!get_resources_cookie()) return false;
	$resources = get_resources_cookie();
	if(check_user_by_email($resources)) return true;	
	return false;
}
function subscribedsyncMailchimp($data = array()) {
    $apiKey = API_MAILCHIMP_OG;
    $listId = IDLIST_MAILCHIMP_OG;

    $memberId = md5(strtolower($data['email']));
    $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
    $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;

    $json = json_encode(array(
        'email_address' => $data['email'],
        'status'        => 'pending', // "subscribed","unsubscribed","cleaned","pending"
        'merge_fields'  => array(
            'COMPANY'     => $data['company']
        )
    ));

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_USERPWD, 'apikey:' . $apiKey);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);                                                                                                                 

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if($httpCode == 200){
    	return true;
    }else{
    	return false;
    }
}
function checkUserMailchimp($email = '', $return_array = false) {
    $apiKey = API_MAILCHIMP_OG;
    $listId = IDLIST_MAILCHIMP_OG;

    $memberId = md5(strtolower($email));
    $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
    $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;    

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_USERPWD, 'apikey:' . $apiKey);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);                                                                                                              

    $result = curl_exec($ch);
    
    $result = json_decode($result,true);
    
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    curl_close($ch);
    
    if($httpCode == 200 && $return_array){
    	return $result;
    }else if($httpCode == 200 && !$return_array){
    	return $result['status'];
    }else{
    	return false;
    }
       
}

add_action( 'wp_ajax_save_register_infor', 'save_register_infor_func' );
add_action( 'wp_ajax_nopriv_save_register_infor', 'save_register_infor_func' );
function save_register_infor_func() {
	if ( !wp_verify_nonce( $_REQUEST['nonce'], "register_nonce_action")) {
		wp_send_json_error(array(
			'status'	=>	'register',
			'mess'		=>	'Register error! Please try again later.',
		));
	}	
	$email = (isset($_POST['email'])) ? sanitize_email($_POST['email']) : '';
	if(!is_email($email)) {		
		wp_send_json_error(array(
			'status'	=>	'register',
			'mess'		=>	'Register error! Please try again later.',
		));
	}
	$company = (isset($_POST['company'])) ? sanitize_title($_POST['company']) : '';
	
	$dataRegister = array(
	    'email'     =>	$email,
		'company'	=>	$company
	);
	$checkEmail = checkUserMailchimp($email);
	if(!$checkEmail || ($checkEmail && $checkEmail != 'subscribed')){
		if(subscribedsyncMailchimp($dataRegister)){
			wp_send_json_success(array(
				'status'	=>	'login',
				'mess'		=>	'Register successful! Please confirm your email.',
			));
		}else{
			wp_send_json_error(array(
				'status'	=>	'register',
				'mess'		=>	'Register error! Please try again later.',
			));
		}
	}else{
		wp_send_json_success(array(
			'status'	=>	'login',
			'mess'		=>	'Email exists! Please login with your email.',
		));
	}
}

add_action( 'wp_ajax_user_login_infor', 'user_login_infor_func' );
add_action( 'wp_ajax_nopriv_user_login_infor', 'user_login_infor_func' );
function user_login_infor_func() {
	if ( !wp_verify_nonce( $_REQUEST['nonce'], "login_nonce_action")) {
		wp_send_json_error();
	}   	
	$email = (isset($_POST['email'])) ? sanitize_email($_POST['email']) : '';
	if(!is_email($email)) wp_send_json_error();
		
	$checkEmail = checkUserMailchimp($email);
	if(!$checkEmail || ($checkEmail && $checkEmail != 'subscribed')){
		delete_resources_cookie();
		wp_send_json_error('Login error! Please try again later.');
	}else{		
		$dataUser = checkUserMailchimp($email,true);
		$dataPost = wp_parse_args($data,array(
			'company'	=>	(isset($dataUser['merge_fields']['COMPANY']))?$dataUser['merge_fields']['COMPANY']:'',
			'email'		=>	$dataUser['email_address']
		));
		if(creat_user_into_data($dataPost)){
			set_resources_cookie('resources',$email);
			wp_send_json_success('Login successful! Redirecting...');
		}else{
			delete_resources_cookie();
			wp_send_json_error('Login error! Please try again later.');
		}		
	}
}

function creat_user_into_data($data = array()){	
	$data = wp_parse_args($data,array(
		'company'	=>	'',
		'email'		=>	''
	));
	$email = $data['email'];
	$company = $data['company'];
	if(!is_email($email)) return false;
	
	if(check_user_by_email($email)) return true;
	
	$my_post = array(
    	'post_type'		=>	'register',
		'post_title'    =>	'New Register',
		'post_status'   =>	'publish'
	);
	$postID = wp_insert_post( $my_post );
	if($postID){
		update_post_meta($postID, 'email_user', $email);
		update_post_meta($postID, 'company_user', $company);
		
		$my_post2 = array(
		     'ID'           => $postID,
		     'post_title'   => 'Register #'.$postID,
		);
		wp_update_post( $my_post2 );
		 
		return $postID;
	}
	return false;
}

function check_user_by_email($email = ''){
	$email = sanitize_email($email);
	$args = array(
		'post_type' => 'register',
	   	'meta_query' => array(
	    	array(
	        	'key' => 'email_user',
	        	'value' => $email
	    	)
	   	),
	   	'fields' => 'ids'
	);
	$vid_query = new WP_Query( $args );
	if($vid_query->have_posts()):
		while ($vid_query->have_posts()):$vid_query->the_post();
	 		return get_the_ID();
		endwhile;
	else:
		return false;
	endif;wp_reset_query();
	return false;
}

function get_user_resources_by_email($email = ''){
	$email = sanitize_email($email);
	$args = array(
		'posts_per_page'	=> 1,
		'post_type'			=> 'register',
		'meta_key'         	=> 'email_user',
		'meta_value'       	=> $email
	);
	$users = get_posts($args);
	if(is_array($users) && !empty($users) && !is_wp_error($users))
		return $users[0]->ID;
	else 
		return false;
}

function check_exits_post_by_id($ID = '', $post_type = 'register'){
	$ID = intval($ID);
	$post_type = sanitize_title($post_type);
	$args = array(
		'posts_per_page'	=> 1,
		'post_type'			=> $post_type,
		'p'					=>	$ID
	);
	$users = get_posts($args);
	if(is_array($users) && !empty($users) && !is_wp_error($users))
		return $users[0]->ID;
	else 
		return false;
}

/********************************
 * Creat TABLE
*********************************/
function creat_table_resources(){	
	global $wpdb;
	global $charset_collate;	
	if($wpdb->get_var("SHOW TABLES LIKE ".RESOURCES_TABLE) != RESOURCES_TABLE) {
		$sql = "CREATE TABLE ".RESOURCES_TABLE."(
					`id` int(10) NOT NULL auto_increment,
					`user_id` int(10) NOT NULL,
					`file_id` int(10) NOT NULL,
					`count` int(10) NOT NULL,
					PRIMARY KEY ( `id` ) ,
					INDEX ( `id` )
				) $charset_collate;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta($sql);
	}
}
add_action( 'after_setup_theme', 'creat_table_resources' );

/********************************
 * Insert row
*********************************/
function add_resources($data = array()){
	global $wpdb;	
	$data = wp_parse_args($data,array(
		'user_ID'	=>	'',
		'file_ID'	=>	''
	));
	$userID = (isset($data['user_ID']))?intval($data['user_ID']):'';
	$fileID = (isset($data['file_ID']))?intval($data['file_ID']):'';
	$count = 1;
	
	if(!$userID || !$fileID) return false;
	
	$has_resources = get_resource($data);
	
	if($has_resources){
		if(update_resources($has_resources))
			return true;
		else 
			return false;
	}else{
		$query = "INSERT INTO ".RESOURCES_TABLE." VALUES(NULL,'".
						$userID."','".
						$fileID."','".
						$count."')";
		$wpdb->query($query);
		
		return true;	
	}
}
/********************************
 * Update row
*********************************/
function update_resources($ID){
	global $wpdb;	
	$count = 1;	
	$currentCount = get_column_resources($ID,'count');
	if($currentCount) $count = $currentCount + 1;
		
	$wpdb->update( 
		RESOURCES_TABLE, 
		array( 
			'count' => $count,
		), 
		array( 'id' => $ID ), 
		array(
			'%d'
		), 
		array( '%d' ) 
	);	
	return true;
}
/********************************
 * Delete row
*********************************/
function delete_resource($ID){
	global $wpdb;	
	$ID = intval($ID);		
	$wpdb->delete( RESOURCES_TABLE, array( 'id' => $ID ), array( '%d' ) );
	return true;	
}
/********************************
 * Get row
*********************************/
function get_resource($data){	
	global $wpdb;	
	$data = wp_parse_args($data,array(
		'user_ID'	=>	'',
		'file_ID'	=>	''
	));
	$userID = (isset($data['user_ID']))?intval($data['user_ID']):'';
	$fileID = (isset($data['file_ID']))?intval($data['file_ID']):'';	
	
	$query = "	SELECT id 
				FROM ".RESOURCES_TABLE." 
				WHERE user_id = ".$userID."
				AND	file_id = ".$fileID;	
						
	$resources_ID = $wpdb->get_row($query);
	if($resources_ID)
		return $resources_ID->id;
	else
		return false;
}
/********************************
 * Get column
*********************************/
function get_column_resources($ID = '', $column = 'id'){
	global $wpdb;		
	
	$query = "	SELECT ".esc_sql($column)." 
				FROM ".RESOURCES_TABLE." 
				WHERE id = ".$ID;
							
	$resources_ID = $wpdb->get_row($query);
	if($resources_ID)
		return $resources_ID->$column;
	else
		return false;
}


add_action( 'wp_ajax_count_download', 'count_download_func' );
add_action( 'wp_ajax_nopriv_count_download', 'count_download_func' );
function count_download_func() {
	
	$userID = (isset($_POST['user_id']))?intval($_POST['user_id']):'';
	$fileID = (isset($_POST['file_id']))?intval($_POST['file_id']):'';	
	
	if(!$fileID || !$userID) wp_send_json_error();
	
	$hasUser = check_exits_post_by_id($userID);
	$hasFile = check_exits_post_by_id($fileID,'all-resources');
	
	if(!$hasUser || !$hasFile) wp_send_json_error();
		
	$add_resources = add_resources(array(
		'user_ID'	=>	$userID,
		'file_ID'	=>	$fileID
	));
	if($add_resources) wp_send_json_success();
	else wp_send_json_error();
	
}

function get_download_resources($column_name = 'user_id', $id = ''){
	global $wpdb;
	$id = intval($id);
	$column_name = sanitize_title($column_name);
	
	if($column_name == 'user_id') $column_name2 = 'file_id';
	else $column_name2 = 'user_id';
	
	$list_download = $wpdb->get_results( 
		"
		SELECT id, ".$column_name2.", count
		FROM ".RESOURCES_TABLE."
		WHERE ".$column_name." = ".$id
	);
	if($list_download) return $list_download;
	else return false;	
}

function get_user_email_register($idUser){
	return get_post_meta($idUser, 'email_user', true);
};