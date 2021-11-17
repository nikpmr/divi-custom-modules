<?php
require($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');

if($_POST['action'] == 'email'){ // Email the result
	$to = $_POST['email'];
	$base64_image = $_POST['attachment'];
	$image_path = save_image($base64_image, 'valuation_' . uniqid());
	$subject = 'TownSites - Home Valuation Tool';
	$body = <<<HTML
		Hello, <b>here is your home valuation report!</b> It can be viewed below.<br><br>
		<img src="$image_path" width="800"/><br><br>
		If you can't see the image, <a href="$image_path">click here</a> to view the report in a web browser.
	HTML; 
	$headers = array('Content-Type: text/html; charset=UTF-8');
	
	$mail = wp_mail( $to, $subject, $body, $headers );
	if(!$mail) exit( json_encode(['result' => 'error', 'resultText' => 'There was a problem sending the email. Please check the address.']) );
	exit( json_encode(['result' => 'success', 'resultText' => "The email was successfully sent to $to." ]) );
}

$address = $_GET['address'];
$zip = $_GET['zip'];
$first_name = $_GET['first_name'];
$last_name = $_GET['last_name'];
$email = $_GET['email'];

if(empty($first_name) || empty($last_name))
	exit( json_encode(['result' => 'error', 'resultText' => 'Please enter a first and last name.']) );
if (!filter_var($email, FILTER_VALIDATE_EMAIL))
	exit( json_encode(['result' => 'error', 'resultText' => 'Please enter a valid email address.']) );

// Get home ID
$home_id_url = 'https://www.redfin.com/stingray/do/avm/location-autocomplete?location='. urlencode($address) .'%20'. urlencode($zip);
$home_id_result = json_decode( str_replace('{}&&', '', curl_fetch($home_id_url)), true );
if( isset($home_id_result['payload']['exactMatch']) ){
	$home_id = preg_replace('/\d_/', '', $home_id_result['payload']['exactMatch']['id']);
	$home_url = 'https://www.redfin.com'. $home_id_result['payload']['exactMatch']['url'];
}
else exit( json_encode(['result' => 'error', 'resultText' => 'No matching properties were found.']) );

// Get property info
$property_url = 'https://www.redfin.com/stingray/api/home/details/avm?propertyId='. $home_id .'&accessLevel=1&includePrimaryPhotoUrl=true';
$property_result = json_decode( str_replace('{}&&', '', curl_fetch($property_url)), true );

$property_result['payload']['photo'] = base64_encode(file_get_contents($property_result['payload']['primaryPhotoUrl']));
$property_result['payload']['recipient'] = array(
	'firstName' => str_replace(['"',"'"], "", $first_name), 
	'lastName' => str_replace(['"',"'"], "", $last_name), 
	'email' => str_replace(['"',"'"], "", $email)
);

// Add email address to DB
if (class_exists(\MailPoet\API\API::class)) {
	$mailpoet_api = \MailPoet\API\API::MP('v1');
	$lists = $mailpoet_api->getLists();
	foreach($lists as $list){
		if($list['name'] == 'Newsletter mailing list'){ // The default mailing list
			$list_id = $list['id'];
			try {
				$mailpoet_api->addSubscriber(
					array(
						'email' => addslashes($email),
						'first_name' => addslashes($first_name),
						'last_name' => addslashes($last_name)
					),
					array($list_id)
				);
			}
			catch (exception $e) {
				exit( json_encode(['result' => 'error', 'resultText' => $e->getMessage()]) );
			}
		}
	}
}

echo json_encode($property_result);

function curl_fetch($url){
	$curl = curl_init( $url);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array("User-Agent: Mozilla/5.0 (Linux) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.138 Mobile Safari/537.36"));
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	$output = curl_exec($curl);
	curl_close($curl);
	return $output;
}
function save_image( $base64_img, $title ) {
	// Upload dir.
	$upload_dir  = wp_upload_dir();
	$upload_path = get_home_path() . 'wp-content/uploads/valuations/';

	$img             = str_replace( 'data:image/png;base64,', '', $base64_img );
	$img             = str_replace( ' ', '+', $img );
	$decoded         = base64_decode( $img );
	$filename        = $title . '.png';
	$file_type       = 'image/png';
	$hashed_filename = md5( $filename . microtime() ) . '_' . $filename;

	// Save the image in the uploads directory.
	$upload_file = file_put_contents( $upload_path . $hashed_filename, $decoded );
	return(get_site_url() . '/wp-content/uploads/valuations/' . $hashed_filename);
}

?>