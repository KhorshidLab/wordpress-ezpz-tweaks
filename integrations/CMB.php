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

namespace WP2X\Integrations;

use Cmb2Grid\Grid\Cmb2Grid;
use WP2X\Engine\Base;
use function add_action;
use function new_cmb2_box;

/**
 * All the CMB related code.
 */
class CMB extends Base {

	/**
	 * Initialize class.
	 *
	 * @since 1.0.0
	 */
	public function initialize() {
		parent::initialize();

		require_once W_PLUGIN_ROOT . 'vendor/cmb2/init.php';
		require_once W_PLUGIN_ROOT . 'vendor/cmb2-grid/Cmb2GridPluginLoad.php';
	}

}
