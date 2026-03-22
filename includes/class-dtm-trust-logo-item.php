<?php
/**
 * Child module: single logo + label.
 *
 * @package DiviTrustMarquee
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class DTM_Trust_Logo_Item extends ET_Builder_Module {

	/**
	 * Module init.
	 */
	public function init() {
		$this->name   = esc_html__( 'Trust Logo', 'divi-trust-marquee' );
		$this->plural = esc_html__( 'Trust Logos', 'divi-trust-marquee' );

		$this->slug       = 'et_pb_dtm_trust_logo_item';
		$this->vb_support = 'on';
		$this->type       = 'child';

		$this->settings_modal_toggles = array(
			'general' => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Logo', 'divi-trust-marquee' ),
				),
			),
		);
	}

	/**
	 * Default props for shortcode attributes.
	 *
	 * @return array
	 */
	public function get_default_props() {
		return array(
			'logo_src'   => '',
			'logo_alt'   => '',
			'logo_label' => '',
		);
	}

	/**
	 * Fields for this child item.
	 *
	 * @return array
	 */
	public function get_fields() {
		return array(
			'logo_src' => array(
				'label'              => esc_html__( 'Logo Image', 'divi-trust-marquee' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => esc_attr__( 'Upload an image', 'divi-trust-marquee' ),
				'choose_text'        => esc_attr__( 'Choose an Image', 'divi-trust-marquee' ),
				'update_text'        => esc_attr__( 'Set As Image', 'divi-trust-marquee' ),
				'description'        => esc_html__( 'PNG, SVG, or JPG. Transparent backgrounds work well.', 'divi-trust-marquee' ),
				'toggle_slug'        => 'main_content',
			),
			'logo_alt' => array(
				'label'           => esc_html__( 'Logo Alt Text', 'divi-trust-marquee' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Short description for accessibility.', 'divi-trust-marquee' ),
				'toggle_slug'     => 'main_content',
			),
			'logo_label' => array(
				'label'           => esc_html__( 'Label', 'divi-trust-marquee' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Organisation or club name shown next to the logo.', 'divi-trust-marquee' ),
				'toggle_slug'     => 'main_content',
			),
		);
	}

	/**
	 * Render one logo row cell.
	 *
	 * @param array  $attrs Shortcode attributes.
	 * @param string $content Inner content (unused).
	 * @param string $render_slug Module slug.
	 * @return string
	 */
	public function shortcode_callback( $attrs, $content = null, $render_slug = '' ) {
		$attrs = shortcode_atts( $this->get_default_props(), $attrs );

		$logo_src   = isset( $attrs['logo_src'] ) ? $attrs['logo_src'] : '';
		$logo_alt   = isset( $attrs['logo_alt'] ) ? $attrs['logo_alt'] : '';
		$logo_label = isset( $attrs['logo_label'] ) ? $attrs['logo_label'] : '';

		if ( '' === trim( (string) $logo_src ) && '' === trim( (string) $logo_label ) ) {
			return '';
		}

		$alt = $logo_alt ? $logo_alt : wp_strip_all_tags( $logo_label );

		$extra_class = '';
		if ( method_exists( $this, 'module_classname' ) ) {
			$extra_class = $this->module_classname( $render_slug );
		}

		ob_start();
		?>
		<div class="dtm-item et_pb_module et_pb_dtm_trust_logo_item<?php echo $extra_class ? ' ' . esc_attr( $extra_class ) : ''; ?>">
			<?php if ( $logo_src ) : ?>
				<span class="dtm-item__logo">
					<img src="<?php echo esc_url( $logo_src ); ?>" alt="<?php echo esc_attr( $alt ); ?>" loading="lazy" decoding="async" />
				</span>
			<?php endif; ?>
			<?php if ( $logo_label ) : ?>
				<span class="dtm-item__label"><?php echo esc_html( $logo_label ); ?></span>
			<?php endif; ?>
		</div>
		<?php
		return ob_get_clean();
	}
}
