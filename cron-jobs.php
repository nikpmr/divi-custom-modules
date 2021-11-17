<?php
require_once(plugin_dir_path( __FILE__ ) . 'store-data.php');

function dicm_cron_schools_run(){
    $_GET['city'] = get_option('dicm_listings_schools_city');
    $_GET['state'] = get_option('dicm_listings_schools_state');
    $_GET['app_id'] = get_option('dicm_listings_schools_app_id');
    $_GET['app_key'] = get_option('dicm_listings_schools_app_key');
    $previous_output = get_option('dicm_listings_schools_output');

    ob_start();
        include_once(plugin_dir_path( __FILE__ ) . 'data/schools.php');
        $data = json_decode(ob_get_contents(), true);
    ob_end_clean();

    // Get location data
    foreach($data['schoolList'] as $i => $listing){
        $address = preg_replace('/(<([^>]+)>)/', ' ', $listing['address']['html']);
        $location = dicm_pull_lat_lng_from_db($previous_output['schoolList'], $listing, 'schoolid');
        if(!$location) $location = dicm_fetch_lat_lng($address);
        $data['schoolList'][$i]['latLng'] = $location;
        usleep(50000);
    }

    store_data([
        'listings_type' => 'schools',
        'output'        => $data
    ]);
}
add_action('dicm_cron_schools', 'dicm_cron_schools_run');

function dicm_cron_churches_run(){
    $_GET['city'] = get_option('dicm_listings_churches_city');
    $_GET['state'] = get_option('dicm_listings_churches_state');
    $_GET['zip'] = get_option('dicm_listings_churches_zip');
    $previous_output = get_option('dicm_listings_churches_output');

    ob_start();
        include_once(plugin_dir_path( __FILE__ ) . 'data/churches.php');
        $data = json_decode(ob_get_contents(), true);  
    ob_end_clean();

    // Get location data
    foreach($data as $i => $listing){
        $address = $listing['address'];
        $location = dicm_pull_lat_lng_from_db($previous_output, $listing, 'name');
        if(!$location) $location = dicm_fetch_lat_lng($address);
        $data[$i]['latLng'] = $location;
        usleep(50000);
    }

    store_data([
        'listings_type' => 'churches',
        'output'        => $data
    ]);
}
add_action('dicm_cron_churches', 'dicm_cron_churches_run');

function dicm_cron_charities_run(){
    $_GET['city'] = get_option('dicm_listings_charities_city');
    $_GET['state'] = get_option('dicm_listings_charities_state');
    $_GET['app_id'] = get_option('dicm_listings_charities_app_id');
    $_GET['app_key'] = get_option('dicm_listings_charities_app_key');
    $previous_output = get_option('dicm_listings_charities_output');

    ob_start();
        include_once(plugin_dir_path( __FILE__ ) . 'data/charities.php');
        $data = json_decode(ob_get_contents(), true);
    ob_end_clean();

    // Get location data
    foreach($data as $i => $listing){
        $address = ''
        . $listing['mailingAddress']['streetAddress1'] . ' '
        . $listing['mailingAddress']['streetAddress2'] . ' '
        . $listing['mailingAddress']['city'] . ' '
        . $listing['mailingAddress']['stateOrProvince'];
        $location = dicm_pull_lat_lng_from_db($previous_output, $listing, 'ein');
        if(!$location) $location = dicm_fetch_lat_lng($address);
        $data[$i]['latLng'] = $location;
        usleep(50000);
    }

    store_data([
        'listings_type' => 'charities',
        'output'        => $data
    ]);
}
add_action('dicm_cron_charities', 'dicm_cron_charities_run');

function dicm_cron_events_run(){
    $_GET['city'] = get_option('dicm_listings_events_city');
    $previous_output = get_option('dicm_listings_events_output');

    ob_start();
        include_once(plugin_dir_path( __FILE__ ) . 'data/events.php');
        $data = json_decode(ob_get_contents(), true);
    ob_end_clean();

    // Get location data
    foreach($data as $i => $listing){
        $address = $listing['address'];
        $location = dicm_pull_lat_lng_from_db($previous_output, $listing, 'moreInfoLink');
        if(!$location) $location = dicm_fetch_lat_lng($address);
        $data[$i]['latLng'] = $location;
        usleep(50000);
    }

    store_data([
        'listings_type' => 'events',
        'output'        => $data
    ]);
}
add_action('dicm_cron_events', 'dicm_cron_events_run');

function dicm_cron_deals_run(){
    $_GET['zip'] = get_option('dicm_listings_deals_zip');
    $previous_output = get_option('dicm_listings_deals_output');

    ob_start();
        include_once(plugin_dir_path( __FILE__ ) . 'data/deals.php');
        $data = json_decode(ob_get_contents(), true);
    ob_end_clean();
    usleep(75000);

    // Get location data
    foreach($data as $i => $listing){
        $address = $listing['address'];
        $location = dicm_pull_lat_lng_from_db($previous_output, $listing, 'moreInfoLink');
        if(!$location) $location = dicm_fetch_lat_lng($address);
        $data[$i]['latLng'] = $location;
        usleep(75000);
    }

    store_data([
        'listings_type' => 'deals',
        'output'        => $data
    ]);
}
add_action('dicm_cron_deals', 'dicm_cron_deals_run');

function dicm_cron_businesses_run(){
    $_GET['city'] = get_option('dicm_listings_businesses_city');
    $_GET['state'] = get_option('dicm_listings_businesses_state');
    $_GET['api_key'] = get_option('dicm_listings_businesses_api_key');
    $_GET['business_type'] = 'businesses';

    ob_start();
        include_once(plugin_dir_path( __FILE__ ) . 'data/businesses.php');
        $data = ob_get_contents();
    ob_end_clean();
    store_data([
        'listings_type' => 'businesses',
        'output'        => json_decode($data, true)
    ]);
}
add_action('dicm_cron_businesses', 'dicm_cron_businesses_run');

function dicm_cron_restaurants_run(){
    $_GET['city'] = get_option('dicm_listings_restaurants_city');
    $_GET['state'] = get_option('dicm_listings_restaurants_state');
    $_GET['api_key'] = get_option('dicm_listings_restaurants_api_key');
    $_GET['business_type'] = 'restaurants';

    ob_start();
        include_once(plugin_dir_path( __FILE__ ) . 'data/businesses.php');
        $data = ob_get_contents();
    ob_end_clean();
    store_data([
        'listings_type' => 'restaurants',
        'output'        => json_decode($data, true)
    ]);
}
add_action('dicm_cron_restaurants', 'dicm_cron_restaurants_run');

function dicm_cron_news_run(){
    $_GET['region'] = get_option('dicm_listings_news_region');
    $previous_output = get_option('dicm_listings_news_output');

    ob_start();
        include_once(plugin_dir_path( __FILE__ ) . 'data/news.php');
        $data = json_decode(ob_get_contents(), true);
    ob_end_clean();
    store_data([
        'listings_type' => 'news',
        'output'        => $data
    ]);
}
add_action('dicm_cron_news', 'dicm_cron_news_run');

function dicm_cron_homes_run(){
    $_GET['webpage'] = get_option('dicm_listings_homes_webpage');
    $_GET['row'] = get_option('dicm_listings_homes_row_selector');
    $_GET['url'] = get_option('dicm_listings_homes_url_selector');
    $_GET['image'] = get_option('dicm_listings_homes_image_selector');
    $_GET['address'] = get_option('dicm_listings_homes_address_selector');
    $_GET['price'] = get_option('dicm_listings_homes_price_selector');
    $_GET['size'] = get_option('dicm_listings_homes_size_selector');
    $_GET['rooms'] = get_option('dicm_listings_homes_rooms_selector');

    ob_start();
        include_once(plugin_dir_path( __FILE__ ) . 'data/homes.php');
        $data = json_decode(ob_get_contents(), true);
    ob_end_clean();
    store_data([
        'listings_type' => 'homes',
        'output'        => $data
    ]);
}
add_action('dicm_cron_homes', 'dicm_cron_homes_run');

function dicm_init_cron(){ // Initialize Cron jobs
    // if (!wp_next_scheduled ('dicm_cron_schools')) wp_schedule_event(time(), 'monthly', 'dicm_cron_schools');
    // if (!wp_next_scheduled ('dicm_cron_churches')) wp_schedule_event(time(), 'monthly', 'dicm_cron_churches');
    // if (!wp_next_scheduled ('dicm_cron_charities')) wp_schedule_event(time(), 'monthly', 'dicm_cron_charities');
    if (!wp_next_scheduled ('dicm_cron_events')) wp_schedule_event(time(), 'twicedaily', 'dicm_cron_events');
    if (!wp_next_scheduled ('dicm_cron_deals')) wp_schedule_event(time(), 'twicedaily', 'dicm_cron_deals');
    if (!wp_next_scheduled ('dicm_cron_businesses')) wp_schedule_event(time(), 'monthly', 'dicm_cron_businesses');
    if (!wp_next_scheduled ('dicm_cron_restaurants')) wp_schedule_event(time(), 'monthly', 'dicm_cron_restaurants');
    // if (!wp_next_scheduled ('dicm_cron_news')) wp_schedule_event(time(), 'twicedaily', 'dicm_cron_news');
    if (!wp_next_scheduled ('dicm_cron_homes')) wp_schedule_event(time(), 'twicedaily', 'dicm_cron_homes');
}
add_action('admin_init', 'dicm_init_cron');

function dicm_pull_lat_lng_from_db($array_to_search, $listing, $key_to_compare){
    // This function checks the DB for location data, in order to avoid having to make a call to the geocode API.
    //      $array_to_search: Pull an array from the database to search.
    //      $listing: A listing from the newly-fetched array.
    //      $key_to_compare: If the key from the database array and the newly-fetched array match, use the location data associated with it.
    
    if(!is_array($array_to_search)) $array_to_search = [];
    foreach($array_to_search as $previous_listing){
        if($previous_listing[$key_to_compare] == $listing[$key_to_compare]){  
            $location = $previous_listing['latLng'];
            break;
        }
    }
    if(isset($location['lat']) && isset($location['lng'])){
        // output('Found previous location: ' . json_encode($location));
    }
    else {
        output('Could not find stored location. Will fetch from API.');
        $location = false;
    }
    return $location;
}

function dicm_fetch_lat_lng($address_query){
    // $address_query = str_replace(' ', '+', $address_query);
    $url = plugin_dir_url( __FILE__ ) . 'data/geocode.php?address=' . urlencode($address_query);
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    $output = curl_exec($curl);
    curl_close($curl);
    output('Fetched location from API: ' . $output);
    $location = json_decode($output, true);
    return $location;
}