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

namespace EZPZ_TWEAKS\Integrations;

use EZPZ_TWEAKS\Engine\Base;

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

		require_once EZPZ_TWEAKS_PLUGIN_ROOT . 'vendor/cmb2/init.php';
//		require_once EZPZ_TWEAKS_PLUGIN_ROOT . 'vendor/cmb2-grid/Cmb2GridPluginLoad.php';
	}

}
