<?php
/**
 * WP_Rig\WP_Rig\Sticky_Header\Component class
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig\Sticky_Header;

use WP_Rig\WP_Rig\Component_Interface;
use function WP_Rig\WP_Rig\wp_rig;
use WP_Customize_Manager;
use function \is_admin;
use function \add_action;
use function \get_theme_mod;
use function \wp_enqueue_script;
use function \get_theme_file_uri;
use function \wp_script_add_data;
use function \get_theme_file_path;

/**
 * Class for managing a sticky header.
 */
class Component implements Component_Interface {

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug() : string {
		return 'sticky-header';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'wp', [ $this, 'init' ] );
		add_action( 'customize_register', [ $this, 'register' ] );
	}

	/**
	 * Initializes sticky header functionality.
	 */
	public function init() {

		// If this is the admin page, return early.
		if ( is_admin() ) {
			return;
		}

		// If sticky header is disabled in Customizer, return early.
		if ( 'no-sticky-header' === get_theme_mod( 'sticky_header' ) ) {
			return;
		}

		// Enqueue the scripts and styles
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue' ] );

	}

	/**
	 * Adds a setting and control for thes ticky header the Customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
	 */
	public function register( WP_Customize_Manager $wp_customize ) {

		$sticky_header_choices = array(
			'sticky-header'    => __( 'Sticky Header on (default)', 'wp-rig' ),
			'no-sticky-header' => __( 'Sticky Header off', 'wp-rig' ),
		);

		$mobile_header_choices = array(
			'sticky-mobile'    => __( 'Sticky Mobile Header on', 'wp-rig' ),
			'no-sticky-mobile' => __( 'Sticky Mobile Header off (default)', 'wp-rig' ),
		);

		$wp_customize->add_section(
			'sticky_header_section',
			array(
				'priority' => 10,
				'title'    => __( 'Sticky Header', 'wp-rig' ),
				'panel'    => 'theme_options',
			)
		);

		$wp_customize->add_setting(
			'sticky_header',
			array(
				'default'           => 'sticky-header',
				'transport'         => 'postMessage',
				'sanitize_callback' => function( $input ) use ( $sticky_header_choices ) : string {
					if ( array_key_exists( $input, $sticky_header_choices ) ) {
						return $input;
					}

					return '';
				},
			)
		);

		$wp_customize->add_control(
			'sticky_header',
			array(
				'label'           => __( 'Sticky Header', 'wp-rig' ),
				'section'         => 'sticky_header_section',
				'type'            => 'radio',
				'description'     => __( 'Sticky Headers will stick the header to the top of the website. By default this will be off for mobile and on for other device sizes.', 'wp-rig' ),
				'choices'         => $sticky_header_choices,
			)
		);

		$wp_customize->add_setting(
			'mobile_header',
			array(
				'default'           => 'no-sticky-mobile',
				'transport'         => 'postMessage',
				'sanitize_callback' => function( $input ) use ( $mobile_header_choices ) : string {
					if ( array_key_exists( $input, $mobile_header_choices ) ) {
						return $input;
					}

					return '';
				},
			)
		);

		$wp_customize->add_control(
			'mobile_header',
			array(
				'label'           => __( 'Sticky Mobile Header', 'wp-rig' ),
				'section'         => 'sticky_header_section',
				'type'            => 'radio',
				'description'     => __( 'Set sticky header for mobile devices (defaults to off).', 'wp-rig' ),
				'choices'         => $mobile_header_choices,
			)
		);

	}

	/**
	 * Enqueues style and scripts. Ensure that the scripts are deferred.
	 */
	public function enqueue() {

		wp_enqueue_style(
			'wp-rig-sticky-style',
			get_theme_file_uri( '/assets/css/sticky.min.css' ),
			[],
			wp_rig()->get_asset_version( get_theme_file_path( '/assets/css/sticky.min.css' ) )
		);

		wp_enqueue_script(
			'wp-rig-sticky-header',
			get_theme_file_uri( '/assets/js/stickyheader.min.js' ),
			array(),
			wp_rig()->get_asset_version( get_theme_file_path( '/assets/js/stickyheader.min.js' ) ),
			true
		);
		$sticky_header_array = array(
			'sticky_mobile' => 'sticky-mobile' === get_theme_mod( 'mobile_header' ) ? 'true' : 'false',
		);
		wp_localize_script( 'wp-rig-sticky-header', 'sticky_header_data', $sticky_header_array );
		wp_script_add_data( 'wp-rig-sticky-header', 'defer', true );

	}

}
