<?php
/**
 * WP2X
 *
 * @package   WP2X
 * @author    Khorshid <info@khorshidlab.com>
 * @copyright 2020 Khorshid
 * @license   GPL 2.0+
 * @link      https://khorshidlab.com
 */

namespace WP2X\Frontend;

use WP2X\Engine\Base;
use function add_action;
use function plugins_url;
use function wp_enqueue_script;
use function wp_enqueue_style;
use function wp_localize_script;

/**
 * Enqueue stuff on the frontend
 */
class Enqueue extends Base {

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public static function enqueue_styles() {
		wp_enqueue_style( W_TEXTDOMAIN . '-plugin-styles', plugins_url( 'assets/css/public.css', W_PLUGIN_ABSOLUTE ), array(), W_VERSION );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public static function enqueue_scripts() {
		wp_enqueue_script( W_TEXTDOMAIN . '-plugin-script', plugins_url( 'assets/js/public.js', W_PLUGIN_ABSOLUTE ), array( 'jquery' ), W_VERSION, false );
	}

	/**
	 * Print the PHP var in the HTML of the frontend for access by JavaScript.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public static function enqueue_js_vars() {
		wp_localize_script(
			W_TEXTDOMAIN . '-plugin-script',
			'w_js_vars',
			array(
				'alert' => __( 'Hey! You have clicked the button!', W_TEXTDOMAIN ),
			)
		);
	}

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function initialize() {
		parent::initialize();

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( self::class, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( self::class, 'enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( self::class, 'enqueue_js_vars' ) );
	}


}
