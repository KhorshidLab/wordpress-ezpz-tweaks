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

function wp_roles_array()
{
	if ( ! function_exists( 'get_editable_roles' ) )
		require_once ABSPATH . 'wp-admin/includes/user.php';

	$editable_roles = get_editable_roles();

	foreach ( $editable_roles as $role => $details )
	{
		$roles[ esc_attr( $role ) ] = translate_user_role( $details['name'] );
	}

	return $roles;
}
