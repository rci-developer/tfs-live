<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wc_print_notices();

do_action( 'woocommerce_before_cart' ); ?>

<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">

<?php do_action( 'woocommerce_before_cart_table' ); ?>
<div class="table-responsive shop-table">
<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents table table-bordered" cellspacing="0">
	<thead>
		<tr>
			<th class="product-remove">&nbsp;</th>
			<th class="product-name"><?php esc_html_e( 'Product', 'royal' ); ?></th>
			<th class="product-price"><?php esc_html_e( 'Price', 'royal' ); ?></th>
			<th class="product-quantity"><?php esc_html_e( 'Quantity', 'royal' ); ?></th>
			<th class="product-subtotal"><?php esc_html_e( 'Total', 'royal' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php do_action( 'woocommerce_before_cart_contents' ); ?>

		<?php
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
				?>
				<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

					<td class="product-remove">
						<?php
							// @codingStandardsIgnoreLine
							echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
								'<a href="%s" class="remove btn remove-item" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
								esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
								esc_html__( 'Remove this item', 'royal' ),
								esc_attr( $product_id ),
								esc_attr( $_product->get_sku() )
							), $cart_item_key );
						?>
					</td>

					<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'royal' ); ?>">
                        <div class="product-thumbnail">
                            <?php
                                    $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

                                    if ( ! $product_permalink ) {
                                            echo wp_kses_post( $thumbnail );
                                    } else {
                                            printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), wp_kses_post( $thumbnail ) );
									}
                            ?>
                        </div>
                            <div class="cart-item-details">
                                <?php
                                    if ( ! $product_permalink  ){
                                        echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) );
                                    } else {
                                        echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', $_product->get_permalink(), $_product->get_name() ), $cart_item, $cart_item_key ) );
                                    }

                                    // Meta data
                                    echo wc_get_formatted_cart_item_data( $cart_item );

	                        		// Backorder notification
	                        		if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
										echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'royal' ) . '</p>' ) );
									}
                                ?>
                                <div class="mobile-price">
                            		<?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );?>
                           		</div>
                            </div>

					</td>

					<td class="product-price" data-title="<?php esc_attr_e( 'Price', 'royal' ); ?>">
						<?php
							echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
						?>
					</td>

					<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'royal' ); ?>">
						<?php
							if ( $_product->is_sold_individually() ) {
								$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
							} else {
								$product_quantity = woocommerce_quantity_input( array(
									'input_name'  	=> "cart[{$cart_item_key}][qty]",
									'input_value' 	=> $cart_item['quantity'],
									'max_value'   	=> $_product->get_max_purchase_quantity(),
									'min_value'   	=> '0',
									'product_name'  => $_product->get_name(),
								), $_product, false );
							}

							echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
						?>
					</td>


					<td class="product-subtotal" data-title="<?php esc_attr_e( 'Total', 'royal' ); ?>">
						<?php
							echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
						?>
					</td>
				</tr>
				<?php
			}
		}

		do_action( 'woocommerce_cart_contents' );
		?>

		<?php do_action( 'woocommerce_after_cart_contents' ); ?>
	</tbody>
</table>
</div>
<div class="actions">

	<button type="submit" class="button btn gray big" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'royal' ); ?>"><?php esc_html_e( 'Update cart', 'royal' ); ?></button>

	<?php do_action( 'woocommerce_cart_actions' ); ?>

	<?php wp_nonce_field( 'woocommerce-cart' ); ?>
</div>

<?php do_action( 'woocommerce_after_cart_table' ); ?>



</form>



<div class="row">
	<div class="col-md-6">
		<?php if ( wc_coupons_enabled() ) { ?>
			<h3 class="underlined"><?php esc_html_e( 'Have a coupon?', 'royal' ); ?></h3>
			<form class="checkout_coupon" method="post">
				<div class="coupon">

					<input type="text" name="coupon_code" class="input-text pull-left col-lg-8 col-md-7" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'royal' ); ?>" /> <input type="submit" class="btn pull-right big" name="apply_coupon" value="<?php esc_attr_e( 'Apply Coupon', 'royal' ); ?>" />

					<?php do_action('woocommerce_cart_coupon'); ?>

				</div>
			</form>
		<?php } ?>
		<div class="space3"></div>
	</div>

	<div class="col-md-6">
		<div class="row">
			<div class="col-xs-12">
				<div class="cart-collaterals">

					<?php
						/**
						 * Cart collaterals hook.
						 *
						 * @hooked woocommerce_cross_sell_display
						 * @hooked woocommerce_cart_totals - 10
						 */
						do_action( 'woocommerce_cart_collaterals' );
					?>

				</div>
			</div>
		</div>
	</div>
</div>


<?php woocommerce_cross_sell_display(); ?>

<?php do_action( 'woocommerce_after_cart' ); ?>
