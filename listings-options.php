<?php
// Sets up the Listings Options panel on the WP Admin page.

function dicm_listings_register_options_pages() {
    add_menu_page(
    	'Listings Options', 					// Title
    	'Listings Options', 					// Menu option
    	'manage_options', 						// Permission
    	'dicm_listings_options_schools', 		// Slug (using slug of first sub-item, so that it emulates first sub-item being clicked)
    	'dicm_listings_render_options_schools' 	// Render function (using slug of first sub-item, see above)
    );
    add_submenu_page(
    	'dicm_listings_options_schools',		// Slug of parent item
    	'Schools', 								
    	'Schools', 								
    	'manage_options', 						
    	'dicm_listings_options_schools', 		
    	'dicm_listings_render_options_schools'	
    );
    add_submenu_page(
    	'dicm_listings_options_schools',		
    	'Places of Worship', 					
    	'Places of Worship', 					
    	'manage_options', 						
    	'dicm_listings_options_churches', 		
    	'dicm_listings_render_options_churches'
    );
    add_submenu_page(
    	'dicm_listings_options_schools',		
    	'Charities', 					
    	'Charities', 					
    	'manage_options', 						
    	'dicm_listings_options_charities', 		
    	'dicm_listings_render_options_charities'
    );
    add_submenu_page(
    	'dicm_listings_options_schools',		
    	'Events', 					
    	'Events', 					
    	'manage_options', 						
    	'dicm_listings_options_events', 		
    	'dicm_listings_render_options_events'
    );
    add_submenu_page(
    	'dicm_listings_options_schools',		
    	'Deals', 					
    	'Deals', 					
    	'manage_options', 						
    	'dicm_listings_options_deals', 		
    	'dicm_listings_render_options_deals'
    );
    add_submenu_page(
    	'dicm_listings_options_schools',		
    	'Businesses', 					
    	'Businesses', 					
    	'manage_options', 						
    	'dicm_listings_options_businesses', 		
    	'dicm_listings_render_options_businesses'
    );
    add_submenu_page(
    	'dicm_listings_options_schools',		
    	'Restaurants', 					
    	'Restaurants', 					
    	'manage_options', 						
    	'dicm_listings_options_restaurants', 		
    	'dicm_listings_render_options_restaurants'
	);
	add_submenu_page(
		'dicm_listings_options_schools',		
		'News', 					
		'News', 					
		'manage_options', 						
		'dicm_listings_options_news', 		
		'dicm_listings_render_options_news'
	);
	add_submenu_page(
		'dicm_listings_options_schools',		
		'Homes', 					
		'Homes', 					
		'manage_options', 						
		'dicm_listings_options_homes', 		
		'dicm_listings_render_options_homes'
	);
}
add_action('admin_menu', 'dicm_listings_register_options_pages');

function dicm_listings_register_options() {
    register_setting( 'dicm_listings_schools', 'dicm_listings_schools_city');
    register_setting( 'dicm_listings_schools', 'dicm_listings_schools_state');
    register_setting( 'dicm_listings_schools', 'dicm_listings_schools_app_id');
    register_setting( 'dicm_listings_schools', 'dicm_listings_schools_app_key');

    register_setting( 'dicm_listings_churches', 'dicm_listings_churches_city');
    register_setting( 'dicm_listings_churches', 'dicm_listings_churches_state');
    register_setting( 'dicm_listings_churches', 'dicm_listings_churches_zip');

    register_setting( 'dicm_listings_charities', 'dicm_listings_charities_city');
    register_setting( 'dicm_listings_charities', 'dicm_listings_charities_state');
    register_setting( 'dicm_listings_charities', 'dicm_listings_charities_app_id');
    register_setting( 'dicm_listings_charities', 'dicm_listings_charities_app_key');

    register_setting( 'dicm_listings_events', 'dicm_listings_events_city');

    register_setting( 'dicm_listings_deals', 'dicm_listings_deals_zip');

    register_setting( 'dicm_listings_businesses', 'dicm_listings_businesses_city');
    register_setting( 'dicm_listings_businesses', 'dicm_listings_businesses_state');

    register_setting( 'dicm_listings_restaurants', 'dicm_listings_restaurants_city');
    register_setting( 'dicm_listings_restaurants', 'dicm_listings_restaurants_state');
	
	register_setting( 'dicm_listings_news', 'dicm_listings_news_region');
	
	register_setting( 'dicm_listings_homes', 'dicm_listings_homes_webpage');
	register_setting( 'dicm_listings_homes', 'dicm_listings_homes_url_selector');
	register_setting( 'dicm_listings_homes', 'dicm_listings_homes_row_selector');
	register_setting( 'dicm_listings_homes', 'dicm_listings_homes_image_selector');
	register_setting( 'dicm_listings_homes', 'dicm_listings_homes_address_selector');
	register_setting( 'dicm_listings_homes', 'dicm_listings_homes_price_selector');
	register_setting( 'dicm_listings_homes', 'dicm_listings_homes_size_selector');
	register_setting( 'dicm_listings_homes', 'dicm_listings_homes_rooms_selector');
}
add_action( 'admin_init', 'dicm_listings_register_options' );

function dicm_listings_render_options_schools() {
	?>
		<div class="dicm_listings wrap" data-listings_type="schools">
			<h1>Listings Options - Schools</h1>
			<form method="post" action="options.php">
			    <?=settings_fields( 'dicm_listings_schools' ); ?>
			    <table class="form-table">
			        <tr>
			            <th scope="row"><label for="dicm_listings_schools_city">City or school district</label></th>
			            <td><input 
			            	type="text" 
			            	id="dicm_listings_schools_city" 
			            	name="dicm_listings_schools_city" 
			            	value="<?=get_option('dicm_listings_schools_city'); ?>" 
			            /></td>
			        </tr>
			        <tr>
			            <th scope="row"><label for="dicm_listings_schools_state">State</label></th>
			            <td>
			            	<select 
			            		id="dicm_listings_schools_state" 
			            		name="dicm_listings_schools_state" 
			            	>
			            		<?=dicm_listings_render_state_select_contents('dicm_listings_schools_state');?>
			            	</select>
			            </td>
			        </tr>
			        <tr>
			            <th scope="row"><label for="dicm_listings_schools_app_id">App ID</label></th>
			            <td><input 
			            	type="text" 
			            	id="dicm_listings_schools_app_id" 
			            	name="dicm_listings_schools_app_id" 
			            	value="<?=get_option('dicm_listings_schools_app_id'); ?>" 
			            /></td>
			        </tr>
			        <tr>
			            <th scope="row"><label for="dicm_listings_schools_app_key">App key</label></th>
			            <td><input 
			            	type="text" 
			            	id="dicm_listings_schools_app_key" 
			            	name="dicm_listings_schools_app_key" 
			            	value="<?=get_option('dicm_listings_schools_app_key'); ?>" 
			            /></td>
			        </tr>
			    </table>
			    <!-- <input 
			    	type="hidden" 
			    	id="dicm_listings_schools_output" 
			    	name="dicm_listings_schools_output" 
			    	value="" 
			    /> -->
			    <div id="dicm_listings_schools_results" class="listings_results">
			    </div>
			</form>
			<script type="text/javascript">
				jQuery(function($){
				 	DicmAdmin.initListings("schools");
			 	});
			</script>
		</div>
	<?php
}
function dicm_listings_render_options_churches() {
	?>
		<div class="dicm_listings wrap" data-listings_type="churches">
			<h1>Listings Options - Places of Worship</h1>
			<form method="post" action="options.php">
			    <?=settings_fields( 'dicm_listings_churches' ); ?>
			    <table class="form-table">
			        <tr>
			            <th scope="row"><label for="dicm_listings_churches_city">City</label></th>
			            <td>
			            	<input 
				            	type="text" 
				            	id="dicm_listings_churches_city" 
				            	name="dicm_listings_churches_city" 
				            	value="<?=get_option('dicm_listings_churches_city'); ?>" 
			            	/>
			        	</td>
			        </tr>
			        <tr>
			            <th scope="row"><label for="dicm_listings_churches_state">State</label></th>
			            <td>
			            	<select 
			            		id="dicm_listings_churches_state" 
			            		name="dicm_listings_churches_state" 
			            	>
			            		<?=dicm_listings_render_state_select_contents('dicm_listings_churches_state'); ?>
			            	</select>
			            </td>
			        </tr>
			        <tr>
			            <th scope="row"><label for="dicm_listings_churches_zip">Zip code</label></th>
			            <td>
			            	<input 
				            	type="text" 
				            	id="dicm_listings_churches_zip" 
				            	name="dicm_listings_churches_zip" 
				            	value="<?=get_option('dicm_listings_churches_zip'); ?>" 
				            />
				        </td>
			        </tr>
			    </table>
			    <!-- <input 
			    	type="hidden" 
			    	id="dicm_listings_churches_output" 
			    	name="dicm_listings_churches_output" 
			    	value="" 
			    /> -->
			    <div id="dicm_listings_churches_results" class="listings_results">
			    </div>
			</form>
			<script type="text/javascript">
				jQuery(function($){
				 	DicmAdmin.initListings("churches");
			 	});
			</script>
		</div>
	<?php
}
function dicm_listings_render_options_charities() {
	?>
		<div class="dicm_listings wrap" data-listings_type="charities">
			<h1>Listings Options - Charities</h1>
			<form method="post" action="options.php">
			    <?=settings_fields( 'dicm_listings_charities' ); ?>
			    <table class="form-table">
			        <tr>
			            <th scope="row"><label for="dicm_listings_charities_city">City</label></th>
			            <td><input 
			            	type="text" 
			            	id="dicm_listings_charities_city" 
			            	name="dicm_listings_charities_city" 
			            	value="<?=get_option('dicm_listings_charities_city'); ?>" 
			            /></td>
			        </tr>
			        <tr>
			            <th scope="row"><label for="dicm_listings_charities_state">State</label></th>
			            <td>
			            	<select 
			            		id="dicm_listings_charities_state" 
			            		name="dicm_listings_charities_state" 
			            	>
			            		<?=dicm_listings_render_state_select_contents('dicm_listings_charities_state');?>
			            	</select>
			            </td>
			        </tr>
			        <tr>
			            <th scope="row"><label for="dicm_listings_charities_app_id">App ID</label></th>
			            <td><input 
			            	type="text" 
			            	id="dicm_listings_charities_app_id" 
			            	name="dicm_listings_charities_app_id" 
			            	value="<?=get_option('dicm_listings_charities_app_id'); ?>" 
			            /></td>
			        </tr>
			        <tr>
			            <th scope="row"><label for="dicm_listings_charities_app_key">App key</label></th>
			            <td><input 
			            	type="text" 
			            	id="dicm_listings_charities_app_key" 
			            	name="dicm_listings_charities_app_key" 
			            	value="<?=get_option('dicm_listings_charities_app_key'); ?>" 
			            /></td>
			        </tr>
			    </table>
			    <!-- <input 
			    	type="hidden" 
			    	id="dicm_listings_charities_output" 
			    	name="dicm_listings_charities_output" 
			    	value="" 
			    /> -->
			    <div id="dicm_listings_charities_results" class="listings_results">
			    </div>
			</form>
			<script type="text/javascript">
				jQuery(function($){
				 	DicmAdmin.initListings("charities");
			 	});
			</script>
		</div>
	<?php
}
function dicm_listings_render_options_events() {
	?>
		<div class="dicm_listings wrap" data-listings_type="events">
			<h1>Listings Options - Events</h1>
			<form method="post" action="options.php">
			    <?=settings_fields( 'dicm_listings_events' ); ?>
			    <table class="form-table">
			        <tr>
			            <th scope="row"><label for="dicm_listings_events_city">City</label></th>
			            <td>
			            	<input 
				            	type="text" 
				            	id="dicm_listings_events_city" 
				            	name="dicm_listings_events_city" 
				            	value="<?=get_option('dicm_listings_events_city'); ?>" 
				            	onblur="DicmAdmin.fetchListings('events-suggestions')"
			            	/>
			        	</td>
			        </tr>
			        <tr class="listings_suggestions_row">
			        	<th></th>
			        	<td>
			        		<ul id="dicm_listings_events_suggestions" class="listings_suggestions"></ul>
			        	</td>
			        </tr>
			    </table>
			    <!-- <input 
			    	type="hidden" 
			    	id="dicm_listings_events_output" 
			    	name="dicm_listings_events_output" 
			    	value="" 
			    /> -->
			    <div id="dicm_listings_events_results" class="listings_results">
			    </div>
			</form>
			<script type="text/javascript">
				jQuery(function($){
				 	DicmAdmin.initListings("events");
			 	});
			</script>
		</div>
	<?php
}
function dicm_listings_render_options_deals() {
	?>
		<div class="dicm_listings wrap" data-listings_type="deals">
			<h1>Listings Options - Deals</h1>
			<form method="post" action="options.php">
			    <?=settings_fields( 'dicm_listings_deals' ); ?>
			    <table class="form-table">
			        <tr>
			            <th scope="row"><label for="dicm_listings_deals_zip">Zip code</label></th>
                        <td>
                        	<input 
            	            	type="text" 
            	            	id="dicm_listings_deals_zip" 
            	            	name="dicm_listings_deals_zip" 
            	            	value="<?=get_option('dicm_listings_deals_zip'); ?>" 
                        	/>
                    	</td>
			        </tr>
			    </table>
			    <!-- <input 
			    	type="hidden" 
			    	id="dicm_listings_deals_output" 
			    	name="dicm_listings_deals_output" 
			    	value="" 
			    /> -->
			    <div id="dicm_listings_deals_results" class="listings_results" >
			    </div>
			</form>
			<script type="text/javascript">
				jQuery(function($){
				 	DicmAdmin.initListings("deals");
			 	});
			</script>
		</div>
	<?php
}
function dicm_listings_render_options_businesses() {
	?>
		<div class="dicm_listings wrap" data-listings_type="businesses">
			<h1>Listings Options - Businesses</h1>
			<form method="post" action="options.php">
			    <?=settings_fields( 'dicm_listings_businesses' ); ?>
			    <table class="form-table">
			        <tr>
			            <th scope="row"><label for="dicm_listings_businesses_city">City</label></th>
			            <td><input 
			            	type="text" 
			            	id="dicm_listings_businesses_city" 
			            	name="dicm_listings_businesses_city" 
			            	value="<?=get_option('dicm_listings_businesses_city'); ?>" 
			            /></td>
			        </tr>
			        <tr>
			            <th scope="row"><label for="dicm_listings_businesses_state">State</label></th>
			            <td>
			            	<select 
			            		id="dicm_listings_businesses_state" 
			            		name="dicm_listings_businesses_state" 
			            	>
			            		<?=dicm_listings_render_state_select_contents('dicm_listings_businesses_state');?>
			            	</select>
			            </td>
			        </tr>
			    </table>
			    <!-- <input 
			    	type="hidden" 
			    	id="dicm_listings_businesses_output" 
			    	name="dicm_listings_businesses_output" 
			    	value="" 
			    /> -->
			    <div id="dicm_listings_businesses_results" class="listings_results">
			    </div>
			</form>
			<script type="text/javascript">
				jQuery(function($){
				 	DicmAdmin.initListings("businesses");
			 	});
			</script>
		</div>
	<?php
}
function dicm_listings_render_options_restaurants() {
	?>
		<div class="dicm_listings wrap" data-listings_type="restaurants">
			<h1>Listings Options - Restaurants</h1>
			<form method="post" action="options.php">
			    <?=settings_fields( 'dicm_listings_restaurants' ); ?>
			    <table class="form-table">
			        <tr>
			            <th scope="row"><label for="dicm_listings_restaurants_city">City</label></th>
			            <td><input 
			            	type="text" 
			            	id="dicm_listings_restaurants_city" 
			            	name="dicm_listings_restaurants_city" 
			            	value="<?=get_option('dicm_listings_restaurants_city'); ?>" 
			            /></td>
			        </tr>
			        <tr>
			            <th scope="row"><label for="dicm_listings_restaurants_state">State</label></th>
			            <td>
			            	<select 
			            		id="dicm_listings_restaurants_state" 
			            		name="dicm_listings_restaurants_state" 
			            	>
			            		<?=dicm_listings_render_state_select_contents('dicm_listings_restaurants_state');?>
			            	</select>
			            </td>
			        </tr>
			    </table>
			    <!-- <input 
			    	type="hidden" 
			    	id="dicm_listings_restaurants_output" 
			    	name="dicm_listings_restaurants_output" 
			    	value="" 
			    /> -->
			    <div id="dicm_listings_restaurants_results" class="listings_results">
			    </div>
			</form>
			<script type="text/javascript">
				jQuery(function($){
				 	DicmAdmin.initListings("restaurants");
			 	});
			</script>
		</div>
	<?php
}
function dicm_listings_render_options_news() {
	?>
		<div class="dicm_listings wrap" data-listings_type="news">
			<h1>Listings Options - News</h1>
			<form method="post" action="options.php">
				<?=settings_fields( 'dicm_listings_news' ); ?>
				<table class="form-table">
					<tr>
						<th scope="row"><label for="dicm_listings_news_region">Region</label></th>
						<td>
							<input 
								type="text" 
								id="dicm_listings_news_region" 
								name="dicm_listings_news_region" 
								value="<?=get_option('dicm_listings_news_region'); ?>" 
								onblur="DicmAdmin.fetchListings('news-suggestions')"
								placeholder="Enter a ZIP code"
							/>
						</td>
					</tr>
					<tr class="listings_suggestions_row">
						<th></th>
						<td>
							<ul id="dicm_listings_news_suggestions" class="listings_suggestions"></ul>
						</td>
					</tr>
				</table>
				<div id="dicm_listings_news_results" class="listings_results">
				</div>
			</form>
			<script type="text/javascript">
				jQuery(function($){
					DicmAdmin.initListings("news");
				});
			</script>
		</div>
	<?php
}
function dicm_listings_render_options_homes() {
	?>
		<div class="dicm_listings wrap" data-listings_type="homes">
			<h1>Listings Options - Homes</h1>
			<form method="post" action="options.php">
				<?=settings_fields( 'dicm_listings_homes' ); ?>
				<table class="form-table">
					<tr>
						<th scope="row" style="width:270px"><label for="dicm_listings_homes_webpage">URL to page</label></th>
						<td>
							<input 
								type="text" 
								id="dicm_listings_homes_webpage" 
								name="dicm_listings_homes_webpage" 
								value="<?=get_option('dicm_listings_homes_webpage'); ?>" 
								size="80"
							/>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="dicm_listings_homes_row_selector">CSS Selector - Home Listing</label></th>
						<td>
							<input 
								type="text" 
								id="dicm_listings_homes_row_selector" 
								name="dicm_listings_homes_row_selector" 
								value="<?=get_option('dicm_listings_homes_row_selector'); ?>" 
								size="80"
							/>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="dicm_listings_homes_url_selector">CSS Selector - Home Listing URL</label></th>
						<td>
							<input 
								type="text" 
								id="dicm_listings_homes_url_selector" 
								name="dicm_listings_homes_url_selector" 
								value="<?=get_option('dicm_listings_homes_url_selector'); ?>" 
								size="80"
							/>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="dicm_listings_homes_image_selector">CSS Selector - Home Listing Image</label></th>
						<td>
							<input 
								type="text" 
								id="dicm_listings_homes_image_selector" 
								name="dicm_listings_homes_image_selector" 
								value="<?=get_option('dicm_listings_homes_image_selector'); ?>" 
								size="80"
							/>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="dicm_listings_homes_address_selector">CSS Selector - Home Listing Address</label></th>
						<td>
							<input 
								type="text" 
								id="dicm_listings_homes_address_selector" 
								name="dicm_listings_homes_address_selector" 
								value="<?=get_option('dicm_listings_homes_address_selector'); ?>" 
								size="80"
							/>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="dicm_listings_homes_price_selector">CSS Selector - Home Listing Price</label></th>
						<td>
							<input 
								type="text" 
								id="dicm_listings_homes_price_selector" 
								name="dicm_listings_homes_price_selector" 
								value="<?=get_option('dicm_listings_homes_price_selector'); ?>" 
								size="80"
							/>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="dicm_listings_homes_size_selector">CSS Selector - Home Listing Size</label></th>
						<td>
							<input 
								type="text" 
								id="dicm_listings_homes_size_selector" 
								name="dicm_listings_homes_size_selector" 
								value="<?=get_option('dicm_listings_homes_size_selector'); ?>" 
								size="80"
							/>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="dicm_listings_homes_rooms_selector">CSS Selector - Home Listing Room Info</label></th>
						<td>
							<input 
								type="text" 
								id="dicm_listings_homes_rooms_selector" 
								name="dicm_listings_homes_rooms_selector" 
								value="<?=get_option('dicm_listings_homes_rooms_selector'); ?>" 
								size="80"
							/>
						</td>
					</tr>
				</table>
				<div id="dicm_listings_homes_results" class="listings_results">
				</div>
			</form>
			<script type="text/javascript">
				jQuery(function($){
					DicmAdmin.initListings("homes");
				});
			</script>
		</div>
	<?php
}

function dicm_listings_render_state_select_contents($name){
	?>
		<option value="AL" <?=selected(get_option($name), "AL");?> >Alabama</option>
		<option value="AK" <?=selected(get_option($name), "AK");?> >Alaska</option>
		<option value="AZ" <?=selected(get_option($name), "AZ");?> >Arizona</option>
		<option value="AR" <?=selected(get_option($name), "AR");?> >Arkansas</option>
		<option value="CA" <?=selected(get_option($name), "CA");?> >California</option>
		<option value="CO" <?=selected(get_option($name), "CO");?> >Colorado</option>
		<option value="CT" <?=selected(get_option($name), "CT");?> >Connecticut</option>
		<option value="DE" <?=selected(get_option($name), "DE");?> >Delaware</option>
		<option value="DC" <?=selected(get_option($name), "DC");?> >District of Columbia</option>
		<option value="FL" <?=selected(get_option($name), "FL");?> >Florida</option>
		<option value="GA" <?=selected(get_option($name), "GA");?> >Georgia</option>
		<option value="HI" <?=selected(get_option($name), "HI");?> >Hawaii</option>
		<option value="ID" <?=selected(get_option($name), "ID");?> >Idaho</option>
		<option value="IL" <?=selected(get_option($name), "IL");?> >Illinois</option>
		<option value="IN" <?=selected(get_option($name), "IN");?> >Indiana</option>
		<option value="IA" <?=selected(get_option($name), "IA");?> >Iowa</option>
		<option value="KS" <?=selected(get_option($name), "KS");?> >Kansas</option>
		<option value="KY" <?=selected(get_option($name), "KY");?> >Kentucky</option>
		<option value="LA" <?=selected(get_option($name), "LA");?> >Louisiana</option>
		<option value="ME" <?=selected(get_option($name), "ME");?> >Maine</option>
		<option value="MD" <?=selected(get_option($name), "MD");?> >Maryland</option>
		<option value="MA" <?=selected(get_option($name), "MA");?> >Massachusetts</option>
		<option value="MI" <?=selected(get_option($name), "MI");?> >Michigan</option>
		<option value="MN" <?=selected(get_option($name), "MN");?> >Minnesota</option>
		<option value="MS" <?=selected(get_option($name), "MS");?> >Mississippi</option>
		<option value="MO" <?=selected(get_option($name), "MO");?> >Missouri</option>
		<option value="MT" <?=selected(get_option($name), "MT");?> >Montana</option>
		<option value="NE" <?=selected(get_option($name), "NE");?> >Nebraska</option>
		<option value="NV" <?=selected(get_option($name), "NV");?> >Nevada</option>
		<option value="NH" <?=selected(get_option($name), "NH");?> >New Hampshire</option>
		<option value="NJ" <?=selected(get_option($name), "NJ");?> >New Jersey</option>
		<option value="NM" <?=selected(get_option($name), "NM");?> >New Mexico</option>
		<option value="NY" <?=selected(get_option($name), "NY");?> >New York</option>
		<option value="NC" <?=selected(get_option($name), "NC");?> >North Carolina</option>
		<option value="ND" <?=selected(get_option($name), "ND");?> >North Dakota</option>
		<option value="OH" <?=selected(get_option($name), "OH");?> >Ohio</option>
		<option value="OK" <?=selected(get_option($name), "OK");?> >Oklahoma</option>
		<option value="OR" <?=selected(get_option($name), "OR");?> >Oregon</option>
		<option value="PA" <?=selected(get_option($name), "PA");?> >Pennsylvania</option>
		<option value="RI" <?=selected(get_option($name), "RI");?> >Rhode Island</option>
		<option value="SC" <?=selected(get_option($name), "SC");?> >South Carolina</option>
		<option value="SD" <?=selected(get_option($name), "SD");?> >South Dakota</option>
		<option value="TN" <?=selected(get_option($name), "TN");?> >Tennessee</option>
		<option value="TX" <?=selected(get_option($name), "TX");?> >Texas</option>
		<option value="UT" <?=selected(get_option($name), "UT");?> >Utah</option>
		<option value="VT" <?=selected(get_option($name), "VT");?> >Vermont</option>
		<option value="VA" <?=selected(get_option($name), "VA");?> >Virginia</option>
		<option value="WA" <?=selected(get_option($name), "WA");?> >Washington</option>
		<option value="WV" <?=selected(get_option($name), "WV");?> >West Virginia</option>
		<option value="WI" <?=selected(get_option($name), "WI");?> >Wisconsin</option>
		<option value="WY" <?=selected(get_option($name), "WY");?> >Wyoming</option>
	<?php
}
