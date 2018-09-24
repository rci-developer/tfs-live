=== Product Visibility by User Role for WooCommerce Pro ===
Contributors: algoritmika, anbinder
Tags: woo commerce, woocommerce, product, visibility, user role, algoritmika, wpcodefactory
Requires at least: 4.4
Tested up to: 4.9
Stable tag: 1.2.4
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Display WooCommerce products by customer's user role.

== Description ==

**Product Visibility by User Role for WooCommerce** plugin lets you show/hide WooCommerce products depending on customer's user role.

You can choose how products should be hidden:

* Hide products in shop and search results.
* Make products non-purchasable.
* Hide products completely.

In free version you can set included or excluded user roles for each product individually. If you want to set user roles visibility options in bulk (for multiple products at once, product categories or product tags), please check [Product Visibility by User Role for WooCommerce Pro](https://wpcodefactory.com/item/product-visibility-by-user-role-for-woocommerce/) plugin.

= Feedback =
* We are open to your suggestions and feedback. Thank you for using or trying out one of our plugins!

== Installation ==

1. Upload the entire 'product-visibility-by-user-role-for-woocommerce-pro' folder to the '/wp-content/plugins/' directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Start by visiting plugin settings at WooCommerce > Settings > Product Visibility.

== Changelog ==

= 1.2.4 - 13/08/2018
* Improve 'Bulk Settings' code combined with 'Modify Query' option
* Improve 'alg_wc_pvbur_can_search' filter preventing the main visibility query to work on wp menu

= 1.2.3 - 27/07/2018
* Allow other plugins to interact with invisible products query using the 'alg_wc_pvbur_can_search' filter or passing a 'alg_wc_pvbur_search' parameter on customs WP_Query queries
* Add WooCommerce minimum requirement
* Improve performance of invisible products search by saving the results of alg_wc_pvbur_get_invisible_products() in cache (Only the ids, not WP_Query)
* Add action 'alg_wc_pvbur_save_metabox' on metabox saving
* Add new function 'alg_wc_pvbur_get_invisible_products_ids()' to get only the invisible products ids, with a cache option

= 1.2.2 - 02/07/2018 =
* Fix category visibility replacing 'key' by 'taxonomy' on tax_query parameters

= 1.2.1 - 29/06/2018 =
* Improve 'alg_wc_pvbur_is_visible()' function. Get the terms isn't working properly. Replace by wp_get_post_terms();
* Add is_search() check to products hiding function to make sure it works on search results too

= 1.2.0 - 02/06/2018 =
* Change the way Modify query option works
* Add new option to filter product terms hiding product categories and tags using the get_terms() function
* Improve invisible product detection on single product pages
* Add new option to hide all products, product categories/tags from user roles

= 1.1.9 - 11/05/2018 =
* Rollback to version 1.1.7

= 1.1.8 - 07/05/2018 =
* Add 'alg_wc_pvbur_is_visible' filter
* Fix 'alg_wc_pvbur_get_user_roles()' function exists
* Fix visible products logic
* Use composer to handle dependencies
* Remove pro version checking from free plugin

= 1.1.7 - 03/04/2018 =
* Add action 'pvbur_save_product' after a product is updated
* Add a new admin option to hide categories from wp nav menu if there is no products to show
* Add a filter 'pvbur_hide_empty_cats' to hide categories from wp nav menu if there is no products to show

= 1.1.6 - 02/04/2018 =
* Add new option on admin to redirect to a page different from 404 in case a product is invisible
* Add new filter 'pvbur_invisible_product_redirect' to redirect to a page different from 404 in case a product is invisible

= 1.1.5 - 26/03/2018 =
* Fix - Core - `is_visible()` - Bulk settings - Products - Returning `false` only.
* Dev - General - Admin Options - "Product quick edit" option added.
* Dev - General - Admin Options - "Products bulk edit" option added.

= 1.1.4 - 12/11/2017 =
* Dev - Core - Possible "`wp_get_current_user()` undefined" error fixed.

= 1.1.3 - 31/10/2017 =
* Dev - Admin Settings - Settings tab title updated.
* Dev - Admin Settings - General - Description updated.

= 1.1.2 - 30/10/2017 =
* Dev - Admin Settings - Bulk Settings - User roles subsections added.
* Dev - Admin Settings - Meta box - Title updated.

= 1.1.1 - 26/10/2017 =
* Dev - Admin Settings - Bulk Settings - "Save all changes" button added to each role's section.
* Dev - Admin Settings - Meta box - Title and descriptions updated.

= 1.1.0 - 25/10/2017 =
* Dev - "Bulk Settings" section added.
* Dev - Admin Settings - Meta box select - `chosen_select` class added.
* Dev - Admin Settings - Meta box on product edit moved to `side` with `low` priority.
* Dev - Code refactoring.
* Dev - Saving settings array as main class property.

= 1.0.0 - 30/08/2017 =
* Initial Release.

== Upgrade Notice ==

= 1.2.4 =
* Improve 'Bulk Settings' code combined with 'Modify Query' option
* Improve 'alg_wc_pvbur_can_search' filter preventing the main visibility query to work on wp menu