<?php

/**
 * @package   WP2X
 * @author    Khorshid <info@khorshidlab.com>
 * @copyright 2020 Khorshid
 * @license   GPL 2.0+
 * @link      https://khorshidlab.com
 *
 * Plugin Name:     WP2X
 * Plugin URI:      https://khorshidlab.com
 * Description:     Plugin for wordpress tweaks
 * Version:         1.0.0
 * Author:          Khorshid
 * Author URI:      https://khorshidlab.com
 * Text Domain:     wp2x
 * License:         GPL 2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:     /languages
 * Requires PHP:    7.0
 * WordPress-Plugin-Boilerplate-Powered: v3.2.0
 */

// If this file is called directly, abort.
use WP2X\Engine\Initialize;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

define( 'W_VERSION', '1.0.0' );
define( 'W_TEXTDOMAIN', 'wp2x' );
define( 'W_NAME', __( 'Khorshid', W_TEXTDOMAIN ) );
define( 'W_PLUGIN_ROOT', plugin_dir_path( __FILE__ ) );
define( 'W_PLUGIN_ROOT_URL', plugin_dir_url( __FILE__ ) );
define( 'W_PLUGIN_ABSOLUTE', __FILE__ );

add_action(
	'init',
	static function () {
		load_plugin_textdomain( W_TEXTDOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
);

if ( version_compare( PHP_VERSION, '7.0.0', '<=' ) ) {
	add_action(
		'admin_init',
		static function () {
			deactivate_plugins( plugin_basename( __FILE__ ) );
		}
	);
	add_action(
		'admin_notices',
		static function () {
			echo wp_kses_post(
				sprintf(
					'<div class="notice notice-error"><p>%s</p></div>',
					__( '"WP2X" requires PHP 5.6 or newer.', W_TEXTDOMAIN )
				)
			);
		}
	);

	// Return early to prevent loading the plugin.
	return;
}

$wp2x_libraries = require_once W_PLUGIN_ROOT . 'vendor/autoload.php';

require_once W_PLUGIN_ROOT . 'functions/functions.php';

// Add your new plugin on the wiki: https://github.com/WPBP/WordPress-Plugin-Boilerplate-Powered/wiki/Plugin-made-with-this-Boilerplate

if ( ! wp_installing() ) {
	add_action(
		'plugins_loaded',
		static function () use ( $wp2x_libraries ) {
			new Initialize( $wp2x_libraries );
		}
	);
}

function change_login_logo() {
	$settings_option = get_option( 'wp2x-settings' );

	if ( isset( $settings_option['custom_logo'] ) ) {
		echo '<style type="text/css">h1 a {background-image: url( "' . $settings_option['custom_logo'] . '" ) !important; }</style>';
	}
}

add_action( 'login_head', 'change_login_logo' );
