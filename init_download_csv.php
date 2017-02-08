<?php
function action_download_csv_func(){
	$ftp_server 	= 	"ftps://194.76.158.251:63421/Export/";
	$ftp_username	=	'FTPSFINDER';
	$ftp_userpass	=	'p6aALS9v';
	
	$localFile 	=	ABSPATH.'/CSV-Partner/partner.csv';
	
	function sort_rawlist($a, $b) {
		$s1 = preg_split("/ /", $a);
		$s2 = preg_split("/ /", $b);
		$d = $s1[6];
		$m = $s1[5];
		$y = $s1[7];
		$t = '00:00';
		if (preg_match('/^\d+:\d+$/',$y) > 0) { // time
			$t = $y;
			$y = date('Y', time());
		}
		$stamp = $d.' '.$m.' '.$y.' '.$t.':00';
		$time1 = strtotime($stamp);
		$d = $s2[6];
		$m = $s2[5];
		$y = $s2[7];
		$t = '00:00';
		if (preg_match('/^\d+:\d+$/',$y) > 0) { // time
			$t = $y;
			$y = date('Y', time());
		}
		$stamp = $d.' '.$m.' '.$y.' '.$t;
		$time2 = strtotime($stamp);
		if ($time1 == $time2) {
			return 0;
		}
		return ($time1 < $time2)?1:-1;
	}
	
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $ftp_server);
	
	curl_setopt($curl, CURLOPT_USERPWD, $ftp_username.':'.$ftp_userpass);
	
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); 
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); 
	curl_setopt($curl, CURLOPT_FTP_SSL, CURLFTPSSL_ALL);
	curl_setopt($curl, CURLOPT_FTPSSLAUTH, CURLFTPAUTH_TLS);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_TIMEOUT, 10);
	
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'LIST -a');
	
	$result = curl_exec($curl);
	$error_no = curl_errno($curl);
	
	$result = explode("\n", $result);	
	$result = array_filter($result, 'strlen'); //delete array empty
	
	if(empty($result) && !is_array($result)) die();
	
	usort($result, 'sort_rawlist');//order by time
	
	$rawlist = preg_split("/\s+/", $result[0]);
	$server_file = 	$rawlist[8];
	
	//Creat csv if not exits
	if (!file_exists($localFile)){
		$list = array (
		    array('creat', 'csv'),
		);
		
		$fp = fopen($localFile, 'w');
		
		foreach ($list as $fields) {
		    fputcsv($fp, $fields);
		}
		
		fclose($fp);
	}
	
	$fp = fopen($localFile, "w");
	
	if($server_file){
		if ( curl_setopt( $curl, CURLOPT_URL, $ftp_server . $server_file )){			
			$data = curl_exec($curl);
			fwrite($fp, $data);
		}
	}	
	curl_close($curl);
	fclose($fp);
}

add_action('action_download_csv','action_download_csv_func');

//Creat table address_igel_partner
function creat_table_address_igel_partner(){
	global $wpdb;
	$table_name = $wpdb->prefix . 'address_igel_partner';
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
		$sql = "CREATE TABLE $table_name (
			ID varchar(10) NOT NULL,
			NAME varchar(100) NOT NULL,
			ADDRESS1_LINE1 varchar(100) NULL,
			ADDRESS1_LINE2 varchar(100) NULL,
			ADDRESS1_POSTALCODE varchar(10) NULL,
			ADDRESS1_CITY varchar(100) NULL,
			UD_COUNTRYNAME varchar(100) NULL,
			TELEPHONE1 varchar(100) NULL,
			FAX varchar(100) NULL,
			EMAILADDRESS1 varchar(100) NULL,
			WEBSITEURL varchar(100) NULL,
			LAT varchar(100) NULL,
			LNG varchar(100) NULL,			
			UNIQUE KEY ID (id)
		) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci;";
	
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}
}
add_action( 'wp_loaded', 'creat_table_address_igel_partner' );

function add_partner_to_sql($data = array()){	
	global $wpdb;
	$table_name = $wpdb->prefix . 'address_igel_partner';
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
		
		$data = wp_parse_args($data,array(
			'ID'	=> '',
			'NAME'	=> '',
			'ADDRESS1_LINE1'	=> '',
			'ADDRESS1_LINE2'	=> '',
			'ADDRESS1_POSTALCODE'	=> '',
			'ADDRESS1_CITY'	=> '',
			'UD_COUNTRYNAME'	=> '',
			'TELEPHONE1'	=> '',
			'FAX'	=> '',
			'EMAILADDRESS1'	=> '',
			'WEBSITEURL'	=> '',
			'LAT'	=> '',
			'LNG'	=> ''			
		));		
				
		$wpdb->insert( 
			$table_name, 
			array( 
				'ID' 					=>	$data['ID'], 
				'NAME' 					=>	$data['NAME'],
				'ADDRESS1_LINE1'		=>	$data['ADDRESS1_LINE1'],
				'ADDRESS1_LINE2'		=>	$data['ADDRESS1_LINE2'],
				'ADDRESS1_POSTALCODE'	=>	$data['ADDRESS1_POSTALCODE'],
				'ADDRESS1_CITY'			=>	$data['ADDRESS1_CITY'],
				'UD_COUNTRYNAME'		=>	$data['UD_COUNTRYNAME'],
				'TELEPHONE1'			=>	$data['TELEPHONE1'],
				'FAX'					=>	$data['FAX'],
				'EMAILADDRESS1'			=>	$data['EMAILADDRESS1'],
				'WEBSITEURL'			=>	$data['WEBSITEURL'],
				'LAT'					=>	$data['LAT'],
				'LNG'					=>	$data['LNG'],				
			), 
			array( 
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
			) 
		);
		return true;
	}else{
		return false;
	}
}

function update_partner_to_sql($ID = '', $data = array()){	
	global $wpdb;
	$table_name = $wpdb->prefix . 'address_igel_partner';
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
		
		$data = wp_parse_args($data,array(
			'ID'	=> '',
			'NAME'	=> '',
			'ADDRESS1_LINE1'	=> '',
			'ADDRESS1_LINE2'	=> '',
			'ADDRESS1_POSTALCODE'	=> '',
			'ADDRESS1_CITY'	=> '',
			'UD_COUNTRYNAME'	=> '',
			'TELEPHONE1'	=> '',
			'FAX'	=> '',
			'EMAILADDRESS1'	=> '',
			'WEBSITEURL'	=> '',
			'LAT'	=> '',
			'LNG'	=> ''			
		));		

		$wpdb->update( 
			$table_name, 
			array(  
				'NAME' 					=>	$data['NAME'],
				'ADDRESS1_LINE1'		=>	$data['ADDRESS1_LINE1'],
				'ADDRESS1_LINE2'		=>	$data['ADDRESS1_LINE2'],
				'ADDRESS1_POSTALCODE'	=>	$data['ADDRESS1_POSTALCODE'],
				'ADDRESS1_CITY'			=>	$data['ADDRESS1_CITY'],
				'UD_COUNTRYNAME'		=>	$data['UD_COUNTRYNAME'],
				'TELEPHONE1'			=>	$data['TELEPHONE1'],
				'FAX'					=>	$data['FAX'],
				'EMAILADDRESS1'			=>	$data['EMAILADDRESS1'],
				'WEBSITEURL'			=>	$data['WEBSITEURL'],
				'LAT'					=>	$data['LAT'],
				'LNG'					=>	$data['LNG'],				
			), 
			array( 'ID' => $ID ), 
			array( 
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
			),
			array( '%s' )
		);		
		return true;
	}else{
		return false;
	}
}
function check_partner_change($datanew = array()){	
	global $wpdb;
	$table_name = $wpdb->prefix . 'address_igel_partner';
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
		
		$datanew = wp_parse_args($datanew,array(
			'ID'	=> '',
			'NAME'	=> '',
			'ADDRESS1_LINE1'	=> '',
			'ADDRESS1_LINE2'	=> '',
			'ADDRESS1_POSTALCODE'	=> '',
			'ADDRESS1_CITY'	=> '',
			'UD_COUNTRYNAME'	=> '',
			'TELEPHONE1'	=> '',
			'FAX'	=> '',
			'EMAILADDRESS1'	=> '',
			'WEBSITEURL'	=> ''		
		));		
		$my_partner_exits = $wpdb->get_row( "
									SELECT ID 
									FROM $table_name
									WHERE ID = '".esc_sql($datanew['ID'])."'");
		$my_partner = $wpdb->get_row( "
									SELECT * 
									FROM $table_name
									WHERE ID = '".esc_sql($datanew['ID'])."'
									AND NAME = '".esc_sql($datanew['NAME'])."'
									AND ADDRESS1_LINE1 = '".esc_sql($datanew['ADDRESS1_LINE1'])."'
									AND ADDRESS1_LINE2 = '".esc_sql($datanew['ADDRESS1_LINE2'])."'
									AND ADDRESS1_POSTALCODE = '".esc_sql($datanew['ADDRESS1_POSTALCODE'])."'
									AND ADDRESS1_CITY = '".esc_sql($datanew['ADDRESS1_CITY'])."'
									AND UD_COUNTRYNAME = '".esc_sql($datanew['UD_COUNTRYNAME'])."'
									AND TELEPHONE1 = '".esc_sql($datanew['TELEPHONE1'])."'
									AND FAX = '".esc_sql($datanew['FAX'])."'
									AND EMAILADDRESS1 = '".esc_sql($datanew['EMAILADDRESS1'])."'
									AND WEBSITEURL = '".esc_sql($datanew['WEBSITEURL'])."'");
		if(!empty($my_partner_exits) && !empty($my_partner)){
			return '1'; // nothing
		}elseif (empty($my_partner_exits)){
			return '2';//add new
		}elseif(!empty($my_partner_exits) && empty($my_partner)){
			return '3'; //update
		}	
	}else{
		return '1';
	}
}

function search_location_address($address1 = '', $addres2 = '', $region = '', $name = ''){
	$url_igel		=	"https://maps.google.com/maps/api/geocode/json?address=".esc_attr($address1).",".esc_attr($addres2).",".esc_attr($region)."&sensor=false&region=".$region;							
	$resp_igel		=	@wp_remote_get($url_igel);
	$location = array();
	if (!is_wp_error($resp_igel) && 200 == wp_remote_retrieve_response_code( $resp_igel ) ){
		$resp_json_igel	=	json_decode($resp_igel['body']);
		$lat_resul		=	$resp_json_igel->results;
		if(!empty($lat_resul)){
			$latlng_resul	=	$lat_resul['0']->geometry->location;
			$lat_igel		=	$latlng_resul->lat;
			$lng_igel		=	$latlng_resul->lng;
			$location = array(
				'lat'	=>	$lat_igel,
				'lng'	=>	$lng_igel
			);
			return $location;
		}else{
			$url_igel		=	"https://maps.google.com/maps/api/geocode/json?address=".esc_attr($address1).",".esc_attr($addres2)."&sensor=false";							
			$resp_igel		=	@wp_remote_get($url_igel);
			$location = array();
			if (!is_wp_error($resp_igel) && 200 == wp_remote_retrieve_response_code( $resp_igel ) ){
				$resp_json_igel	=	json_decode($resp_igel['body']);
				$lat_resul		=	$resp_json_igel->results;
				if(!empty($lat_resul)){
					$latlng_resul	=	$lat_resul['0']->geometry->location;
					$lat_igel		=	$latlng_resul->lat;
					$lng_igel		=	$latlng_resul->lng;
					$location = array(
						'lat'	=>	$lat_igel,
						'lng'	=>	$lng_igel
					);
					return $location;
				}else{
					$url_igel		=	"https://maps.google.com/maps/api/geocode/json?address=".esc_attr($name)."&sensor=false";							
					$resp_igel		=	@wp_remote_get($url_igel);
					$location = array();
					if (!is_wp_error($resp_igel) && 200 == wp_remote_retrieve_response_code( $resp_igel ) ){
						$resp_json_igel	=	json_decode($resp_igel['body']);
						$lat_resul		=	$resp_json_igel->results;
						if(!empty($lat_resul)){
							$latlng_resul	=	$lat_resul['0']->geometry->location;
							$lat_igel		=	$latlng_resul->lat;
							$lng_igel		=	$latlng_resul->lng;
							$location = array(
								'lat'	=>	$lat_igel,
								'lng'	=>	$lng_igel
							);
							return $location;
						}
					}
				}
			}
		}
	}
	return false;
}

function insert_address_tosql(){
	
	include 'parsecsv.lib.php';
	$local_folder	=	ABSPATH.'/CSV-Partner/partner.csv';
	
	$csv = new parseCSV();
	$csv->encoding('UTF-16', 'UTF-8');
	$csv->delimiter = ";";
	$csv->auto($local_folder);
	$dataCSV = $csv->data;
	/*
	$error_add = array(14,17,22,30,38,40,41,43,44,52,53,54,55,57,58,102,103,106,118,121,130,131,185,196,202,215,217,218,237,266,268,273,274,275,284,288,291,303,308,379,386,389,391,399,404,410,417,430,431,439,451,495,500,515,535,578,579,604,625,656,666,668,676,678,680,688,703,719,727,739,779,795,796,809,810,839,845,904,965,967,975,1001,1042,1060,1067,1073,1090,1115,1120);
	
	foreach ($error_add as $add){
		$partner = $dataCSV[$add];		
		$location = search_location_address(esc_attr($partner['ADDRESS1_LINE1']),esc_attr($partner['ADDRESS1_CITY']),esc_attr($partner['UD_COUNTRYNAME']),esc_attr($partner['NAME']));
		if($location){
			$partner['LAT'] = $location['lat'];
			$partner['LNG'] = $location['lng'];	
			if($partner['LAT'] && $partner['LNG']){
				add_partner_to_sql($partner);	
				echo 'Add ok' .$add .'<br>';					
			}else{
				echo 'Error' .$add . $partner['LNG'] .', '. $partner['LNG'] .'<br>';
			}
		}else{
			echo 'Not ok';
		}
	}
	exit();
	*/
	if(!empty($dataCSV) && !is_wp_error($dataCSV)){
		foreach($dataCSV as $k=>$partner){
			if(check_partner_change($partner) == '2'){
				//search location	
				$location = search_location_address(esc_attr($partner['ADDRESS1_LINE1']),esc_attr($partner['ADDRESS1_CITY']),esc_attr($partner['UD_COUNTRYNAME']),esc_attr($partner['NAME']));
				if(!empty($location) && !is_wp_error($location)){
					$partner['LAT'] = $location['lat'];
					$partner['LNG'] = $location['lng'];	
					if($partner['LAT'] && $partner['LNG']){
						add_partner_to_sql($partner);						
					}
				}
			}elseif (check_partner_change($partner) == '3'){
				//search location
				$location = search_location_address(esc_attr($partner['ADDRESS1_LINE1']),esc_attr($partner['ADDRESS1_CITY']),esc_attr($partner['UD_COUNTRYNAME']),esc_attr($partner['NAME']));
				if(!empty($location) && !is_wp_error($location)){
					$partner['LAT'] = $location['lat'];
					$partner['LNG'] = $location['lng'];		
					if($partner['LAT'] && $partner['LNG']){	
						update_partner_to_sql($partner['ID'], $partner);						
					}
				}
			}
		}		
	}
}
add_action( 'insert_address_tosql_action', 'insert_address_tosql' );
//add_action( 'wp_ajax_insert_address_tosql_action', 'insert_address_tosql' );
//add_action( 'wp_ajax_nopriv_insert_address_tosql_action', 'insert_address_tosql' );