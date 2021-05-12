<?php $fragment = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general'; ?>

<div class="wrap ezpz-tweaks-tabs">
	<h2><img src="<?php echo EZPZ_TWEAKS_PLUGIN_ROOT_URL . 'assets/img/EzPzTweaks-logo.svg' ?>" style="width: 50px;vertical-align: middle;padding: 15px;"><?php echo EZPZ_TWEAKS_NAME ?></h2>

	<!-- Start tabs -->
	<ul class="wp-tab-bar">
		<li class="<?php echo $fragment == 'general' ? 'wp-tab-active' : '' ?>"><a href="#general"><?php _e( 'General', EZPZ_TWEAKS_TEXTDOMAIN ) ?></a></li>
		<li class="<?php echo $fragment == 'branding' ? 'wp-tab-active' : '' ?>"><a href="#branding"><?php _e( 'Branding', EZPZ_TWEAKS_TEXTDOMAIN ) ?></a></li>
		<li class="<?php echo $fragment == 'import-export' ? 'wp-tab-active' : '' ?>"><a href="#import-export"><?php _e( 'Import & Export', EZPZ_TWEAKS_TEXTDOMAIN ) ?></a></li>
	</ul>
	<div id="general" class="wp-tab-panel" style="<?php echo $fragment != 'general' ? 'display: none' : '' ?>">
		<?php
		$locale        = get_locale();
		$settings_page = new EZPZ_TWEAKS\Backend\Settings_Page();
		$cmb           = new_cmb2_box(
			array(
				'id'           => EZPZ_TWEAKS_TEXTDOMAIN . '_options',
				'object_types' => array( 'options-page' ),
				'hookup'       => true,
				'show_names'   => true,
				'show_on'      => array(
					'key'      => 'options-page',
					'value'    => array( EZPZ_TWEAKS_TEXTDOMAIN . '-settings' )
				),
			)
		);

		if ( $locale == 'fa_IR' ) {
			$cmb->add_field(
				array(
					'name'             => __( 'Admin Font', EZPZ_TWEAKS_TEXTDOMAIN ),
					'desc'             => __( 'Change WordPress admin font', EZPZ_TWEAKS_TEXTDOMAIN ),
					'id'               => 'admin-font-fa',
					'type'             => 'select',
					'show_option_none' => false,
					'options'          => $settings_page->custom_fonts(),
				)
			);

			$cmb->add_field(
				array(
					'name'             => __( 'Editor Font', EZPZ_TWEAKS_TEXTDOMAIN ),
					'desc'             => __( 'Change WordPress editor font', EZPZ_TWEAKS_TEXTDOMAIN ),
					'id'               => 'editor-font-fa',
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
					'type'       => 'text',
					'attributes' => array( 'data-placeholder' => __( 'Choose a font', EZPZ_TWEAKS_TEXTDOMAIN ), 'data-placeholder_search' => __( 'Type to search...', EZPZ_TWEAKS_TEXTDOMAIN ) )
				)
			);

			$cmb->add_field(
				array(
					'name'       => __( 'Editor Font', EZPZ_TWEAKS_TEXTDOMAIN ),
					'desc'       => __( 'Change WordPress editor font', EZPZ_TWEAKS_TEXTDOMAIN ),
					'id'         => 'editor-font',
					'type'       => 'text',
					'attributes' => array( 'data-placeholder' => __( 'Choose a font', EZPZ_TWEAKS_TEXTDOMAIN ), 'data-placeholder_search' => __( 'Type to search...', EZPZ_TWEAKS_TEXTDOMAIN ) )
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
					'name' 		=> __( 'Who can access this plugin', EZPZ_TWEAKS_TEXTDOMAIN ),
					'id'   		=> 'plugin_access',
					'type' 		=> 'radio',
					'options'   => array(
						'super_admin'   	  => __( 'Super Admin', EZPZ_TWEAKS_TEXTDOMAIN ),
						'manage_options'	  => __( 'Anyone with the "mange_options" capability', EZPZ_TWEAKS_TEXTDOMAIN ),
						get_current_user_id() => __( 'Only the current user', EZPZ_TWEAKS_TEXTDOMAIN ),
					),
					'default' 	=> 'super_admin',
				)
			);
		}

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
				'desc' => __( 'Remove website field from comment form', EZPZ_TWEAKS_TEXTDOMAIN ),
				'id'   => 'disable_website_field',
				'type' => 'checkbox',
			)
		);

		$cmb->add_field(
			array(
				'name' => __( 'Remove Welcome Panel', EZPZ_TWEAKS_TEXTDOMAIN ),
				'desc' => __( 'Welcome panel is a meta box added to the dashboard screen of admin area. It shows shortcuts to different sections of your WordPress site', EZPZ_TWEAKS_TEXTDOMAIN ),
				'id'   => 'remove_welcome_panel',
				'type' => 'checkbox',
			)
		);

		$cmb->add_field(
			array(
				'name' => __( 'Disable Emojis', EZPZ_TWEAKS_TEXTDOMAIN ),
				'desc' => __( 'Remove wp-emoji-release.min.js file', EZPZ_TWEAKS_TEXTDOMAIN ),
				'id'   => 'disable_wp_emoji',
				'type' => 'checkbox',
			)
		);

		$cmb->add_field(
			array(
				'name' => __( 'Disable Embeds', EZPZ_TWEAKS_TEXTDOMAIN ),
				'desc' => __( 'Remove wp-embed.min.js file and reduce HTTP requests', EZPZ_TWEAKS_TEXTDOMAIN ),
				'id'   => 'disable_wp_embed',
				'type' => 'checkbox',
			)
		);

		$cmb->add_field(
			array(
				'name' => __( 'Disable XML-RPC', EZPZ_TWEAKS_TEXTDOMAIN ),
				'desc' => __( 'Disabling this feature makes your site more secure', EZPZ_TWEAKS_TEXTDOMAIN ),
				'id'   => 'disable_xmlrpc',
				'type' => 'checkbox',
			)
		);

		$cmb->add_field(
			array(
				'name' => __( 'Remove Shortlink', EZPZ_TWEAKS_TEXTDOMAIN ),
				'desc' => __( 'This is used for a shortlink to your pages and posts. However, if you are already using pretty permalinks, then there is no reason to keep this', EZPZ_TWEAKS_TEXTDOMAIN ),
				'id'   => 'remove_shortlink',
				'type' => 'checkbox',
			)
		);

		$cmb->add_field(
			array(
				'name' => __( 'Remove WP Version', EZPZ_TWEAKS_TEXTDOMAIN ),
				'desc' => __( 'Remove the WordPress version number from all different areas on your site', EZPZ_TWEAKS_TEXTDOMAIN ),
				'id'   => 'remove_wp_version',
				'type' => 'checkbox',
			)
		);

		$user_roles = ezpz_tweaks_wp_roles_array();

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
	<div id="branding" class="wp-tab-panel" style="<?php echo $fragment != 'branding' ? 'display: none' : '' ?>">
		<?php
		$cmb = new_cmb2_box(
			array(
				'id'         => EZPZ_TWEAKS_TEXTDOMAIN . '_options_branding',
				'hookup'     => false,
				'show_on'    => array(
					'key'    => 'options-page',
					'value'  => array( EZPZ_TWEAKS_TEXTDOMAIN . '-settings-branding' )
				),
				'show_names' => true,
			)
		);

		$cmb->add_field(
			array(
				'before_row' => '<h2 class="title">'. __( 'Brand Custom Menu', EZPZ_TWEAKS_TEXTDOMAIN ) .'</h2>',
				'name' => __( 'Enable Branding', EZPZ_TWEAKS_TEXTDOMAIN ),
				'desc' => __( 'By selecting this option, you can add your brand to the WordPress menu and customize how it displays.', EZPZ_TWEAKS_TEXTDOMAIN ),
				'id'   => 'enable_branding',
				'type' => 'checkbox',
			)
		);

		$cmb->add_field(
			array(
				'name'         => __( 'Menu Title', EZPZ_TWEAKS_TEXTDOMAIN ),
				'id'           => 'menu_title',
				'type'         => 'text',
				'attributes'    => array(
					'data-conditional-id'     => 'enable_branding',
					'data-conditional-value'  => 'on',
					'required' => true,
				),
			)
		);

		$cmb->add_field(
			array(
				'name'         => __( 'Menu Slug', EZPZ_TWEAKS_TEXTDOMAIN ),
				'id'           => 'menu_slug',
				'type'         => 'text',
				'attributes'    => array(
					'data-conditional-id'     => 'enable_branding',
					'data-conditional-value'  => 'on',
					'required' => true,
				),
			)
		);

		$cmb->add_field(
			array(
				'name'         => __( 'Menu Logo', EZPZ_TWEAKS_TEXTDOMAIN ),
				'desc'         => __( 'Upload an image or enter an URL.', EZPZ_TWEAKS_TEXTDOMAIN ),
				'id'           => 'branding_menu_logo',
				'type'         => 'file',
				'attributes'    => array(
					'data-conditional-id'     => 'enable_branding',
					'data-conditional-value'  => 'on',
					'required' => true,
				),
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
				'name'    => __( 'Page Content', EZPZ_TWEAKS_TEXTDOMAIN ),
				'desc'    => __( 'You can use HTML to design branding page', EZPZ_TWEAKS_TEXTDOMAIN ),
				'id'      => 'page_content',
				'type'    => 'wysiwyg',
				'before' => '<div data-conditional-id="enable_branding" data-conditional-value="on">',
    			'after' => '</div>',
				'options' => array(
					'wpautop' => true, // use wpautop?
					'textarea_rows' => get_option('default_post_edit_rows', 10)
				),
			)
		);

		$cmb->add_field(
			array(
				'before_row' => '<h2 class="title">'. __( 'Admin Footer', EZPZ_TWEAKS_TEXTDOMAIN ) .'</h2>',
				'name'    => __( 'Footer Text', EZPZ_TWEAKS_TEXTDOMAIN ),
				'desc'    => __( 'Change footer text', EZPZ_TWEAKS_TEXTDOMAIN ),
				'id'      => 'footer_text',
				'type'    => 'wysiwyg',
				'options' => array(
					'wpautop' => true, // use wpautop?
					'textarea_rows' => get_option('default_post_edit_rows', 10)
				),
			)
		);

		$cmb->add_field(
			array(
				'name' => __( 'Visibility', EZPZ_TWEAKS_TEXTDOMAIN ),
				'desc' => __( 'Hide the entire admin footer', EZPZ_TWEAKS_TEXTDOMAIN ),
				'id'   => 'footer_visibility',
				'type' => 'checkbox',
			)
		);

		cmb2_metabox_form( EZPZ_TWEAKS_TEXTDOMAIN . '_options_branding', EZPZ_TWEAKS_TEXTDOMAIN . '-settings-branding' );
		?>
	</div>
	<div id="import-export" class="wp-tab-panel" style="<?php echo $fragment != 'import-export' ? 'display: none' : '' ?>">
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
