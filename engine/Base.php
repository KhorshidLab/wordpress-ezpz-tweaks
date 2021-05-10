<?php

/**
 * EZPZ_TWEAKS
 *
 * @package   EZPZ_TWEAKS
 * @author    WordPress EzPz <info@wpezpz.dev>
 * @copyright 2020 WordPress EzPz
 * @license   GPL 2.0+
 * @link      https://wpezpz.dev
 */

namespace EZPZ_TWEAKS\Engine;

use function ezpz_tweaks_get_settings;

/**
 * Base skeleton of the plugin
 */
class Base {

	/**
	 * @var array The settings of the plugin.
	 */
	public $settings = array();

	/** Initialize the class and get the plugin settings */
	public function initialize() {
		$this->settings = ezpz_tweaks_get_settings();

		return true;
	}

}
