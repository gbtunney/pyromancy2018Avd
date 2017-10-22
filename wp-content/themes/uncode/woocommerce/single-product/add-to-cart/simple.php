<?php
/**
 * Simple product add to cart
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}

?>

<?php
	// Availability
	$availability      = $product->get_availability();
	$availability_html = empty( $availability['availability'] ) ? '' : '<p class="stock ' . esc_attr( $availability['class'] ) . '">' . esc_html( $availability['availability'] ) . '</p>';

	echo wc_get_stock_html( $product );
?>

<?php if ( $product->is_in_stock() ) : ?>

	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

	<form class="cart" method="post" enctype='multipart/form-data'>
	 	<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

	 	<?php do_action( 'woocommerce_before_add_to_cart_quantity' ); ?>

	 	<?php
			woocommerce_quantity_input( array(
				'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
				'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
				'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : $product->get_min_purchase_quantity(),
			) );
	 	?>

	 	<?php do_action( 'woocommerce_after_add_to_cart_quantity' ); ?>

	 	<?php
	 	$link = array(
			'url'   => '',
			'label' => '',
			'class' => ''
		);
	 	if ( $product->is_purchasable() ) {
			$link['url']    = apply_filters( 'add_to_cart_url', esc_url( $product->add_to_cart_url() ) );
			$link['label']  = $product->single_add_to_cart_text();
			$ajax_add_to_cart = get_option('woocommerce_enable_ajax_add_to_cart') == 'yes' ? ' ajax_add_to_cart' : '';
			$link['class']  = apply_filters( 'add_to_cart_class', 'add_to_cart_button' . $ajax_add_to_cart );
		} else {
			$link['url']    = apply_filters( 'not_purchasable_url', get_permalink( $product->get_id() ) );
			$link['label']  = apply_filters( 'not_purchasable_text', esc_html__( 'Read More', 'woocommerce' ) );
		}
	 	// Display the submit button.
    echo sprintf( '<button type="submit" name="add-to-cart" data-product_id="%s" data-product_sku="%s" data-quantity="1" class="%s button alt btn btn-default product_type_simple ' . uncode_btn_style() . '" value="%s">%s</button>', esc_attr( $product->get_id() ), esc_attr( $product->get_sku() ), esc_attr( $link['class'] ), esc_attr( $product->get_id() ), esc_html( $link['label'] ) );
		do_action( 'woocommerce_after_add_to_cart_button' ); ?>

	</form>

	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php endif; ?>
