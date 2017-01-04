<?php
/*
* Contect ftps:// with CRUL
* Open file CSV form FTPS
* Creat new file on local
* Order list file (Array) by date : sort_rawlist
* Author www.levantoan.com
*/
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

$ftp_server = 'ftps://999.999.999.999:63421/Export/';

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $ftp_server);

curl_setopt($curl, CURLOPT_USERPWD, "USER:PASS");

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
$localFile = 'partner.csv';
$fp = fopen($localFile, "w");

if($server_file){
	if ( ! curl_setopt( $curl, CURLOPT_URL, $ftp_server . $server_file ))
		die( "Could not set cURL file name: $server_file" );
	$data = curl_exec($curl);
	fwrite($fp, $data);
}

curl_close($curl);
fclose($fp);
