<?php
/**
 * The `wp_rig()` function.
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

/**
 * Provides access to all available template tags of the theme.
 *
 * When called for the first time, the function will initialize the theme.
 *
 * @return Template_Tags Template tags instance exposing template tag methods.
 */
function wp_rig() : Template_Tags {
	static $theme = null;

	if ( null === $theme ) {
		$theme = new Theme(
			[
				new Localization\Component(),
				new Base_Support\Component(),
				new Editor\Component(),
				new Accessibility\Component(),
				new Image_Sizes\Component(),
				new Lazyload\Component(),
				new AMP\Component(),
				new PWA\Component(),
				new Scroll_To_Top\Component(),
				new Top_Bar\Component(),
				new Sticky_Header\Component(),
				new Nav_Menus\Component(),
				new Sidebars\Component(),
				new Custom_Logo\Component(),
				new Post_Thumbnails\Component(),
				new Customizer\Component(),
				new Styles\Component(),
			]
		);
		$theme->initialize();
	}

	return $theme->template_tags();
}
