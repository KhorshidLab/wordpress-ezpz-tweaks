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
class Settings_Page extends Base
{

	/**
	 * @var false|mixed|void
	 */
	public $settings_option;

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function initialize()
	{
		$this->settings_option = get_option('wp2x-settings');
		$this->change_admin_font();

		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
		add_action( 'admin_footer_text', array( $this, 'custom_footer' ) );
		add_action( 'login_head', array( $this, 'change_login_logo' ) );

		$realpath = realpath(dirname(__FILE__));
		assert(is_string($realpath));
		$plugin_basename = plugin_basename(plugin_dir_path($realpath) . W_TEXTDOMAIN . '.php');
		add_filter('plugin_action_links_' . $plugin_basename, array($this, 'add_action_links'));
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function add_plugin_admin_menu()
	{
		/*
		 * Add a settings page for this plugin to the main menu
		 *
		 */

		// Menu icon
		$icon_svg = 'data:image/svg+xml;base64,PHN2ZyBpZD0iS0xfTG9nbyIgZGF0YS1uYW1lPSJLTCBMb2dvIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNTkuMiAyNTUuODQiPjxkZWZzPjxzdHlsZT4uY2xzLTF7ZmlsbDojMDA1MmNjfS5jbHMtMntmaWxsOiNmZmFiMDB9PC9zdHlsZT48L2RlZnM+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNMTI5LjM5IDEyNi45Nkw3NC42IDE4MC4yNWw3NS43NCA3NS40OSAxMDguMzUuMS0xMjkuMy0xMjguODh6IiBpZD0iX2JvdCIgZGF0YS1uYW1lPSJcIGJvdCIvPjxwYXRoIGNsYXNzPSJjbHMtMiIgZD0iTTI1OS4yIDBIMTUxLjk3TDIuMDcgMTUwLjE4bDI2LjggNzguMzJMMjU5LjIuMzdWMHoiIGlkPSJfIiBkYXRhLW5hbWU9Ii8iLz48cGF0aCBjbGFzcz0iY2xzLTEiIGQ9Ik0wIC4wMWg3Ni40N3YyNTUuODNIMHoiIGlkPSJfMiIgZGF0YS1uYW1lPSJ8Ii8+PHBhdGggY2xhc3M9ImNscy0yIiBkPSJNMC0uMTlsLjEgMTA3LjIgNzUuNjIgNzQuOTUgNTMuMzgtNTQuMjFMMC0uMTl6IiBpZD0iXzMiIGRhdGEtbmFtZT0iXCIvPjxwYXRoIGZpbGw9IiMxNzJiNGQiIGQ9Ik0xMzAuNzMgMTI4LjMzbC0yNi4zIDI1LjE5LTI0IDI0LjI4IDcwLjU2LTI4LjgtMjAuMjYtMjAuNjd6IiBpZD0ic2hhZG93IiBvcGFjaXR5PSIuMiIvPjwvc3ZnPg==';

		add_menu_page(__('Khorshid', W_TEXTDOMAIN), W_NAME, 'manage_options', W_TEXTDOMAIN, array($this, 'display_plugin_admin_page'), $icon_svg, 3);

		add_submenu_page(W_TEXTDOMAIN, __('Settings', W_TEXTDOMAIN), __('Settings', W_TEXTDOMAIN), 'manage_options', W_TEXTDOMAIN . '-settings', [$this, 'settings_page']);
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function display_plugin_admin_page()
	{
		include_once W_PLUGIN_ROOT . 'backend/views/admin.php';
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @param array $links Array of links.
	 * @return array
	 * @since 1.0.0
	 */
	public function add_action_links(array $links)
	{
		return array_merge(array('settings' => '<a href="' . admin_url('admin.php?page=' . W_TEXTDOMAIN . '-settings') . '">' . __('Settings', W_TEXTDOMAIN) . '</a>', 'donate' => '<a href="#">' . __('Donate', W_TEXTDOMAIN) . '</a>',), $links);
	}

	public function custom_footer()
	{
		return '<img src="' . W_PLUGIN_ROOT_URL . 'assets/img/khorshid-logo.svg" width="30" style="vertical-align: middle;" /><a href="https://khorshidlab.com" style="	text-decoration: none;color: #0052cc;margin-right: 5px;font-weight: bold;" target="_blank">' . __('WordPress Support', W_TEXTDOMAIN) . ': ' . __('Khorshid', W_TEXTDOMAIN) . '</a>';
	}

	public function settings_page()
	{
		include W_PLUGIN_ROOT . "backend/views/settings.php";
	}

	public function change_admin_font()
	{
		$admin_font = $this->settings_option['admin-font'];

		if( isset( $admin_font ) && $admin_font != 'wp-default' )
		{
			add_action( 'wp_enqueue_scripts', array( $this, 'remove_google_fonts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'remove_google_fonts' ) );

			switch ( $admin_font )
			{
				case 'vazir':
					$font_family = 'Vazir';
					break;
				case 'iranian':
					$font_family = 'IranianSans';
					break;
				case 'estedad':
					$font_family = 'Estedad';
					break;
				case 'noto':
					$font_family = 'NotoSans';
					break;
			}

			add_action( 'admin_head', function() use( $font_family ) {
				echo '<style>body, .rtl h1, .rtl h2, .rtl h3, .rtl h4, .rtl h5, .rtl h6, #wpadminbar *:not([class="ab-icon"]), .wp-core-ui, .media-menu, .media-frame *, .media-modal *{font-family:"'. $font_family .'" !important;}</style>' . PHP_EOL;
				echo '<script>jQuery(document).ready(function(){jQuery("tr.user-profile-picture").remove();});</script>';
			} );
		}
	}

	public function remove_google_fonts()
	{
		// Unload Open Sans
		wp_deregister_style( 'open-sans' );
		wp_register_style( 'open-sans', false );

	}

	public function change_login_logo()
	{
		if( isset( $this->settings_option['custom_logo'] ) )
			echo '<style type=”text/css”>h1 a {background-image: url( ' . $this->settings_option['custom_logo'] . ') !important; }</style>';
	}
}
