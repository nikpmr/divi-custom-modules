<?php
if(!defined( 'ABSPATH' )){
	require($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
	if(!is_user_logged_in()) exit(http_response_code(401));
}

$city = trim($_GET['city']);
$state = trim($_GET['state']);
$app_id = trim($_GET['app_id']);
$app_key = trim($_GET['app_key']);

$url = 'https://api.schooldigger.com/v1.2/schools';
$params = array(
	'q' 		=> $city,
	'st'		=> $state,
	'appID'		=> $app_id,
	'appKey'	=> $app_key,
	'perPage'	=> 50
);
$result = curlFetch($url, $params);
echo $result;

function curlFetch($url, $params){
	$curl = curl_init( $url . '?' . http_build_query($params) );
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	$output = curl_exec($curl);
	curl_close($curl);
	return $output;
}