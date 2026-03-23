<?php
/**
 * Normalize Divi field values (strings, JSON, arrays, upload objects).
 *
 * @package DiviTrustMarquee
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether a string is only JavaScript's default object string (possibly repeated, comma-separated).
 * Old Visual Builder saves sometimes stored this when field values were objects.
 *
 * @param string $s String to check.
 * @return bool
 */
function dtm_string_is_only_js_object_junk( $s ) {
	$s = trim( (string) $s );
	if ( '' === $s ) {
		return false;
	}
	$parts = preg_split( '/\s*,\s*/', $s );
	foreach ( $parts as $part ) {
		if ( strcasecmp( trim( $part ), '[object Object]' ) !== 0 ) {
			return false;
		}
	}
	return true;
}

/**
 * Remove [object Object] tokens and tidy leftover spaces from user-facing text.
 *
 * @param string $value Raw string.
 * @return string
 */
function dtm_strip_js_object_placeholders( $value ) {
	$value = preg_replace( '/\s*,\s*/', ' ', (string) $value );
	$value = preg_replace( '/\[object Object\]/i', '', $value );
	$value = preg_replace( '/\s+/u', ' ', trim( $value ) );
	return $value;
}

/**
 * Plain text from mixed Divi / shortcode values.
 *
 * @param mixed $value Raw value.
 * @return string
 */
function dtm_normalize_text( $value ) {
	$out = '';
	if ( is_string( $value ) ) {
		$out = $value;
	} elseif ( is_array( $value ) ) {
		if ( isset( $value['text'] ) && is_scalar( $value['text'] ) ) {
			$out = (string) $value['text'];
		} elseif ( isset( $value['value'] ) && is_scalar( $value['value'] ) ) {
			$out = (string) $value['value'];
		}
	} elseif ( is_scalar( $value ) ) {
		$out = (string) $value;
	}
	return dtm_strip_js_object_placeholders( $out );
}

/**
 * Image URL from upload field (string URL, JSON string, or array with url/src).
 *
 * @param mixed $value Raw value.
 * @return string
 */
function dtm_normalize_upload_url( $value ) {
	if ( is_object( $value ) ) {
		if ( isset( $value->url ) && is_string( $value->url ) ) {
			return dtm_normalize_upload_url( $value->url );
		}
		if ( isset( $value->src ) && is_string( $value->src ) ) {
			return dtm_normalize_upload_url( $value->src );
		}
		return '';
	}
	if ( is_string( $value ) ) {
		$t = trim( $value );
		if ( '' === $t || dtm_string_is_only_js_object_junk( $t ) ) {
			return '';
		}
		if ( strlen( $t ) > 0 && ( '{' === $t[0] || '[' === $t[0] ) ) {
			$d = json_decode( $t, true );
			if ( is_array( $d ) ) {
				return dtm_normalize_upload_url( $d );
			}
		}
		return $t;
	}
	if ( is_array( $value ) ) {
		if ( ! empty( $value['url'] ) && is_string( $value['url'] ) ) {
			return dtm_normalize_upload_url( $value['url'] );
		}
		if ( ! empty( $value['src'] ) && is_string( $value['src'] ) ) {
			return dtm_normalize_upload_url( $value['src'] );
		}
	}
	return '';
}

/**
 * CSS color string from Divi color / color-alpha (hex, rgba, or component array).
 *
 * @param mixed $value Raw value.
 * @return string
 */
function dtm_normalize_color( $value ) {
	if ( is_object( $value ) ) {
		if ( isset( $value->hex ) && is_string( $value->hex ) ) {
			return dtm_normalize_color( $value->hex );
		}
		if ( isset( $value->r, $value->g, $value->b ) ) {
			return dtm_normalize_color(
				array(
					'r' => $value->r,
					'g' => $value->g,
					'b' => $value->b,
					'a' => isset( $value->a ) ? $value->a : 1.0,
				)
			);
		}
		return '';
	}
	if ( is_string( $value ) ) {
		$t = trim( $value );
		if ( '' === $t ) {
			return '';
		}
		if ( strlen( $t ) > 0 && '{' === $t[0] ) {
			$d = json_decode( $t, true );
			if ( is_array( $d ) ) {
				return dtm_normalize_color( $d );
			}
		}
		if ( dtm_string_is_only_js_object_junk( $t ) ) {
			return '';
		}
		return $t;
	}
	if ( is_array( $value ) ) {
		if ( ! empty( $value['hex'] ) && is_string( $value['hex'] ) ) {
			return dtm_normalize_color( $value['hex'] );
		}
		if ( isset( $value['r'], $value['g'], $value['b'] ) ) {
			$r = (int) $value['r'];
			$g = (int) $value['g'];
			$b = (int) $value['b'];
			$a = isset( $value['a'] ) ? (float) $value['a'] : 1.0;
			if ( $a < 1 ) {
				return 'rgba(' . $r . ',' . $g . ',' . $b . ',' . $a . ')';
			}
			return sprintf( '#%02x%02x%02x', max( 0, min( 255, $r ) ), max( 0, min( 255, $g ) ), max( 0, min( 255, $b ) ) );
		}
	}
	return '';
}

/**
 * Numeric value from range / mixed input.
 *
 * @param mixed $value Raw value.
 * @return float
 */
function dtm_normalize_number( $value ) {
	if ( is_string( $value ) && dtm_string_is_only_js_object_junk( $value ) ) {
		return 0.0;
	}
	if ( is_string( $value ) || is_int( $value ) || is_float( $value ) ) {
		return (float) preg_replace( '/[^0-9.+-]/', '', (string) $value );
	}
	if ( is_array( $value ) && isset( $value['value'] ) ) {
		return dtm_normalize_number( $value['value'] );
	}
	return 0.0;
}
