<?php

if(!defined( 'ABSPATH' )){
    require($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
    if(!is_user_logged_in()) exit(http_response_code(401));
}

$business_id = $_GET['business_id'];
$api_key = get_option('dicm_listings_businesses_api_key');

$url = 'https://api.yelp.com/v3/businesses/'.$business_id.'/reviews';
$params = array();
$headers = array(
    'Authorization: Bearer ' . $api_key
);
$results = curlFetch($url, $params, $headers);

echo json_encode($results); 

function curlFetch( $url, $params, $headers = array() ){
    $curl = curl_init( $url . '?' . http_build_query($params) );
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $output = curl_exec($curl);
    if( curl_errno($curl) ) $output = curl_error($curl);
    curl_close($curl);
    return $output;
}