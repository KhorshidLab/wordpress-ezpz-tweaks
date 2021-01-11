<div class="wrap wp2x-tabs">
	<!-- Start tabs -->
	<ul class="wp-tab-bar">
		<li class="wp-tab-active"><a href="#general"><?php _e( 'General', W_TEXTDOMAIN ) ?></a></li>
		<li><a href="#import-export"><?php _e( 'Import & Export', W_TEXTDOMAIN ) ?></a></li>
	</ul>
	<div id="general" class="wp-tab-panel">
		<?php
		$settings_page = new WP2X\Backend\Settings_Page();
		$cmb           = new_cmb2_box(
				array(
						'id'         => W_TEXTDOMAIN . '_options',
						'hookup'     => false,
						'show_on'    => array(
								'key'   => 'options-page',
								'value' => array( W_TEXTDOMAIN . '-settings' )
						),
						'show_names' => true,
				)
		);

		if ( get_locale() == 'fa_IR' ) {
			$cmb->add_field(
					array(
							'name'             => __( 'Admin Font', W_TEXTDOMAIN ),
							'desc'             => __( 'Change WordPress admin font', W_TEXTDOMAIN ),
							'id'               => 'admin-font',
							'type'             => 'select',
							'show_option_none' => false,
							'options'          => $settings_page->custom_fonts(),
					)
			);

			$cmb->add_field(
					array(
							'name'             => __( 'Editor Font', W_TEXTDOMAIN ),
							'desc'             => __( 'Change WordPress editor font', W_TEXTDOMAIN ),
							'id'               => 'editor-font',
							'type'             => 'select',
							'show_option_none' => false,
							'options'          => $settings_page->custom_fonts(),
					)
			);
		} else {
			$cmb->add_field(
					array(
							'name'       => __( 'Admin Font', W_TEXTDOMAIN ),
							'desc'       => __( 'Change WordPress admin font', W_TEXTDOMAIN ),
							'id'         => 'admin-font',
							'type'       => 'font',
							'attributes' => array( 'data-placeholder' => __( 'Choose a font', 'cmb2' ) )
					)
			);

			$cmb->add_field(
					array(
							'name'       => __( 'Editor Font', W_TEXTDOMAIN ),
							'desc'       => __( 'Change WordPress editor font', W_TEXTDOMAIN ),
							'id'         => 'editor-font',
							'type'       => 'font',
							'attributes' => array( 'data-placeholder' => __( 'Choose a font', 'cmb2' ) )
					)
			);
		}

		$cmb->add_field(
				array(
						'name'         => __( 'Change WordPress Logo', W_TEXTDOMAIN ),
						'desc'         => __( 'Upload an image or enter an URL.', W_TEXTDOMAIN ),
						'id'           => 'custom_logo',
						'type'         => 'file',
						'options'      => array(
								'url' => true, // Hide the text input for the url
						),
						'text'         => array(
								'add_upload_file_text' => __( 'Add File', W_TEXTDOMAIN )
						),
						'query_args'   => array(
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
						'name' => __( 'Login Page Custom Text', W_TEXTDOMAIN ),
						'desc' => __( 'Add custom text to wordpress admin login page', W_TEXTDOMAIN ),
						'id'   => 'login_custom_text',
						'type' => 'textarea',
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

		$cmb->add_field(
				array(
						'name' => __( 'Custom CSS', W_TEXTDOMAIN ),
						'desc' => __( 'Apply custom css to admin area', W_TEXTDOMAIN ),
						'id'   => 'custom_css',
						'type' => 'textarea',
				)
		);

		cmb2_metabox_form( W_TEXTDOMAIN . '_options', W_TEXTDOMAIN . '-settings' );
		?>
	</div>
	<div class="wp-tab-panel" id="import-export" style="display: none;">
		<div class="postbox">
			<h3 class="hndle"><span><?php _e( 'Export Settings', W_TEXTDOMAIN ); ?></span></h3>
			<div class="inside">
				<p><?php _e( 'Export the plugin\'s settings for this site as a .json file. This will allows you to easily import the configuration to another installation.', W_TEXTDOMAIN ); ?></p>
				<form method="post">
					<p><input type="hidden" name="w_action" value="export_settings"/></p>
					<p>
						<?php wp_nonce_field( 'w_export_nonce', 'w_export_nonce' ); ?>
						<?php submit_button( __( 'Export', W_TEXTDOMAIN ), 'secondary', 'submit', false ); ?>
					</p>
				</form>
			</div>
		</div>

		<div class="postbox">
			<h3 class="hndle"><span><?php _e( 'Import Settings', W_TEXTDOMAIN ); ?></span></h3>
			<div class="inside">
				<p><?php _e( 'Import the plugin\'s settings from a .json file. This file can be retrieved by exporting the settings from another installation.', W_TEXTDOMAIN ); ?></p>
				<form method="post" enctype="multipart/form-data">
					<p>
						<input type="file" name="w_import_file"/>
					</p>
					<p>
						<input type="hidden" name="w_action" value="import_settings"/>
						<?php wp_nonce_field( 'w_import_nonce', 'w_import_nonce' ); ?>
						<?php submit_button( __( 'Import', W_TEXTDOMAIN ), 'secondary', 'submit', false ); ?>
					</p>
				</form>
			</div>
		</div>
	</div>
	<!-- End tabs -->

</div>
