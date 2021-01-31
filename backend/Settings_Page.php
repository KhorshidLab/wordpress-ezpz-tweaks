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
		$this->get_locale      = get_locale();
		$this->settings_option = get_option( 'ezpz-tweaks-settings' );
		$this->branding_option = get_option( 'ezpz-tweaks-settings-branding' );

		add_action( 'admin_enqueue_scripts', array( $this, 'change_admin_font' ), 30 );
		add_action( 'admin_enqueue_scripts', array( $this, 'change_editor_font' ), 30 );
		add_action( 'admin_enqueue_scripts', array( $this, 'apply_admin_custom_css' ), 30 );
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
		add_action( 'admin_footer_text', array( $this, 'custom_footer' ) );
		add_action( 'wp_before_admin_bar_render', array( $this, 'adminbar_logo' ) );
		add_action( 'admin_head', array( $this, 'hide_core_update_notifications_from_users' ), 1 );
		add_action( 'admin_init', array( $this, 'remove_welcome_panel' ) );
		add_action( 'wp_dashboard_setup', array( $this, 'remove_dashboard_widgets' ) );

		$realpath = realpath( dirname( __FILE__ ) );
		assert( is_string( $realpath ) );
		$plugin_basename = plugin_basename( plugin_dir_path( $realpath ) . EZPZ_TWEAKS_TEXTDOMAIN . '.php' );

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

		add_submenu_page( 'options-general.php', __( 'EzPz Tweaks', EZPZ_TWEAKS_TEXTDOMAIN ), __( 'EzPz Tweaks', EZPZ_TWEAKS_TEXTDOMAIN ), 'update_core', EZPZ_TWEAKS_TEXTDOMAIN . '-settings', [
			$this,
			'display_plugin_settings_page'
		] );

		if( isset( $this->branding_option['enable_branding'] ) ) {
			add_menu_page( $this->branding_option['menu_title'], $this->branding_option['menu_title'], 'manage_options', $this->branding_option['menu_slug'], array(
				$this,
				'display_branding_page'
			), $this->branding_option['custom_logo'], 3 );

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
			'donate'   => '<a href="#">' . __( 'Donate', EZPZ_TWEAKS_TEXTDOMAIN ) . '</a>',
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
		$admin_font  = $this->settings_option['admin-font'];

		if ( isset( $admin_font ) && $admin_font != 'wp-default' ) {
			if ( $this->get_locale == 'fa_IR' ) {
				add_action( 'wp_enqueue_scripts', array( $this, 'remove_google_fonts' ) );
				add_action( 'admin_enqueue_scripts', array( $this, 'remove_google_fonts' ) );
			} else {
				$font_styles .= '@import url("https://fonts.googleapis.com/css?family=' . str_replace( ' ', '+', $admin_font ) . '"); ';
			}

			$font_styles .= 'body, h1, h2, h3, h4, h5, h6, label, input, textarea, .components-notice, #wpadminbar *:not([class="ab-icon"]), .wp-core-ui, .media-menu, .media-frame *, .media-modal *{font-family:"' . $admin_font . '" !important;}';
			wp_add_inline_style( EZPZ_TWEAKS_TEXTDOMAIN . '-admin-styles', $font_styles );
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

			$font_styles .= 'body#tinymce, #editorcontainer #content, #wp_mce_fullscreen, .block-editor-writing-flow input, .block-editor-writing-flow textarea, .block-editor-writing-flow p {font-family:"' . $editor_font . '" !important;}';
			wp_add_inline_style( EZPZ_TWEAKS_TEXTDOMAIN . '-admin-styles', $font_styles );
		}
	}

	public function remove_google_fonts() {
		// Unload Open Sans
		wp_deregister_style( 'open-sans' );
		wp_register_style( 'open-sans', false );

	}

	public function adminbar_logo() {
		if ( isset( $this->branding_option['enable_branding'] ) && isset( $this->branding_option['custom_logo'] ) ) {
			echo '<style type="text/css">
			#wpadminbar #wp-admin-bar-wp-logo>.ab-item {
			    padding: 0 7px;
			    background-image: url(' . $this->branding_option['custom_logo'] . ') !important;
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
			'wp-default'  => __( 'WordPress Default', EZPZ_TWEAKS_TEXTDOMAIN ),
			'Vazir'       => __( 'Vazir', EZPZ_TWEAKS_TEXTDOMAIN ),
			'Estedad'     => __( 'Estedad', EZPZ_TWEAKS_TEXTDOMAIN ),
			'IranianSans' => __( 'Iranian', EZPZ_TWEAKS_TEXTDOMAIN ),
			'NotoSans'    => __( 'Noto', EZPZ_TWEAKS_TEXTDOMAIN ),
		);

		return $fonts;
	}

	public function apply_admin_custom_css() {
		if ( isset( $this->settings_option['custom_css'] ) ) {
			wp_add_inline_style( EZPZ_TWEAKS_TEXTDOMAIN . '-admin-styles', $this->settings_option['custom_css'] );
		}
	}

}
