<?php
function action_download_csv_func(){
	$ftp_server 	= 	"";
	$ftp_username	=	'';
	$ftp_userpass	=	'';
	$remoteFile 	= 	'/public_html/Export/';
	$ftp_conn 		= 	ftp_connect($ftp_server) or die("Could not connect to $ftp_server");	
	
	$local_folder	=	ABSPATH.'/partner.csv';
	
	function sort_rawlist($a, $b) {
		$s1 = preg_split("/ /", $a, 9, PREG_SPLIT_NO_EMPTY);
		$s2 = preg_split("/ /", $b, 9, PREG_SPLIT_NO_EMPTY);
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
	
	if (@ftp_login($ftp_conn, $ftp_username, $ftp_userpass)){
		
		ftp_pasv($ftp_conn, true);
		
		$file_list = ftp_rawlist($ftp_conn, 'public_html/igel/Export/*.csv');
		if(empty($file_list) && !is_array($file_list)) die();
		
		usort($file_list, 'sort_rawlist');
		
		$rawlist = preg_split("/\s+/", $file_list[0]);	
		$server_file = 	$rawlist[8];
		if (!file_exists($local_folder)){
			$list = array (
			    array('creat', 'csv'),
			);
			
			$fp = fopen($local_folder, 'w');
			
			foreach ($list as $fields) {
			    fputcsv($fp, $fields);
			}
			
			fclose($fp);
		}
		ftp_get($ftp_conn, $local_folder, $server_file, FTP_BINARY);
		
	}
	ftp_close($ftp_conn);
}
add_action('action_download_csv','action_download_csv_func');
