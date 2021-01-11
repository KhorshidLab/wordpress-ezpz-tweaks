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

namespace WP2X\Frontend;

use WP2X\Engine\Base;

class Settings extends Base {
	/**
	 * @var false|mixed|void
	 */
	private $settings_option;

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function initialize() {
		$this->settings_option = get_option( 'wp2x-settings' );

		add_action( 'init', array( $this, 'disable_emojis' ) );
		add_action( 'init', array( $this, 'disable_embeds_code_init' ), 9999 );
		add_action( 'init', array( $this, 'disable_xmlrpc' ) );
		add_action( 'init', array( $this, 'hide_admin_bar' ), 9999 );
		add_action( 'after_setup_theme', array( $this, 'remove_shortlink' ) );
		add_filter( 'after_setup_theme', array( $this, 'remove_wp_version_from_head' ) );

		add_filter( 'rest_authentication_errors', array( $this, 'disable_wp_rest_api' ) );
		add_filter( 'comment_form_default_fields', array( $this, 'remove_website_field' ) );
		add_filter( 'login_message', array( $this, 'add_login_page_custom_text' ) );
	}

	public function disable_emojis() {
		if ( isset( $this->settings_option['disable_wp_emoji'] ) ) {
			remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
			remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
			remove_action( 'wp_print_styles', 'print_emoji_styles' );
			remove_action( 'admin_print_styles', 'print_emoji_styles' );
			remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
			remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
			remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
			add_filter( 'tiny_mce_plugins', array( $this, 'disable_emojis_tinymce' ) );
			add_filter( 'wp_resource_hints', array( $this, 'disable_emojis_remove_dns_prefetch' ), 10, 2 );
		}
	}

	public function disable_emojis_tinymce( $plugins ) {
		if ( is_array( $plugins ) ) {
			return array_diff( $plugins, array( 'wpemoji' ) );
		} else {
			return array();
		}
	}

	public function disable_emojis_remove_dns_prefetch( $urls, $relation_type ) {
		if ( 'dns-prefetch' == $relation_type ) {
			/** This filter is documented in wp-includes/formatting.php */
			$emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );
			$urls          = array_diff( $urls, array( $emoji_svg_url ) );
		}

		return $urls;
	}

	public function disable_wp_rest_api( $result ) {
		if ( isset( $this->settings_option['disable_rest_api'] ) ) {
			if ( ! empty( $result ) ) {
				return $result;
			}

			if ( ! is_user_logged_in() ) {
				return new WP_Error( 'rest_not_logged_in', 'You are not currently logged in.', array( 'status' => 401 ) );
			}
		}

		return $result;
	}

	public function remove_website_field( $fields ) {
		if ( isset( $this->settings_option['disable_website_field'] ) ) {
			if ( isset( $fields['url'] ) ) {
				unset( $fields['url'] );
			}
		}

		return $fields;
	}

	public function disable_embeds_code_init() {
		if ( isset( $this->settings_option['disable_wp_embed'] ) ) {
			// Remove the REST API endpoint.
			remove_action( 'rest_api_init', 'wp_oembed_register_route' );

			// Turn off oEmbed auto discovery.
			add_filter( 'embed_oembed_discover', '__return_false' );

			// Don't filter oEmbed results.
			remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );

			// Remove oEmbed discovery links.
			remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

			// Remove oEmbed-specific JavaScript from the front-end and back-end.
			remove_action( 'wp_head', 'wp_oembed_add_host_js' );
			add_filter( 'tiny_mce_plugins', array( $this, 'disable_embeds_tiny_mce_plugin' ) );

			// Remove all embeds rewrite rules.
			add_filter( 'rewrite_rules_array', array( $this, 'disable_embeds_rewrites' ) );

			// Remove filter of the oEmbed result before any HTTP requests are made.
			remove_filter( 'pre_oembed_result', 'wp_filter_pre_oembed_result', 10 );
		}
	}

	public function disable_embeds_tiny_mce_plugin( $plugins ) {
		return array_diff( $plugins, array( 'wpembed' ) );
	}

	public function disable_embeds_rewrites( $rules ) {
		foreach ( $rules as $rule => $rewrite ) {
			if ( false !== strpos( $rewrite, 'embed=true' ) ) {
				unset( $rules[ $rule ] );
			}
		}

		return $rules;
	}

	public function disable_xmlrpc() {
		if ( isset( $this->settings_option['disable_xmlrpc'] ) ) {
			// Remove RSD link from head
			remove_action( 'wp_head', 'rsd_link' );

			add_filter( 'xmlrpc_enabled', '__return_false' );
			add_filter( 'xmlrpc_methods', '__return_empty_array', PHP_INT_MAX );
			add_filter( 'wp_headers', array( $this, 'remove_x_pingback' ) );
			add_filter( 'bloginfo_url', array( $this, 'remove_pingback_url' ), 1, 2 );
			add_filter( 'bloginfo', array( $this, 'remove_pingback_url' ), 1, 2 );

			// Force to uncheck pingbck and trackback options
			add_filter( 'pre_option_default_ping_status', '__return_zero' );
			add_filter( 'pre_option_default_pingback_flag', '__return_zero' );

			$this->set_disabled_header();
		}
	}

	public function set_disabled_header() {
		// Return immediately if SCRIPT_FILENAME not set
		if ( ! isset( $_SERVER['SCRIPT_FILENAME'] ) ) {
			return;
		}

		$file = basename( $_SERVER['SCRIPT_FILENAME'] );

		// Break only if xmlrpc.php file was requested.
		if ( 'xmlrpc.php' !== $file ) {
			return;
		}

		$header = 'HTTP/1.1 403 Forbidden';

		header( $header );
		echo $header;
		die();
	}

	public function remove_x_pingback( $headers ) {
		unset( $headers['X-Pingback'] );

		return $headers;
	}

	public function remove_pingback_url( $output, $show ) {
		if ( $show == 'pingback_url' ) {
			$output = '';
		}

		return $output;
	}

	public function hide_admin_bar() {
		if ( isset( $this->settings_option['hide_admin_bar'] ) ) {
			$user_roles = wp_roles_array();

			foreach ( $user_roles as $role => $name ) {
				if ( current_user_can( $role ) ) {
					show_admin_bar( false );
					break;
				}
			}
		}
	}

	public function remove_shortlink() {
		if ( isset( $this->settings_option['remove_shortlink'] ) ) {
			// remove HTML meta tag
			// <link rel='shortlink' href='http://example.com/?p=25' />
			remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );

			// remove HTTP header
			// Link: <https://example.com/?p=25>; rel=shortlink
			remove_action( 'template_redirect', 'wp_shortlink_header', 11 );
		}
	}

	public function remove_wp_version_from_head() {
		if ( isset( $this->settings_option['remove_wp_version'] ) ) {
			// remove version from head
			remove_action( 'wp_head', 'wp_generator' );

			// remove version from rss
			add_filter( 'the_generator', '__return_empty_string' );

			// remove version from scripts and styles
			add_filter( 'style_loader_src', array( $this, 'remove_version_scripts_styles' ), 9999 );
			add_filter( 'script_loader_src', array( $this, 'remove_version_scripts_styles' ), 9999 );
		}
	}

	public function remove_version_scripts_styles( $src ) {
		if ( strpos( $src, 'ver=' ) ) {
			$src = remove_query_arg( 'ver', $src );
		}

		return $src;
	}

	public function add_login_page_custom_text()
	{
		if ( isset( $this->settings_option['login_custom_text'] ) ) {
			$message = '<div style="box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.2); background: #e6f6fb; color: #444; border-top: 4px solid #00a0d2; margin: 0 0 1em; padding: 12px; font-size: 14px; text-align: center;">
							<p><strong>'. $this->settings_option['login_custom_text'] .'</strong></p>
						</div>';

			return $message;
		}
	}
}
