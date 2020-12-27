<?php

define('W_PLUGIN_ROOT', __DIR__);
define('W_TEXTDOMAIN', 'wp2x');
define('W_NAME', 'WP2X');
define('W_PLUGIN_ABSOLUTE', __DIR__);
define('W_VERSION', '1.0.0');

// Load CMB2
define('CMB2_DIR', dirname(__DIR__, 2) . '/vendor/cmb2/');
require_once CMB2_DIR . 'includes/helper-functions.php';
spl_autoload_register('cmb2_autoload_classes');

// Load Cmb2Grid
spl_autoload_register(function ($class) {
	$prefix = 'Cmb2Grid\\';
	$base_dir = dirname(__DIR__, 2) . '/vendor/cmb2-grid/';
	$sep = '/';
	$length = strlen($prefix);
	if (strncmp($prefix, $class, $length) !== 0) {
		return;
	}
	$relative_class = substr($class, $length);
	$file = $base_dir . str_replace('\\', $sep, $relative_class) . '.php';
	if (file_exists($file)) {
		require_once $file;
	}
});
