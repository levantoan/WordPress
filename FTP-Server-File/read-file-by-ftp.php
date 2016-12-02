<?php
/*
Read a file csv last modified in folder
*/
$ftp_server 	= 	"ftp.domain.com";
$ftp_username	=	'ftp_user';
$ftp_userpass	=	'ftp_pass';
$remoteFile 	= 	'/public_html/igel/Export/'; //dir to file
$ftp_conn 		= 	ftp_connect($ftp_server) or die("Could not connect to $ftp_server");				
// login
ob_start();
if (@ftp_login($ftp_conn, $ftp_username, $ftp_userpass)){				  		
	// do something...
	ftp_pasv($ftp_conn, true); /* try it with and without this line to see which works (depends on server) */
	$filex = array();	
	$file_list	=	ftp_nlist($ftp_conn, "public_html/igel/Export");
	foreach($file_list as $line) 
	{ 
		ob_start();
		ftp_get($ftp_conn, 'php://output', $line, FTP_ASCII);				    	
		$filex[ftp_mdtm($ftp_conn, $line)] = ob_get_clean();
	}					    	
	asort($filex);
	$data = '';
	foreach ($filex as $firstFile){
		$data = $firstFile;
		break;
	}				    
}else{
  $data = '';
}
ob_end_clean();
// close connection
ftp_close($ftp_conn);