<?php
// $test_location = array( 'lat' => 42.464146, 'lng' => -83.47461 ); // Used for testing purposes

$address = urlencode($_GET['address']);
$url = 'https://api.nettoolkit.com/v1/geo/geocodes?address=' . $address;
$location_array = json_decode(curlFetch($url), true);
if($location_array['code'] != 1000) echo json_encode($location_array); // An error occurred
else{
    $location = array( 
        'lat' => $location_array['results'][0]['latitude'],
        'lng' => $location_array['results'][0]['longitude'],
    );
    echo json_encode($location);
}

function curlFetch($url){
    $api_key = '6giWOzjwh1JSdYC6aHbzzzVGzlJnyU5S6qnuQZaN';
    $curl = curl_init($url);
    curl_setopt( $curl, CURLOPT_HTTPHEADER, array('X-NTK-KEY: ' . $api_key) );
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, TRUE );
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}