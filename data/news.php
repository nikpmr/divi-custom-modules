<?php
if(!defined( 'ABSPATH' )){
	require($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
	if(!is_user_logged_in()) exit(http_response_code(401));
}

include_once('phpQuery.php');

$region = trim(urldecode($_GET['region']));

$news_list =  []; // Patch
$results = curlFetch( 'https://patch.com' . $region );
phpQuery::newDocument($results);
foreach(pq('#main article') as $i => $row){
	pq($row)->html( preg_replace( '/__.....\"/', '"', pq($row)->html() ) );
	pq($row)->html( str_replace( '<!-- -->, <!-- -->Patch Staff', '', pq($row)->html() ) );
	$type = (strpos(pq($row)->html(), 'styles_LocalStreamCard') === FALSE) ? 'normal' : 'facebook';
	if($type == 'normal' && empty(pq($row)->find('.styles_Card__TitleLink')->html())) continue; // Skip empty articles

	if($type == 'normal'){ // Is normal article
		$news_list[$i]['moreInfoLink'] = 'https://patch.com' . pq($row)->find('.styles_Card__TitleLink')->attr('href');
		$news_list[$i]['title'] = pq($row)->find('.styles_Card__TitleLink')->html();
		$news_list[$i]['byline'] = pq($row)->find('.styles_Card__Byline')->html();
		$news_list[$i]['description'] = pq($row)->find('.styles_Card__Description')->html();
		$news_list[$i]['date'] = pq($row)->find('time')->attr('datetime');
		$news_list[$i]['region'] = pq($row)->find('.styles_Card__CommunityName span')->html();
	}
	else if($type == 'facebook'){ // Is Facebook article
		$news_list[$i]['moreInfoLink'] = pq($row)->find('.styles_LocalStreamCard__ExternalLink')->attr('href');
		$news_list[$i]['title'] = 'From Social Media';
		$news_list[$i]['byline'] = pq($row)->find('.styles_Name__Text')->html();
		$news_list[$i]['description'] = pq($row)->find('.styles_Card__Body')->html();
		$news_list[$i]['date'] = '';
		$news_list[$i]['region'] = pq($row)->find('.styles_ProfileDetails__ProfileText')->eq(0)->html();
	}
	$news_list[$i]['image'] = pq($row)->find('noscript img.styles_Card__ThumbnailImage')->attr('src');
	$news_list[$i]['postType'] = $type;
	$news_list[$i]['category'] = 'News';
	// $news_list[$i]['html'] = pq($row)->html();
}
echo( json_encode($news_list) );

function curlFetch($url){
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array("User-Agent: Mozilla/5.0 (Linux) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Mobile Safari/537.36"));
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	$output = curl_exec($curl);
	curl_close($curl);
	return $output;
}