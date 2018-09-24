<?php
/*
Plugin Name: Price by User Role for WooCommerce Pro
Description: Display WooCommerce products prices by user roles.
Version: 1.1.0
Author: Algoritmika Ltd
Author URI: http://algoritmika.com
Text Domain: price-by-user-role-for-woocommerce
Domain Path: /langs
Copyright: © 2017 Algoritmika Ltd.
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Check if WooCommerce is active
$plugin = 'woocommerce/woocommerce.php';
if (
	! in_array( $plugin, apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) ) ) &&
	! ( is_multisite() && array_key_exists( $plugin, get_site_option( 'active_sitewide_plugins', array() ) ) )
) {
	return;
}

if ( 'price-by-user-role-for-woocommerce.php' === basename( __FILE__ ) ) {
	// Check if Pro is active, if so then return
	$plugin = 'price-by-user-role-for-woocommerce-pro/price-by-user-role-for-woocommerce-pro.php';
	if (
		in_array( $plugin, apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) ) ) ||
		( is_multisite() && array_key_exists( $plugin, get_site_option( 'active_sitewide_plugins', array() ) ) )
	) {
		return;
	}
}

if ( ! class_exists( 'Alg_WC_Price_By_User_Role' ) ) :

/**
 * Main Alg_WC_Price_By_User_Role Class
 *
 * @class   Alg_WC_Price_By_User_Role
 * @since   1.1.0
 * @version 1.0.0
 */
final class Alg_WC_Price_By_User_Role {

	/**
	 * Plugin version.
	 *
	 * @var   string
	 * @since 1.0.0
	 */
	public $version = '1.1.0';

	/**
	 * @var   Alg_WC_Price_By_User_Role The single instance of the class
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Main Alg_WC_Price_By_User_Role Instance
	 *
	 * Ensures only one instance of Alg_WC_Price_By_User_Role is loaded or can be loaded.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @static
	 * @return  Alg_WC_Price_By_User_Role - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Alg_WC_Price_By_User_Role Constructor.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @access  public
	 */
	function __construct() {

		// Set up localisation
		load_plugin_textdomain( 'price-by-user-role-for-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );

		// The filter
		add_filter( 'alg_wc_price_by_user_role', array( $this, 'alg_wc_price_by_user_role' ), PHP_INT_MAX, 2 );

		// Include required files
		$this->includes();

		// Settings & Scripts
		if ( is_admin() ) {
			add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_woocommerce_settings_tab' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );
		}
	}

	/**
	 * alg_wc_price_by_user_role.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function alg_wc_price_by_user_role( $value, $type ) {
		switch ( $type ) {
			case 'per_product_settings':
				return true;
		}
		return $value;
	}

	/**
	 * Show action links on the plugin screen
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 * @param   mixed $links
	 * @return  array
	 */
	function action_links( $links ) {
		$custom_links = array();
		$custom_links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_wc_price_by_user_role' ) . '">' . __( 'Settings', 'woocommerce' ) . '</a>';
		if ( 'price-by-user-role-for-woocommerce.php' === basename( __FILE__ ) ) {
			$custom_links[] = '<a href="https://wpcodefactory.com/item/price-user-role-woocommerce/">' . __( 'Unlock All', 'price-by-user-role-for-woocommerce' ) . '</a>';
		}
		return array_merge( $custom_links, $links );
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function includes() {
		// Functions
		require_once( 'includes/alg-wc-price-by-user-role-functions.php' );
		// Settings
		require_once( 'includes/admin/class-alg-wc-price-by-user-role-settings-section.php' );
		$settings = array();
		$settings[] = require_once( 'includes/admin/class-alg-wc-price-by-user-role-settings-general.php' );
		$settings[] = require_once( 'includes/admin/class-alg-wc-price-by-user-role-settings-multipliers.php' );
		$settings[] = require_once( 'includes/admin/class-alg-wc-price-by-user-role-settings-per-product.php' );
		if ( is_admin() && get_option( 'alg_wc_price_by_user_role_version', '' ) !== $this->version ) {
			foreach ( $settings as $section ) {
				foreach ( $section->get_settings() as $value ) {
					if ( isset( $value['default'] ) && isset( $value['id'] ) ) {
						add_option( $value['id'], $value['default'], '', 'no' );
					}
				}
			}
			update_option( 'alg_wc_price_by_user_role_version', $this->version );
		}
		// Core
		require_once( 'includes/class-alg-wc-price-by-user-role-core.php' );
	}

	/**
	 * Add Price by User Role settings tab to WooCommerce settings.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function add_woocommerce_settings_tab( $settings ) {
		$settings[] = include( 'includes/admin/class-alg-wc-settings-price-by-user-role.php' );
		return $settings;
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

if ( ! function_exists( 'alg_wc_price_by_user_role' ) ) {
	/**
	 * Returns the main instance of Alg_WC_Price_By_User_Role to prevent the need to use globals.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return  Alg_WC_Price_By_User_Role
	 */
	function alg_wc_price_by_user_role() {
		return Alg_WC_Price_By_User_Role::instance();
	}
}

alg_wc_price_by_user_role();
