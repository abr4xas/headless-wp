<?php

declare(strict_types=1);

/**
 * Plugin Name: Headless Mode
 * Plugin URI: https://angelcruz.dev
 * Description: This plugin disables access to the front end of your site unless the logged-in user can edit posts. It also automatically accepts requests to REST API or WP_GRAPHQL endpoints.
 * Version: 1.0.0
 * Author: Angel Cruz
 * Author URI: https://angelcruz.dev
 * License: GPL V2
 * Text Domain: headless-mode
 * Requires at least: 5.0
 * Requires PHP: 7.4
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define default client URL constant if not already defined.
if ( ! defined( 'HEADLESS_MODE_CLIENT_URL' ) ) {
	define( 'HEADLESS_MODE_CLIENT_URL', 'http://localhost:3000' );
}

/**
 * Define default URL constant for internal use.
 */
if ( ! defined( 'HEADLESS_MODE_DEFAULT_URL' ) ) {
	define( 'HEADLESS_MODE_DEFAULT_URL', 'http://localhost:3000' );
}

/**
 * Load plugin classes.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-request-validator.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-redirect-handler.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-headless-mode.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/class-admin-settings.php';

/**
 * Initialize the plugin.
 *
 * @return void
 */
function headless_mode_init(): void {
	$plugin = Headless_Mode::get_instance();
	$plugin->init();
}

// Initialize plugin after WordPress is loaded.
add_action( 'plugins_loaded', 'headless_mode_init' );

/**
 * Activation hook.
 *
 * @return void
 */
function headless_mode_activate(): void {
	// Flush rewrite rules if needed in future versions.
	flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'headless_mode_activate' );

/**
 * Deactivation hook.
 *
 * @return void
 */
function headless_mode_deactivate(): void {
	// Clean up if needed in future versions.
	flush_rewrite_rules();
}

register_deactivation_hook( __FILE__, 'headless_mode_deactivate' );
