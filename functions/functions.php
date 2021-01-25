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

/**
 * Get the settings of the plugin in a filterable way
 *
 * @since 1.0.0
 * @return array
 */
function w_get_settings() {
	return apply_filters( 'w_get_settings', get_option( EZPZ_TWEAKS_TEXTDOMAIN . '-settings' ) );
}

function wp_roles_array() {
	if ( ! function_exists( 'get_editable_roles' ) ) {
		require_once ABSPATH . 'wp-admin/includes/user.php';
	}

	$editable_roles = get_editable_roles();

	foreach ( $editable_roles as $role => $details ) {
		$roles[ esc_attr( $role ) ] = translate_user_role( $details['name'] );
	}

	return $roles;
}
