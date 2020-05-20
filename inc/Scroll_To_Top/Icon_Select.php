<?php
/**
 * WP_Rig\WP_Rig\Scroll_To_Top\Icon_Select class
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig\Scroll_To_Top;

use \WP_Customize_Control;
use function WP_Rig\WP_Rig\wp_rig;
use function \checked;
use function \esc_attr;
use function \wp_enqueue_style;
use function \get_template_directory_uri;

/**
 * Class for displaying icons in a customizer radio selection.
 */
class Icon_Select extends WP_Customize_Control  {

	/**
	 * The type of control being rendered.
	 *
	 * @var string $type The type of control
	 */
	public $type = 'icon_select';

	/**
	 * Enqueue the icons css file.
	 */
	public function enqueue() {
		wp_enqueue_style(
			'we-custom-control-css',
			get_template_directory_uri() . '/assets/css/icomoon.min.css',
			[],
			wp_rig()->get_asset_version( get_theme_file_path( '/assets/css/icomoon.min.css' ) )
		);
	}

	/**
	 * Render the control in the customizer
	 */
	public function render_content() {

		?>

		<span class="customize-control-title"><?php echo esc_attr( $this->label ); ?></span>
		<span id="_customize-description-<?php echo esc_attr( $this->id ); ?>" class="description customize-control-description"><?php echo esc_attr( $this->description ); ?></span>

		<?php foreach ( $this->choices as $id => $value ) { ?>

		<span>
			<input id="_customize-input-<?php echo esc_attr( $this->id ); ?>-radio-<?php echo esc_attr( $id ); ?>" type="radio" aria-describedby="_customize-description-<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $id ); ?>" name="_customize-radio-<?php echo esc_attr( $this->id ); ?>" data-customize-setting-link="<?php echo esc_attr( $this->id ); ?>" <?php checked( esc_attr( $id ), $this->value() ); ?>>
			<label for="_customize-input-<?php echo esc_attr( $this->id ); ?>-radio-<?php esc_attr( $id ); ?>"><i aria-hidden="true" class="fa fa-<?php echo esc_attr( $id ); ?>"></i> (<?php echo esc_attr( $value ); ?>)</label><br>
		</span>

		<?php
		} // foreach

	}

}
