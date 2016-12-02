<?php
/*
 * Download csv last modified and creat my local by ftp
 * */
$download = isset($_GET['download']) ? 1 : 0;
if(!$download) exit();

$ftp_server 	= 	"";
$ftp_username	=	'';
$ftp_userpass	=	'';
$remoteFile 	= 	'/public_html/igel/Export/';
$ftp_conn 		= 	ftp_connect($ftp_server) or die("Could not connect to $ftp_server");	

$local_folder	=	'partner.csv';

// login
//ob_start();
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
	
	if (ftp_get($ftp_conn, $local_folder, $server_file, FTP_BINARY)){
	    echo "Holy Crap Finally!\n";
	}else{
	    echo "Of Course.\n";
	    die();
	}
}
//ob_end_clean();
// close connection
ftp_close($ftp_conn);