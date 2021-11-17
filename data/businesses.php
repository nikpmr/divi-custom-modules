<?php
if(!defined( 'ABSPATH' )){
	require($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
	if(!is_user_logged_in()) exit(http_response_code(401));
}

$city = trim($_GET['city']);
$state = trim($_GET['state']);
$api_key = 'TNboLiFxzJsNO-OtVSl3YAKLzCeNFAP8r_po7ZzJuJGWqcV0tHMoVx4bf6Wv_O6MLjBvfN85HWUR51MhI85ZLWDn30woUO8dOn-6IQAwJElBeWYpObtrq80qLguAYXYx';
$business_type = $_GET['business_type']; // 'businesses' or 'restaurants'

// Instructing Yelp to search by category is not completely accurate, since a lot of restaurants show up in non-restaurant categories.
// But it does make the results easier to sort through.
$categories_param = ($business_type == 'restaurants') ? 'food,restaurants,bars'
: 'active,arts,auto,beautysvc,bicycles,education,eventservices,financialservices,health,homeservices,hotelstravel,localflavor,localservices,massmedia,nightlife,pets,professional,publicservicesgovt,realestate,religiousorgs,shopping';

$all_results = array();
for($i = 0; $i < 10; $i++){ //Obtains first 500 results, which should be enough (Yelp normally returns ~2000)
	$url = 'https://api.yelp.com/v3/businesses/search';
	$params = array(
		'location' 		=> $city . ', ' . $state,
		'categories'	=> $categories_param,
		'limit'			=> 50,
		'radius'		=> 8046, //meters (5 miles)
		'sort_by'		=> 'best_match',
		'offset'		=> $i * 50
	);
	$headers = array(
	    'Authorization: Bearer ' . $api_key
	);
	$results = curlFetch($url, $params, $headers);
	// var_dump($results);
	$results = json_decode($results, true);
	if($results['businesses']) $all_results = array_merge($all_results, $results['businesses']); // Merge all pages of results
	else break;
}

// Sort results into restaurants and businesses.
// If a result has one or more restaurant categories, it's a restaurant. Otherwise, it's a business.
$categories_test = returnCategories();
$restaurant_results = array();
$business_results = array();
foreach($all_results as $result){
	$is_restaurant_result = false;
	$result_categories = $result['categories'];
	foreach($result_categories as $result_category){
		if( in_array($result_category['alias'], $categories_test['restaurants']) ) $is_restaurant_result = true;
	}
	if($is_restaurant_result) array_push($restaurant_results, $result);
	else  array_push($business_results, $result);
}
echo ($business_type == 'restaurants') ? json_encode($restaurant_results) : json_encode($business_results);

function curlFetch( $url, $params, $headers = array() ){
	$curl = curl_init( $url . '?' . http_build_query($params) );
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	$output = curl_exec($curl);
	if( curl_errno($curl) ) $output = curl_error($curl);
	curl_close($curl);
	return $output;
}
function returnCategories(){ 
	// Pull the JSON file supplied by Yelp and sort the categories as restaurant or business categories.
	// If a category (or one of its parents or grandparents) tests positive against the $restaurant_categories_test array, 
	// it's a restaurant category. Otherwise, it's a business category.
	$restaurant_categories = array();
	$business_categories = array();
	$restaurant_categories_test = array('food', 'restaurants', 'bars');
	$json_path = __DIR__ . '/business-categories.json';
	$all_categories = json_decode(file_get_contents($json_path), true);

	foreach($all_categories as $category){
		$is_restaurant_category = false;
		if(in_array($category['alias'], $restaurant_categories_test)) $is_restaurant_category = true; // Check the alias
		else{
			$parents = $category['parents'];
			foreach($parents as $parent){ // Check the parents
				if(in_array($parent, $restaurant_categories_test)) $is_restaurant_category = true;
				else{
					foreach($all_categories as $parent_category){ // Traverse array and find the parent category
						if($parent_category['alias'] == $parent){
							$grandparents = $parent_category['parents'];
							foreach($grandparents as $grandparent){ // Check the grandparents
								if(in_array($grandparent, $restaurant_categories_test)) $is_restaurant_category = true;
							}
							break;
						}
					}
				}
			}
		}
		if($is_restaurant_category) array_push($restaurant_categories, $category['alias']);
		else array_push($business_categories, $category['alias']);
	}
	return array('restaurants' => $restaurant_categories, 'businesses' => $business_categories);
}