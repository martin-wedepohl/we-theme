<?php
/**
 * Render your site front page, whether the front page displays the blog posts index or a static page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#front-page-display
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

get_header();

// Use grid layout if blog index is displayed.
if ( is_home() ) {
	wp_rig()->print_styles( 'wp-rig-content' );
} else {
	wp_rig()->print_styles( 'wp-rig-front-page' );
}

?>
	<main id="primary" class="site-main">
		<?php

		while ( have_posts() ) {
			the_post();
			the_content();
		}

		?>
	</main><!-- #primary -->
<?php
get_footer();
