<?php
/**
 * Product Visibility by User Role for WooCommerce Pro - Bulk Settings
 *
 * @version 1.2.0
 * @since   1.2.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Pro_PVBUR_Settings_Bulk' ) ) {

	class Alg_WC_Pro_PVBUR_Settings_Bulk {

		/**
		 * Constructor.
		 *
		 * @version 1.2.0
		 * @since   1.2.0
		 */
		public static function get_settings( $settings ) {

			$section = wp_list_filter( $settings, array(
				'title' => 'Products',
				'desc'  => 'Visible',
			), 'AND' );

			$i = 0;
			if ( is_array( $section ) && ! empty( $section ) ) {
				reset( $section );

				foreach ( $section as $section_index => $value ) {
					$str_arr = explode( 'alg_wc_pvbur_bulk_visible_products_', $value['id'] );
					$role    = $str_arr[ count( $str_arr ) - 1 ];

					$new_settings = array(
						array(
							'title'    => __( 'Hide All', 'product-visibility-by-user-role-for-woocommerce' ),
							'desc_tip' => __( 'Hides all products, product categories/tags from this user role', 'product-visibility-by-user-role-for-woocommerce' ),
							'desc'     => __( 'Enable', 'product-visibility-by-user-role-for-woocommerce' ),
							'id'       => "alg_wc_pvbur_bulk_hide_all_from_{$role}",
							'default'  => 'no',
							'type'     => 'checkbox',
						),
					);

					array_splice( $settings, $section_index + $i, 0, $new_settings );
					$i ++;
				}
			}

			return $settings;
		}
	}
}