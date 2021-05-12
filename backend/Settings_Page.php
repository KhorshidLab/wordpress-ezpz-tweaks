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

namespace EZPZ_TWEAKS\Backend;

use EZPZ_TWEAKS\Engine\Base;


/**
 * Create the settings page in the backend
 */
class Settings_Page extends Base {

	/**
	 * @var false|mixed|void
	 */
	public $get_locale;
	public $settings_option;
	public $branding_option;

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function initialize() {
		if ( !parent::initialize() ) {
			return;
		}

		$this->get_locale      = get_locale();
		$this->settings_option = get_option( 'ezpz-tweaks-settings' );
		$this->branding_option = get_option( 'ezpz-tweaks-settings-branding' );

		add_action( 'admin_enqueue_scripts', array( $this, 'apply_admin_custom_css' ), 30 );
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
		add_action( 'admin_footer_text', array( $this, 'custom_footer' ), PHP_INT_MAX );
		add_action( 'wp_before_admin_bar_render', array( $this, 'adminbar_logo' ) );
		add_action( 'admin_head', array( $this, 'hide_core_update_notifications_from_users' ), 1 );
		add_action( 'admin_init', array( $this, 'remove_welcome_panel' ) );
		add_action( 'wp_dashboard_setup', array( $this, 'remove_dashboard_widgets' ) );
		add_action( "cmb2_save_options-page_fields_" . EZPZ_TWEAKS_TEXTDOMAIN . "_options", array( $this, 'show_notices_on_custom_url_change' ), 30, 3 );
		add_action( 'admin_head', array( $this, 'change_admin_font' ), 30 );
		add_action( 'admin_head', array( $this, 'change_editor_font' ), 30 );

		add_filter( 'plugin_action_links_' . EZPZ_TWEAKS_PLUGIN_BASENAME, array( $this, 'add_action_links' ) );
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function add_plugin_admin_menu() {
		/*
		 * Add a settings page for this plugin to the main menu
		 *
		 */

		$plugin_access = isset( $this->settings_option['plugin_access'] ) ? $this->settings_option['plugin_access'] : 'super_admin';
		$capability	   = $plugin_access == 'super_admin' ? 'delete_users' : 'manage_options';

		if( $plugin_access == 'super_admin' || $plugin_access == 'manage_options' || $plugin_access == get_current_user_id() ) {
			add_submenu_page( 'options-general.php', __( 'EzPz Tweaks', EZPZ_TWEAKS_TEXTDOMAIN ), __( 'EzPz Tweaks', EZPZ_TWEAKS_TEXTDOMAIN ), $capability, EZPZ_TWEAKS_TEXTDOMAIN . '-settings', [
				$this,
				'display_plugin_settings_page'
			] );
		}

		if( isset( $this->branding_option['enable_branding'] ) ) {
			add_menu_page( $this->branding_option['menu_title'], $this->branding_option['menu_title'], 'manage_options', $this->branding_option['menu_slug'], array(
				$this,
				'display_branding_page'
			), $this->branding_option['branding_menu_logo'], 79 );

			add_action( 'admin_enqueue_scripts', function() {
				wp_add_inline_style( EZPZ_TWEAKS_TEXTDOMAIN . '-admin-styles', '#toplevel_page_' . $this->branding_option['menu_slug'] . ' img { width: 16px !important; }' );
			}, 30 );
		}
	}

	/**
	 * Render the branding page.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function display_branding_page() {
		if( isset( $this->branding_option['page_content'] ) ) {
			echo $this->branding_option['page_content'];
		} else {
			?>
			<div class="notice notice-info is-dismissible">
				<p>
					<?php _e( 'Edit this page from: Settings > EzPz Tweaks > Branding tab > Page Content.', EZPZ_TWEAKS_TEXTDOMAIN ) ?>
					<br>
					<a target="_blank" href="<?php echo admin_url( '/options-general.php?page=ezpz-tweaks-settings&tab=branding' ) ?>">
						<?php _e( 'Edit Page', EZPZ_TWEAKS_TEXTDOMAIN ) ?>
					</a>
				</p>
			</div>
			<?php
		}
	}


	/**
	 * Render the settings page for this plugin.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function display_plugin_settings_page() {
		include EZPZ_TWEAKS_PLUGIN_ROOT . "backend/views/settings.php";
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @param array $links Array of links.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public function add_action_links( array $links ) {
		return array_merge( array(
			'settings' => '<a href="' . admin_url( 'admin.php?page=' . EZPZ_TWEAKS_TEXTDOMAIN . '-settings' ) . '">' . __( 'Settings', EZPZ_TWEAKS_TEXTDOMAIN ) . '</a>',
		), $links );
	}

	public function custom_footer( $text ) {
		if ( isset( $this->branding_option['footer_visibility'] ) ) {
			return;
		} else {
			if ( isset( $this->branding_option['footer_text'] ) ) {
				return $this->branding_option['footer_text'];
			} else {
				return $text;
			}
		}
	}

	public function change_admin_font() {
		$font_styles = '';
		$field_name  = $this->get_locale == 'fa_IR' ? 'admin-font-fa': 'admin-font';
		$admin_font  = isset( $_POST[ $field_name ] ) ? $_POST[ $field_name ] : $this->settings_option[ $field_name ];

		if ( isset( $admin_font ) && $admin_font != 'wp-default' ) {
			if ( $this->get_locale == 'fa_IR' ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'remove_google_fonts' ) );
				add_action( 'admin_enqueue_scripts', array( $this, 'remove_google_fonts' ) );
			} else {
				$font_styles .= '<style>@import url("https://fonts.googleapis.com/css?family=' . $admin_font . '");</style>';
				$admin_font   = ezpz_tweaks_get_google_font_name( $admin_font );
			}

			$font_styles .= '<style>body, h1, h2, h3, h4, h5, h6, label, input, textarea, .components-notice, #wpadminbar *:not([class="ab-icon"]), .wp-core-ui, .media-menu, .media-frame *, .media-modal * {font-family:"' . $admin_font . '" !important;}</style>';

			echo $font_styles;
		}
	}

	public function change_editor_font() {
		$font_styles = '';
		$field_name  = $this->get_locale == 'fa_IR' ? 'editor-font-fa': 'editor-font';
		$editor_font = $this->settings_option[ $field_name ];

		if ( isset( $editor_font ) && $editor_font != 'wp-default' ) {
			if ( $this->get_locale == 'fa_IR' ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'remove_google_fonts' ) );
				add_action( 'admin_enqueue_scripts', array( $this, 'remove_google_fonts' ) );
			} else {
				$font_styles .= '<style>@import url("https://fonts.googleapis.com/css?family=' . $editor_font . '");</style>';
				$editor_font   = ezpz_tweaks_get_google_font_name( $editor_font );
			}

			$font_styles .= '<style>body#tinymce, #editorcontainer #content, #wp_mce_fullscreen, .block-editor-writing-flow input, .block-editor-writing-flow textarea, .block-editor-writing-flow p {font-family:"' . $editor_font . '" !important;}</style>';

			echo $font_styles;
		}
	}

	public function remove_google_fonts() {
		// Unload Open Sans
		wp_deregister_style( 'open-sans' );
		wp_register_style( 'open-sans', false );

	}

	public function adminbar_logo() {
		if ( isset( $this->settings_option['custom_logo'] ) ) {
			echo '<style type="text/css">
			#wpadminbar #wp-admin-bar-wp-logo>.ab-item {
			    padding: 0 7px;
			    background-image: url(' . $this->settings_option['custom_logo'] . ') !important;
			    background-size: 50%;
			    background-position: center;
			    background-repeat: no-repeat;
			    opacity: 1;
			}
			#wpadminbar #wp-admin-bar-wp-logo>.ab-item .ab-icon:before {
			    content: " ";
			    top: 2px;
			}
        </style>';
		}
	}

	public function hide_core_update_notifications_from_users() {
		if ( isset( $this->settings_option['hide_update_notifications'] ) ) {
			$user_roles = ezpz_tweaks_wp_roles_array();

			foreach ( $user_roles as $role => $name ) {
				if ( current_user_can( $role ) ) {
					remove_action( 'admin_notices', 'update_nag', 3 );
					break;
				}
			}
		}
	}

	public function remove_welcome_panel() {
		if ( isset( $this->settings_option['remove_welcome_panel'] ) ) {
			remove_action( 'welcome_panel', 'wp_welcome_panel' );
		}
	}

	public function remove_dashboard_widgets() {
		if ( isset( $this->settings_option['remove_dashboard_widgets'] ) ) {
			global $wp_meta_boxes;

			$selected_widgets = $this->settings_option['remove_dashboard_widgets'];

			if ( in_array( 'dashboard_primary', $selected_widgets ) ) {
				unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'] );
			}

			if ( in_array( 'dashboard_quick_press', $selected_widgets ) ) {
				unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press'] );
			}

			if ( in_array( 'dashboard_incoming_links', $selected_widgets ) ) {
				unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links'] );
			}

			if ( in_array( 'dashboard_right_now', $selected_widgets ) ) {
				unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now'] );
			}

			if ( in_array( 'dashboard_plugins', $selected_widgets ) ) {
				unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins'] );
			}

			if ( in_array( 'dashboard_recent_drafts', $selected_widgets ) ) {
				unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts'] );
			}

			if ( in_array( 'dashboard_recent_comments', $selected_widgets ) ) {
				unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments'] );
			}

			if ( in_array( 'dashboard_site_health', $selected_widgets ) ) {
				unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_site_health'] );
			}

			if ( in_array( 'dashboard_activity', $selected_widgets ) ) {
				unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity'] );
			}
		}
	}

	public function custom_fonts() {
		$fonts = array(
			'wp-default'  => 'پیشفرض وردپرس',
			'Vazir'       => 'وزیر',
			'Estedad'     => 'استعداد',
			'Shabnam'     => 'شبنم',
			'Samim'       => 'صمیم',
		);

		return $fonts;
	}

	public function apply_admin_custom_css() {
		if ( isset( $this->settings_option['custom_css'] ) ) {
			wp_add_inline_style( EZPZ_TWEAKS_TEXTDOMAIN . '-admin-styles', $this->settings_option['custom_css'] );
		}
	}

	public function show_notices_on_custom_url_change( $object_id, $updated, $cmb ) {
		if( in_array( 'custom_login_url', $updated ) ) {
			$hide_login = new \EZPZ_TWEAKS\Integrations\WPS_Hide_Login();

			echo '<div class="updated notice is-dismissible"><p>' . sprintf( __( 'Your login page is now here: <strong><a href="%1$s">%2$s</a></strong>. Bookmark this page!', EZPZ_TWEAKS_TEXTDOMAIN ), $hide_login->new_login_url(), $hide_login->new_login_url() ) . '</p></div>';
		}
	}

}
