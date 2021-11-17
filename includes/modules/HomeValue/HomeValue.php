<?php
/**
 * Basic Call To Action module (title, content, and button) with FULL builder support
 * This module appears on Visual Builder and requires react component to be provided
 * Due to full builder support, all advanced options (except button options) are added by default
 *
 * @since 1.0.0
 */
class DICM_Home_Value extends ET_Builder_Module {
	// Module slug (also used as shortcode tag)
	public $slug       = 'dicm_home_value';

	// Visual Builder support (off|partial|on)
	public $vb_support = 'on';

	/**
	 * Module properties initialization
	 *
	 * @since 1.0.0
	 */
	function init() {
		// Module name
		$this->name             = esc_html__( 'Home Value', 'dicm-divi-custom-modules' );

		// Module Icon
		// Load customized svg icon and use it on builder as module icon. If you don't have svg icon, you can use
		// $this->icon for using etbuilder font-icon. (See CustomCta / DICM_CTA class)
		$this->icon             = '1';

		// Toggle settings
		$this->settings_modal_toggles  = array(
			'general'  => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Text', 'dicm-divi-custom-modules' ),
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
			'image' => array(
				'label'              => esc_html__( 'Image', 'dicm-divi-custom-modules' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => esc_attr__( 'Upload an image', 'et_builder' ),
				'choose_text'        => esc_attr__( 'Choose a Slide Image', 'et_builder' ),
				'update_text'        => esc_attr__( 'Set As Image', 'et_builder' ),
				'affects'            => array(
					'image_alt',
				),
				'toggle_slug'        => 'main_content',
				'dynamic_content'    => 'image',
				'mobile_options'     => true,
				'hover'              => 'tabs',
			),
			'title' => array(
				'label'           => esc_html__( 'Title', 'dicm-divi-custom-modules' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Text entered here will appear as title.', 'dicm-divi-custom-modules' ),
				'toggle_slug'     => 'main_content',
			),
			'content' => array(
				'label'           => esc_html__( 'Content', 'dicm-divi-custom-modules' ),
				'type'            => 'tiny_mce',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Content entered here will appear inside the module.', 'dicm-divi-custom-modules' ),
				'toggle_slug'     => 'main_content',
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
		$title                 = $this->props['title'];
		$image                 = $this->props['image'];
		$form_html = <<<HTML
			<form>
				<input type="text" class="input" name="address" placeholder="Home address" /> 
				<input type="text" class="input" name="zip" placeholder="Zip code" /> 
				<input type="text" class="input" name="first_name" placeholder="First name" /> 
				<input type="text" class="input" name="last_name" placeholder="Last name" /> 
				<input type="text" class="input" name="email" placeholder="Email address" /> 
				<input type="button" class="et_pb_button" value="Get Home Value" onclick="DicmPage.homeValue.getHomeValue(this)" />
			</form>
			<div class="result_text"></div>
		HTML;
		$image_html = <<<HTML
			<div class="image_wrapper"><img src="{$image}" /></div>
		HTML;

		// 3rd party module with full VB support doesn't need to manually wrap its module. Divi builder
		// has automatically wrapped it
		return sprintf(
			'
			<div>
				%3$s
				<div>
					<h4 class="dicm-title">%1$s</h4>
					<div class="dicm-content">%2$s</div>
					%4$s
				</div>
			</div>
			',
			esc_html( $title ),
			$this->content,
			!empty($image) ? $image_html : "",
			$form_html
		);
	}
}

new DICM_Home_Value;
