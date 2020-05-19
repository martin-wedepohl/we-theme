<?php
/**
 * WP_Rig\WP_Rig\Top_Bar\Component class
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig\Top_Bar;

use WP_Rig\WP_Rig\Component_Interface;
use function WP_Rig\WP_Rig\wp_rig;
use \WP_Customize_Manager;
use function \add_action;
use function \is_admin;
use function \get_theme_mod;
use function \wp_enqueue_script;
use function \get_theme_file_uri;
use function \get_theme_file_path;

/**
 * Class for managing top bar functionality.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'top-bar';
	}

	/**
	 * Initialize the top bar class.
	 */
	public function initialize() {
		add_action( 'wp', [ $this, 'action_top_bar' ] );
		add_action( 'customize_register', [ $this, 'action_customize_register' ] );
	}

	/**
	 * Initializes the scroll to top functionality.
	 */
	public function action_top_bar() {
		// If this is the admin page, return early.
		if ( is_admin() ) {
			return;
		}

		// If top-bar is disabled in Customizer, return early.
		if ( 'no-top-bar' === get_theme_mod( 'top-bar' ) ) {
			return;
		}

		// Add the top bar.
		add_action( 'wp_body_open', [ $this, 'add_top_bar_to_body' ] );
	}

	/**
	 * Adds a setting and control for lazy loading the Customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 */
	public function action_customize_register( WP_Customize_Manager $wp_customize ) {
		$top_bar_choices = [
			'top-bar'    => __( 'Top bar on (default)', 'wp-rig' ),
			'no-top-bar' => __( 'Top bar off', 'wp-rig' ),
		];

		$wp_customize->add_section(
			'top-bar',
			[
				'priority' => 10,
				'title'    => __( 'Top Bar Options', 'wp-rig' ),
				'panel'    => 'theme_options',
			]
		);

		$wp_customize->add_setting(
			'top-bar',
			[
				'default'           => 'top-bar',
				'transport'         => 'postMessage',
				'sanitize_callback' => function( $input ) use ( $top_bar_choices ) : string {
					if ( array_key_exists( $input, $top_bar_choices ) ) {
						return $input;
					}

					return '';
				},
			]
		);

		$wp_customize->add_control(
			'top-bar',
			[
				'label'       => __( 'Top Bar', 'wp-rig' ),
				'section'     => 'top-bar',
				'type'        => 'radio',
				'description' => __( 'The top bar adds additional information at the very top of the web page.', 'wp-rig' ),
				'choices'     => $top_bar_choices,
			]
		);
	}

	/**
	 * Add the anchor that is used to scroll to the top of the page
	 */
	public function add_top_bar_to_body() {
		echo '<div id="top-bar"></div>';
	}

}
