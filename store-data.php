<?php
if (!defined('ABSPATH')) { // Script was loaded directly
    require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
    $post = json_decode(file_get_contents('php://input'), true);
    store_data($post);
}

function store_data($post){
    $option_name_array = array(
        'schools'       => 'dicm_listings_schools_output',
        'churches'      => 'dicm_listings_churches_output',
        'charities'     => 'dicm_listings_charities_output',
        'events'        => 'dicm_listings_events_output',
        'deals'         => 'dicm_listings_deals_output',
        'businesses'    => 'dicm_listings_businesses_output',
        'restaurants'   => 'dicm_listings_restaurants_output',
        'news'          => 'dicm_listings_news_output',
        'homes'         => 'dicm_listings_homes_output'
    );
    $option_name = $option_name_array[$post['listings_type']];
    $output = $post['output'];
    if(empty($output)) output('Error updating ' . $post['listings_type'] . ': Output was empty.');
    else{
        if(get_option($option_name) !== FALSE) update_option($option_name, $output);
        else add_option($option_name, $output);
        output('Saved changes to DB: ' . $post['listings_type']);
    }
}