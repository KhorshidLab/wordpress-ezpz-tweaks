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

namespace WP2X\Backend;

use WP2X\Engine\Base;

/**
 * Create the settings page in the backend
 */
class Settings_Page extends Base {

	/**
	 * @var false|mixed|void
	 */
	public $settings_option;
	public $get_locale;

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function initialize() {
		$this->get_locale      = get_locale();
		$this->settings_option = get_option( 'wp2x-settings' );

		add_action( 'admin_enqueue_scripts', array( $this, 'change_admin_font' ), 30 );
		add_action( 'admin_enqueue_scripts', array( $this, 'change_editor_font' ), 30 );
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
		add_action( 'admin_footer_text', array( $this, 'custom_footer' ) );
		add_action( 'wp_before_admin_bar_render', array( $this, 'adminbar_logo' ) );
		add_action( 'admin_head', array( $this, 'hide_core_update_notifications_from_users' ), 1 );
		add_action( 'admin_init', array( $this, 'remove_welcome_panel' ) );
		add_action( 'wp_dashboard_setup', array( $this, 'remove_dashboard_widgets' ) );

		$realpath = realpath( dirname( __FILE__ ) );
		assert( is_string( $realpath ) );
		$plugin_basename = plugin_basename( plugin_dir_path( $realpath ) . W_TEXTDOMAIN . '.php' );

		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );
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

		// Menu icon
		$icon_svg = 'data:image/svg+xml;base64,PHN2ZyBpZD0iS0xfTG9nbyIgZGF0YS1uYW1lPSJLTCBMb2dvIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNTkuMiAyNTUuODQiPjxkZWZzPjxzdHlsZT4uY2xzLTF7ZmlsbDojMDA1MmNjfS5jbHMtMntmaWxsOiNmZmFiMDB9PC9zdHlsZT48L2RlZnM+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNMTI5LjM5IDEyNi45Nkw3NC42IDE4MC4yNWw3NS43NCA3NS40OSAxMDguMzUuMS0xMjkuMy0xMjguODh6IiBpZD0iX2JvdCIgZGF0YS1uYW1lPSJcIGJvdCIvPjxwYXRoIGNsYXNzPSJjbHMtMiIgZD0iTTI1OS4yIDBIMTUxLjk3TDIuMDcgMTUwLjE4bDI2LjggNzguMzJMMjU5LjIuMzdWMHoiIGlkPSJfIiBkYXRhLW5hbWU9Ii8iLz48cGF0aCBjbGFzcz0iY2xzLTEiIGQ9Ik0wIC4wMWg3Ni40N3YyNTUuODNIMHoiIGlkPSJfMiIgZGF0YS1uYW1lPSJ8Ii8+PHBhdGggY2xhc3M9ImNscy0yIiBkPSJNMC0uMTlsLjEgMTA3LjIgNzUuNjIgNzQuOTUgNTMuMzgtNTQuMjFMMC0uMTl6IiBpZD0iXzMiIGRhdGEtbmFtZT0iXCIvPjxwYXRoIGZpbGw9IiMxNzJiNGQiIGQ9Ik0xMzAuNzMgMTI4LjMzbC0yNi4zIDI1LjE5LTI0IDI0LjI4IDcwLjU2LTI4LjgtMjAuMjYtMjAuNjd6IiBpZD0ic2hhZG93IiBvcGFjaXR5PSIuMiIvPjwvc3ZnPg==';

		add_menu_page( __( 'Khorshid', W_TEXTDOMAIN ), __( 'Khorshid', W_TEXTDOMAIN ), 'manage_options', W_TEXTDOMAIN, array(
			$this,
			'display_plugin_about_page'
		), $icon_svg, 3 );

		add_submenu_page( W_TEXTDOMAIN, __( 'Settings', W_TEXTDOMAIN ), __( 'Settings', W_TEXTDOMAIN ), 'manage_options', W_TEXTDOMAIN . '-settings', [
			$this,
			'display_plugin_settings_page'
		] );
	}

	/**
	 * Render the about page for this plugin.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function display_plugin_about_page() {
		include_once W_PLUGIN_ROOT . 'backend/views/about.php';
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function display_plugin_settings_page() {
		include W_PLUGIN_ROOT . "backend/views/settings.php";
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
			'settings' => '<a href="' . admin_url( 'admin.php?page=' . W_TEXTDOMAIN . '-settings' ) . '">' . __( 'Settings', W_TEXTDOMAIN ) . '</a>',
			'donate'   => '<a href="#">' . __( 'Donate', W_TEXTDOMAIN ) . '</a>',
		), $links );
	}

	public function custom_footer() {
		return '<img src="' . W_PLUGIN_ROOT_URL . 'assets/img/khorshid-logo.svg" width="30" style="vertical-align: middle;" /><a href="https://khorshidlab.com" style="	text-decoration: none;color: #0052cc;margin-right: 5px;font-weight: bold;" target="_blank">' . __( 'WordPress Support', W_TEXTDOMAIN ) . ': ' . __( 'Khorshid', W_TEXTDOMAIN ) . '</a>';
	}

	public function change_admin_font() {
		$font_styles = '';
		$admin_font  = $this->settings_option['admin-font'];

		if ( isset( $admin_font ) && $admin_font != 'wp-default' ) {
			if ( $this->get_locale == 'fa_IR' ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'remove_google_fonts' ) );
				add_action( 'admin_enqueue_scripts', array( $this, 'remove_google_fonts' ) );
			} else {
				$font_styles .= '@import url("https://fonts.googleapis.com/css?family=' . str_replace( ' ', '+', $admin_font ) . '"); ';
			}

			$font_styles .= 'body, h1, h2, h3, h4, h5, h6, label, input, textarea, .components-notice, #wpadminbar *:not([class="ab-icon"]), .wp-core-ui, .media-menu, .media-frame *, .media-modal *{font-family:"' . $admin_font . '" !important;}';
			wp_add_inline_style( W_TEXTDOMAIN . '-admin-styles', $font_styles );
		}
	}

	public function change_editor_font() {
		$font_styles = '';
		$editor_font = $this->settings_option['editor-font'];

		if ( isset( $editor_font ) && $editor_font != 'wp-default' ) {
			if ( $this->get_locale == 'fa_IR' ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'remove_google_fonts' ) );
				add_action( 'admin_enqueue_scripts', array( $this, 'remove_google_fonts' ) );
			} else {
				$font_styles .= '@import url("https://fonts.googleapis.com/css?family=' . str_replace( ' ', '+', $editor_font ) . '"); ';
			}

			$font_styles .= '#editorcontainer #content, #wp_mce_fullscreen, .block-editor-writing-flow input, .block-editor-writing-flow textarea, .block-editor-writing-flow p {font-family:"' . $editor_font . '" !important;}';
			wp_add_inline_style( W_TEXTDOMAIN . '-admin-styles', $font_styles );
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
			$user_roles = wp_roles_array();

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
			'wp-default'  => __( 'WordPress Default', W_TEXTDOMAIN ),
			'Vazir'       => __( 'Vazir', W_TEXTDOMAIN ),
			'Estedad'     => __( 'Estedad', W_TEXTDOMAIN ),
			'IranianSans' => __( 'Iranian', W_TEXTDOMAIN ),
			'NotoSans'    => __( 'Noto', W_TEXTDOMAIN ),
		);

		return $fonts;
	}

}
