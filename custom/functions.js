DicmBuilder = { // Handles custom functions added to Divi Frontend Builder modals. 
	populateListings:function(element, listingsType){
		switch(listingsType){
			case 'schools':
				var city = $('#et-fb-schools_city').val();
				var state = $('#et-fb-schools_state li').attr('data-value');
				var appID = $('#et-fb-schools_api_app_id').val();
				var appKey = $('#et-fb-schools_api_app_key').val();

				$.get('https://api.schooldigger.com/v1.2/schools',{
					st:state,
					q:city,
					appID:appID,
					appKey:appKey,
					perPage:50
				}, function(data){
					data.location = city + ', ' + state;
					DicmBuilder.changeValue('#et-fb-output', JSON.stringify(data));
				});
			break;
		}
	},
	changeValue(element, value) { // Changes the value of a form field powered by React. 
		if (typeof element == 'string') element = $(element);
	    if (element instanceof jQuery) element = element[0];
	    var event = new Event('input', { bubbles: true });
	    element.value = value;
	    element.dispatchEvent(event);
	},
	triggerModuleRefresh(element){ // Attempts to refresh the module currently being edited.
		var $switch = (typeof element != 'undefined') 
			? $(element).closest('.et-fb-form__group').find('input')
			: $('.et-fb-modal .et-fb-form__group[data-custom_field=1] input').first();
		this.changeValue($switch, $switch.val() + '-');
	}
}

DicmPage = { // Handles functions of custom modules that are needed on the page.
	listings:{
		init:function(){
			if($('#cff').length){ // Integrate listings with feed
				$('.dicm_listings').each(function(){
					var $list = $(this).find('.listings_list');
					if($list.attr('data-feed_position') != 'none'){
						var feedPosition = parseInt($list.attr('data-feed_position')) - 1;
						$(this).insertBefore('#cff .cff-item:eq(' + feedPosition + ')');
					}
				});
			}
			$('.dicm_listings .listings_filter').each(function(){
				var $list = $(this).closest('.dicm_listings').find('.listings_list');
				var append = $(this).attr('data-append') || '';

				// Create a list of categories to display in the filter
				var listCategories = [];
				$list.find('.category').each(function(){
					var category = $(this).attr('data-category');
					if(category.indexOf('; ') === -1) listCategories.push(category);
					else for( var i in category.split('; ') ) listCategories.push(category.split('; ')[i]);
				});
				listCategories.sort();
				listCategories = [...new Set(listCategories)]; // Creates unique category array
				var listOutput = `
					<a href="#n" class="selected" onclick="DicmPage.listings.filter(this)" data-category="">
						<b>View:</b> &nbsp; All ${append}
					</a>
				`;
				for(var i in listCategories) 
					if(listCategories[i] != '')
						listOutput += `
							<a href="#n" onclick="DicmPage.listings.filter(this)" data-category="${listCategories[i]}">
								${listCategories[i]} ${append}
							</a>
						`;
				$(this).html(listOutput);

				// Handle search box events
				$(this).closest('.dicm_listings').find('.listings_search input').bind("change keyup input",function() { 
					DicmPage.listings.search(this);
				});

				DicmPage.listings.prioritizeList($list);
				DicmPage.listings.limitList($list);
				DicmPage.listings.limitFilter(this);
				DicmPage.listings.styleList($list);
				DicmPage.listings.lazyLoadImages($list);
			});
		},
		filter:function(element){
			var category = $(element).attr('data-category');
			var $filter = $(element).closest('.listings_filter')
			var $list = $(element).closest('.dicm_listings').find('.listings_list');
			$filter.children('a').removeClass('selected');
			$(element).addClass('selected');
			if(category == '') { // If link does not have a data-category attribute, e.g., "All", then show all listings
				DicmPage.listings.limitList($list, false);
				$list.children('li').removeClass('unlisted');
			}
			else {
				DicmPage.listings.limitList($list, false);
				$list.children('li').each(function(){
					var listingCategory = $(this).find('.category').attr('data-category');
					if(listingCategory.indexOf('; ') === -1){
						if(listingCategory == category) $(this).removeClass('unlisted');
						else $(this).addClass('unlisted');
					}
					else{
						if( listingCategory.split('; ').includes(category) ) $(this).removeClass('unlisted');
						else $(this).addClass('unlisted');
					}
				});
			}
			this.limitList($list);
			this.limitFilter($filter);
			var mapElement = $('.listings_map[data-identifier=' + $(element).closest('.listings_filter').attr('data-identifier') + ']');
			this.refreshMapMarkers(mapElement);
			this.lazyLoadImages($list);
		},
		limitList:function(list, limit){
			if(typeof limit == 'boolean' && limit === false){ // Setting limit to false resets the list to default
				$(list).children().removeClass('.limited')
				$(list).closest('.dicm_listings').find('.listings_show_more').remove();
				return;
			}
			limit = limit || parseInt($(list).attr('data-limit')) || 0;
			if(limit == 0) return; // If limit is unset, use data-limit attribute, otherwise do nothing
			var $listedItems = $(list).children('li:not(.unlisted,.search_hidden)'); // Filter out unlisted items
			$listedItems.addClass('limited');
			var page = parseInt(window.location.pathname.split('/')[2]) || 0;
			var offset = Math.max(0, page - 1) * limit;
			var $itemsToShow = $(Array.from($listedItems).slice(offset, offset + limit));
			$itemsToShow.removeClass('limited');
			$(list).closest('.dicm_listings').find('.listings_show_more').remove();
			if($itemsToShow.length < $listedItems.length){ // Add a Show More button
				var showMoreButtonHtml = `
					<div class="listings_show_more">
						<input 
							type="button" 
							class="et_pb_button" 
							onclick="DicmPage.listings.showMore(this, ${limit})"
							value="Show More"
						/>
					</div>
				`;
				if($listedItems.filter(':visible').length) $(list).after(showMoreButtonHtml);
			}
			DicmPage.listings.styleList(list);
		},
		limitFilter:function(filter, limit){
			limit = limit || $(filter).attr('data-limit') || 10;
			var $listedItems = $(filter).children('a');
			$listedItems.addClass('limited');
			var $itemsToShow = $listedItems.filter( ':lt(' + limit + '), .selected' );
			$itemsToShow.removeClass('limited');
			$(filter).addClass('limited_filter')
			if($itemsToShow.length < $listedItems.length){ // Add a Show All button
				var showAllButtonHtml = `
					<a 
						class="listings_filter_show_all"
						onclick="DicmPage.listings.filterShowAll(this)"
						href="#n"
					>
						More...
					</a>
				`;
				$(filter).append(showAllButtonHtml);
			}
		},
		showMore:function(element, limit){
			var $list = $(element).closest('.dicm_listings').find('.listings_list');
			var $listedItems = $list.children('li:not(.unlisted,.search_hidden)');  
			if($listedItems.filter(':visible').length) $listedItems = $listedItems.filter(':visible').last().nextAll(':not(.unlisted,.search_hidden)'); // Select only items ahead of last visible item
			var $itemsToShow = $listedItems.filter( '.limited:lt(' + limit + ')' );
			$itemsToShow.removeClass('limited');
			DicmPage.listings.refreshMapMarkers('.listings_map[data-identifier=' + $(element).closest('.dicm_listings').find('.listings_list').attr('data-identifier') + ']');
			if($listedItems.filter( '.limited').length == 0) $(element).remove(); // Remove Show More button
			DicmPage.listings.styleList($list);
			DicmPage.listings.lazyLoadImages($list);
		},
		filterShowAll:function(element){
			$(element).closest('.listings_filter').removeClass('limited_filter');
			$(element).closest('.listings_filter').find('a').not('.listings_filter_show_all')
			.removeClass('limited');
			$(element).remove();
		},
		initMaps:function(){
			$('.listings_map:not(.hide_map)').each(function(){
				var location = $(this).attr('data-location');
				var selector = $(this).attr('data-map_target_selector');
				var height = $(this).attr('data-map_height');
				var that = this;
				DicmPage.map.init(this, null, height);
				DicmPage.map.findLatLng(location, function(latLng){
					$(that).data('map').defaultLatLng = latLng;
					// DicmPage.map.centerAt(that, latLng, 15);
				});
				setTimeout(() => { DicmPage.listings.refreshMapMarkers(this); }, 500);
				if(selector) $(this).appendTo(selector);
			});
		},
		refreshMapMarkers:function(mapElement){
			if($(mapElement).hasClass('hide_map')) return;
			DicmPage.map.clearMarkers(mapElement);
			var $list = $('.listings_list[data-identifier=' + $(mapElement).attr('data-identifier') + ']');
			var bounds = new google.maps.LatLngBounds();
			$list.children('li:not(.limited,.unlisted,.search_hidden)').each(function(){
				var title = $(this).find('.title').html();
				var latLng = {
					lat: parseFloat($(this).attr('data-lat')), 
					lng: parseFloat($(this).attr('data-lng'))
				};
				if(!isNaN(latLng.lat) && !isNaN(latLng.lng)){
					var marker = DicmPage.map.addMarker(mapElement, latLng, title);
					marker.listingElement = this;
					marker.addListener('click', function () {
						$('html, body').animate({ scrollTop: $(this.listingElement).offset().top - 300 }, 350);
						$(this.listingElement).fadeOut(350).fadeIn(200).fadeOut(200).fadeIn(200);
					});
					var googleLatLng = new google.maps.LatLng(latLng.lat, latLng.lng);
					bounds.extend(googleLatLng);
				}
			});
			if($(mapElement).is(':visible')) $(mapElement).data('map').fitBounds(bounds);
			// DicmPage.map.centerAt(mapElement, $('mapElement').data('map').defaultLatLng, 15);
		},
		viewOnMap:function(element, scrollToElement = false){
			var $mapElement = $('.listings_map[data-identifier=' + $(element).closest('.listings_list').attr('data-identifier') + ']');
			var latLng = {
				lat: parseFloat($(element).closest('li').attr('data-lat')), 
				lng: parseFloat($(element).closest('li').attr('data-lng'))
			};
			if(scrollToElement) $('html, body').animate({ scrollTop: $mapElement.offset().top - 300}, 350);
			DicmPage.map.centerAt($mapElement, latLng, 17);
		},
		prioritizeList: function(list){
			$(list).children('li').each(function(){
				if($(this).hasClass('has_custom_page')) $(this).prependTo(list);
			});
		},
		styleList: function(list) {
			$(list).children('li').removeClass('left right last');
			var $visibleListings = $(list).children('li:visible');
			$visibleListings.filter(function(i){return i % 2 === 0}).addClass('left');
			$visibleListings.filter(function(i){return i % 2 === 1}).addClass('right');
			$visibleListings.last().addClass('last');
		},
		lazyLoadImages: function(list){
			var $visibleLazyLoadImages = $(list).find('li:visible *[data-background]');
			$visibleLazyLoadImages.each(function(){
				var src = $(this).attr('data-background');
				if(src.indexOf(',') !== -1) src = $.trim(src.match(/\/\/(.+?) /g).slice(-1)[0]) // Convert any srcset's
				$(this).css('background-image', 'url(' + src + ')' );
			});
		},
		search: function(element){
			var query = $(element).val();
			var $list = $(element).closest('.dicm_listings').find('.listings_list');
			var $listings = $list.children('li');
			$listings.addClass('search_hidden');
			$listings.each(function(){
				var queryCheck = $(this).data('search') || '';
				if(queryCheck.toLowerCase().indexOf(query.toLowerCase()) !== -1 || query == '') 
					$(this).removeClass('search_hidden');
			});
			$listings.removeClass('limited');
			DicmPage.listings.limitList($list);
			DicmPage.listings.limitFilter($list.closest('.dicm_listings').find('.listings_filter'));
			DicmPage.listings.styleList($list);
			DicmPage.listings.lazyLoadImages($list);
		},
		clearSearch: function(element){
			var $searchBox = $(element).closest('.listings_search').find('input');
			$searchBox.val('').trigger('change');
		},
	},
	map:{
		init: function (element, width, height) {
			width = width || '100%';
			height = height || '400px';
			var $element = $(element);
		    var latLng = { lat: 41.850033, lng: -87.6500523 };
		    $element.data('map', new google.maps.Map($element[0], { zoom: 4, center: latLng }));
		    if(typeof width !== 'undefined' && typeof height !== 'undefined')
		    	$element.css({'width':width, 'height':height});
		},
		findLatLng: function(addressQuery, callback) {
		    var url = DicmPageVars.pluginPath + 'data/geocode.php?address=' + addressQuery;
		    $.ajax({
		        url: url,
		        context: document.body
		    }).done(function(data) {
				// console.log(data);
		        callback(JSON.parse(data));
		    }).fail(function(data) {
		        console.warn(data);
		    });
		},
		centerAt: function (mapElement, latLng, zoom) {
			if(!$(mapElement).length) return;
		    $(mapElement).data('map').setCenter(latLng);
		    if(typeof zoom !== 'undefined')
		    	$(mapElement).data('map').setZoom(zoom);
		},
		addMarker: function (mapElement, latLng, title) {
			if(!$(mapElement).length) return;
			$(mapElement).data('map').markers = $(mapElement).data('map').markers || [];
			var markerID = $(mapElement).data('map').markers.length;
			var marker = new google.maps.Marker({
			  position: latLng,
			  map: $(mapElement).data('map'),
			  title: title
			});
			$(mapElement).data('map').markers[markerID] = marker;
			return marker;
		},
		clearMarkers:function(mapElement){
			if(!$(mapElement).length) return;
			for(var i in $(mapElement).data('map').markers){
				$(mapElement).data('map').markers[i].setMap(null);
			}
			$(mapElement).data('map').markers = [];
		}
	},
	homeValue:{
		getHomeValue : function(element){
			var $form = $(element).closest('form');
			var $resultText = $(element).closest('.dicm_home_value').find('.result_text');
			$resultText.html('Getting home value...');
			$.getJSON(DicmPageVars.pluginPath + 'data/home-value.php', $form.serializeArray(), function(data){
				if(data.errorMessage == 'Success') {
					var payload = encodeURIComponent(JSON.stringify(data.payload));
					$(`
						<form id="redirect-form" method="post" action="/home-valuation">
							<input type="hidden" name="home_valuation_data" value="${payload}" />
						</form>
					`).appendTo('body');
					$('#redirect-form')[0].submit();
				}
				else if(data.result == 'error'){
					$resultText.html(data.resultText);
				}
			});
		},
		init : function(element){
			if($('#home-valuation-column').length){
				if(DicmPageVars.homeValuationData == null){
					alert('No home valuation data!');
					return;
				}
				var data = JSON.parse(decodeURIComponent(DicmPageVars.homeValuationData));
				console.log(data);
				
				// Replace text
				$('#home-valuation-header h2').html(data.sectionPreviewText.replace(/(\(.*?\))/,'<span>$1<span>'));
				$('#home-valuation-header h3').html(data.streetAddress.assembledAddress);
				$('#home-valuation-header p').html(`
					<strong>${data.sqFt.value} ft², ${data.numBeds} Bedrooms, ${data.numBaths} Bathrooms</strong><br>
				`);
				$('#home-valuation-image img').attr('src', 'data:image/jpg;base64,' + data.photo);
				$('#home-valuation-success').data('sending_message', `
					<h3><i class="fas fa-hourglass-start"></i>&nbsp; Sending email...</h3>
				`).data('success_message', `
					<h3><i class="fas fa-check-square"></i>&nbsp; We've successfully obtained a home valuation report for ${data.streetAddress.assembledAddress}.</h3>
					<p>A copy of this report has been sent to the email address you provided.</p>
				`).data('recipient', data.recipient);
				$('#home-valuation-success .et_pb_text_inner').html('');
				
				// Gather comps
				var comps = [];
				for(var comp of data.comparables){
					var newComp = {};
					newComp.address1 = comp.streetAddress.assembledAddress;
					newComp.address2 = `${comp.city}, ${comp.state} ${comp.zip}`;
					newComp.info = `${comp.sqFt.value} ft², ${comp.beds} Beds, ${comp.baths} Baths`;
					newComp.url = 'https://redfin.com' + comp.url;
					newComp.price = '$' + comp.priceInfo.amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
					newComp.latLng = {
						lat: comp.latLong.latitude, 
						lng: comp.latLong.longitude
					};
					comps.push(newComp);
				}
				
				// Add map
				var mapElement = '#map-section';
				$(mapElement).addClass('listings_map').attr('data-identifier','home_value');
				DicmPage.map.init(mapElement);
				DicmPage.map.clearMarkers(mapElement);
				var bounds = new google.maps.LatLngBounds();
				var marker = DicmPage.map.addMarker(mapElement, {lat:data.latLong.latitude, lng:data.latLong.longitude}, data.streetAddress.assembledAddress);
				var googleLatLng = new google.maps.LatLng(data.latLong.latitude, data.latLong.longitude);
				bounds.extend(googleLatLng);
				
				// Add comp to page and add map marker
				var $comparables = $('#comparables-section');
				$comparables.find('.dicm_homevalue').remove();
				var $html = $(`<div class="dicm_listings dicm_homevalue"><ul class="listings_list" data-identifier="home_value"></ul></div>`);
				for(var [i, comp] of comps.entries()){
					var id = 'comparable-' + i;
					$html.find('ul').append(`
						<li id="${id}" data-lat="${comp.latLng.lat}" data-lng="${comp.latLng.lng}" data-search="" class="left">
							<div class="listings_content">
								<div class="listings_icon"><img src="http://template.townsites.org/wp-content/uploads/sites/2/2021/06/home.png"></div>
								<div class="info1">
									<span class="title">${comp.price}</span>
									<span class="category" data-category="home">${comp.info}</span>
									<span class="date"></span>
									<a class="more_info hide_on_print" href="${comp.url}" target="_blank">
										View Property <i class="fas fa-arrow-circle-right"></i>
									</a>
								</div>
								<div class="info_container">
									<div class="info2">
										<span class="address">
											${comp.address1}<br>${comp.address2}
										</span>
									</div>
									<div class="info3">
										<a class="view_map hide_on_print" href="#n" onclick="DicmPage.listings.viewOnMap(this, true)">
											<i class="fas fa-map-marker-alt"></i> View on map
										</a>
									</div>
								</div>
							</div>
						</li>
					`);
					if(!isNaN(comp.latLng.lat) && !isNaN(comp.latLng.lng)){
						var marker = DicmPage.map.addMarker(mapElement, comp.latLng, comp.address1);
						marker.listingElement = '#' + id;
						marker.addListener('click', function () {
							$('html, body').animate({ scrollTop: $(this.listingElement).offset().top - 300 }, 350);
							$(this.listingElement).fadeOut(350).fadeIn(200).fadeOut(200).fadeIn(200);
						});
						var googleLatLng = new google.maps.LatLng(comp.latLng.lat, comp.latLng.lng);
						bounds.extend(googleLatLng);
					}
				}
				$html.appendTo($comparables);
				$(mapElement).data('map').fitBounds(bounds);
				
				google.maps.event.addListenerOnce($(mapElement).data('map'), 'tilesloaded', function(){
					DicmPage.homeValue.createPdf('#home-valuation-column');
				});
				
			}
		},
		createPdf : function(contentSelector){
			var $statusElement = $('#home-valuation-success');
			$statusElement.find('.et_pb_text_inner').html($statusElement.data('sending_message'))
			.fadeOut(350).fadeIn(350).fadeOut(350).fadeIn(350);
    
			// Temporarily lock scrolling
			window.scrollTo(0,0);
			$('body').css({'overflow':'hidden'});
			$(document).bind('scroll',function () { window.scrollTo(0,0); });
			$('.hide_on_print').hide();
			
			// Create a canvas 
			html2canvas(document.querySelector(contentSelector),{
				allowTaint:false,
				useCORS:true
			}).then(canvas => {
				// Unlock scrolling
				$(document).unbind('scroll'); 
				$('body').css({'overflow':'visible'});
				$('.hide_on_print').show();
								
				var base64 = canvas.toDataURL();
				var url = DicmPageVars.pluginPath + 'data/home-value.php';
				var emailAddress = $statusElement.data('recipient').email;
				
				// Send email
				var xhr  = new XMLHttpRequest();
				xhr.onreadystatechange = function(){
					if (xhr.readyState == 4 && xhr.status == 200){ // Callback
						console.log(xhr.responseText);
						$statusElement.find('.et_pb_text_inner').html($statusElement.data('success_message'));
					}
				}; 
				xhr.open("POST", url, true);
				var boundary = '------multipartformboundary' + (new Date).getTime(), dashdash = '--', crlf = '\r\n',
				content = dashdash+boundary+crlf+'Content-Disposition: form-data; name="action";"'+crlf+crlf+'email'+crlf+dashdash+boundary+dashdash+crlf;
				content += dashdash+boundary+crlf+'Content-Disposition: form-data; name="email";"'+crlf+crlf+emailAddress+crlf+dashdash+boundary+dashdash+crlf;
				content += dashdash+boundary+crlf+'Content-Disposition: form-data; name="attachment";"'+crlf+crlf+base64+crlf+dashdash+boundary+dashdash+crlf;
				xhr.setRequestHeader("Content-type", "multipart/form-data; boundary="+boundary);
				xhr.send(content);
			});
		}
	},
	reviews:{
		init : function(){
			$('.dicm_reviews .dicm-content').each(function(){
				var reviewsElement = this;
				var yelpID = $(this).attr('data-yelp-id');
				$.getJSON(DicmPageVars.pluginPath + 'data/reviews.php', {
					'business_id': yelpID
				}, function(data){
					var reviews = $.parseJSON(data).reviews;
					$(reviewsElement).empty();
					reviews.forEach(review => {
						var reviewHtml = `
							<div class="review">
								<div class="review_top">
									<div class="name">${review.user.name}</div>
									<div class="date">${review.time_created}</div>
									<div class="rating">Rating: ${review.rating} <i class="fas fa-star"></i></div>
								</div>
								<div class="content">
									${review.text}
									<a href="${review.url}" target="_blank">Read More</a>
								</div>
							</div>
						`;
						$(reviewsElement).append(reviewHtml);
					});
				});
			});
		}
	},
	search:{
		init : function(){
			if(!$('#search_listings_section').length) return;

			// $('#news_results').prependTo('#main-content #left-area');
			if($('#left-area article:eq(1)').length) $('#establishments_results').insertAfter('#left-area article:eq(1)');
			else $('#establishments_results').appendTo('#left-area');
			$('#events_deals_results').appendTo('#sidebar');

			$('.dicm_listings').each(function(){ // Hide empty listings
				if(!$(this).find('ul.listings_list li:visible').length) $(this).hide();
			});

			$('.search-results .pagination .alignleft a').addClass('search_next').html('Next Page &nbsp;<i class="fas fa-arrow-right"></i>');
			$('.search-results .pagination .alignright a').addClass('search_prev').html('<i class="fas fa-arrow-left"></i>&nbsp; Previous Page');

			$('.dicm_header .main_search input[type=text]').val($('#s').val());

			$('#search_listings_section').hide();
			$('.not-found-title').next().andSelf().hide();
		}
	},
	blog:{
		init : function(){
			if(!$('#blog_listings_section').length) return;
			$('#blog_listings_section .et_pb_module').appendTo('#sidebar');
			$('#blog_listings_section').hide();
		}
	}
}

$(document).ready(function(){
	DicmPage.listings.init();
	DicmPage.listings.initMaps();
	DicmPage.reviews.init();
	DicmPage.search.init();
	DicmPage.blog.init();
	DicmPage.homeValue.init();
});