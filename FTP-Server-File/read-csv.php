<?php
//read csv
$export_folder = ABSPATH.'/CSV-Partner/partner.csv';
if(!file_exists($export_folder)) wp_send_json_error();

$datas = array_map('str_getcsv', file($export_folder));

$files = array();
foreach ($datas as $address){
	$line = '';						
	if(count($address)>0){
		foreach ($address as $ex){
			$line .= $ex;
		}
	}else{
		$line = $address;
	}
	$files[] =  explode(';', $line);
}
