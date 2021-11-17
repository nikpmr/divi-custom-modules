//Handles custom options (toggles, fields, etc.) added to Divi Frontend Builder modals.

DicmOptions = {
	replaceToggle(toggle_name, replacement_html){ //replaces entire toggle contents with specified HTML
		insertionQ('.et-fb-modal .et-fb-form__toggle[data-name=' + toggle_name + ']').every(function(element){
			$(element).attr('data-custom_toggle','1');
			$(element).addClass('test');
			var outer_replacement_html = `
				<div class="et-fb-form__toggle-html">
					${replacement_html}
				</div>
			`;
			if(!$(element).find('.et-fb-form__toggle-html').length)
				$(element).find('.et-fb-form__toggle-title').after(outer_replacement_html);
		});
	},
	replaceField(toggle_name, field_name, replacement_html){ //replace a single field with specified HTML
		insertionQ('.et-fb-modal .et-fb-form__toggle[data-name=' + toggle_name + '] .et-fb-form__group [name=' + field_name + ']')
		.every(function(element){
			var $container = $(element).closest('.et-fb-form__group');

			$container.attr('data-custom_field','1');
			if(replacement_html == '') $container.addClass('no_padding');
			var outer_replacement_html = `
				<div class="et-fb-form__option-html">
					${replacement_html}
				</div>
			`;
			if(!$container.find('.et-fb-form__option-html').length)
				$container.append(outer_replacement_html);
		});
	}
}

$(document).ready(function(){
	DicmOptions.replaceToggle('facebook_feed_main_content', `
		<a href="/wp-admin/admin.php?page=cff-top" target="_blank">Click here</a> 
		to edit the Facebook Feed settings for your community page.
	`);
	DicmOptions.replaceField('listings_main_content', 'schools_populate_listings', `
		<a class="et-fb-modal__button" href="#!" onclick="DicmBuilder.populateListings(this,'schools')">Populate Listings</a>
	`);
	// DicmOptions.replaceField('listings_main_content', 'output', '');
});