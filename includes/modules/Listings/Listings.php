<?php
/**
 * Basic Call To Action module (title, content, and button) with FULL builder support
 * This module appears on Visual Builder and requires react component to be provided
 * Due to full builder support, all advanced options (except button options) are added by default
 *
 * @since 1.0.0
 */
class DICM_Listings extends ET_Builder_Module {
	// Module slug (also used as shortcode tag)
	public $slug       = 'dicm_listings';

	// Visual Builder support (off|partial|on)
	public $vb_support = 'partial';

	// Module item's slug
	// public $child_slug = 'dicm_listings_item';

	/**
	 * Module properties initialization
	 *
	 * @since 1.0.0
	 */
	function init() {
		// Module name
		$this->name             = esc_html__( 'Listings', 'dicm-divi-custom-modules' );

		// Module Icon
		// Load customized svg icon and use it on builder as module icon. If you don't have svg icon, you can use
		// $this->icon for using etbuilder font-icon. (See CustomCta / DICM_CTA class)
		$this->icon             = '5';

		// Toggle settings
		$this->settings_modal_toggles  = array(
			'general'  => array(
				'toggles' => array(
					'listings_main_content' => esc_html__( 'General', 'dicm-divi-custom-modules' ),
					'elements' => esc_html__( 'Elements', 'dicm-divi-custom-modules' ),
				),
			),
		);
	}

	/**
	 * Module's specific fields
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function get_fields() {
		return array(
			//Main content
			'title' => array(
				'label'           	=> esc_html__( 'Title', 'dicm-divi-custom-modules' ),
				'type'            	=> 'text',
				'option_category' 	=> 'basic_option',
				'description'     	=> esc_html__( 'Text entered here will appear as title.', 'dicm-divi-custom-modules' ),
				'toggle_slug'     	=> 'listings_main_content',
			),
			'listings_type' => array(
				'label'       		=> esc_html__( 'Type', 'et_builder' ),
				'type'        		=> 'select',
				'default'     		=> 'schools',
				'option_category' 	=> 'basic_option',
				'options'			=> array(
					'schools'       	=> esc_html__( 'Schools', 'et_builder' ),
					'churches'      	=> esc_html__( 'Places of Worship', 'et_builder' ),
					'charities'     	=> esc_html__( 'Charities', 'et_builder' ),
					'businesses'		=> esc_html__( 'Businesses', 'et_builder' ),
					'restaurants'		=> esc_html__( 'Restaurants', 'et_builder' ),
					'events' 			=> esc_html__( 'Events', 'et_builder' ),
					'deals'    			=> esc_html__( 'Deals', 'et_builder' ),
					'news'    			=> esc_html__( 'News', 'et_builder' ),
					'homes'				=> esc_html__( 'Homes', 'et_builder' ),
					'multiple_1'		=> esc_html__( 'Multiple: All Establishments', 'et_builder' ),
					'multiple_2'		=> esc_html__( 'Multiple: Events & Deals', 'et_builder' ),
				),
				'description' => esc_html__( 'Choose the type of information this listings item will display.', 'et_builder' ),
				'toggle_slug' => 'listings_main_content',
			),
			'format' => array(
				'label'       => esc_html__( 'Format', 'et_builder' ),
				'type'        => 'select',
				'default'     => 'format_list',
				'option_category' => 'basic_option',
				'options'         => array(
					'format_list'    => esc_html__( 'List', 'et_builder' ),
					'format_grid'    => esc_html__( 'Grid', 'et_builder' ),
				),
				'description' => esc_html__( 'Choose the format in which the listings will display.', 'et_builder' ),
				'toggle_slug' => 'listings_main_content',
			),
			//Elements
			'limit' => array(
				'label'           => esc_html__( 'Listings Per Page', 'et_builder' ),
				'description'     => esc_html__( 'Choose the amount of listings to display per page. Choosing 0 shows all listings at once.', 'et_builder' ),
				'type'            => 'number',
				'option_category' => 'basic_option',
				'toggle_slug'     => 'elements',
				'default'         => '10',
				'allow_empty'     => true,
				'range_settings'  => array(
					'min'  => '0',
					'max'  => '30',
					'step' => '1',
				),
				'responsive'      => true,
			),
			'show_map'         => array(
				'label'            => esc_html__( 'Show Map', 'et_builder' ),
				'type'             => 'yes_no_button',
				'default_on_front' => 'on',
				'options'          => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'affects'          => array(
					'map_target_selector',
					'map_height'
				),
				'toggle_slug'      => 'elements',
			),
			'map_target_selector' => array(
				'label'           => esc_html__( 'Map Target Selector (Optional)', 'dicm-divi-custom-modules' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Use a CSS selector to indicate which element you\'d like to attach the map to.', 'dicm-divi-custom-modules' ),
				'toggle_slug'     => 'elements',
			),
			'map_height' => array(
				'label'           => esc_html__( 'Map Height', 'et_builder' ),
				'description'     => esc_html__( 'Adjust the height of the map. Enter "100%" to fill all available space.', 'et_builder' ),
				'type'            => 'range',
				'option_category' => 'basic_option',
				'toggle_slug'     => 'elements',
				'default'         => '400px',
				'default_unit'    => 'px',
				'default_on_front'=> '',
				'allowed_units'   => array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' ),
				'allow_empty'     => true,
				'range_settings'  => array(
					'min'  => '0',
					'max'  => '1100',
					'step' => '1',
				),
				'responsive'      => true,
			),
			'show_search'         => array(
				'label'            => esc_html__( 'Show Search Bar', 'et_builder' ),
				'type'             => 'yes_no_button',
				'default_on_front' => 'on',
				'options'          => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'toggle_slug'      => 'elements',
			),
			'show_filter'         => array(
				'label'            => esc_html__( 'Show Filter', 'et_builder' ),
				'type'             => 'yes_no_button',
				'default_on_front' => 'on',
				'options'          => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'toggle_slug'      => 'elements',
			),
			'icon' => array(
				'label'              => esc_html__( 'Listing Icon', 'dicm-divi-custom-modules' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => esc_attr__( 'Upload an image', 'et_builder' ),
				'choose_text'        => esc_attr__( 'Choose a Slide Image', 'et_builder' ),
				'update_text'        => esc_attr__( 'Set As Image', 'et_builder' ),
				'description' => esc_html__( 'Choose the icon that displays next to each listing.', 'et_builder' ),
				'affects'            => array(
					'image_alt',
				),
				'toggle_slug'        => 'elements',
				'dynamic_content'    => 'image',
				'mobile_options'     => true,
				'hover'              => 'tabs',
			),
			'integrate_with_feed'         => array(
				'label'            => esc_html__( 'Integrate with Feed', 'et_builder' ),
				'description'     => esc_html__( 'Integrate this content with the news feed (if one is present on the page)', 'et_builder' ),
				'type'             => 'yes_no_button',
				'default_on_front' => 'off',
				'options'          => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' ),
				),
				'affects'          => array(
					'feed_position',
				),
				'toggle_slug'      => 'elements',
			),
			'feed_position' => array(
				'label'           => esc_html__( 'Position In Feed', 'et_builder' ),
				'description'     => esc_html__( 'Choose the position of this element in the feed.', 'et_builder' ),
				'type'            => 'number',
				'option_category' => 'basic_option',
				'toggle_slug'     => 'elements',
				'default'         => '1',
				'allow_empty'     => true,
				'range_settings'  => array(
					'min'  => '0',
					'max'  => '30',
					'step' => '1',
				),
				'responsive'      => true,
			),
		);
	}

	/**
	 * Module's advanced options configuration
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function get_advanced_fields_config() {
		return array(
		);
	}

	/**
	 * Render module output
	 *
	 * @since 1.0.0
	 *
	 * @param array  $attrs       List of unprocessed attributes
	 * @param string $content     Content being processed
	 * @param string $render_slug Slug of module that is used for rendering output
	 *
	 * @return string module's rendered output
	 */
	function render( $attrs, $content = null, $render_slug ) {
		// Module specific props added on $this->get_fields()
		$title 					= esc_html( $this->props['title'] );
		$listings_type 			= esc_html( $this->props['listings_type'] );
		$output_options			= array(
			'schools' 		=> 'dicm_listings_schools_output',
			'churches' 		=> 'dicm_listings_churches_output',
			'charities'		=> 'dicm_listings_charities_output',
			'events' 		=> 'dicm_listings_events_output',
			'deals'			=> 'dicm_listings_deals_output',
			'businesses'	=> 'dicm_listings_businesses_output',
			'restaurants'	=> 'dicm_listings_restaurants_output',
			'news'			=> 'dicm_listings_news_output',
			'homes'			=> 'dicm_listings_homes_output',
			'multiple_1'	=> array(
				// 'dicm_listings_schools_output',
				// 'dicm_listings_churches_output',
				// 'dicm_listings_charities_output',
				'dicm_listings_businesses_output',
				'dicm_listings_restaurants_output'
			),
			'multiple_2'	=> array(
				'dicm_listings_events_output',
				'dicm_listings_deals_output'
			)
		);

		$all_outputs = array();

		$listings_sources_array = $output_options[$listings_type];
		if(!is_array($listings_sources_array)) $listings_sources_array = array($listings_sources_array);
		foreach($listings_sources_array as $listings_source){
			if($listings_source){
				$output = get_option($listings_source);
				if(is_string($output)){ // Decode output if it was formatted the old way
					$output = rawurldecode($output);
					$output = json_decode($output, true);
				}
			}
			$listings_source_type = array_search($listings_source, $output_options);
			$all_outputs[$listings_source_type] = $output;
		}
		
		$show_map 				= ( esc_html( $this->props['show_map'] ) == 'on' );
		$show_map_output		= ($show_map) ? '' : 'hide_map';
		$show_search			= ( esc_html( $this->props['show_search'] ) == 'on' );
		$show_search_output		= ($show_search) ? '' : 'hide_search';
		$map_target_selector 	= esc_html( $this->props['map_target_selector'] );
		$map_height 			= esc_html( $this->props['map_height'] );
		$show_filter 			= ( esc_html( $this->props['show_filter'] ) == 'on' );
		$show_filter_output		= ($show_filter) ? '' : 'hide_filter';
		$icon					= esc_html( $this->props['icon'] );
		$icon_html 				= (!empty($icon)) ? "<div class=\"listings_icon\"><img src=\"{$icon}\"></div>" : '';
		$location_options 		= array(
			'schools' 		=> get_option('dicm_listings_schools_city') . ', ' . get_option('dicm_listings_schools_state'),
			'churches' 		=> get_option('dicm_listings_churches_zip'),
			'charities'		=> get_option('dicm_listings_charities_city') . ', ' . get_option('dicm_listings_charities_state'),
			'events'		=> get_option('dicm_listings_events_city'),
			'deals'			=> get_option('dicm_listings_deals_zip'),
			'businesses'	=> get_option('dicm_listings_businesses_city') . ', ' . get_option('dicm_listings_businesses_state'),
			'restaurants'	=> get_option('dicm_listings_restaurants_city') . ', ' . get_option('dicm_listings_restaurants_state'),
		);
		$location				= (isset($location_options[$listings_type])) ? $location_options[$listings_type] : '';
		$identifier 			= bin2hex(random_bytes(3));
		$limit 					= esc_html( $this->props['limit'] );
		$format 				= esc_html( $this->props['format'] );
		$integrate_with_feed	= ( esc_html( $this->props['integrate_with_feed'] ) == 'on' );
		$feed_position			= ($integrate_with_feed) ? esc_html( $this->props['feed_position'] ) : 'none';

		$output_html = '';
		$search_query = $_GET['s'];

		$fetch_list = array($listings_type);
		if($listings_type == 'multiple_1') $fetch_list = array(
			'restaurants', 
			'businesses', 
			// 'schools', 
			// 'churches', 
			// 'charities'
		);
		if($listings_type == 'multiple_2') $fetch_list = array('events', 'deals');

		// The output HTML for multiples
		if(count($fetch_list) > 1) $output_html .= <<<HTML
			<div 
				class="listings_map {$show_map_output}" 
				data-map_target_selector="{$map_target_selector}" 
				data-location="{$location}"
				data-identifier="{$identifier}"
				data-map_height="{$map_height}"
			></div>
			<div class="listings_filter {$show_filter_output}" data-append="" data-identifier="{$identifier}"></div>
			<ul class="listings_list" data-identifier="{$identifier}" data-limit="{$limit}" data-feed_position="{$feed_position}">
		HTML;

		foreach($fetch_list as $fetch){
			$output = $all_outputs[$fetch];
			if(defined('ET_FB_URI')){ // Limit results on page
				$pageBuilderLimit = (intval($limit) > 0 && intval($limit) < 10) ? intval($limit) : 10;
				$output = array_slice($output, 0, $pageBuilderLimit);
			}
			if(isset($_GET['s']) && $_GET['s'] == '' && $output != null) $output = array_slice($output, 0, 16); // Simulate a loaded search query

			switch($fetch){
				case 'schools':
					if(count($fetch_list) == 1) $output_html .= <<<HTML
						<div 
							class="listings_map schools_map {$show_map_output}" 
							data-map_target_selector="{$map_target_selector}" 
							data-location="{$location}"
							data-identifier="{$identifier}"
							data-map_height="{$map_height}"
						></div>
						<div class="listings_search {$show_search_output}">
							<input type="text" placeholder="Search Schools" />
							<a href="#n" class="close_button" onclick="DicmPage.listings.clearSearch(this);">&#10005;</a>
						</div>
						<div class="listings_filter schools_filter {$show_filter_output}" data-append="schools" data-identifier="{$identifier}"></div>
						<ul class="listings_list schools_list" data-identifier="{$identifier}" data-limit="{$limit}" data-feed_position="{$feed_position}">
					HTML;
					if(is_array($output['schoolList'])) $output = $output['schoolList'];
					foreach($output as $school){
						$query_check = "{$school['schoolName']}.{$school['schoolLevel']}";
						if(!empty($search_query) && stripos($query_check, $search_query) === FALSE) continue;

						$output_html .= <<<HTML
							<li data-lat="{$school['latLng']['lat']}" data-lng="{$school['latLng']['lng']}" data-search="{$query_check}">
								<div class="listings_content">
									{$icon_html}
									<div class="info1">
										<span class="title">{$school['schoolName']}</span>
										<span class="category" data-category="{$school['schoolLevel']}">
											{$school['schoolLevel']} school
										</span>
										<a class="more_info" href="{$school['url']}" target="_blank">
											More Info <i class="fas fa-arrow-circle-right"></i>
										</a>
									</div>
									<div class="info_container">
										<div class="info2">
											<span class="address">
												{$school['address']['street']} <br>
												{$school['address']['city']}, {$school['address']['state']} {$school['address']['zip']}
											</span>
										</div>
										<div class="info3">
											<span class="phone">{$school['phone']}</span>
											<a class="view_map {$show_map_output}" href="#n" onclick="DicmPage.listings.viewOnMap(this)">
												<i class="fas fa-map-marker-alt"></i> View on map
											</a>
										</div>
									</div>
								</div>
							</li>
						HTML;
					}
					if(count($fetch_list) == 1) $output_html .= '</ul>';
				break;
				case 'churches':
						if(count($fetch_list) == 1) $output_html .= <<<HTML
						<div 
							class="listings_map churches_map {$show_map_output}" 
							data-map_target_selector="{$map_target_selector}" 
							data-location="{$location}"
							data-identifier="{$identifier}"
							data-map_height="{$map_height}"
						></div>
						<div class="listings_search {$show_search_output}">
							<input type="text" placeholder="Search Places of Worship" />
							<a href="#n" class="close_button" onclick="DicmPage.listings.clearSearch(this);">&#10005;</a>
						</div>
						<div class="listings_filter churches_filter {$show_filter_output}" data-append="" data-identifier="{$identifier}">
						</div>
						<ul class="listings_list churches_list" data-identifier="{$identifier}" data-limit="{$limit}" data-feed_position="{$feed_position}">
					HTML;
					foreach($output as $church){
						$query_check = "{$church['name']}.{$church['category']}";
						if(!empty($search_query) && stripos($query_check, $search_query) === FALSE) continue;

						$churchPhone = (isset($church['phone'])) ? $church['phone'] : '';
						$output_html .= <<<HTML
							<li data-lat="{$church['latLng']['lat']}" data-lng="{$church['latLng']['lng']}" data-search="{$query_check}">
								<div class="listings_content">
									{$icon_html}
									<div class="info1">
										<span class="title">{$church['name']}</span>
										<span class="category" data-category="{$church['category']}">
											{$church['category']}
										</span>
										<a class="more_info" href="{$church['moreInfoLink']}" target="_blank">
											More Info <i class="fas fa-arrow-circle-right"></i>
										</a>
									</div>
									<div class="info_container">
										<div class="info2">
											<span class="address">{$church['address']}</span>
										</div>
										<div class="info3">
											<span class="phone">{$church['phone']}</span>
											<a class="view_map {$show_map_output}" href="#n" onclick="DicmPage.listings.viewOnMap(this)">
												<i class="fas fa-map-marker-alt"></i> View on map
											</a>
										</div>
									<div>
								</div>
							</li>
						HTML;
					}
					if(count($fetch_list) == 1) $output_html .= '</ul>';
				break;
				case 'charities':
					if(count($fetch_list) == 1) $output_html .= <<<HTML
						<div 
							class="listings_map charities_map {$show_map_output}" 
							data-map_target_selector="{$map_target_selector}" 
							data-location="{$location}"
							data-identifier="{$identifier}"
							data-map_height="{$map_height}"
						></div>
						<div class="listings_search {$show_search_output}">
							<input type="text" placeholder="Search Charities" />
							<a href="#n" class="close_button" onclick="DicmPage.listings.clearSearch(this);">&#10005;</a>
						</div>
						<div class="listings_filter charities_filter {$show_filter_output}" data-append="" data-identifier="{$identifier}"></div>
						<ul class="listings_list charities_list" data-identifier="{$identifier}" data-limit="{$limit}" data-feed_position="{$feed_position}">
					HTML;
					foreach($output as $charity){
						$query_check = "{$charity['charityName']}.{$charity['irsClassification']['nteeClassification']}";
						if(!empty($search_query) && stripos($query_check, $search_query) === FALSE) continue;
						
						$output_html .= <<<HTML
							<li data-lat="{$charity['latLng']['lat']}" data-lng="{$charity['latLng']['lng']}" data-search="{$query_check}">
								<div class="listings_content">
									{$icon_html}
									<div class="info1">
										<span class="title">{$charity['charityName']}</span>
										<span class="category" data-category="{$charity['irsClassification']['nteeClassification']}">
											{$charity['irsClassification']['nteeClassification']}
										</span>
										<a class="more_info" href="{$charity['charityNavigatorURL']}" target="_blank">
											More Info <i class="fas fa-arrow-circle-right"></i>
										</a>
									</div>
									<div class="info_container">
										<div class="info2">
											<span class="address">
												{$charity['mailingAddress']['streetAddress1']}
												{$charity['mailingAddress']['streetAddress2']} <br>
												{$charity['mailingAddress']['city']}, 
												{$charity['mailingAddress']['stateOrProvince']} 
												{$charity['mailingAddress']['postalCode']}
											</span>
										</div>
										<div class="info3">
											<a class="view_map {$show_map_output}" href="#n" onclick="DicmPage.listings.viewOnMap(this)">
												<i class="fas fa-map-marker-alt"></i> View on map
											</a>
										</div>
									</div>
								</div>
							</li>
						HTML;
					}
					if(count($fetch_list) == 1) $output_html .= '</ul>';
				break;
				case 'events':
					if(count($fetch_list) == 1) $output_html .= <<<HTML
						<div 
							class="listings_map events_map {$show_map_output}" 
							data-map_target_selector="{$map_target_selector}" 
							data-location="{$location}"
							data-identifier="{$identifier}"
							data-map_height="{$map_height}"
						></div>
						<div class="listings_search {$show_search_output}">
							<input type="text" placeholder="Search Events" />
							<a href="#n" class="close_button" onclick="DicmPage.listings.clearSearch(this);">&#10005;</a>
						</div>
						<div class="listings_filter events_filter {$show_filter_output}" data-append="" data-identifier="{$identifier}"></div>
						<ul class="listings_list events_list" data-identifier="{$identifier}" data-limit="{$limit}" data-feed_position="{$feed_position}">
					HTML;
					foreach($output as $event){
						$query_check = $event['name'];
						if(!empty($search_query) && stripos($query_check, $search_query) === FALSE) continue;

						$output_html .= <<<HTML
							<li data-lat="{$event['latLng']['lat']}" data-lng="{$event['latLng']['lng']}" data-search="{$query_check}">
								<div class="listings_image" data-background="{$event['image']}"></div>
								<div class="listings_content">
									{$icon_html}
									<div class="info1">
										<span class="title">{$event['name']}</span>
										<span class="category" data-category="Event" style="display:none;">Event</span>
										<span class="date">{$event['date']}</span>
										<a class="more_info" href="{$event['moreInfoLink']}" target="_blank">
											More Info <i class="fas fa-arrow-circle-right"></i>
										</a>
									</div>
									<div class="info_container">
										<div class="info2">
											<span class="address">
												{$event['address']}
											</span>

										</div>
										<div class="info3">
											<a class="view_map {$show_map_output}" href="#n" onclick="DicmPage.listings.viewOnMap(this)">
												<i class="fas fa-map-marker-alt"></i> View on map
											</a>
										</div>
									</div>
								</div>
							</li>
						HTML;
					}
					if(count($fetch_list) == 1) $output_html .= '</ul>';
				break;
				case 'deals':
					if(count($fetch_list) == 1) $output_html .= <<<HTML
						<div 
							class="listings_map deals_map {$show_map_output}" 
							data-map_target_selector="{$map_target_selector}" 
							data-location="{$location}"
							data-identifier="{$identifier}"
							data-map_height="{$map_height}"
						></div>
						<div class="listings_search {$show_search_output}">
							<input type="text" placeholder="Search Deals" />
							<a href="#n" class="close_button" onclick="DicmPage.listings.clearSearch(this);">&#10005;</a>
						</div>
						<div class="listings_filter deals_filter {$show_filter_output}" data-append="" data-identifier="{$identifier}"></div>
						<ul class="listings_list deals_list" data-identifier="{$identifier}" data-limit="{$limit}" data-feed_position="{$feed_position}">
					HTML;
					foreach($output as $deal){
						$query_check = "{$deal['company']}.{$deal['name']}";
						if(!empty($search_query) && stripos($query_check, $search_query) === FALSE) continue;

						$output_html .= <<<HTML
							<li data-lat="{$deal['latLng']['lat']}" data-lng="{$deal['latLng']['lng']}" data-search="{$query_check}">
							<div class="listings_image" data-background="{$deal['image']}"></div>
							<div class="listings_content">
								{$icon_html}
								<div class="info1">
									<span class="title">{$deal['company']}</span>
									<span class="category" data-category="Deal" style="display:none;">Deal</span>
									<span class="deal_name">{$deal['name']}</span>
									<a class="more_info" href="{$deal['moreInfoLink']}" target="_blank">
										More Info <i class="fas fa-arrow-circle-right"></i>
									</a>
								</div>
								<div class="info_container">
									<div class="info2">
										<span class="address">
											{$deal['address']}
										</span>
										<span class="ratings" style="display:none;">
											{$deal['ratingCount']} ratings on Groupon
										</span>

									</div>
									<div class="info3">
										<a class="view_map {$show_map_output}" href="#n" onclick="DicmPage.listings.viewOnMap(this)">
											<i class="fas fa-map-marker-alt"></i> View on map
										</a>
									</div>
								</div>
							</div>
							</li>
						HTML;
					}
					if(count($fetch_list) == 1) $output_html .= '</ul>';
				break;
				case 'businesses':
					if(count($fetch_list) == 1) $output_html .= <<<HTML
						<div 
							class="listings_map businesses_map {$show_map_output}" 
							data-map_target_selector="{$map_target_selector}" 
							data-location="{$location}"
							data-identifier="{$identifier}"
							data-map_height="{$map_height}"
						></div>
						<div class="listings_search {$show_search_output}">
							<input type="text" placeholder="Search Businesses" />
							<a href="#n" class="close_button" onclick="DicmPage.listings.clearSearch(this);">&#10005;</a>
						</div>
						<div class="listings_filter businesses_filter {$show_filter_output}" data-append="" data-identifier="{$identifier}" data-limit="25"></div>
						<ul class="listings_list businesses_list" data-identifier="{$identifier}" data-limit="{$limit}" data-feed_position="{$feed_position}">
					HTML;
					foreach($output as $business){
						if($business['categories'] == null) $business['categories'] = array();
						$categories = implode( '; ', array_map(function($i){ return $i['title']; }, $business['categories']) ); 
						$display_address = ($business['location']['display_address'] != null) ? $business['location']['display_address'] : array();
						$address1 = (array_key_exists(0, $display_address)) ? $display_address[0] : '';
						$address2 = (array_key_exists(1, $display_address)) ? $display_address[1] : '';

						$query_check = "{$business['name']}.{$categories}";
						if(!empty($search_query) && stripos($query_check, $search_query) === FALSE) continue;

						// Check if the listing has a custom page associated with it
						$more_info_link = $business['url'];
						$more_info_link_target = "_blank";
						$has_custom_page = '';
						$custom_page_query = new WP_Query([
							'meta_key'   => 'business_id', 
							'meta_value' => $business['id'],
							'post_type'  => 'project'
						]);
						$custom_page_query->request;
						if ($custom_page_query->have_posts()) {
							$custom_page_query->the_post();
							$more_info_link = get_the_permalink();
							$more_info_link_target = "_self";
							if(!isset($_GET['s'])) $has_custom_page = 'has_custom_page';
						} 
						wp_reset_postdata();

						$output_html .= <<<HTML
							<li 
								data-lat="{$business['coordinates']['latitude']}" 
								data-lng="{$business['coordinates']['longitude']}"
								data-business_id="{$business['id']}"
								class="{$has_custom_page}"
								data-search="{$query_check}"
							>
								<div class="listings_image" data-background="{$business['image_url']}"></div>
								<div class="listings_content">
									{$icon_html}
									<div class="info1">
										<span class="title">{$business['name']}</span>
										<span class="category" data-category="{$categories}">{$categories}</span>
										<a class="more_info" href="{$more_info_link}" target="{$more_info_link_target}">
											More Info <i class="fas fa-arrow-circle-right"></i>
										</a>
										<a class="claim_listing_link" href="#n" onclick="DichPage.claimListing(this)">Claim this listing</a>
										<span class="claim_listing_id">{$business['id']}</span>
									</div>
									<div class="info_container">
										<div class="info2">
											<span class="address">
												{$address1} <br>
												{$address2}
											</span>
											<span class="rating">
												Rated {$business['rating']} <i class="fas fa-star"></i>
											</span>

										</div>
										<div class="info3">
											<a class="view_map {$show_map_output}" href="#n" onclick="DicmPage.listings.viewOnMap(this)">
												<i class="fas fa-map-marker-alt"></i> View on map
											</a>
										</div>
									</div>
								</div>
							</li>
						HTML;
					}
					if(count($fetch_list) == 1) $output_html .= '</ul>';
				break;
				case 'restaurants':
					if(count($fetch_list) == 1) $output_html .= <<<HTML
						<div 
							class="listings_map restaurants_map {$show_map_output}" 
							data-map_target_selector="{$map_target_selector}" 
							data-location="{$location}"
							data-identifier="{$identifier}"
							data-map_height="{$map_height}"
						></div>
						<div class="listings_search {$show_search_output}">
							<input type="text" placeholder="Search Restaurants" />
							<a href="#n" class="close_button" onclick="DicmPage.listings.clearSearch(this);">&#10005;</a>
						</div>
						<div class="listings_filter restaurants_filter {$show_filter_output}" data-append="" data-identifier="{$identifier}" data-limit="25"></div>
						<ul class="listings_list restaurants_list" data-identifier="{$identifier}" data-limit="{$limit}" data-feed_position="{$feed_position}">
					HTML;
					foreach($output as $restaurant){
						if($restaurant['categories'] == null) $restaurant['categories'] = array();
						$categories = implode( '; ', array_map(function($i){ return $i['title']; }, $restaurant['categories']) ); 
						$display_address = ($restaurant['location']['display_address'] != null) ? $restaurant['location']['display_address'] : array();
						$address1 = (array_key_exists(0, $display_address)) ? $display_address[0] : '';
						$address2 = (array_key_exists(1, $display_address)) ? $display_address[1] : '';

						$query_check = "{$restaurant['name']}.{$categories}";
						if(!empty($search_query) && stripos($query_check, $search_query) === FALSE) continue;

						// Check if the listing has a custom page associated with it
						$more_info_link = $restaurant['url'];
						$more_info_link_target = "_blank";
						$has_custom_page = '';
						$custom_page_query = new WP_Query([
							'meta_key'   => 'business_id', 
							'meta_value' => $restaurant['id'],
							'post_type'  => 'project'
						]);
						$custom_page_query->request;
						if ($custom_page_query->have_posts()) {
							$custom_page_query->the_post();
							$more_info_link = get_the_permalink();
							$more_info_link_target = "_self";
							if(!isset($_GET['s'])) $has_custom_page = 'has_custom_page';
							$d = 'a';
						} 
						wp_reset_postdata();

						$output_html .= <<<HTML
							<li 
								data-lat="{$restaurant['coordinates']['latitude']}" 
								data-lng="{$restaurant['coordinates']['longitude']}"
								data-business_id="{$restaurant['id']}"
								class="{$has_custom_page}"
								data-search="{$query_check}"
							>
								<div class="listings_image" data-background="{$restaurant['image_url']}"></div>
								<div class="listings_content">
									{$icon_html}
									<div class="info1">
										<span class="title">{$restaurant['name']}</span>
										<span class="category" data-category="{$categories}">{$categories}</span>
										<a class="more_info" href="{$more_info_link}" target="{$more_info_link_target}">
											More Info <i class="fas fa-arrow-circle-right"></i>
										</a>
										<a class="claim_listing_link" href="#n" onclick="DichPage.claimListing(this)">Claim this listing</a>
										<span class="claim_listing_id">{$restaurant['id']}</span>
									</div>
									<div class="info_container">
										<div class="info2">
											<span class="address">
												{$address1} <br>
												{$address2}
											</span>
											<span class="rating">
												Rated {$restaurant['rating']} <i class="fas fa-star"></i>
											</span>

										</div>
										<div class="info3">
											<a class="view_map {$show_map_output}" href="#n" onclick="DicmPage.listings.viewOnMap(this)">
												<i class="fas fa-map-marker-alt"></i> View on map
											</a>
										</div>
									</div>
								</div>
							</li>
						HTML;
					}
					if(count($fetch_list) == 1) $output_html .= '</ul>';
				break;
				case 'news':
					if(count($fetch_list) == 1) $output_html .= <<<HTML
						<div class="listings_search {$show_search_output}">
							<input type="text" placeholder="Search News" />
							<a href="#n" class="close_button" onclick="DicmPage.listings.clearSearch(this);">&#10005;</a>
						</div>
						<div class="listings_filter news_filter {$show_filter_output}" data-append="" data-identifier="{$identifier}"></div>
						<ul class="listings_list news_list" data-identifier="{$identifier}" data-limit="{$limit}" data-feed_position="{$feed_position}">
					HTML;
					foreach($output as $news){
						$query_check = "{$news['title']}.{$news['byline']}.{$news['description']}";
						if(!empty($search_query) && stripos($query_check, $search_query) === FALSE) continue;

						$date = (!empty($news['date'])) ? date('m/d/Y g:i A', strtotime($news['date'])) : '';
						$output_html .= <<<HTML
							<li data-lat="" data-lng="" data-search="{$query_check}">
								<div class="listings_image" data-background="{$news['image']}"></div>
								<div class="listings_content">
									{$icon_html}
									<div class="info1">
										<span class="title">{$news['title']}</span>
										<span class="category" data-category="news">{$news['byline']}</span>
										<span class="date">{$date}</span>
										<a class="more_info" href="{$news['moreInfoLink']}" target="_blank">
											Read Article <i class="fas fa-arrow-circle-right"></i>
										</a>
									</div>
									<div class="info_container">
										<div class="info2">
											<span class="address">
												{$news['description']}
											</span>

										</div>
										
									</div>
								</div>
							</li>
						HTML;
					}
					if(count($fetch_list) == 1) $output_html .= '</ul>';
				break;
				case 'homes':
					if(count($fetch_list) == 1) $output_html .= <<<HTML
						<div 
							class="listings_map homes_map {$show_map_output}" 
							data-map_target_selector="{$map_target_selector}" 
							data-location="{$location}"
							data-identifier="{$identifier}"
							data-map_height="{$map_height}"
						></div>
						<div class="listings_search {$show_search_output}">
							<input type="text" placeholder="Search Homes" />
							<a href="#n" class="close_button" onclick="DicmPage.listings.clearSearch(this);">&#10005;</a>
						</div>
						<div class="listings_filter homes_filter {$show_filter_output}" data-append="" data-identifier="{$identifier}"></div>
						<ul class="listings_list homes_list" data-identifier="{$identifier}" data-limit="{$limit}" data-feed_position="{$feed_position}">
					HTML;
					foreach($output as $home){
						$query_check = "{$home['address']}.{$home['url']}}";
						if(!empty($search_query) && stripos($query_check, $search_query) === FALSE) continue;

						$output_html .= <<<HTML
							<li data-lat="" data-lng="" data-search="{$query_check}">
								<div class="listings_image" data-background="{$home['image']}"></div>
								<div class="listings_content">
									{$icon_html}
									<div class="info1">
										<span class="title">{$home['price']}</span>
										<span class="category" data-category="home">{$home['rooms']}</span>
										<span class="date">{$date}</span>
										<a class="more_info" href="{$home['url']}" target="_blank">
											View Home <i class="fas fa-arrow-circle-right"></i>
										</a>
									</div>
									<div class="info_container">
										<div class="info2">
											<span class="address">
												{$home['address']}
											</span>

										</div>
										
									</div>
								</div>
							</li>
						HTML;
					}
					if(count($fetch_list) == 1) $output_html .= '</ul>';
				break;
			}
		}
		if(count($fetch_list) > 1) $output_html .= '</ul>';

		// 3rd party module with full VB support doesn't need to manually wrap its module. Divi builder
		// has automatically wrapped it
		return sprintf(
			'
			<div%5$s class="et_pb_module dicm_listings %3$s %4$s">
				<h4 class="dicm-title">%1$s</h4>
				<div class="dicm-body">%2$s</div>
			</div>
			',
			$title,
			stripslashes($output_html),
			$format,
			$this->module_classname( $render_slug ),
			$this->module_id()
		);
	}
}

new DICM_Listings;
