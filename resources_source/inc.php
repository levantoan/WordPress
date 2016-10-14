<?php
define('TEMP_URL_OG',get_stylesheet_directory_uri());
$resources_option = wp_parse_args(get_option('resources_options'),array(
	'mailchimp_api_key'	=>	'',
	'mailchimp_list_id'	=>	''
));
define('API_MAILCHIMP_OG',$resources_option['mailchimp_api_key']);//81e5118c0e569b78adc82200439046c3-us11
define('IDLIST_MAILCHIMP_OG',$resources_option['mailchimp_list_id']);//2779cace18
$resources_table = $wpdb->prefix . "resources";

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
	$checkEmail = checkUserMailchimp($resources);
	if(!$checkEmail || ($checkEmail && $checkEmail != 'subscribed')) return false;
	if(check_user_by_email($resources)) return true;		
	return false;
}

function subscribedsyncMailchimp($data = array()) {
    $apiKey = API_MAILCHIMP_OG;
    $listId = IDLIST_MAILCHIMP_OG;

    $memberId = md5(strtolower($data['email']));
    $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
    $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;
    /*
     * Add user to interest group
     * Get interest category https://us12.api.mailchimp.com/3.0/lists/'.$listId.'/interest-categories?apikey='.$api_mailchimp
     * Get interest ID https://us12.api.mailchimp.com/3.0/lists/'.$listId.'/interest-categories/'.$interest_category_ID.'/interests?apikey='.$api_mailchimp
     * */
	if($data['receive_infor'] == 'yes' ){
    	$group = array( '53be256b83' => true );
	}else{
		$group = array( '53be256b83' => false );
	}
    
    $json = json_encode(array(
        'email_address' => $data['email'],
        'status'        => 'pending', // "subscribed","unsubscribed","cleaned","pending"
        'merge_fields'  => array(
            'COMPANY'     => $data['company']
        ),
        'interests'	=> $group
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
	$company = (isset($_POST['company'])) ? sanitize_text_field($_POST['company']) : '';
	$receive_infor = (isset($_POST['receive_infor_f'])) ? sanitize_text_field($_POST['receive_infor_f']) : 'no';
	
	$dataRegister = array(
	    'email'     	=>	$email,
		'company'		=>	$company,
		'receive_infor'	=>	$receive_infor
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
	$vid_query = get_posts($args);
	if(is_array($vid_query) && !empty($vid_query) && !is_wp_error($vid_query))
		return $vid_query[0];
	else 
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
	if(is_array($users) && !empty($users) && !is_wp_error($users)){
		return $users[0]->ID;
	}else{ 
		if($post_type == 'register') delete_resource_by_user_id($ID);
		if($post_type == 'all-resources') delete_resource_by_file_id($ID);
		return false;
	}
}

/********************************
 * Creat TABLE
*********************************/
function creat_table_resources(){	
	global $wpdb, $resources_table;
	global $charset_collate;
	/*
	$sql = "DROP TABLE IF EXISTS $resources_table;";
    $wpdb->query($sql);*/	
	if($wpdb->get_var("SHOW TABLES LIKE '$resources_table'") != $resources_table) {
		$sql = "CREATE TABLE $resources_table(
					`id` int(10) NOT NULL auto_increment,
					`user_id` int(10) NOT NULL,
					`file_id` int(10) NOT NULL,
					`count` int(10) NOT NULL,
					`time_down` datetime NOT NULL,
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
	global $wpdb, $resources_table;	
	$data = wp_parse_args($data,array(
		'user_ID'	=>	'',
		'file_ID'	=>	''
	));
	$userID = (isset($data['user_ID']))?intval($data['user_ID']):'';
	$fileID = (isset($data['file_ID']))?intval($data['file_ID']):'';
	$count = 1;
	
	if(!$userID || !$fileID) return false;	
	
	$wpdb->insert( 
		$resources_table, 
		array( 
			'user_id' 	=>	$userID, 
			'file_id'	=>	$fileID,
			'count'		=>	$count,
			'time_down'	=>	current_time('mysql', 1)
		), 
		array( 
			'%d', 
			'%d',
			'%d',
			'%s'
		) 
	);
	
	return true;
}
/********************************
 * Update row
*********************************/
function update_resources($ID){
	global $wpdb, $resources_table;	
	$count = 1;	
	$currentCount = get_column_resources($ID,'count');
	if($currentCount) $count = $currentCount + 1;
		
	$wpdb->update( 
		$resources_table, 
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
	global $wpdb, $resources_table;	
	$ID = intval($ID);		
	$wpdb->delete( $resources_table, array( 'id' => $ID ), array( '%d' ) );
	return true;	
}

function delete_resource_by_user_id($UserID){
	global $wpdb, $resources_table;	
	$UserID = intval($UserID);		
	$wpdb->delete( $resources_table, array( 'user_id' => $UserID ), array( '%d' ) );
	return true;	
}

function delete_resource_by_file_id($fileID){
	global $wpdb, $resources_table;	
	$fileID = intval($fileID);		
	$wpdb->delete( $resources_table, array( 'file_id' => $fileID ), array( '%d' ) );
	return true;	
}

/********************************
 * Get row
*********************************/
function get_resource($data){	
	global $wpdb, $resources_table;	
	$data = wp_parse_args($data,array(
		'user_ID'	=>	'',
		'file_ID'	=>	''
	));
	$userID = (isset($data['user_ID']))?intval($data['user_ID']):'';
	$fileID = (isset($data['file_ID']))?intval($data['file_ID']):'';	
	
	$query = "	SELECT id 
				FROM $resources_table 
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
	global $wpdb, $resources_table;		
	
	$query = "	SELECT ".esc_sql($column)." 
				FROM $resources_table
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
	global $wpdb, $resources_table;
	$id = intval($id);
	$column_name = sanitize_title($column_name);
	
	if($column_name == 'user_id') $column_name2 = 'file_id';
	else $column_name2 = 'user_id';
	
	$list_download = $wpdb->get_results( 
		"
		SELECT id, $column_name2, time_down
		FROM $resources_table
		WHERE ".$column_name." = ".$id
	);
	if($list_download) return $list_download;
	else return false;	
}

function get_user_email_register($idUser){
	return get_post_meta($idUser, 'email_user', true);
};

add_action('admin_menu', 'resources_setting_menu');
function resources_setting_menu() {
	add_submenu_page( 
		'edit.php?post_type=all-resources', 
		'Setting', 
		'Resources Setting', 
		'manage_options', 
		'resources-setting',
		'resources_setting_callback'
	);
}
function resources_setting_callback(){
	include 'options-page.php';
}
add_action( 'admin_init', 'resources_register_mysettings' );
function resources_register_mysettings() {
	register_setting( 'resources-options-group', 'resources_options' );
}

function export_data_resourece_func(){
	if ( !wp_verify_nonce( $_REQUEST['nonce'], "export_nonce_action")) {
    	exit("No naughty business please");
	}
	global $wpdb, $resources_table;
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	date_default_timezone_set('Europe/London');
	
	if (PHP_SAPI == 'cli')
		die('This example should only be run from a Web Browser');
	
	/** Include PHPExcel */
	require_once dirname(__FILE__) . '/PHPExcel/PHPExcel.php';
	
	
	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();
	
	// Set document properties
	$objPHPExcel->getProperties()->setCreator("NextStep")
								 ->setLastModifiedBy("NextStep")
								 ->setTitle("Resource Download")
								 ->setSubject("Resource Download")
								 ->setDescription("")
								 ->setKeywords("resource download")
								 ->setCategory("Resource");
	
	
	// Add some data
	$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A1', 'Document')
	            ->setCellValue('B1', 'Email Address')
	            ->setCellValue('C1', 'Company')	            
	            ->setCellValue('D1', 'Date');                   
	
	$list_download = $wpdb->get_results( 
		"
		SELECT user_id, file_id, time_down
		FROM $resources_table"
	);
	
	if(isset($list_download) && is_array($list_download)):
		$stt = 2;
		foreach ($list_download as $downloader):
			if(!check_exits_post_by_id($downloader->user_id)) continue;
			if(!check_exits_post_by_id($downloader->file_id,'all-resources')) continue;
			$objPHPExcel->setActiveSheetIndex(0)
	            ->setCellValue('A'.$stt, get_the_title($downloader->file_id))
	            ->setCellValue('B'.$stt, get_user_email_register($downloader->user_id))	            
	            ->setCellValue('C'.$stt, get_post_meta($downloader->user_id,'company_user',true))
	            ->setCellValue('D'.$stt, get_date_from_gmt($downloader->time_down, 'F j, Y H:i:s'));
		$stt++;
		endforeach;
	endif;wp_reset_query();
	
	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle('Resources List Download');
	
	
	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
	
	
	// Redirect output to a client’s web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="resources _download_'.date('dmY').'.xls"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');
	
	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	exit;
}
add_action( 'wp_ajax_export_data_resourece', 'export_data_resourece_func' );