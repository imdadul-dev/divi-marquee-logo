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
		// PHP-only module: "on" requires React; "partial" renders via PHP in the Visual Builder (avoids "[object Object]").
		$this->vb_support = 'partial';

		$this->is_structural = true;

		$this->child_slug      = 'et_pb_dtm_trust_logo_item';
		$this->child_item_text = esc_html__( 'Logo', 'divi-trust-marquee' );

		$this->main_css_element = '%%order_class%%';

		$this->settings_modal_toggles = array(
			'general'  => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Heading', 'divi-trust-marquee' ),
					'marquee'      => esc_html__( 'Marquee', 'divi-trust-marquee' ),
					'logos'        => esc_html__( 'Logos', 'divi-trust-marquee' ),
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
			'heading_text'        => '',
			'module_bg'           => '',
			'strip_bg'            => '',
			'marquee_duration'    => '45',
			'logo_max_height'     => '48',
			'label_color'         => '#4a4a4a',
			'heading_color'       => '#111111',
			'separator_color'     => '#9a9a9a',
			'show_dividers'       => 'on',
			'force_marquee_motion' => 'off',
			'pause_on_hover'      => 'on',
			'logo_max_width'      => '120',
			'logo_object_fit'     => 'contain',
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
				'description'     => esc_html__( 'Optional. Leave empty to show only the scrolling logo strip.', 'divi-trust-marquee' ),
				'toggle_slug'     => 'main_content',
				'default'         => '',
			),
			'module_bg' => array(
				'label'           => esc_html__( 'Module Background', 'divi-trust-marquee' ),
				'type'            => 'color-alpha',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Background behind the whole module (heading and strip). Leave empty for transparent.', 'divi-trust-marquee' ),
				'toggle_slug'     => 'marquee',
				'default'         => '',
			),
			'strip_bg' => array(
				'label'           => esc_html__( 'Logo Strip Background', 'divi-trust-marquee' ),
				'type'            => 'color-alpha',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Background of the scrolling logo bar only. Leave empty for no strip background.', 'divi-trust-marquee' ),
				'toggle_slug'     => 'marquee',
				'default'         => '',
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
				'default'         => '#4a4a4a',
			),
			'separator_color' => array(
				'label'           => esc_html__( 'Separator Color', 'divi-trust-marquee' ),
				'type'            => 'color-alpha',
				'option_category' => 'basic_option',
				'toggle_slug'     => 'marquee',
				'default'         => '#9a9a9a',
			),
			'show_dividers' => array(
				'label'           => esc_html__( 'Show Dividers', 'divi-trust-marquee' ),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'toggle_slug'     => 'marquee',
				'default'         => 'on',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'divi-trust-marquee' ),
					'off' => esc_html__( 'No', 'divi-trust-marquee' ),
				),
				'description'     => esc_html__( 'Vertical lines between each logo and label pair.', 'divi-trust-marquee' ),
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
				'description'     => esc_html__( 'Time for one full pass; the strip repeats forever automatically.', 'divi-trust-marquee' ),
			),
			'force_marquee_motion' => array(
				'label'           => esc_html__( 'Marquee when “reduce motion” is on', 'divi-trust-marquee' ),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'toggle_slug'     => 'marquee',
				'default'         => 'off',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'divi-trust-marquee' ),
					'off' => esc_html__( 'No', 'divi-trust-marquee' ),
				),
				'description'     => esc_html__( 'If Yes, the endless auto-scroll still runs when the visitor has “reduce motion” enabled (otherwise the strip becomes a static row).', 'divi-trust-marquee' ),
			),
			'pause_on_hover' => array(
				'label'           => esc_html__( 'Pause Marquee on Hover', 'divi-trust-marquee' ),
				'type'            => 'yes_no_button',
				'option_category' => 'basic_option',
				'toggle_slug'     => 'marquee',
				'default'         => 'on',
				'options'         => array(
					'on'  => esc_html__( 'Yes', 'divi-trust-marquee' ),
					'off' => esc_html__( 'No', 'divi-trust-marquee' ),
				),
				'description'     => esc_html__( 'When enabled, scrolling pauses while the pointer is over the logo strip.', 'divi-trust-marquee' ),
			),
			'logo_max_height' => array(
				'label'           => esc_html__( 'Logo Max Height (px)', 'divi-trust-marquee' ),
				'type'            => 'range',
				'option_category' => 'basic_option',
				'toggle_slug'     => 'logos',
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
			'logo_max_width' => array(
				'label'           => esc_html__( 'Logo Max Width (px)', 'divi-trust-marquee' ),
				'type'            => 'range',
				'option_category' => 'basic_option',
				'toggle_slug'     => 'logos',
				'default'         => '120',
				'range_settings'  => array(
					'min'  => '40',
					'max'  => '400',
					'step' => '1',
				),
				'validate_unit'   => false,
				'fixed_unit'      => '',
				'fixed_range'     => true,
				'description'     => esc_html__( 'Maximum width per logo image (also capped by viewport for small screens).', 'divi-trust-marquee' ),
			),
			'logo_object_fit' => array(
				'label'            => esc_html__( 'Image Fit', 'divi-trust-marquee' ),
				'type'             => 'select',
				'option_category'  => 'basic_option',
				'toggle_slug'      => 'logos',
				'default'          => 'contain',
				'options'          => array(
					'contain'    => esc_html__( 'Contain', 'divi-trust-marquee' ),
					'cover'      => esc_html__( 'Cover', 'divi-trust-marquee' ),
					'fill'       => esc_html__( 'Fill', 'divi-trust-marquee' ),
					'scale-down' => esc_html__( 'Scale down', 'divi-trust-marquee' ),
					'none'       => esc_html__( 'None', 'divi-trust-marquee' ),
				),
				'description'      => esc_html__( 'How each image fits inside the max width and height box.', 'divi-trust-marquee' ),
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
		// Divi 5 can pass child content in slightly different shapes (string HTML, shortcode string, or arrays/objects).
		// Normalize to a string so the rest of the renderer can stay the same.

		if ( ! empty( $this->content ) ) {
			$content = $this->content;
			if ( is_string( $content ) ) {
				return (string) $content;
			}
			if ( is_array( $content ) ) {
				// Common case: arrays of already-rendered chunks.
				if ( isset( $content['content'] ) ) {
					$content = $content['content'];
				}
				if ( is_string( $content ) ) {
					return (string) $content;
				}
				$parts = array();
				foreach ( $content as $v ) {
					if ( is_string( $v ) ) {
						$parts[] = $v;
					} elseif ( is_scalar( $v ) ) {
						$parts[] = (string) $v;
					}
				}
				return implode( '', $parts );
			}
			if ( is_object( $content ) && method_exists( $content, '__toString' ) ) {
				return (string) $content;
			}
		}

		if ( empty( $raw_content ) ) {
			return '';
		}

		if ( is_string( $raw_content ) ) {
			return do_shortcode( $raw_content );
		}

		if ( is_array( $raw_content ) ) {
			// Prefer any nested "content" key when Divi supplies structured payloads.
			if ( isset( $raw_content['content'] ) ) {
				$maybe = $raw_content['content'];
				if ( is_string( $maybe ) ) {
					return do_shortcode( $maybe );
				}
			}

			$parts = array();
			foreach ( $raw_content as $v ) {
				if ( is_string( $v ) ) {
					$parts[] = $v;
				} elseif ( is_scalar( $v ) ) {
					$parts[] = (string) $v;
				}
			}
			return do_shortcode( implode( '', $parts ) );
		}

		if ( is_object( $raw_content ) && method_exists( $raw_content, '__toString' ) ) {
			return do_shortcode( (string) $raw_content );
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
			return '#ececec';
		}
		if ( preg_match( '/^#([0-9a-f]{3,8})$/i', $color ) ) {
			return $color;
		}
		if ( preg_match( '/^rgba?\([^)]+\)$/i', $color ) ) {
			return $color;
		}
		return '#ececec';
	}

	/**
	 * Whitelist object-fit for logo images.
	 *
	 * @param string $value Raw attribute.
	 * @return string
	 */
	private function dtm_sanitize_object_fit( $value ) {
		$allowed = array( 'contain', 'cover', 'fill', 'scale-down', 'none' );
		$v         = strtolower( trim( (string) $value ) );
		return in_array( $v, $allowed, true ) ? $v : 'contain';
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

		$heading = isset( $a['heading_text'] ) ? dtm_normalize_text( $a['heading_text'] ) : '';
		$strip = isset( $a['strip_bg'] ) ? dtm_normalize_color( $a['strip_bg'] ) : '';

		$module_bg = isset( $a['module_bg'] ) ? dtm_normalize_color( $a['module_bg'] ) : '';
		if ( '' === trim( $module_bg ) ) {
			$module_bg_css = 'transparent';
		} else {
			$module_bg_css = $this->dtm_escape_css_color( $module_bg );
		}

		$duration = isset( $a['marquee_duration'] ) ? dtm_normalize_number( $a['marquee_duration'] ) : 0;
		if ( $duration <= 0 ) {
			$duration = 45;
		}
		if ( $duration < 5 ) {
			$duration = 5;
		}

		$logo_h = isset( $a['logo_max_height'] ) ? absint( dtm_normalize_number( $a['logo_max_height'] ) ) : 0;
		if ( $logo_h <= 0 ) {
			$logo_h = 48;
		}
		if ( $logo_h < 16 ) {
			$logo_h = 16;
		}

		$logo_w = isset( $a['logo_max_width'] ) ? absint( dtm_normalize_number( $a['logo_max_width'] ) ) : 0;
		if ( $logo_w <= 0 ) {
			$logo_w = 120;
		}
		if ( $logo_w < 40 ) {
			$logo_w = 40;
		}
		if ( $logo_w > 400 ) {
			$logo_w = 400;
		}

		$object_fit = isset( $a['logo_object_fit'] ) ? $this->dtm_sanitize_object_fit( $a['logo_object_fit'] ) : 'contain';

		$label_c = isset( $a['label_color'] ) ? dtm_normalize_color( $a['label_color'] ) : '';
		if ( '' === $label_c ) {
			$label_c = '#4a4a4a';
		}

		$heading_c = isset( $a['heading_color'] ) ? dtm_normalize_color( $a['heading_color'] ) : '';
		if ( '' === $heading_c ) {
			$heading_c = '#111111';
		}

		$separator_c = isset( $a['separator_color'] ) ? dtm_normalize_color( $a['separator_color'] ) : '';
		if ( '' === $separator_c ) {
			$separator_c = '#9a9a9a';
		}

		$show_dividers_raw = isset( $a['show_dividers'] ) ? strtolower( (string) $a['show_dividers'] ) : 'on';
		$show_dividers     = ! in_array( $show_dividers_raw, array( 'off', 'false', '0', 'no' ), true );

		$pause_on_hover_raw = isset( $a['pause_on_hover'] ) ? strtolower( (string) $a['pause_on_hover'] ) : 'on';
		$pause_on_hover     = ! in_array( $pause_on_hover_raw, array( 'off', 'false', '0', 'no' ), true );

		$force_motion_raw = isset( $a['force_marquee_motion'] ) ? strtolower( (string) $a['force_marquee_motion'] ) : 'off';
		$force_marquee    = ! in_array( $force_motion_raw, array( 'off', 'false', '0', 'no' ), true );

		$inner = trim( $this->dtm_get_inner_html( $content ) );
		if ( '' === $inner ) {
			return '';
		}

		$strip_safe = '' === trim( $strip ) ? 'transparent' : $this->dtm_escape_css_color( $strip );
		$heading_c_safe   = $this->dtm_escape_css_color( $heading_c );
		$label_c_safe     = $this->dtm_escape_css_color( $label_c );
		$separator_c_safe = $this->dtm_escape_css_color( $separator_c );

		$style_id = 'dtm-' . substr(
			md5(
				$inner . $duration . $logo_h . $logo_w . $object_fit . $label_c . $separator_c . $module_bg_css
				. ( $show_dividers ? '1' : '0' )
				. ( $force_marquee ? '1' : '0' )
				. ( $pause_on_hover ? '1' : '0' )
			),
			0,
			8
		);

		$module_class = '';
		if ( method_exists( $this, 'module_classname' ) ) {
			$module_class = $this->module_classname( $function_name );
		}

		$esc_inner = function_exists( 'et_core_esc_previously' ) ? et_core_esc_previously( $inner ) : $inner;

		ob_start();
		?>
		<div class="dtm-trust-bar et_pb_module et_pb_dtm_trust_marquee<?php echo $module_class ? ' ' . esc_attr( $module_class ) : ''; ?><?php echo $show_dividers ? '' : ' dtm-trust-bar--no-dividers'; ?><?php echo $force_marquee ? ' dtm-trust-bar--force-marquee' : ''; ?><?php echo $pause_on_hover ? ' dtm-trust-bar--pause-hover' : ''; ?>" data-dtm-id="<?php echo esc_attr( $style_id ); ?>">
			<style>
				[data-dtm-id="<?php echo esc_attr( $style_id ); ?>"] {
					--dtm-module-bg: <?php echo esc_html( $module_bg_css ); ?>;
					--dtm-strip-bg: <?php echo esc_html( $strip_safe ); ?>;
					--dtm-heading-color: <?php echo esc_html( $heading_c_safe ); ?>;
					--dtm-label-color: <?php echo esc_html( $label_c_safe ); ?>;
					--dtm-separator: <?php echo esc_html( $separator_c_safe ); ?>;
					--dtm-logo-max-h: <?php echo esc_html( (string) $logo_h ); ?>px;
					--dtm-logo-max-w: <?php echo esc_html( (string) $logo_w ); ?>px;
					--dtm-logo-object-fit: <?php echo esc_html( $object_fit ); ?>;
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
