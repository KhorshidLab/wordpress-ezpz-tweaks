<?php
/**
 * EZPZ_TWEAKS
 *
 * @package   EZPZ_TWEAKS
 * @author    Khorshid <info@khorshidlab.com>
 * @copyright 2020 Khorshid
 * @license   GPL 2.0+
 * @link      https://khorshidlab.com
 */

namespace EZPZ_TWEAKS\Frontend;

use EZPZ_TWEAKS\Engine\Base;
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
		wp_enqueue_style( EZPZ_TWEAKS_TEXTDOMAIN . '-plugin-styles', plugins_url( 'assets/css/public.css', EZPZ_TWEAKS_PLUGIN_ABSOLUTE ), array(), EZPZ_TWEAKS_VERSION );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public static function enqueue_scripts() {
		wp_enqueue_script( EZPZ_TWEAKS_TEXTDOMAIN . '-plugin-script', plugins_url( 'assets/js/public.js', EZPZ_TWEAKS_PLUGIN_ABSOLUTE ), array( 'jquery' ), EZPZ_TWEAKS_VERSION, false );
	}

	/**
	 * Print the PHP var in the HTML of the frontend for access by JavaScript.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public static function enqueue_js_vars() {
		wp_localize_script(
			EZPZ_TWEAKS_TEXTDOMAIN . '-plugin-script',
			'w_js_vars',
			array(
				'alert' => __( 'Hey! You have clicked the button!', EZPZ_TWEAKS_TEXTDOMAIN ),
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
