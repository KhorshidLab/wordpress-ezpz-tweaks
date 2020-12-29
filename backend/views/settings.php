<div id="tabs-1" class="wrap">
	<?php
	$cmb = new_cmb2_box(
		array(
			'id'         => W_TEXTDOMAIN . '_options',
			'hookup'     => false,
			'show_on'    => array( 'key' => 'options-page', 'value' => array( W_TEXTDOMAIN . '-settings' ) ),
			'show_names' => true,
		)
	);

	$cmb->add_field(
		array(
			'name'             => __( 'Admin Font', W_TEXTDOMAIN ),
			'desc'             => __( 'Change WordPress admin font', W_TEXTDOMAIN ),
			'id'               => 'admin-font',
			'type'             => 'select',
			'show_option_none' => false,
			'options'          => array(
				'wp-default'   => __( 'WordPress Default', W_TEXTDOMAIN ),
				'vazir'        => __( 'Vazir', W_TEXTDOMAIN ),
				'estedad'      => __( 'Estedad', W_TEXTDOMAIN ),
				'iranian'      => __( 'Iranian', W_TEXTDOMAIN ),
				'noto'         => __( 'Noto', W_TEXTDOMAIN ),
			),
		)
	);

	$cmb->add_field(
		array(
			'name'    => __( 'Change WordPress Logo', W_TEXTDOMAIN ),
			'desc'    => __( 'Upload an image or enter an URL.', W_TEXTDOMAIN ),
			'id'      => 'custom_logo',
			'type'    => 'file',
			'options' => array(
					'url' => true, // Hide the text input for the url
			),
			'text'    => array(
					'add_upload_file_text' => __( 'Add File', W_TEXTDOMAIN )
			),
			'query_args' => array(
				 'type' => array(
				     'image/jpeg',
				     'image/png',
				 ),
			),
			'preview_size' => array( 150, 150 ),
		)
	);

	$cmb->add_field(
		array(
			'name' => __( 'Disable WP REST API', W_TEXTDOMAIN ),
			'desc' => __( ' API consumers be authenticated, which effectively prevents anonymous external access.', W_TEXTDOMAIN ),
			'id'   => 'disable_rest_api',
			'type' => 'checkbox',
		)
	);

	$cmb->add_field(
		array(
			'name' => __( 'Disable Comment Website Field', W_TEXTDOMAIN ),
			'id'   => 'disable_website_field',
			'type' => 'checkbox',
		)
	);

	$cmb->add_field(
			array(
					'name' => __( 'Remove Welcome Panel', W_TEXTDOMAIN ),
					'id'   => 'remove_welcome_panel',
					'type' => 'checkbox',
			)
	);

	$cmb->add_field(
		array(
			'name' => __( 'Disable Emojis', W_TEXTDOMAIN ),
			'id'   => 'disable_wp_emoji',
			'type' => 'checkbox',
		)
	);

	$cmb->add_field(
		array(
			'name' => __( 'Disable Embeds', W_TEXTDOMAIN ),
			'id'   => 'disable_wp_embed',
			'type' => 'checkbox',
		)
	);

	$cmb->add_field(
		array(
			'name' => __( 'Disable XML-RPC', W_TEXTDOMAIN ),
			'id'   => 'disable_xmlrpc',
			'type' => 'checkbox',
		)
	);

	$cmb->add_field(
		array(
			'name' => __( 'Remove Shortlink', W_TEXTDOMAIN ),
			'id'   => 'remove_shortlink',
			'type' => 'checkbox',
		)
	);

	$cmb->add_field(
		array(
			'name' => __( 'Remove WP Version', W_TEXTDOMAIN ),
			'id'   => 'remove_wp_version',
			'type' => 'checkbox',
		)
	);

	$user_roles = wp_roles_array();

	$cmb->add_field(
		array(
			'name'    => __( 'Hide Admin Bar', W_TEXTDOMAIN ),
			'desc'    => __( 'Hide admin bar for user roles', W_TEXTDOMAIN ),
			'id'      => 'hide_admin_bar',
			'type'    => 'multicheck',
			'options' => $user_roles,
		)
	);

	$cmb->add_field(
		array(
			'name'    => __( 'Hide Update Notifications', W_TEXTDOMAIN ),
			'desc'    => __( 'Hide update notifications for user roles', W_TEXTDOMAIN ),
			'id'      => 'hide_update_notifications',
			'type'    => 'multicheck',
			'options' => $user_roles,
		)
	);

	$cmb->add_field(
		array(
			'name'    => __( 'Remove Dashboard Widgets', W_TEXTDOMAIN ),
			'desc'    => __( 'Check widgets to remove from dashboard', W_TEXTDOMAIN ),
			'id'      => 'remove_dashboard_widgets',
			'type'    => 'multicheck',
			'options' => array(
				'dashboard_activity'        => __( 'Dashboard Activity', W_TEXTDOMAIN ),
				'dashboard_site_health'     => __( 'Dashboard Site Health', W_TEXTDOMAIN ),
				'dashboard_quick_press'     => __( 'Dashboard Quick Press', W_TEXTDOMAIN ),
				'dashboard_incoming_links'  => __( 'Dashboard Incoming Links', W_TEXTDOMAIN ),
				'dashboard_right_now'       => __( 'Dashboard Right Now', W_TEXTDOMAIN ),
				'dashboard_plugins'         => __( 'Dashboard Plugins', W_TEXTDOMAIN ),
				'dashboard_recent_drafts'   => __( 'Dashboard Recent Drafts', W_TEXTDOMAIN ),
				'dashboard_recent_comments' => __( 'Dashboard Recent Comments', W_TEXTDOMAIN ),
				'dashboard_primary'         => __( 'Dashboard Primary', W_TEXTDOMAIN ),
			),
		)
	);

	cmb2_metabox_form( W_TEXTDOMAIN . '_options', W_TEXTDOMAIN . '-settings' );
	?>
</div>
