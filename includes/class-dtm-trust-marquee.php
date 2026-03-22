<?php
/**
 * Parent module: heading + marquee strip of child logos.
 *
 * @package DiviTrustMarquee
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class DTM_Trust_Marquee extends ET_Builder_Module {

	/**
	 * Module init.
	 */
	public function init() {
		$this->name      = esc_html__( 'Trust Bar Marquee', 'divi-trust-marquee' );
		$this->plural    = esc_html__( 'Trust Bar Marquees', 'divi-trust-marquee' );
		$this->slug      = 'et_pb_dtm_trust_marquee';
		$this->vb_support = 'on';

		$this->is_structural = true;

		$this->child_slug      = 'et_pb_dtm_trust_logo_item';
		$this->child_item_text = esc_html__( 'Logo', 'divi-trust-marquee' );

		$this->main_css_element = '%%order_class%%';

		$this->settings_modal_toggles = array(
			'general'  => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Heading', 'divi-trust-marquee' ),
					'marquee'      => esc_html__( 'Marquee', 'divi-trust-marquee' ),
				),
			),
			'advanced' => array(
				'toggles' => array(
					'width' => array(
						'title'    => esc_html__( 'Sizing', 'divi-trust-marquee' ),
						'priority' => 80,
					),
				),
			),
		);
	}

	/**
	 * Default attribute values.
	 *
	 * @return array
	 */
	public function get_default_props() {
		return array(
			'heading_text'        => esc_html__( 'TRUSTED BY CLUBS AND ORGANISATIONS GLOBALLY', 'divi-trust-marquee' ),
			'strip_bg'            => '#efefef',
			'marquee_duration'    => '45',
			'logo_max_height'     => '48',
			'label_color'         => '#666666',
			'heading_color'       => '#111111',
			'separator_color'     => '#cccccc',
		);
	}

	/**
	 * Parent fields.
	 *
	 * @return array
	 */
	public function get_fields() {
		return array(
			'heading_text' => array(
				'label'           => esc_html__( 'Heading', 'divi-trust-marquee' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Text above the logo strip.', 'divi-trust-marquee' ),
				'toggle_slug'     => 'main_content',
				'default'         => esc_html__( 'TRUSTED BY CLUBS AND ORGANISATIONS GLOBALLY', 'divi-trust-marquee' ),
			),
			'strip_bg' => array(
				'label'           => esc_html__( 'Strip Background', 'divi-trust-marquee' ),
				'type'            => 'color-alpha',
				'option_category' => 'basic_option',
				'toggle_slug'     => 'marquee',
				'default'         => '#efefef',
			),
			'heading_color' => array(
				'label'           => esc_html__( 'Heading Color', 'divi-trust-marquee' ),
				'type'            => 'color-alpha',
				'option_category' => 'basic_option',
				'toggle_slug'     => 'main_content',
				'default'         => '#111111',
			),
			'label_color' => array(
				'label'           => esc_html__( 'Label Color', 'divi-trust-marquee' ),
				'type'            => 'color-alpha',
				'option_category' => 'basic_option',
				'toggle_slug'     => 'marquee',
				'default'         => '#666666',
			),
			'separator_color' => array(
				'label'           => esc_html__( 'Separator Color', 'divi-trust-marquee' ),
				'type'            => 'color-alpha',
				'option_category' => 'basic_option',
				'toggle_slug'     => 'marquee',
				'default'         => '#cccccc',
			),
			'marquee_duration' => array(
				'label'           => esc_html__( 'Full Loop Duration (seconds)', 'divi-trust-marquee' ),
				'type'            => 'range',
				'option_category' => 'basic_option',
				'toggle_slug'     => 'marquee',
				'default'         => '45',
				'range_settings'  => array(
					'min'  => '10',
					'max'  => '120',
					'step' => '1',
				),
				'validate_unit'   => false,
				'fixed_unit'      => '',
				'fixed_range'     => true,
				'description'     => esc_html__( 'Higher value = slower scroll.', 'divi-trust-marquee' ),
			),
			'logo_max_height' => array(
				'label'           => esc_html__( 'Logo Max Height (px)', 'divi-trust-marquee' ),
				'type'            => 'range',
				'option_category' => 'basic_option',
				'toggle_slug'     => 'marquee',
				'default'         => '48',
				'range_settings'  => array(
					'min'  => '24',
					'max'  => '120',
					'step' => '1',
				),
				'validate_unit'   => false,
				'fixed_unit'      => '',
				'fixed_range'     => true,
			),
		);
	}

	/**
	 * Resolve inner HTML from Divi-rendered children.
	 *
	 * @param string|null $raw_content Shortcode inner content.
	 * @return string
	 */
	private function dtm_get_inner_html( $raw_content ) {
		if ( ! empty( $this->content ) ) {
			return (string) $this->content;
		}
		if ( ! empty( $raw_content ) ) {
			return do_shortcode( $raw_content );
		}
		return '';
	}

	/**
	 * Escape a color value for use inside a style block (hex or rgba from Divi picker).
	 *
	 * @param string $color Raw color.
	 * @return string
	 */
	private function dtm_escape_css_color( $color ) {
		$color = trim( (string) $color );
		if ( '' === $color ) {
			return '#efefef';
		}
		if ( preg_match( '/^#([0-9a-f]{3,8})$/i', $color ) ) {
			return $color;
		}
		if ( preg_match( '/^rgba?\([^)]+\)$/i', $color ) ) {
			return $color;
		}
		return '#efefef';
	}

	/**
	 * Output HTML for the trust bar + duplicated marquee track.
	 *
	 * @param array  $attrs Shortcode attributes.
	 * @param string $content Raw inner shortcodes or rendered HTML.
	 * @param string $function_name Module slug.
	 * @return string
	 */
	public function shortcode_callback( $attrs, $content = null, $function_name = '' ) {
		$defaults = $this->get_default_props();
		$a        = shortcode_atts( $defaults, $attrs );

		$heading = isset( $a['heading_text'] ) ? $a['heading_text'] : '';
		$strip   = isset( $a['strip_bg'] ) ? $a['strip_bg'] : '#efefef';

		$duration = isset( $a['marquee_duration'] ) ? floatval( $a['marquee_duration'] ) : 45;
		if ( $duration < 5 ) {
			$duration = 5;
		}

		$logo_h = isset( $a['logo_max_height'] ) ? absint( $a['logo_max_height'] ) : 48;
		if ( $logo_h < 16 ) {
			$logo_h = 16;
		}

		$label_c     = isset( $a['label_color'] ) ? $a['label_color'] : '#666666';
		$heading_c   = isset( $a['heading_color'] ) ? $a['heading_color'] : '#111111';
		$separator_c = isset( $a['separator_color'] ) ? $a['separator_color'] : '#cccccc';

		$inner = trim( $this->dtm_get_inner_html( $content ) );
		if ( '' === $inner ) {
			return '';
		}

		$strip_safe       = $this->dtm_escape_css_color( $strip );
		$heading_c_safe   = $this->dtm_escape_css_color( $heading_c );
		$label_c_safe     = $this->dtm_escape_css_color( $label_c );
		$separator_c_safe = $this->dtm_escape_css_color( $separator_c );

		$style_id = 'dtm-' . substr( md5( $inner . $duration . $logo_h . $label_c . $separator_c ), 0, 8 );

		$module_class = '';
		if ( method_exists( $this, 'module_classname' ) ) {
			$module_class = $this->module_classname( $function_name );
		}

		$esc_inner = function_exists( 'et_core_esc_previously' ) ? et_core_esc_previously( $inner ) : $inner;

		ob_start();
		?>
		<div class="dtm-trust-bar et_pb_module et_pb_dtm_trust_marquee<?php echo $module_class ? ' ' . esc_attr( $module_class ) : ''; ?>" data-dtm-id="<?php echo esc_attr( $style_id ); ?>">
			<style>
				[data-dtm-id="<?php echo esc_attr( $style_id ); ?>"] {
					--dtm-strip-bg: <?php echo esc_html( $strip_safe ); ?>;
					--dtm-heading-color: <?php echo esc_html( $heading_c_safe ); ?>;
					--dtm-label-color: <?php echo esc_html( $label_c_safe ); ?>;
					--dtm-separator: <?php echo esc_html( $separator_c_safe ); ?>;
					--dtm-logo-max-h: <?php echo esc_html( (string) $logo_h ); ?>px;
					--dtm-duration: <?php echo esc_html( (string) $duration ); ?>s;
				}
			</style>
			<?php if ( $heading ) : ?>
				<h2 class="dtm-trust-bar__heading"><?php echo esc_html( $heading ); ?></h2>
			<?php endif; ?>
			<div class="dtm-trust-bar__strip">
				<div class="dtm-marquee" role="presentation">
					<div class="dtm-marquee__track">
						<div class="dtm-marquee__set">
							<?php echo $esc_inner; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- builder HTML; escaped via et_core_esc_previously when available. ?>
						</div>
						<div class="dtm-marquee__set" aria-hidden="true">
							<?php echo $esc_inner; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
