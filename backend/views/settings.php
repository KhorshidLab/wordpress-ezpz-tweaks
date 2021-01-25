<div class="wrap ezpz-tweaks-tabs">
	<!-- Start tabs -->
	<ul class="wp-tab-bar">
		<li class="wp-tab-active"><a href="#general"><?php _e( 'General', EZPZ_TWEAKS_TEXTDOMAIN ) ?></a></li>
		<li><a href="#import-export"><?php _e( 'Import & Export', EZPZ_TWEAKS_TEXTDOMAIN ) ?></a></li>
	</ul>
	<div id="general" class="wp-tab-panel">
		<?php
		$locale        = get_locale();
		$settings_page = new EZPZ_TWEAKS\Backend\Settings_Page();
		$cmb           = new_cmb2_box(
				array(
						'id'         => EZPZ_TWEAKS_TEXTDOMAIN . '_options',
						'hookup'     => false,
						'show_on'    => array(
								'key'   => 'options-page',
								'value' => array( EZPZ_TWEAKS_TEXTDOMAIN . '-settings' )
						),
						'show_names' => true,
				)
		);

		if ( $locale == 'fa_IR' ) {
			$cmb->add_field(
					array(
							'name'             => __( 'Admin Font', EZPZ_TWEAKS_TEXTDOMAIN ),
							'desc'             => __( 'Change WordPress admin font', EZPZ_TWEAKS_TEXTDOMAIN ),
							'id'               => 'admin-font',
							'type'             => 'select',
							'show_option_none' => false,
							'options'          => $settings_page->custom_fonts(),
					)
			);

			$cmb->add_field(
					array(
							'name'             => __( 'Editor Font', EZPZ_TWEAKS_TEXTDOMAIN ),
							'desc'             => __( 'Change WordPress editor font', EZPZ_TWEAKS_TEXTDOMAIN ),
							'id'               => 'editor-font',
							'type'             => 'select',
							'show_option_none' => false,
							'options'          => $settings_page->custom_fonts(),
					)
			);
		} else {
			$cmb->add_field(
					array(
							'name'       => __( 'Admin Font', EZPZ_TWEAKS_TEXTDOMAIN ),
							'desc'       => __( 'Change WordPress admin font', EZPZ_TWEAKS_TEXTDOMAIN ),
							'id'         => 'admin-font',
							'type'       => 'font',
							'attributes' => array( 'data-placeholder' => __( 'Choose a font', 'cmb2' ) )
					)
			);

			$cmb->add_field(
					array(
							'name'       => __( 'Editor Font', EZPZ_TWEAKS_TEXTDOMAIN ),
							'desc'       => __( 'Change WordPress editor font', EZPZ_TWEAKS_TEXTDOMAIN ),
							'id'         => 'editor-font',
							'type'       => 'font',
							'attributes' => array( 'data-placeholder' => __( 'Choose a font', 'cmb2' ) )
					)
			);
		}

		$cmb->add_field(
				array(
						'name'         => __( 'Change WordPress Logo', EZPZ_TWEAKS_TEXTDOMAIN ),
						'desc'         => __( 'Upload an image or enter an URL.', EZPZ_TWEAKS_TEXTDOMAIN ),
						'id'           => 'custom_logo',
						'type'         => 'file',
						'options'      => array(
								'url' => true, // Hide the text input for the url
						),
						'text'         => array(
								'add_upload_file_text' => __( 'Add File', EZPZ_TWEAKS_TEXTDOMAIN )
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
						'name'         => __( 'Change WP Login URL', EZPZ_TWEAKS_TEXTDOMAIN ),
						'desc'         => __( 'Protect your website by changing the login URL and preventing access to the wp-login.php page and the wp-admin directory to non-connected people.', EZPZ_TWEAKS_TEXTDOMAIN ),
						'id'           => 'custom_login_url',
						'type'         => 'text',
						'before_field' => '<code>' . trailingslashit( home_url() ) . '</code>',
				)
		);

		$cmb->add_field(
				array(
						'name' => __( 'Login Page Custom Text', EZPZ_TWEAKS_TEXTDOMAIN ),
						'desc' => __( 'Add custom text to wordpress admin login page', EZPZ_TWEAKS_TEXTDOMAIN ),
						'id'   => 'login_custom_text',
						'type' => 'textarea',
				)
		);

		if ( current_user_can( 'administrator' ) ) {
			$cmb->add_field(
					array(
							'name' => __( 'Disable Public Access to WP REST API', EZPZ_TWEAKS_TEXTDOMAIN ),
							'desc' => __( ' API consumers be authenticated, which effectively prevents anonymous external access.', EZPZ_TWEAKS_TEXTDOMAIN ),
							'id'   => 'disable_rest_api',
							'type' => 'checkbox',
					)
			);
		}

		$cmb->add_field(
				array(
						'name' => __( 'Disable Comment Website Field', EZPZ_TWEAKS_TEXTDOMAIN ),
						'id'   => 'disable_website_field',
						'type' => 'checkbox',
				)
		);

		$cmb->add_field(
				array(
						'name' => __( 'Remove Welcome Panel', EZPZ_TWEAKS_TEXTDOMAIN ),
						'id'   => 'remove_welcome_panel',
						'type' => 'checkbox',
				)
		);

		$cmb->add_field(
				array(
						'name' => __( 'Disable Emojis', EZPZ_TWEAKS_TEXTDOMAIN ),
						'id'   => 'disable_wp_emoji',
						'type' => 'checkbox',
				)
		);

		$cmb->add_field(
				array(
						'name' => __( 'Disable Embeds', EZPZ_TWEAKS_TEXTDOMAIN ),
						'id'   => 'disable_wp_embed',
						'type' => 'checkbox',
				)
		);

		$cmb->add_field(
				array(
						'name' => __( 'Disable XML-RPC', EZPZ_TWEAKS_TEXTDOMAIN ),
						'id'   => 'disable_xmlrpc',
						'type' => 'checkbox',
				)
		);

		$cmb->add_field(
				array(
						'name' => __( 'Remove Shortlink', EZPZ_TWEAKS_TEXTDOMAIN ),
						'id'   => 'remove_shortlink',
						'type' => 'checkbox',
				)
		);

		$cmb->add_field(
				array(
						'name' => __( 'Remove WP Version', EZPZ_TWEAKS_TEXTDOMAIN ),
						'id'   => 'remove_wp_version',
						'type' => 'checkbox',
				)
		);

		$user_roles = wp_roles_array();

		$cmb->add_field(
				array(
						'name'    => __( 'Hide Admin Bar', EZPZ_TWEAKS_TEXTDOMAIN ),
						'desc'    => __( 'Hide admin bar for user roles', EZPZ_TWEAKS_TEXTDOMAIN ),
						'id'      => 'hide_admin_bar',
						'type'    => 'multicheck',
						'options' => $user_roles,
				)
		);

		$cmb->add_field(
				array(
						'name'    => __( 'Hide Update Notifications', EZPZ_TWEAKS_TEXTDOMAIN ),
						'desc'    => __( 'Hide update notifications for user roles', EZPZ_TWEAKS_TEXTDOMAIN ),
						'id'      => 'hide_update_notifications',
						'type'    => 'multicheck',
						'options' => $user_roles,
				)
		);

		$cmb->add_field(
				array(
						'name'    => __( 'Remove Dashboard Widgets', EZPZ_TWEAKS_TEXTDOMAIN ),
						'desc'    => __( 'Check widgets to remove from dashboard', EZPZ_TWEAKS_TEXTDOMAIN ),
						'id'      => 'remove_dashboard_widgets',
						'type'    => 'multicheck',
						'options' => array(
								'dashboard_activity'        => __( 'Dashboard Activity', EZPZ_TWEAKS_TEXTDOMAIN ),
								'dashboard_site_health'     => __( 'Dashboard Site Health', EZPZ_TWEAKS_TEXTDOMAIN ),
								'dashboard_quick_press'     => __( 'Dashboard Quick Press', EZPZ_TWEAKS_TEXTDOMAIN ),
								'dashboard_incoming_links'  => __( 'Dashboard Incoming Links', EZPZ_TWEAKS_TEXTDOMAIN ),
								'dashboard_right_now'       => __( 'Dashboard Right Now', EZPZ_TWEAKS_TEXTDOMAIN ),
								'dashboard_plugins'         => __( 'Dashboard Plugins', EZPZ_TWEAKS_TEXTDOMAIN ),
								'dashboard_recent_drafts'   => __( 'Dashboard Recent Drafts', EZPZ_TWEAKS_TEXTDOMAIN ),
								'dashboard_recent_comments' => __( 'Dashboard Recent Comments', EZPZ_TWEAKS_TEXTDOMAIN ),
								'dashboard_primary'         => __( 'Dashboard Primary', EZPZ_TWEAKS_TEXTDOMAIN ),
						),
				)
		);

		$cmb->add_field(
				array(
						'name' => __( 'Custom CSS', EZPZ_TWEAKS_TEXTDOMAIN ),
						'desc' => __( 'Apply custom css to admin area', EZPZ_TWEAKS_TEXTDOMAIN ),
						'id'   => 'custom_css',
						'type' => 'textarea',
				)
		);

		cmb2_metabox_form( EZPZ_TWEAKS_TEXTDOMAIN . '_options', EZPZ_TWEAKS_TEXTDOMAIN . '-settings' );
		?>
	</div>
	<div class="wp-tab-panel" id="import-export" style="display: none;">
		<div class="postbox">
			<h3 class="hndle"><span><?php _e( 'Export Settings', EZPZ_TWEAKS_TEXTDOMAIN ); ?></span></h3>
			<div class="inside">
				<p><?php _e( 'Export the plugin\'s settings for this site as a .json file. This will allows you to easily import the configuration to another installation.', EZPZ_TWEAKS_TEXTDOMAIN ); ?></p>
				<form method="post">
					<p><input type="hidden" name="w_action" value="export_settings"/></p>
					<p>
						<?php wp_nonce_field( 'w_export_nonce', 'w_export_nonce' ); ?>
						<?php submit_button( __( 'Export', EZPZ_TWEAKS_TEXTDOMAIN ), 'secondary', 'submit', false ); ?>
					</p>
				</form>
			</div>
		</div>

		<div class="postbox">
			<h3 class="hndle"><span><?php _e( 'Import Settings', EZPZ_TWEAKS_TEXTDOMAIN ); ?></span></h3>
			<div class="inside">
				<p><?php _e( 'Import the plugin\'s settings from a .json file. This file can be retrieved by exporting the settings from another installation.', EZPZ_TWEAKS_TEXTDOMAIN ); ?></p>
				<form method="post" enctype="multipart/form-data">
					<p>
						<input type="file" name="w_import_file"/>
					</p>
					<p>
						<input type="hidden" name="w_action" value="import_settings"/>
						<?php wp_nonce_field( 'w_import_nonce', 'w_import_nonce' ); ?>
						<?php submit_button( __( 'Import', EZPZ_TWEAKS_TEXTDOMAIN ), 'secondary', 'submit', false ); ?>
					</p>
				</form>
			</div>
		</div>
	</div>
	<!-- End tabs -->

</div>
