<?php
/**
 * Plugin_name
 *
 * @package   Plugin_name
 * @author    Khorshid <info@khorshidlab.com>
 * @copyright 2020 Khorshid
 * @license   GPL 2.0+
 * @link      https://khorshidlab.com
 */

$w_debug = new WPBP_Debug( __( 'Plugin Name', W_TEXTDOMAIN ) );

/**
 * Log text inside the debugging plugins.
 *
 * @param string $text The text.
 * @return void
 */
function w_log( string $text ) {
	global $w_debug;
	$w_debug->log( $text );
}
