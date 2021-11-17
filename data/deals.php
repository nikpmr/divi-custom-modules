<?php
if(!defined( 'ABSPATH' )){
	require($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
	// if(!is_user_logged_in()) exit(http_response_code(401));
}

include_once('phpQuery.php');

$zip = trim($_GET['zip']);
$deal_list =  [];
$distance = '5.0';
$total_pages = 1;
$page_limit = 2; // Set to false for no limit

// Geolocate zip code
$lat_lng_url = plugin_dir_url( __FILE__ ) . 'geocode.php?address=' . $zip;
$lat_lng_results = json_decode( curl_fetch($lat_lng_url), true );
$lat = round($lat_lng_results['lat'], 3);
$lng = round($lat_lng_results['lng'], 3);

	
	$phantom_js_api_key = 'ak-3qc36-vkjm8-mpd79-h2y71-bv7r7';
	$results_url = "https://www.groupon.com/browse/detroit?page=$page&lat=$lat&lng=$lng&distance=%5B0.0..5.0%5D";
	$phantom_js_url = 'https://phantomjscloud.com/api/browser/v2/'. $phantom_js_api_key .'/?request={url:%22'. urlencode($results_url) .'%22,renderType:%22html%22,requestSettings:{userAgent:%22Mozilla/5.0%20(Linux)%20AppleWebKit/537.36%20(KHTML,%20like%20Gecko)%20Chrome/81.0.4044.138%20Mobile%20Safari/537.36%22}}';
	$results = curl_fetch($phantom_js_url);
	
	// echo($results); die();

	phpQuery::newDocument($results);

	foreach(pq('figure.card-ui') as $j => $row){
		if(pq($row)->find('.cui-location-name')->length == 0) continue; // Skip if no address found (non-local deal)

		$id = str_replace( 'deal:', '', pq($row)->attr('data-bhc') );
		$deal_list[$id]['moreInfoLink'] = 'https://www.groupon.com/deals/' . $id;
		$deal_list[$id]['name'] = trimAll( pq($row)->find('.cui-udc-subtitle')->html() );
		$deal_list[$id]['company'] = trimAll( pq($row)->find('.cui-udc-title')->html() );
		$deal_list[$id]['address'] = trimAll( pq($row)->find('.cui-location-name')->html() );
		$deal_list[$id]['ratingCount'] = trimAll( pq($row)->find('.cui-review-rating .rating-count')->html() );
		$deal_list[$id]['category'] = 'Deal';
		$deal_list[$id]['image'] = trimAll( pq($row)->find('.cui-image')->attr('srcset') );
	}
	


$deal_list = array_values($deal_list);
echo( json_encode($deal_list) );

function trimAll($string){
	return str_replace("\n", "", trim($string));
}
function curl_fetch($url){
	$user_agent = "User-Agent: Mozilla/5.0 (Linux) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Mobile Safari/537.36";
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array($user_agent));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$output = curl_exec($ch);
	curl_close($ch);
	return $output;
}