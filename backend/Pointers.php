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

namespace WP2X\Backend;

use WP2X\Engine\Base;

/**
 * All the WP pointers.
 */
class Pointers extends Base {

	/**
	 * Initialize the Pointers.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function initialize() {
		parent::initialize();

		new \PointerPlus( array( 'prefix' => W_TEXTDOMAIN ) );
		\add_filter( 'wp2x-pointerplus_list', array( $this, 'custom_initial_pointers' ), 10, 2 );
	}

	/**
	 * Add pointers.
	 * Check on https://github.com/Mte90/pointerplus/blob/master/pointerplus.php for examples
	 *
	 * @param array  $pointers The list of pointers.
	 * @param string $prefix   For your pointers.
	 * @since 1.0.0
	 * @return array
	 */
	public function custom_initial_pointers( array $pointers, string $prefix ) {
		return \array_merge(
			$pointers,
			array(
				$prefix . '_contextual_help' => array(
					'selector'   => '#show-settings-link',
					'title'      => \__( 'Boilerplate Help', W_TEXTDOMAIN ),
					'text'       => \__( 'A pointer for help tab.<br>Go to Posts, Pages or Users for other pointers.', W_TEXTDOMAIN ),
					'edge'       => 'top',
					'align'      => 'left',
					'icon_class' => 'dashicons-welcome-learn-more',
				),
			)
		);
	}

}
