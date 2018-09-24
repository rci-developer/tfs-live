<?php
/**
 * Product Visibility by User Role for WooCommerce Pro - General Section Settings
 *
 * @version 1.2.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Pro_PVBUR_Settings_General' ) ) {

	class Alg_WC_Pro_PVBUR_Settings_General {

		/**
		 * Constructor.
		 *
		 * @version 1.2.0
		 * @since   1.1.7
		 */
		public static function get_settings( $settings ) {

			$general_section_end = wp_list_filter( $settings, array(
				'id'   => 'alg_wc_pvbur_general_options',
				'type' => 'sectionend',
			), 'AND' );

			if ( is_array( $general_section_end ) && ! empty( $general_section_end ) ) {
				reset( $general_section_end );
				$general_section_end_index = key( $general_section_end );

				$new_settings = array(
					array(
						'title'    => __( 'Hide menu items', 'product-visibility-by-user-role-for-woocommerce' ),
						'desc_tip' => __( 'Hides nav menu items from empty product categories and tags.', 'product-visibility-by-user-role-for-woocommerce' ) . ' ' . sprintf( __( 'Only categories/tags marked in <a href="%s">bulk settings</a> will be hidden.', 'product-visibility-by-user-role-for-woocommerce' ), admin_url( 'admin.php?page=wc-settings&tab=alg_wc_pvbur&section=bulk' ) ). '<br />' . __( 'This options uses the <strong>wp_get_nav_menu_items</strong> filter', 'product-visibility-by-user-role-for-woocommerce' ) ,
						'desc'     => __( 'Enable', 'product-visibility-by-user-role-for-woocommerce' ),
						'id'       => 'alg_wc_pvbur_hide_menu_items',
						'default'  => 'no',
						'type'     => 'checkbox',
					),
					array(
						'title'    => __( 'Hide products terms', 'product-visibility-by-user-role-for-woocommerce' ),
						'desc_tip' => __( 'Hides products categories and tags from being displayed on front-end.', 'product-visibility-by-user-role-for-woocommerce' ) . ' ' . sprintf( __( 'Only categories/tags marked in <a href="%s">bulk settings</a> will be hidden.', 'product-visibility-by-user-role-for-woocommerce' ), admin_url( 'admin.php?page=wc-settings&tab=alg_wc_pvbur&section=bulk' ) ). '<br />' . __( 'This options works filtering terms from <strong>get_terms()</strong> function', 'product-visibility-by-user-role-for-woocommerce' ) ,
						'desc'     => __( 'Enable', 'product-visibility-by-user-role-for-woocommerce' ),
						'id'       => 'alg_wc_pvbur_hide_product_terms',
						'default'  => 'no',
						'type'     => 'checkbox',
					),
					array(
						'title'    => __( 'Redirect', 'product-visibility-by-user-role-for-woocommerce' ),
						'desc_tip' => sprintf( __( 'This option is useful only if <strong>%s</strong> is enabled', 'product-visibility-by-user-role-for-woocommerce' ), 'Modify Query' ),
						'desc'     => '<br />' . __( 'Redirects to a page different from 404, in case a product is considered invisible', 'product-visibility-by-user-role-for-woocommerce' ),
						'id'       => 'alg_wc_pvbur_redirect',
						'default'  => '',
						'type'     => 'text',
					),
				);

				array_splice( $settings, $general_section_end_index, 0, $new_settings );
			}

			return $settings;
		}
	}
}