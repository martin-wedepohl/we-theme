<?php
/**
 * WP_Rig\WP_Rig\Scroll_To_Top\Component class
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig\Scroll_To_Top;

use WP_Rig\WP_Rig\Component_Interface;
use function WP_Rig\WP_Rig\wp_rig;
use \WP_Customize_Manager;
use function \esc_attr;
use function \is_admin;
use function \add_action;
use function \get_theme_mod;
use function \wp_enqueue_script;
use function \get_theme_file_uri;
use function \get_theme_file_path;

/**
 * Class for managing scroll to top functionality.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'scroll-to-top';
	}

	/**
	 * Initialize the scroll to top class.
	 */
	public function initialize() {
		add_action( 'wp', [ $this, 'action_scroll_to_top' ] );
		add_action( 'customize_register', [ $this, 'action_customize_register' ] );
	}

	/**
	 * Initializes the scroll to top functionality.
	 */
	public function action_scroll_to_top() {
		// If this is the admin page, return early.
		if ( is_admin() ) {
			return;
		}

		// If scroll-to-top is disabled in Customizer, return early.
		if ( 'no-scroll-to-top' === get_theme_mod( 'scroll_to_top' ) ) {
			return;
		}

		// Enqueue the assets.
		add_action( 'wp_enqueue_scripts', [ $this, 'action_enqueue_assets' ] );

		// Add the anchor to the top of the page.
		add_action( 'wp_body_open', [ $this, 'add_anchor_to_body' ], 1 );
	}

	/**
	 * Enqueue style and scripts.
	 */
	public function action_enqueue_assets() {

		wp_enqueue_style(
			'wp-rig-scroll-to-top-style',
			get_theme_file_uri( '/assets/css/scroll-to-top.min.css' ),
			[],
			wp_rig()->get_asset_version( get_theme_file_path( '/assets/css/scroll-to-top.min.css' ) )
		);

		wp_enqueue_script(
			'wp-rig-scroll-to-top-script',
			get_theme_file_uri( '/assets/js/scroll-to-top.min.js' ),
			[],
			wp_rig()->get_asset_version( get_theme_file_path( '/assets/js/scroll-to-top.min.js' ) ),
			false
		);

		// Defer javascript to end of DOM.
		wp_script_add_data( 'wp-rig-scroll-to-top-script', 'defer', true );

		// Localize the script.
		$bottom = get_theme_mod( 'scroll-to-top-bottom', 20 ) . 'px';
		$right  = get_theme_mod( 'scroll-to-top-right', 20 ) . 'px';
		$scroll = get_theme_mod( 'scroll-to-top-scroll-len', 20 );

		$css_data = [
			'bottom' => $bottom,
			'right'  => $right,
			'scroll' => $scroll,
		];
		wp_localize_script( 'wp-rig-scroll-to-top-script', 'css_objects', $css_data );
	}

	/**
	 * Adds a setting and control for lazy loading the Customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 */
	public function action_customize_register( WP_Customize_Manager $wp_customize ) {
		$scroll_to_top_choices = [
			'scroll-to-top'    => __( 'Scroll to top on (default)', 'wp-rig' ),
			'no-scroll-to-top' => __( 'Scroll to top off', 'wp-rig' ),
		];

		$wp_customize->add_section(
			'scroll_to_top',
			[
				'priority' => 10,
				'title'    => __( 'Scroll To Top Options', 'wp-rig' ),
				'panel'    => 'theme_options',
			]
		);

		$wp_customize->add_setting(
			'scroll_to_top',
			[
				'default'           => 'scroll-to-top',
				'transport'         => 'postMessage',
				'sanitize_callback' => function( $input ) use ( $scroll_to_top_choices ) : string {
					if ( array_key_exists( $input, $scroll_to_top_choices ) ) {
						return $input;
					}

					return '';
				},
			]
		);

		$wp_customize->add_setting(
			'scroll-to-top-icon',
			array(
				'default'           => 'arrow-up',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);

		$wp_customize->add_setting(
			'scroll-to-top-scroll-len',
			[
				'default'           => 20,
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
			]
		);

		$wp_customize->add_setting(
			'scroll-to-top-bottom',
			[
				'default'           => 20,
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
			]
		);

		$wp_customize->add_setting(
			'scroll-to-top-right',
			[
				'default'           => 20,
				'transport'         => 'postMessage',
				'sanitize_callback' => 'sanitize_text_field',
			]
		);

		$wp_customize->add_control(
			'scroll_to_top',
			[
				'label'           => __( 'Scroll to top', 'wp-rig' ),
				'section'         => 'scroll_to_top',
				'type'            => 'radio',
				'description'     => __( 'Scroll to top puts an icon in the bottom right of the web page that when clicked will scroll to the top of the page.', 'wp-rig' ),
				'choices'         => $scroll_to_top_choices,
			]
		);

		$wp_customize->add_control(
			new Icon_Select(
				$wp_customize,
				'scroll-to-top-icon',
				array(
					'label'       => __( 'Select icon to use' ),
					'description' => __( 'Select the icon that is used at the bottom of the page.', 'wp-rig' ),
					'section'     => 'scroll_to_top',
					'choices'     => [
						'arrow-up'        => __( 'Arrow - default', 'wp-rig' ),
						'chevron-up'      => __( 'Chevron', 'wp-rig' ),
						'angle-up'        => __( 'Angle', 'wp-rig' ),
						'angle-double-up' => __( 'Double Angle', 'wp-rig' ),
						'caret-up'        => __( 'Caret', 'wp-rig' ),
						'hand-o-up'       => __( 'Hand', 'wp-rig' ),
					],
				)
			)
		);

		$wp_customize->add_control(
			'scroll-to-top-scroll-len',
			[
				'label'           => __( 'Scroll height', 'wp-rig' ),
				'section'         => 'scroll_to_top',
				'type'            => 'number',
				'description'     => __( 'Number of pixels to scroll before the scroll to top icon appears.', 'wp-rig' ),
				'input_attrs'     => [
					'min'  => 1,
					'max'  => 1000,
					'step' => 1,
				],
			]
		);

		$wp_customize->add_control(
			'scroll-to-top-bottom',
			[
				'label'           => __( 'Icon bottom', 'wp-rig' ),
				'section'         => 'scroll_to_top',
				'type'            => 'number',
				'description'     => __( 'Number of pixels the icon is from the bottom of the screen.', 'wp-rig' ),
				'input_attrs'     => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 1,
				],
			]
		);

		$wp_customize->add_control(
			'scroll-to-top-right',
			[
				'label'       => __( 'Icon right', 'wp-rig' ),
				'section'     => 'scroll_to_top',
				'type'        => 'number',
				'description' => __( 'Number of pixels the icon is from the right of the screen.', 'wp-rig' ),
				'input_attrs' => [
					'min'  => 0,
					'max'  => 1000,
					'step' => 1,
				],
			]
		);

	}

	/**
	 * Add the anchor that is used to scroll to the top of the page
	 */
	public function add_anchor_to_body() {
		$icon = get_theme_mod( 'scroll-to-top-icon', 'arrow-up' );
		echo '<a id="top-of-page" aria-hidden="true"></a>';
		echo '<div id="back-to-top" title="Go to the top of the page" aria-hidden="true"><a href="#top-of-page"><i class="fa fa-' . esc_attr( $icon ) . '" ></i></a></div>';
	}

}
