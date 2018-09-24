<?php
/*
Plugin Name: Product Visibility by User Role for WooCommerce Pro
Plugin URI: https://wpcodefactory.com/item/product-visibility-by-user-role-for-woocommerce/
Description: Display WooCommerce products by customer's user role.
Version: 1.2.4
Author: Algoritmika Ltd
Author URI: http://algoritmika.com
Text Domain: product-visibility-by-user-role-for-woocommerce
Domain Path: /langs
Copyright: © 2018 Algoritmika Ltd.
WC requires at least: 3.0.0
WC tested up to: 3.4
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

//require_once "vendor/autoload.php";

if ( ! class_exists( 'Alg_WC_Pro_PVBUR' ) ) :

	/**
	 * Main Alg_WC_Pro_PVBUR Class
	 *
	 * @class   Alg_WC_Pro_PVBUR
	 * @version 1.1.8
	 * @since   1.0.0
	 */
	final class Alg_WC_Pro_PVBUR {

		/**
		 * Plugin version.
		 *
		 * @var   string
		 * @since 1.0.0
		 */
		public $version = '1.1.8';

		/**
		 * @var   Alg_WC_Pro_PVBUR The single instance of the class
		 * @since 1.0.0
		 */
		protected static $_instance = null;

		/**
		 * @var Alg_WC_Pro_PVBUR_Core
		 */
		public $core;

		/**
		 * Main Alg_WC_Pro_PVBUR Instance
		 *
		 * Ensures only one instance of Alg_WC_Pro_PVBUR is loaded or can be loaded.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @static
		 * @return  Alg_WC_Pro_PVBUR - Main instance
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * Alg_WC_Pro_PVBUR Initializer
		 *
		 * @version 1.2.0
		 * @since   1.0.0
		 * @access  public
		 */
		function init() {
			$this->handle_localization();

			// Include required files
			$this->includes();

			// Settings & Scripts
			if ( is_admin() ) {
				add_filter( 'alg_wc_pvbur_settings_', array( 'Alg_WC_Pro_PVBUR_Settings_General', 'get_settings' ), 11 );
				add_filter( 'alg_wc_pvbur_settings_bulk', array( 'Alg_WC_Pro_PVBUR_Settings_Bulk', 'get_settings' ), 11 );
				add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );
			}
		}

		/**
		 * Localization
		 *
		 * @version 1.1.8
		 * @since   1.0.0
		 */
		private function handle_localization() {
			$domain = 'product-visibility-by-user-role-for-woocommerce';
			$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
			$loaded = load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . 'plugins' . '/' . $domain . '-pro' . '/' . $domain . '-' . $locale . '.mo' );
			if ( $loaded ) {
				return $loaded;
			} else {
				load_plugin_textdomain( $domain, false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );
			}
		}

		/**
		 * Alg_WC_PVBUR.
		 *
		 * @version 1.1.9
		 * @since   1.0.0
		 */
		function alg_wc_pvbur( $value, $type, $args = array() ) {
			switch ( $type ) {
				case 'premium_version':
					return 'yes';
				case 'settings':
					return '';
				case 'bulk_settings':
					return get_option( 'alg_wc_pvbur_bulk_options_section_enabled', 'no' );
				case 'products_bulk_edit':
					return get_option( 'alg_wc_pvbur_add_to_bulk_edit', 'no' );
			}
			return $value;
		}

		/**
		 * Show action links on the plugin screen
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param   mixed $links
		 *
		 * @return  array
		 */
		function action_links( $links ) {
			$custom_links   = array();
			$custom_links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_wc_pvbur' ) . '">' . __( 'Settings', 'woocommerce' ) . '</a>';
			return array_merge( $custom_links, $links );
		}

		/**
		 * Include required core files used in admin and on the frontend.
		 *
		 * @version 1.1.0
		 * @since   1.0.0
		 */
		function includes() {
			// Functions
			require_once( 'includes/alg-wc-pro-pvbur-functions.php' );

			$this->settings = array();

			if ( is_admin() && get_option( 'alg_wc_pro_pvbur_version', '' ) !== $this->version ) {
				foreach ( $this->settings as $section ) {
					foreach ( $section->get_settings() as $value ) {
						if ( isset( $value['default'] ) && isset( $value['id'] ) ) {
							$autoload = isset( $value['autoload'] ) ? ( bool ) $value['autoload'] : true;
							add_option( $value['id'], $value['default'], '', ( $autoload ? 'yes' : 'no' ) );
						}
					}
				}
				update_option( 'alg_wc_pro_pvbur_version', $this->version );
			}

			// Core
			$this->core = require_once( 'includes/class-alg-wc-pro-pvbur-core.php' );
		}

		/**
		 * Get the plugin url.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @return  string
		 */
		function plugin_url() {
			return untrailingslashit( plugin_dir_url( __FILE__ ) );
		}

		/**
		 * Get the plugin path.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @return  string
		 */
		function plugin_path() {
			return untrailingslashit( plugin_dir_path( __FILE__ ) );
		}

	}

endif;

if ( ! function_exists( 'Alg_WC_Pro_PVBUR' ) ) {
	/**
	 * Returns the main instance of Alg_WC_Pro_PVBUR to prevent the need to use globals.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return  Alg_WC_Pro_PVBUR
	 */
	function Alg_WC_Pro_PVBUR() {
		return Alg_WC_Pro_PVBUR::instance();
	}
}

$plugin = Alg_WC_Pro_PVBUR();
add_filter( 'alg_wc_pvbur', array( $plugin, 'alg_wc_pvbur' ), PHP_INT_MAX, 3 );
require_once "vendor/autoload.php";
$plugin->init();