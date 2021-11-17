<?php
/*
Plugin Name: Divi Custom Modules
Plugin URI:  https://elegantthemes.com
Description: Custom module examples for Divi built using create-divi-extension
Version:     1.0.0
Author:      Elegant Themes
Author URI:  https://elegantthemes.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: dicm-divi-custom-modules
Domain Path: /languages
*/

if ( ! function_exists( 'dicm_initialize_extension' ) ): // Creates the extension's main class instance.
	function dicm_initialize_extension() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/DiviCustomModules.php';
	}
	add_action( 'divi_extensions_init', 'dicm_initialize_extension' );
endif;

function dicm_include_custom_scripts() { // Include page css/js
	$pluginUrl = plugin_dir_url( __FILE__ );
	wp_enqueue_script('insq', $pluginUrl  . 'custom/insQ.js');
	wp_enqueue_script('html2canvas', $pluginUrl  . 'custom/html2canvas.min.js');
	// wp_enqueue_script('jspdf', 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js');
	wp_enqueue_script('dicm_custom_options', $pluginUrl  . 'custom/options.js',);
	wp_enqueue_script('dicm_custom_functions', $pluginUrl  . 'custom/functions.js');
	wp_localize_script('dicm_custom_functions', 'DicmPageVars', array(
	    'pluginPath' => plugin_dir_url( __FILE__ ),
		'homeValuationData' => $_POST['home_valuation_data']
	));
	wp_enqueue_style( 'dicm_custom_css', $pluginUrl  . 'custom/style.css' );
}
add_action('wp_enqueue_scripts', 'dicm_include_custom_scripts');

function dcim_include_admin_scripts() { // Include admin css/js
	$pluginUrl = plugin_dir_url( __FILE__ );
	wp_enqueue_script('dicm_admin_functions', $pluginUrl  . 'custom/admin-functions.js');
	wp_localize_script('dicm_admin_functions', 'DicmAdminVars', array(
		'pluginPath' => plugin_dir_url( __FILE__ ),
		'schoolsOutput' => get_option('dicm_listings_schools_output'),
		'churchesOutput' => get_option('dicm_listings_churches_output'),
		'charitiesOutput' => get_option('dicm_listings_charities_output'),
		'eventsOutput' => get_option('dicm_listings_events_output'),
		'dealsOutput' => get_option('dicm_listings_deals_output'),
		'businessesOutput' => get_option('dicm_listings_businesses_output'),
		'restaurantsOutput' => get_option('dicm_listings_restaurants_output'),
		'newsOutput' => get_option('dicm_listings_news_output'),
		'homesOutput' => get_option('dicm_listings_homes_output')
	));
	wp_enqueue_style( 'dicm_admin_css', $pluginUrl  . 'custom/admin-style.css' );
}
add_action('admin_enqueue_scripts', 'dcim_include_admin_scripts');

// show wp_mail() errors
add_action( 'wp_mail_failed', 'onMailError', 10, 1 );
function onMailError( $wp_error ) {
	echo "<pre>";
	print_r($wp_error);
	echo "</pre>";
}

include_once(plugin_dir_path( __FILE__ ) . 'listings-options.php');
include_once(plugin_dir_path( __FILE__ ) . 'shortcodes.php');
include_once(plugin_dir_path( __FILE__ ) . 'cron-jobs.php');