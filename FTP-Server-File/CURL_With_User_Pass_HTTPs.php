<?php
/*
* CURL with user and pass
* Header json
* Body json data
*/
//Curl get Key
$ftp_server 	= 	"https://999.999.999.999:4444/sub/";
$ftp_username	=	'USER';
$ftp_userpass	=	'PASS';

$data = array(
  'products' 		=> 'SUB_SERVICE_SET_UNIVERSAL_DESKTOP_CONVERTER', 
  'totalLicenses' => 3
);
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $ftp_server);
//Setting
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); 
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); 
curl_setopt($curl, CURLOPT_FTP_SSL, CURLFTPSSL_ALL);
curl_setopt($curl, CURLOPT_FTPSSLAUTH, CURLFTPAUTH_TLS);
curl_setopt($curl, CURLOPT_TIMEOUT, 10);
//User:pass
curl_setopt($curl, CURLOPT_USERPWD, $ftp_username.':'.$ftp_userpass);
//header
//curl_setopt($curl, CURLOPT_HEADER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
  'Accept: application/json'
));
//Body
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));


$result = curl_exec($curl);
$error_no = curl_errno($curl);
curl_close($curl);

if($error_no == 0){
  $result = json_decode($result,true);
  if($result && !empty($result)){
    if(isset($result['activationKey'])){			
      $activation_key = $result['activationKey'];
    }
  }
}
//#Curl get Key
