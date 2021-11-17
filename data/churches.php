<?php
if(!defined( 'ABSPATH' )){
	require($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
	if(!is_user_logged_in()) exit(http_response_code(401));
}

include_once('phpQuery.php');

$city = trim($_GET['city']);
$state = trim($_GET['state']);
$zip = trim($_GET['zip']);

$church_list1 =  []; // ChurchFinder
$city_hyphens = strtolower( str_replace(' ', '-', $city) );
$state_lowercase = strtolower($state);
phpQuery::newDocument( curlFetch('https://www.churchfinder.com/churches/' . $state_lowercase . '/' . $city_hyphens) );
foreach(pq('.standard-church-list-item .views-row') as $i => $row){
	$church_list1[$i]['moreInfoLink'] = pq($row)->find('.views-field-path a')->attr('href');
	$church_list1[$i]['name'] = pq($row)->find('.views-field-title a')->html();
	$church_list1[$i]['address'] = pq($row)->find('.field-name-field-address .field-item')->html();
	$church_list1[$i]['phone'] = '';
	$church_list1[$i]['category'] = trim( pq($row)->find('.field-name-field-specific-denomination')->html() );
}
$church_list2 =  []; // ReformJudaism
phpQuery::newDocument( curlFetch('https://reformjudaism.org/find-a-congregation/keywords?field_event_location_latlon=' . $zip . '&field_list_stateprovince='. $state) );
foreach(pq('.view-congregation-search .views-row') as $i => $row){
	$church_list2[$i]['moreInfoLink'] = 'https://reformjudaism.org' . pq($row)->find('h2 a')->attr('href');
	$church_list2[$i]['name'] = pq($row)->find('h2 a')->html();
	$church_list2[$i]['address'] = pq($row)->find('.field-name-field-text .field-item')->html();
	$church_list2[$i]['phone'] = pq($row)->find('.contact-info .phone')->html();
	$church_list2[$i]['website'] = pq($row)->find('.contact-info .website a')->attr('href');
	$church_list2[$i]['category'] = 'Jewish';
}
$church_list3 =  []; // IslamiCity
phpQuery::newDocument( curlFetch('http://www.islamicity.com/orgs/action.lasso.asp?-DB=services&-LAY=yellowpages&-error=orgslist.asp&-FORMAT=orgslist.asp&-Max=100&-Token=Search+Results%3A&-Op=eq&Category=OTHER&-Op=cn&-op=tz&Zip='. $zip .'&-op=tz&-tokendistance=10&-find=FIND') );
foreach(pq('.content table .content table table table tr:has(a)') as $i => $row){
	if(trim(pq($row)->find('font i')->text()) == 'Mosques'){
		$church_list3[$i]['moreInfoLink'] = pq($row)->find('a')->attr('href');
		$church_list3[$i]['name'] = pq($row)->find('a:first font')->html();
		$church_list3[$i]['address'] = trim( pq($row)->find('address')->text() );
		$church_list3[$i]['phone'] = trim( pq($row)->find('p:first')->text() );
		$church_list3[$i]['category'] = 'Islam';
		// Remove garbage text in phone number
		preg_match( '/(.+)(\d\d\d\d)/', $church_list3[$i]['phone'], $phoneTest );
		if(count($phoneTest) > 0) $church_list3[$i]['phone'] = $phoneTest[0];
	}
}

$all_churches = array_merge($church_list1, $church_list2, $church_list3);
echo( json_encode($all_churches) );

function curlFetch($url){
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	$output = curl_exec($curl);
	curl_close($curl);
	return $output;
}