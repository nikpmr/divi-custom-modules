<?php
/**
 * Basic Call To Action module (title, content, and button) with FULL builder support
 * This module appears on Visual Builder and requires react component to be provided
 * Due to full builder support, all advanced options (except button options) are added by default
 *
 * @since 1.0.0
 */
class DICM_Header extends ET_Builder_Module {
	// Module slug (also used as shortcode tag)
	public $slug       = 'dicm_header';

	// Visual Builder support (off|partial|on)
	public $vb_support = 'on';

	/**
	 * Module properties initialization
	 *
	 * @since 1.0.0
	 */
	function init() {
		// Module name
		$this->name             = esc_html__( 'Header', 'dicm-divi-custom-modules' );

		// Module Icon
		// Load customized svg icon and use it on builder as module icon. If you don't have svg icon, you can use
		// $this->icon for using etbuilder font-icon. (See CustomCta / DICM_CTA class)
		$this->icon             = 'a';

		// Toggle settings
		$this->settings_modal_toggles  = array(
			'general'  => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Header', 'dicm-divi-custom-modules' ),
				)
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
				'update_text'        => esc_attr__( 'Set As Slide Image', 'et_builder' ),
				'affects'            => array(
					'image_alt',
				),
				'description'        => esc_html__( 'Image which appears next to your title.', 'et_builder' ),
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
			'login' => array(
				'label'             => esc_html__( 'Show Login Button', 'et_builder' ),
				'type'              => 'yes_no_button',
				'default_on_front' => 'on',
				'options'           => array(
					'on'  => esc_html__( 'On', 'et_builder' ),
					'off' => esc_html__( 'Off', 'et_builder' ),
				),
				// 'tab_slug'        => $all_types_tab_slug,
				'toggle_slug'     => 'main_content',
			),
			'search' => array(
				'label'             => esc_html__( 'Show Search Bar', 'et_builder' ),
				'type'              => 'yes_no_button',
				'default_on_front' => 'on',
				'options'           => array(
					'on'  => esc_html__( 'On', 'et_builder' ),
					'off' => esc_html__( 'Off', 'et_builder' ),
				),
				// 'tab_slug'        => $all_types_tab_slug,
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
		$login                 = $this->props['login'];
		$search                = $this->props['search'];

		$title = preg_replace('/\&\#91;community\&\#93;/', get_community(), $title);
		$title = preg_replace('/\&\#91;state\&\#93;/', get_state(), $title);

		// 3rd party module with full VB support doesn't need to manually wrap its module. Divi builder
		// has automatically wrapped it
		return sprintf('
			<div>
				%2$s
				<h2 class="dicm-title">%1$s</h2>
				<form action="/">
					%3$s
					%4$s
				</form>
			</div>
			',
			esc_html( $title ),
			!empty($image) ? '<div class="image_wrapper"><a href="/"><img src="' . $image . '" /></a></div>' : '',
			$login == 'on' ? '<a class="login_button" href="#!">Login</a>' : '',
			$search == 'on' ? '<div class="main_search"><input type="text" name="s" placeholder="Search" /></div>' : ''
		);
	}
}

new DICM_Header;
