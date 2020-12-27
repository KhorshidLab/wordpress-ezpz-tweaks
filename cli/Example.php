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

namespace WP2X\Cli;

use WP2X\Engine\Base;
use WP_CLI;
use function apply_filters;
use function defined;

if (defined('WP_CLI') && WP_CLI) {

	/**
	 * WP CLI command example
	 */
	class Example extends Base
	{

		/**
		 * Initialize the commands
		 *
		 * @return void
		 * @since 1.0.0
		 */
		public function __construct()
		{
			WP_CLI::add_command('w_commandname', array($this, 'command_example'));
		}

		/**
		 * Initialize the class.
		 *
		 * @return void
		 */
		public function initialize()
		{
			if (!apply_filters('wp2x_w_enqueue_admin_initialize', true)) {
				return;
			}

			parent::initialize();
		}

		/**
		 * Example command
		 * API reference: https://wp-cli.org/docs/internal-api/
		 *
		 * @param array $args The attributes.
		 * @return void
		 * @since 1.0.0
		 */
		public function command_example(array $args)
		{
			// Message prefixed with "Success: ".
			WP_CLI::success($args[0]);
			// Message prefixed with "Warning: ".
			WP_CLI::warning($args[0]);
			// Message prefixed with "Debug: ". when '--debug' is used
			WP_CLI::debug($args[0]);
			// Message prefixed with "Error: ".
			WP_CLI::error($args[0]);
			// Message with no prefix
			WP_CLI::log($args[0]);
			// Colorize a string for output
			WP_CLI::colorize($args[0]);
			// Halt script execution with a specific return code
			WP_CLI::halt($args[0]);
		}

	}

}
