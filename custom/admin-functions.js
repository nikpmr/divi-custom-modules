jQuery(function($){
	DicmAdmin = { // Handles functions of custom modules that are needed on the admin panel.
		initListings:function(listingsType){
			listingsType = (typeof listingsType == 'object') 
			? $(listingsType).closest('.dicm_listings').attr('data-listings_type') : listingsType;
			var submitButtonHtml = `
				<p class="submit">
					<input type="button" class="button button-primary" onclick="DicmAdmin.fetchListings(this)" value="Fetch Data">
					<input type="button" class="button button-primary" onclick="DicmAdmin.storeDataAndSubmit()" value="Save Changes">
				</p>
			`;
			$('.listings_results').html('<ul></ul>');

			switch(listingsType){
				case 'schools':
					if(DicmAdminVars.schoolsOutput != ''){
						var savedOutput = (typeof DicmAdminVars.schoolsOutput == 'object') 
						? DicmAdminVars.schoolsOutput : DicmAdmin.decodeFromDb(DicmAdminVars.schoolsOutput);
						savedOutput = (typeof savedOutput.schoolList != 'undefined') ? savedOutput.schoolList : savedOutput;
						for(var i in savedOutput) DicmAdmin.renderListing(savedOutput[i].schoolName, i);
					}
				break;
				case 'churches':
						if(DicmAdminVars.churchesOutput != ''){
						var savedOutput = (typeof DicmAdminVars.churchesOutput == 'object') 
						? DicmAdminVars.churchesOutput : DicmAdmin.decodeFromDb(DicmAdminVars.churchesOutput);
						for(var i in savedOutput) DicmAdmin.renderListing(savedOutput[i].name, i);
					}
				break;
				case 'charities':
						if(DicmAdminVars.charitiesOutput != ''){
						var savedOutput = (typeof DicmAdminVars.charitiesOutput == 'object') 
						? DicmAdminVars.charitiesOutput : DicmAdmin.decodeFromDb(DicmAdminVars.charitiesOutput);
						for(var i in savedOutput) DicmAdmin.renderListing(savedOutput[i].charityName.toLowerCase(), i);
					}
				break;
				case 'events':
						if(DicmAdminVars.eventsOutput != ''){
						var savedOutput = (typeof DicmAdminVars.eventsOutput == 'object') 
						? DicmAdminVars.eventsOutput : DicmAdmin.decodeFromDb(DicmAdminVars.eventsOutput);
						for(var i in savedOutput) DicmAdmin.renderListing(savedOutput[i].name.toLowerCase(), i);
					}
				break;
				case 'deals':
						if(DicmAdminVars.dealsOutput != ''){
						var savedOutput = (typeof DicmAdminVars.dealsOutput == 'object') 
						? DicmAdminVars.dealsOutput : DicmAdmin.decodeFromDb(DicmAdminVars.dealsOutput);
						for(var i in savedOutput) DicmAdmin.renderListing(savedOutput[i].company, i);
					}
				break;
				case 'businesses':
						if(DicmAdminVars.businessesOutput != ''){
						var savedOutput = (typeof DicmAdminVars.businessesOutput == 'object') 
						? DicmAdminVars.businessesOutput : DicmAdmin.decodeFromDb(DicmAdminVars.businessesOutput);
						for(var i in savedOutput) DicmAdmin.renderListing(savedOutput[i].name, i);
					}
				break;
				case 'restaurants':
					if(DicmAdminVars.restaurantsOutput != ''){
						var savedOutput = (typeof DicmAdminVars.restaurantsOutput == 'object') 
						? DicmAdminVars.restaurantsOutput : DicmAdmin.decodeFromDb(DicmAdminVars.restaurantsOutput);
						for(var i in savedOutput) DicmAdmin.renderListing(savedOutput[i].name, i);
					}
				break;
				case 'news':
						if(DicmAdminVars.newsOutput != ''){
						var savedOutput = (typeof DicmAdminVars.newsOutput == 'object') 
						? DicmAdminVars.newsOutput : DicmAdmin.decodeFromDb(DicmAdminVars.newsOutput);
						for(var i in savedOutput) DicmAdmin.renderListing(savedOutput[i].title, i);
					}
				break;
				case 'homes':
						if(DicmAdminVars.homesOutput != ''){
						var savedOutput = (typeof DicmAdminVars.homesOutput == 'object') 
						? DicmAdminVars.homesOutput : DicmAdmin.decodeFromDb(DicmAdminVars.homesOutput);
						for(var i in savedOutput) DicmAdmin.renderListing(savedOutput[i].address, i);
					}
				break;
			}
			DicmAdminVars.output = savedOutput;
			$('.listings_results').prepend(submitButtonHtml);
		},
		renderListing:function(listingData, index){
			var html = `
				<li data-listing_key="${index}">
					${listingData} &nbsp;
					<!-- <a class="close_button" href="#n" onclick="DicmAdmin.deleteListing(this)">&#215;</a> -->
				</li>
			`;
			$('.listings_results ul').append(html);
		},
		deleteListing:function(element){
			var listingKey = parseInt( $(element).closest('li').data('listing_key') );
			var listingsType = $(element).closest('.dicm_listings').data('listings_type');
			if(listingsType == 'schools') DicmAdminVars.output.schoolList[listingKey] = null;
			else DicmAdminVars.output[listingKey] = null;
			$(element).closest('li').remove();
		},
		fetchListings:function(listingsType){
			listingsType = (typeof listingsType == 'object') 
			? $(listingsType).closest('.dicm_listings').attr('data-listings_type') : listingsType;
			var submitButtonHtml = `
			<p class="submit">
				<input type="button" class="button button-primary" onclick="DicmAdmin.fetchListings(this)" value="Fetch Data">
				<input type="button" class="button button-primary" onclick="DicmAdmin.storeDataAndSubmit()" value="Save Changes">
			</p>
			`;
			var waitingMessage = '<p><i>Fetching data, please wait...</i></p> <ul></ul>';
			var geocodingMessage = '<p><i>Geocoding, please wait...</i></p> <ul></ul>';
			switch(listingsType){
				case 'schools':
					var city = $('#dicm_listings_schools_city').val();
					var state = $('#dicm_listings_schools_state').val();
					var appID = $('#dicm_listings_schools_app_id').val();
					var appKey = $('#dicm_listings_schools_app_key').val();

					if(city != '' && state != '' && appID != '' && appKey != ''){
						$('#dicm_listings_schools_results').html(waitingMessage);
						$.getJSON(DicmAdminVars.pluginPath + 'data/schools.php',{
							city: $.trim(city),
							state: $.trim(state),
							app_id: $.trim(appID),
							app_key: $.trim(appKey)
						}, function(data){
							if(data.code == 401){
								$('#dicm_listings_schools_results').html('<p>SchoolDigger could not return any results. Please ensure your App ID and Key are entered correctly.</p>' + submitButtonHtml);
							}
							else {
								if(data.schoolList.length == 0) 
									$('#dicm_listings_schools_results').html('<p>SchoolDigger could not return any results for the city/school district and state provided.</p>' + submitButtonHtml);
								else{
									data.location = city + ', ' + state;
									var count = 0;
									for(let i in data.schoolList){ //Save location data
										setTimeout(function() {
											var address = data.schoolList[i].address.html.replace(/(<([^>]+)>)/ig," ");
											DicmAdmin.findLatLng(address, function(latLng){
												data.schoolList[i].latLng = latLng;
												count++;
												if(count == data.schoolList.length){
													// $('#dicm_listings_schools_output').val( DicmAdmin.encodeForDb(data) );
													DicmAdminVars.output = data.schoolList;
													$('.listings_results p').replaceWith(submitButtonHtml);
												}
											});
										}, 50 * i);
									}
									$('.listings_results').html(geocodingMessage);
									for(var i in data.schoolList)
										$('#dicm_listings_schools_results ul').append('<li>' + data.schoolList[i].schoolName + '</li>');
								}
							}
						})
						.fail(function(data){
							console.log(data.responseText);
							$('#dicm_listings_schools_results').html('<p>There was an error retrieving the data.</p>' + submitButtonHtml);
						});
					}
				break;
				case 'churches':
					var city = $('#dicm_listings_churches_city').val();
					var state = $('#dicm_listings_churches_state').val();
					var zip = $('#dicm_listings_churches_zip').val();

					if(city != '' && state != '' && zip != ''){
						$('#dicm_listings_churches_results').html(waitingMessage);
						$.getJSON(DicmAdminVars.pluginPath + 'data/churches.php', {
							city: $.trim(city),
							state: $.trim(state),
							zip: $.trim(zip)
						}, function(data){
							if(data.length == 0) 
								$('#dicm_listings_churches_results').html('<p>Sorry, we couldn\'t find any results for this city, state and zip code.</p>' + submitButtonHtml);
							else{
								var count = 0;
								for(let i in data){ //Save location data
									setTimeout(function() {
										DicmAdmin.findLatLng(data[i].address, function(latLng){
											data[i].latLng = latLng;
											count++;
											if(count == data.length){
												// $('#dicm_listings_churches_output').val( DicmAdmin.encodeForDb(data) );
												DicmAdminVars.output = data;
												$('.listings_results p').replaceWith(submitButtonHtml);
											}
										});
									}, 50 * i);
								}
								$('.listings_results').html(geocodingMessage);
								for(var i in data)
									$('#dicm_listings_churches_results ul').append('<li>' + data[i].name + '</li>');
							}
						})
						.fail(function(data){
							console.log(data.responseText);
							$('#dicm_listings_churches_results').html('<p>There was an error retrieving the data.</p>' + submitButtonHtml);
						});
					}
				break;
				case 'charities':
					var city = $('#dicm_listings_charities_city').val();
					var state = $('#dicm_listings_charities_state').val();
					var appID = $('#dicm_listings_charities_app_id').val();
					var appKey = $('#dicm_listings_charities_app_key').val();

					if(city != '' && state != '' && appID != '' && appKey != ''){
						$('#dicm_listings_charities_results').html(waitingMessage);
						$.getJSON(DicmAdminVars.pluginPath + 'data/charities.php',{
							city: $.trim(city),
							state: $.trim(state),
							app_id: $.trim(appID),
							app_key: $.trim(appKey),
						}, function(data){
							if(data.length == 0 || typeof data["errorMessage"] == 'string') 
								$('#dicm_listings_charities_results').html('<p>CharityNavigator could not return any results for the city and state provided.</p>' + submitButtonHtml);
							else{
								var count = 0;
								for (let i in data) { //Save location data
							        setTimeout(function() {
							            var address = ''
										+ data[i].mailingAddress.streetAddress1 + ' ' 
							            + (data[i].mailingAddress.streetAddress2 || '') + ' '
							            + data[i].mailingAddress.city + ' ' 
										+ data[i].mailingAddress.stateOrProvince;
							            DicmAdmin.findLatLng(address, function(latLng) {
							                data[i].latLng = latLng;
							                count++;
							                if(count == data.length){
							                	// $('#dicm_listings_charities_output').val( DicmAdmin.encodeForDb(data) );
												DicmAdminVars.output = data;
												$('.listings_results p').replaceWith(submitButtonHtml);
							                }
							            });
							        }, 75 * i);
								}
								$('.listings_results').html(geocodingMessage);
								for(var j in data)
									$('#dicm_listings_charities_results ul').append('<li>' + data[j].charityName.toLowerCase() + '</li>');
							}
						})
						.fail(function(data){
							console.log(data.responseText);
							$('#dicm_listings_charities_results').html('<p>CharityNavigator could not return any results. Please ensure your App ID and Key are entered correctly.</p>' + submitButtonHtml);
						});
					}
				break;
				case 'events-suggestions':
					var city = $('#dicm_listings_events_city').val();
					if(city != ''){
						$.get('https://allevents.in/api/index.php/geo/web/city_suggestions_full/' + city, {}, function(data){
							if(data.length == 0)
								$('#dicm_listings_events_suggestions').html('<li>No cities found.</li>');
							else {
								$('#dicm_listings_events_suggestions').html('<li>Did you mean:</li>');
								for(var i in data){
									$('#dicm_listings_events_suggestions').append(`
										<li><a 
											href="#n" 
											onclick="DicmAdmin.placeSuggestion( '#dicm_listings_events_city', '${data[i].query}', 'events' )"
										>
											${data[i].city}, ${data[i].region}
										</a></li>
									`);
								}
							}
						});
					}
				break;
				case 'events':
					var city = $('#dicm_listings_events_city').val();

					if(city != ''){
						$('#dicm_listings_events_results').html(waitingMessage);
						$.get(DicmAdminVars.pluginPath + 'data/events.php', {
							city: $.trim(city)
						}, function(data){
							data = JSON.parse(data);
							if(data.length == 0) 
								$('#dicm_listings_events_results').html('<p>Sorry, we couldn\'t find any results for this city.</p>' + submitButtonHtml);
							else{
								var count = 0;
								for(let i in data){ //Save location data
									setTimeout(function() {
										DicmAdmin.findLatLng(data[i].address, function(latLng){
											data[i].latLng = latLng;
											count++;
											if(count == data.length){
												// $('#dicm_listings_events_output').val( DicmAdmin.encodeForDb(data) );
												DicmAdminVars.output = data;
												$('.listings_results p').replaceWith(submitButtonHtml);
											}
										});
									}, 50 * i);
								}
								$('.listings_results').html(geocodingMessage);
								for(var i in data)
									$('#dicm_listings_events_results ul').append('<li>' + data[i].name + '</li>');
							}
						})
						.fail(function(data){
							console.log(data.responseText);
							$('#dicm_listings_events_results').html('<p>There was an error retrieving the data.</p>' + submitButtonHtml);
						});
					}
				break;
				case 'deals':
					var zip = $('#dicm_listings_deals_zip').val();

					if(zip != ''){
						$('#dicm_listings_deals_results').html(waitingMessage);
						$.getJSON(DicmAdminVars.pluginPath + 'data/deals.php', {
							zip: $.trim(zip)
						}, function(data){
							// console.log(data); return;
							if(data.length == 0) 
								$('#dicm_listings_deals_results').html('<p>Sorry, we couldn\'t find any results for this zip code.</p>' + submitButtonHtml);
							else{
								var count = 0;
								for(let i in data){ //Save location data
									setTimeout(function() {
										DicmAdmin.findLatLng(data[i].address, function(latLng){
											data[i].latLng = latLng;
											count++;
											if(count == data.length){
												// $('#dicm_listings_deals_output').val( DicmAdmin.encodeForDb(data) );
												DicmAdminVars.output = data;
												$('.listings_results p').replaceWith(submitButtonHtml);
											}
										});
									}, 100 * i);
								}
								$('.listings_results').html(geocodingMessage);
								for(var i in data)
									$('#dicm_listings_deals_results ul').append('<li>' + data[i].company + '</li>');
							}
						})
						.fail(function(data){
							console.log(data.responseText);
							$('#dicm_listings_deals_results').html('<p>There was an error retrieving the data.</p>' + submitButtonHtml);
						});
					}
				break;
				case 'businesses':
					var city = $('#dicm_listings_businesses_city').val();
					var state = $('#dicm_listings_businesses_state').val();
					var api_key = $('#dicm_listings_businesses_api_key').val();

					if(city != '' && state != ''  && api_key != ''){
						$('#dicm_listings_businesses_results').html(waitingMessage);
						$.getJSON(DicmAdminVars.pluginPath + 'data/businesses.php', {
							city: $.trim(city),
							state: $.trim(state),
							api_key: $.trim(api_key),
							business_type: 'businesses'
						}, function(data){
							if(data.length == 0) 
								$('#dicm_listings_businesses_results').html('<p>Yelp could not return any results for the city and state provided.</p>' + submitButtonHtml);
							else{
								// $('#dicm_listings_businesses_output').val( DicmAdmin.encodeForDb(data) );
								DicmAdminVars.output = data;
								$('#dicm_listings_businesses_results').html('<ul></ul>');
								for(var i in data)
									$('#dicm_listings_businesses_results ul').append('<li>' + data[i].name + '</li>');
								$('#dicm_listings_businesses_results').prepend(submitButtonHtml);
							}
						})
						.fail(function(data){
							console.log(data.responseText);
							$('#dicm_listings_businesses_results').html('<p>Yelp could not return any results. Please ensure your API key is entered correctly.</p>' + submitButtonHtml);
						});
					}
				break;
				case 'restaurants':
					var city = $('#dicm_listings_restaurants_city').val();
					var state = $('#dicm_listings_restaurants_state').val();
					var api_key = $('#dicm_listings_restaurants_api_key').val();

					if(city != '' && state != ''  && api_key != ''){
						$('#dicm_listings_restaurants_results').html(waitingMessage);
						$.getJSON(DicmAdminVars.pluginPath + 'data/businesses.php', {
							city: $.trim(city),
							state: $.trim(state),
							api_key: $.trim(api_key),
							business_type: 'restaurants'
						}, function(data){
							if(data.length == 0) 
								$('#dicm_listings_restaurants_results').html('<p>Yelp could not return any results for the city and state provided.</p>' + submitButtonHtml);
							else{
								// $('#dicm_listings_restaurants_output').val( DicmAdmin.encodeForDb(data) );
								DicmAdminVars.output = data;
								$('#dicm_listings_restaurants_results').html('<ul></ul>');
								for(var i in data)
									$('#dicm_listings_restaurants_results ul').append('<li>' + data[i].name + '</li>');
								$('#dicm_listings_restaurants_results').prepend(submitButtonHtml);
							}
						})
						.fail(function(data){
							$('#dicm_listings_restaurants_results').html('<p>Yelp could not return any results. Please ensure your API key is entered correctly.</p>' + submitButtonHtml);
						});
					}
				break;
				case 'news-suggestions':
					var zip = $('#dicm_listings_news_region').val();
					if(zip != ''){
						$.get('https://patch.com/api_v1/patches.json?limit=5&query=' + zip, {}, function(data){
							if(data.length == 0)
								$('#dicm_listings_news_suggestions').html('<li>No regions found.</li>');
							else {
								$('#dicm_listings_news_suggestions').html('<li>Select from the available regions below. If your region isn\'t listed, select one nearby.</li>');
								for(var i in data){
									$('#dicm_listings_news_suggestions').append(`
										<li><a 
											href="#n" 
											onclick="DicmAdmin.placeSuggestion( '#dicm_listings_news_region', '${data[i].alias}', 'news' )"
										>
											${data[i].name}, ${data[i].region.abbreviation}
										</a></li>
									`);
								}
							}
						});
					}
				break;
				case 'news':
					var region = $('#dicm_listings_news_region').val();

					if(region != ''){
						$('#dicm_listings_news_results').html(waitingMessage);
						$.get(DicmAdminVars.pluginPath + 'data/news.php', {
							region: $.trim(region)
						}, function(data){
							data = JSON.parse(data);
							if(data.length == 0) 
								$('#dicm_listings_news_results').html('<p>Sorry, we couldn\'t find any results for this region.</p>' + submitButtonHtml);
							else{
								for(var i in data){
									data[i].latLng = {lat: '', lng: ''};
									$('#dicm_listings_news_results ul').append('<li>' + data[i].title + '</li>');
								}
								DicmAdminVars.output = data;
								$('.listings_results p').replaceWith(submitButtonHtml);
							}
						})
						.fail(function(data){
							console.log(data.responseText);
							$('#dicm_listings_news_results').html('<p>There was an error retrieving the data.</p>' + submitButtonHtml);
						});
					}
				break;
				case 'homes':
					var webpage = $('#dicm_listings_homes_webpage').val();
					var row = $('#dicm_listings_homes_row_selector').val();
					var url = $('#dicm_listings_homes_url_selector').val();
					var image = $('#dicm_listings_homes_image_selector').val();
					var address = $('#dicm_listings_homes_address_selector').val();
					var price = $('#dicm_listings_homes_price_selector').val();
					var size = $('#dicm_listings_homes_size_selector').val();
					var rooms = $('#dicm_listings_homes_rooms_selector').val();

					if(webpage != ''){
						$('#dicm_listings_homes_results').html(waitingMessage);
						$.get(DicmAdminVars.pluginPath + 'data/homes.php', {
							webpage: $.trim(webpage),
							row: $.trim(row),
							url: $.trim(url),
							image: $.trim(image),
							address: $.trim(address),
							price: $.trim(price),
							size: $.trim(size),
							rooms: $.trim(rooms)
						}, function(data){
							data = JSON.parse(data);
							if(data.length == 0) 
								$('#dicm_listings_homes_results').html('<p>No items were retrieved. Check the CSS selectors.</p>' + submitButtonHtml);
							else{
								var count = 0;
								for(let i in data){ //Save location data
									setTimeout(function() {
										DicmAdmin.findLatLng(data[i].address, function(latLng){
											data[i].latLng = latLng;
											count++;
											if(count == data.length){
												// $('#dicm_listings_events_output').val( DicmAdmin.encodeForDb(data) );
												DicmAdminVars.output = data;
												$('.listings_results p').replaceWith(submitButtonHtml);
											}
										});
									}, 50 * i);
								}
								$('.listings_results').html(geocodingMessage);
								for(var i in data)
									$('#dicm_listings_homes_results ul').append('<li>' + data[i].address + '</li>');
							}
						})
						.fail(function(data){
							console.log(data.responseText);
							$('#dicm_listings_homes_results').html('<p>There was an error retrieving the data.</p>' + submitButtonHtml);
						});
					}
				break;
			}
		},
		placeSuggestion(inputSelector, text, listingType){
			$(inputSelector).val(text);
			$('.dicm_listings .listings_suggestions').empty();
			DicmAdmin.fetchListings(listingType);
		},
		findLatLng: function(addressQuery, callback) {
		    var url = DicmAdminVars.pluginPath + 'data/geocode.php?address=' + addressQuery;
			url = url.replace('#', '');
		    $.ajax({
		        url: url,
		        context: document.body
		    }).done(function(data) {
				console.log(data);
				callback(JSON.parse(data));
		    }).fail(function(data) {
		        console.warn(data);
		    });
		},
		encodeForDb: function(jsonData){
			jsonData = JSON.stringify(jsonData);
			jsonData = encodeURIComponent(jsonData);
			return(jsonData);
		},
		decodeFromDb: function(dbData){
			dbData = decodeURIComponent(dbData);
			dbData = JSON.parse(dbData);
			return(dbData);
		},
		storeDataAndSubmit: function(){
			DicmAdminVars.output = DicmAdminVars.output.filter((el) => { return el != null; });
			$('.listings_results').prepend('<i>Saving changes...</i><br>');
			fetch(DicmAdminVars.pluginPath + 'store-data.php', {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json'
				},
				body: JSON.stringify({
					listings_type: $('.dicm_listings').attr('data-listings_type'),
					output: DicmAdminVars.output
				})
			})
			.then(result => result.text())
			.then(data => {
				console.log(data);
				$('.dicm_listings form')[0].submit();
			})
			.catch((error) => {
			    console.error('Error: ', error);
				alert('An error occurred. The data was not saved.');
			});
		}
	}
});