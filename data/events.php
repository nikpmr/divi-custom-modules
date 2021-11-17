<?php
if(!defined( 'ABSPATH' )){
	require($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
	if(!is_user_logged_in()) exit(http_response_code(401));
}

include_once('phpQuery.php');

$city = trim($_GET['city']);
$city_urlencode = rawurlencode($city);

$event_list =  []; // AllEvents
$url = "https://allevents.in/plugin/city-events-plugin.php?width=100%&height=0&header=0&transparency=false&border=0&count=50&city=$city_urlencode&keywords=All";
$result = curl_fetch($url);
phpQuery::newDocument($result);
foreach(pq('ul.events-style-resgrid li') as $i => $row){
	$event_list[$i]['moreInfoLink'] = pq($row)->find('.title a')->attr('href');
	$event_list[$i]['name'] = pq($row)->find('.title a h3')->html();
	$event_list[$i]['address'] = pq($row)->find('.venue')->html();
	$event_list[$i]['date'] = pq($row)->find('.time, .up-time-display')->html();
	$event_list[$i]['category'] = 'Event';
	$event_list[$i]['image'] = pq($row)->find('img')->attr('src');
}

echo( json_encode($event_list) );

function curl_fetch($url){
	$user_agent = "User-Agent: Mozilla/5.0 (Linux) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Mobile Safari/537.36";
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array($user_agent));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$output = curl_exec($ch);
	curl_close($ch);
	return $output;
}