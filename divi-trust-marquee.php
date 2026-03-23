<?php
/**
 * Plugin Name: Divi Trust Bar Marquee
 * Description: A Divi module with a heading and a horizontally scrolling logo strip (marquee), similar to a “trusted by” bar.
 * Version: 1.0.11
 * Author: Custom
 * Text Domain: divi-trust-marquee
 * Requires at least: 5.0
 * Requires PHP: 7.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'DTM_VERSION', '1.0.11' );
define( 'DTM_PLUGIN_FILE', __FILE__ );
define( 'DTM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'DTM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Load modules after Divi Builder is ready.
 */
function dtm_load_modules() {
	if ( ! class_exists( 'ET_Builder_Module' ) ) {
		return;
	}

	require_once DTM_PLUGIN_DIR . 'includes/dtm-helpers.php';
	require_once DTM_PLUGIN_DIR . 'includes/class-dtm-trust-logo-item.php';
	require_once DTM_PLUGIN_DIR . 'includes/class-dtm-trust-marquee.php';

	new DTM_Trust_Logo_Item();
	new DTM_Trust_Marquee();
}
add_action( 'et_builder_ready', 'dtm_load_modules', 11 );

/**
 * Front-end styles (module markup is rendered by Divi on the page).
 */
function dtm_enqueue_assets() {
	if ( ! class_exists( 'ET_Builder_Element' ) ) {
		return;
	}

	wp_register_style(
		'dtm-trust-marquee',
		DTM_PLUGIN_URL . 'assets/css/trust-marquee.css',
		array(),
		DTM_VERSION
	);
	wp_enqueue_style( 'dtm-trust-marquee' );
}
add_action( 'wp_enqueue_scripts', 'dtm_enqueue_assets', 20 );
