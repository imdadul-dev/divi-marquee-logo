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
 * Plain text from mixed Divi / shortcode values.
 *
 * @param mixed $value Raw value.
 * @return string
 */
function dtm_normalize_text( $value ) {
	if ( is_string( $value ) ) {
		return $value;
	}
	if ( is_array( $value ) ) {
		if ( isset( $value['text'] ) && is_scalar( $value['text'] ) ) {
			return (string) $value['text'];
		}
		if ( isset( $value['value'] ) && is_scalar( $value['value'] ) ) {
			return (string) $value['value'];
		}
		return '';
	}
	if ( is_scalar( $value ) ) {
		return (string) $value;
	}
	return '';
}

/**
 * Image URL from upload field (string URL, JSON string, or array with url/src).
 *
 * @param mixed $value Raw value.
 * @return string
 */
function dtm_normalize_upload_url( $value ) {
	if ( is_string( $value ) ) {
		$t = trim( $value );
		if ( '' === $t ) {
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
			return $value['url'];
		}
		if ( ! empty( $value['src'] ) && is_string( $value['src'] ) ) {
			return $value['src'];
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
		return $t;
	}
	if ( is_array( $value ) ) {
		if ( ! empty( $value['hex'] ) && is_string( $value['hex'] ) ) {
			return $value['hex'];
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
	if ( is_string( $value ) || is_int( $value ) || is_float( $value ) ) {
		return (float) preg_replace( '/[^0-9.+-]/', '', (string) $value );
	}
	if ( is_array( $value ) && isset( $value['value'] ) ) {
		return dtm_normalize_number( $value['value'] );
	}
	return 0.0;
}
