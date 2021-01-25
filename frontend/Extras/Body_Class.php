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

namespace EZPZ_TWEAKS\Frontend\Extras;

use EZPZ_TWEAKS\Engine\Base;
use function add_filter;

/**
 * Add custom css class to <body>
 */
class Body_Class extends Base {

	/**
	 * Add class in the body on the frontend
	 *
	 * @param array $classes The array with all the classes of the page.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public static function add_w_class( array $classes ) {
		$classes[] = EZPZ_TWEAKS_TEXTDOMAIN;

		return $classes;
	}

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function initialize() {
		parent::initialize();

		add_filter( 'body_class', array( self::class, 'add_w_class' ), 10, 3 );
	}

}
