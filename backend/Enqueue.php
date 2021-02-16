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

namespace EZPZ_TWEAKS\Backend;

use EZPZ_TWEAKS\Engine\Base;
use function add_action;
use function plugins_url;
use function wp_enqueue_script;
use function wp_enqueue_style;

/**
 * This class contain the Enqueue stuff for the backend
 */
class Enqueue extends Base {

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function initialize() {
//		if ( !parent::initialize() ) {
//			return;
//		}

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
	}


	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_admin_styles() {
		wp_enqueue_style( EZPZ_TWEAKS_TEXTDOMAIN . '-admin-styles', plugins_url( 'assets/css/admin.css', EZPZ_TWEAKS_PLUGIN_ABSOLUTE ), array( 'dashicons' ), EZPZ_TWEAKS_VERSION );
	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_admin_scripts() {
		wp_enqueue_script( EZPZ_TWEAKS_TEXTDOMAIN . '-admin-script', plugins_url( 'assets/js/admin.js', EZPZ_TWEAKS_PLUGIN_ABSOLUTE ), array( 'jquery', 'jquery-ui-sortable', 'underscore' ), EZPZ_TWEAKS_VERSION, false );
		wp_enqueue_code_editor( array( 'type' => 'text/css' ) );
	}


}
