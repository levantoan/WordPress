<?php
// Function to get the client ip address
function get_client_ip_env() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
 
    return $ipaddress;
}
$ipaddress = get_client_ip_env();

echo 'Your ID: '.$ipaddress;

function getLocationByIP($ip = "UNKNOWN",$elements = ''){
	if($ip != "UNKNOWN"){
		$xml = simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=".$ip);
		if ($sxe === false) {
			return;
		}else{
			$onego = '';
			foreach ($xml as $key => $value)
			{
				if($key == 'geoplugin_'.$elements){
			   		$onego = $value;
				}
			}
		}
		return $onego;
	}
}

echo $countryName = getLocationByIP($ipaddress,'countryName');
echo $countryCode = getLocationByIP($ipaddress,'countryCode');

/*
geoplugin_request= 117.0.37.64 
geoplugin_status= 200 
geoplugin_credit= Some of the returned data includes GeoLite data created by MaxMind, available from http://www.maxmind.com. 
geoplugin_city= Hanoi 
geoplugin_region= Ha Ná»™i 
geoplugin_areaCode= 0 
geoplugin_dmaCode= 0 
geoplugin_countryCode= VN 
geoplugin_countryName= Vietnam 
geoplugin_continentCode= AS 
geoplugin_latitude= 21.0333 
geoplugin_longitude= 105.849998 
geoplugin_regionCode= 44 
geoplugin_regionName= Ha Ná»™i 
geoplugin_currencyCode= VND 
geoplugin_currencySymbol= ₫ 
geoplugin_currencySymbol_UTF8= â‚« 
geoplugin_currencyConverter= 22365 */

//Meta query in WP
$yourCountryCode = getLocationByIP(get_client_ip_env(), 'countryCode');
if($yourCountryCode !== false){
	$args['meta_query'] = array(
		array(
			'key' => 'choose_country',
			'value' => serialize(strval($yourCountryCode)),
			'compare' => 'LIKE',
		)
	);
}
