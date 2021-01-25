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

namespace EZPZ_TWEAKS\Internals;

use EZPZ_TWEAKS\Engine\Base;
use function add_shortcode;
use function shortcode_atts;

/**
 * Shortcodes of this plugin
 */
class Shortcode extends Base {

	/**
	 * Shortcode example
	 *
	 * @param array $atts Parameters.
	 *
	 * @return string
	 * @since 1.0.0
	 */
	public static function foobar_func( array $atts ) {
		shortcode_atts(
			array(
				'foo' => 'something',
				'bar' => 'something else',
			),
			$atts
		);

		return '<span class="foo">foo = ' . $atts['foo'] . '</span>' .
		       '<span class="bar">foo = ' . $atts['bar'] . '</span>';
	}

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function initialize() {
		parent::initialize();

		add_shortcode( 'foobar', array( $this, 'foobar_func' ) );
	}

}
