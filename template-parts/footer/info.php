<?php
/**
 * Template part for displaying the footer info
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

?>

<div class="site-info">
	<span class="copyright">
		&copy; 2002-<?php echo current_time( 'Y' ); ?> Wedepohl Engineering
	</span>
	<?php if ( function_exists( 'the_privacy_policy_link' ) ) { ?>
	<span class="privacy">
		<?php the_privacy_policy_link(); ?>
	</span><!-- .privacy -->
	<?php } ?>
</div><!-- .site-info -->
