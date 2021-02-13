<?php
/*
Plugin Name: WPS Hide Login
Description: Protect your website by changing the login URL and preventing access to wp-login.php page and wp-admin directory while not logged-in
Author: WPServeur, NicolasKulka, tabrisrp
Author URI: https://wpserveur.net
Version: 1.4.2
Requires at least: 4.1
Tested up to: 4.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

namespace EZPZ_TWEAKS\Integrations;

use EZPZ_TWEAKS\Engine\Base;

class WPS_Hide_Login extends Base {

	private $wp_login_php;
	private $settings_option;

	public function __construct() {
		global $wp_version;
		$this->settings_option = get_option( 'ezpz-tweaks-settings' );

		if( isset( $this->settings_option['custom_login_url'] ) )
		{
			if ( version_compare( $wp_version, '4.0-RC1-src', '<' ) ) {
				add_action( 'admin_notices', array( $this, 'admin_notices_incompatible' ) );
				add_action( 'network_admin_notices', array( $this, 'admin_notices_incompatible' ) );

				return;
			}


			if ( is_multisite() && ! function_exists( 'is_plugin_active_for_network' ) || ! function_exists( 'is_plugin_active' ) ) {
				require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

			}

			add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 9999 );
			add_action( 'wp_loaded', array( $this, 'wp_loaded' ) );
			add_action( 'setup_theme', array( $this, 'setup_theme' ), 1 );

			add_filter( 'site_url', array( $this, 'site_url' ), 10, 4 );
			add_filter( 'network_site_url', array( $this, 'network_site_url' ), 10, 3 );
			add_filter( 'wp_redirect', array( $this, 'wp_redirect' ), 10, 2 );
			add_filter( 'site_option_welcome_email', array( $this, 'welcome_email' ) );

			remove_action( 'template_redirect', 'wp_redirect_admin_locations', 1000 );

			add_action( 'template_redirect', array( $this, 'wps_hide_login_redirect_page_email_notif_woocommerce' ) );
			add_filter( 'login_url', array( $this, 'login_url' ), 10, 3 );
		}
	}

	private function use_trailing_slashes() {

		return ( '/' === substr( get_option( 'permalink_structure' ), - 1, 1 ) );

	}

	private function user_trailingslashit( $string ) {

		return $this->use_trailing_slashes() ? trailingslashit( $string ) : untrailingslashit( $string );

	}

	private function wp_template_loader() {

		global $pagenow;

		$pagenow = 'index.php';

		if ( ! defined( 'WP_USE_THEMES' ) ) {

			define( 'WP_USE_THEMES', true );

		}

		wp();

		if ( $_SERVER['REQUEST_URI'] === $this->user_trailingslashit( str_repeat( '-/', 10 ) ) ) {

			$_SERVER['REQUEST_URI'] = $this->user_trailingslashit( '/wp-login-php/' );

		}

		require_once( ABSPATH . WPINC . '/template-loader.php' );

		die;

	}

	private function new_login_slug() {

		if ( isset( $this->settings_option['custom_login_url'] ) ) {
			return $this->settings_option['custom_login_url'];
		} else if ( ( is_multisite() && is_plugin_active_for_network( EZPZ_TWEAKS_PLUGIN_BASENAME ) && isset( $this->settings_option['custom_login_url'] ) ) ) {
			return $this->settings_option['custom_login_url'];
		} else if ( $slug = 'login' ) {
			return $slug;
		}
	}

	public function new_login_url( $scheme = null ) {
		if ( get_option( 'permalink_structure' ) ) {

			return $this->user_trailingslashit( home_url( '/', $scheme ) . $this->new_login_slug() );

		} else {

			return home_url( '/', $scheme ) . '?' . $this->new_login_slug();

		}

	}

	public function admin_notices_incompatible() {

		echo '<div class="error notice is-dismissible"><p>' . __( 'Please upgrade to the latest version of WordPress to activate', EZPZ_TWEAKS_TEXTDOMAIN ) . '</p></div>';

	}

	public function plugins_loaded() {

		global $pagenow;

		if ( ! is_multisite()
		     && ( strpos( $_SERVER['REQUEST_URI'], 'wp-signup' ) !== false
		          || strpos( $_SERVER['REQUEST_URI'], 'wp-activate' ) !== false ) && apply_filters( 'wps_hide_login_signup_enable', false ) === false ) {

			wp_die( __( 'This feature is not enabled.', EZPZ_TWEAKS_TEXTDOMAIN ) );

		}

		$request = parse_url( $_SERVER['REQUEST_URI'] );

		if ( ( strpos( rawurldecode( $_SERVER['REQUEST_URI'] ), 'wp-login.php' ) !== false
		       || untrailingslashit( $request['path'] ) === site_url( 'wp-login', 'relative' ) )
		     && ! is_admin() ) {

			$this->wp_login_php = true;

			$_SERVER['REQUEST_URI'] = $this->user_trailingslashit( '/' . str_repeat( '-/', 10 ) );

			$pagenow = 'index.php';

		} elseif ( untrailingslashit( $request['path'] ) === home_url( $this->new_login_slug(), 'relative' )
		           || ( ! get_option( 'permalink_structure' )
		                && isset( $_GET[ $this->new_login_slug() ] )
		                && empty( $_GET[ $this->new_login_slug() ] ) ) ) {

			$pagenow = 'wp-login.php';

		} elseif ( ( strpos( rawurldecode( $_SERVER['REQUEST_URI'] ), 'wp-register.php' ) !== false
		             || untrailingslashit( $request['path'] ) === site_url( 'wp-register', 'relative' ) )
		           && ! is_admin() ) {

			$this->wp_login_php = true;

			$_SERVER['REQUEST_URI'] = $this->user_trailingslashit( '/' . str_repeat( '-/', 10 ) );

			$pagenow = 'index.php';
		}

	}

	public function setup_theme() {
		global $pagenow;

		if ( ! is_user_logged_in() && 'customize.php' === $pagenow ) {
			wp_die( __( 'This has been disabled', EZPZ_TWEAKS_TEXTDOMAIN ), 403 );
		}
	}

	public function wp_loaded() {
		global $pagenow;

		$request = parse_url( $_SERVER['REQUEST_URI'] );

		if ( is_admin() && ! is_user_logged_in() && ! defined( 'DOING_AJAX' ) && $pagenow !== 'admin-post.php' && ( isset( $_GET ) && empty( $_GET['adminhash'] ) && $request['path'] !== '/wp-admin/options.php' ) ) {
			wp_safe_redirect( home_url( '/404' ) );
			die();
		}

		if ( $pagenow === 'wp-login.php'
		     && $request['path'] !== $this->user_trailingslashit( $request['path'] )
		     && get_option( 'permalink_structure' ) ) {

			wp_safe_redirect( $this->user_trailingslashit( $this->new_login_url() )
			                  . ( ! empty( $_SERVER['QUERY_STRING'] ) ? '?' . $_SERVER['QUERY_STRING'] : '' ) );

			die;

		} elseif ( $this->wp_login_php ) {

			if ( ( $referer = wp_get_referer() )
			     && strpos( $referer, 'wp-activate.php' ) !== false
			     && ( $referer = parse_url( $referer ) )
			     && ! empty( $referer['query'] ) ) {

				parse_str( $referer['query'], $referer );

				if ( ! empty( $referer['key'] )
				     && ( $result = wpmu_activate_signup( $referer['key'] ) )
				     && is_wp_error( $result )
				     && ( $result->get_error_code() === 'already_active'
				          || $result->get_error_code() === 'blog_taken' ) ) {

					wp_safe_redirect( $this->new_login_url()
					                  . ( ! empty( $_SERVER['QUERY_STRING'] ) ? '?' . $_SERVER['QUERY_STRING'] : '' ) );

					die;

				}

			}

			$this->wp_template_loader();

		} elseif ( $pagenow === 'wp-login.php' ) {
			global $error, $interim_login, $action, $user_login;

			if ( is_user_logged_in() && ! isset( $_REQUEST['action'] ) ) {
				wp_safe_redirect( admin_url() );
				die();
			}

			@require_once ABSPATH . 'wp-login.php';

			die;

		}
	}

	public function site_url( $url, $path, $scheme, $blog_id ) {

		return $this->filter_wp_login_php( $url, $scheme );

	}

	public function network_site_url( $url, $path, $scheme ) {

		return $this->filter_wp_login_php( $url, $scheme );

	}

	public function wp_redirect( $location, $status ) {

		return $this->filter_wp_login_php( $location );

	}

	public function filter_wp_login_php( $url, $scheme = null ) {

		if ( strpos( $url, 'wp-login.php' ) !== false ) {

			if ( is_ssl() ) {

				$scheme = 'https';

			}

			$args = explode( '?', $url );

			if ( isset( $args[1] ) ) {

				parse_str( $args[1], $args );

				if ( isset( $args['login'] ) ) {
					$args['login'] = rawurlencode( $args['login'] );
				}

				$url = add_query_arg( $args, $this->new_login_url( $scheme ) );

			} else {

				$url = $this->new_login_url( $scheme );

			}

		}

		return $url;

	}

	public function welcome_email( $value ) {

		return $value = str_replace( 'wp-login.php', trailingslashit( $this->settings_option['custom_login_url'] ), $value );

	}

	/**
	 * Update redirect for Woocommerce email notification
	 */
	public function wps_hide_login_redirect_page_email_notif_woocommerce() {

		if ( ! class_exists( 'WC_Form_Handler' ) ) {
			return false;
		}

		if ( ! empty( $_GET ) && isset( $_GET['action'] ) && 'rp' === $_GET['action'] && isset( $_GET['key'] ) && isset( $_GET['login'] ) ) {
			wp_redirect( $this->new_login_url() );
			exit();
		}
	}

	/**
	 *
	 * Update url redirect : wp-admin/options.php
	 *
	 * @param $login_url
	 * @param $redirect
	 * @param $force_reauth
	 *
	 * @return string
	 */
	public function login_url( $login_url, $redirect, $force_reauth ) {

		if ( $force_reauth === false ) {
			return $login_url;
		}

		if ( empty( $redirect ) ) {
			return $login_url;
		}

		$redirect = explode( '?', $redirect );

		if ( $redirect[0] === admin_url( 'options.php' ) ) {
			$login_url = admin_url();
		}

		return $login_url;
	}

}
