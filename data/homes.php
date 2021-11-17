<?php
if(!defined( 'ABSPATH' )){
	require($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
	if(!is_user_logged_in()) exit(http_response_code(401));
}

include_once('phpQuery.php');

$webpage = $_GET['webpage'];
$row_selector = $_GET['row'];

$url_selector = trim(explode(';', $_GET['url'])[0]); 
$url_attr = (isset(explode(';', $_GET['url'])[1])) ? trim(explode(';', $_GET['url'])[1]) : false;

$image_selector = trim(explode(';', $_GET['image'])[0]); 
$image_attr = (isset(explode(';', $_GET['image'])[1])) ? trim(explode(';', $_GET['image'])[1]) : false;

$address_selector = trim(explode(';',$_GET['address'])[0]); 
$address_attr = (isset(explode(';', $_GET['address'])[1])) ? trim(explode(';', $_GET['address'])[1]) : false;

$price_selector = trim(explode(';',$_GET['price'])[0]); 
$price_attr = (isset(explode(';', $_GET['price'])[1])) ? trim(explode(';', $_GET['price'])[1]) : false;

$size_selector = trim(explode(';',$_GET['size'])[0]); 
$size_attr = (isset(explode(';', $_GET['size'])[1])) ? trim(explode(';', $_GET['size'])[1]) : false;

$rooms_selector = trim(explode(';',$_GET['rooms'])[0]); 
$rooms_attr = (isset(explode(';', $_GET['rooms'])[1])) ? trim(explode(';', $_GET['rooms'])[1]) : false;

$home_list =  [];

$phantom_js_url = 'https://phantomjscloud.com/api/browser/v2/ak-3qc36-vkjm8-mpd79-h2y71-bv7r7/?request={url:%22' . $webpage . '%22,renderType:%22html%22}';

phpQuery::newDocument( curl_fetch( $phantom_js_url ) );
foreach(pq($row_selector) as $i => $row){
	if($url_attr) $home_list[$i]['url'] = pq($row)->find($url_selector)->attr($url_attr);
	else $home_list[$i]['url'] = pq($row)->find($url_selector)->html();
	if(strpos('://', $home_list[$i]['url']) === false) $home_list[$i]['url'] = rtrim($webpage, '/') . '/' . trim($home_list[$i]['url'], '/');
	
	if($image_attr) $home_list[$i]['image'] = pq($row)->find($image_selector)->attr($image_attr);
	else $home_list[$i]['image'] = pq($row)->find($image_selector)->html();
	
	if($address_attr) $home_list[$i]['address'] = pq($row)->find($address_selector)->attr($address_attr);
	else $home_list[$i]['address'] = pq($row)->find($address_selector)->html();
	
	if($price_attr) $home_list[$i]['price'] = pq($row)->find($price_selector)->attr($price_attr);
	else $home_list[$i]['price'] = pq($row)->find($price_selector)->html();
	
	if($size_attr) $home_list[$i]['size'] = pq($row)->find($size_selector)->attr($size_attr);
	else $home_list[$i]['size'] = pq($row)->find($size_selector)->html();
	
	if($rooms_attr) $home_list[$i]['rooms'] = pq($row)->find($rooms_selector)->attr($rooms_attr);
	else $home_list[$i]['rooms'] = pq($row)->find($rooms_selector)->html();
}

echo( json_encode($home_list) );

function curl_fetch($url){
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	$output = curl_exec($curl);
	curl_close($curl);
	return $output;
}