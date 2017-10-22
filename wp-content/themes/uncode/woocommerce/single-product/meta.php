<?php
/**
 * Single Product Meta
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

?>
<hr />
<div class="product_meta">
	<p>
	<?php do_action( 'woocommerce_product_meta_start' ); ?>

	<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>

		<span class="sku_wrapper detail-container"><span class="detail-label"><?php esc_html_e( 'SKU', 'woocommerce' ); ?></span> <span class="sku" itemprop="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'woocommerce' ); ?></span></span>

	<?php endif; ?>

	<?php
		$product_categories = wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in detail-container">' . _n( '<span class="detail-label">' . esc_html__('Category','woocommerce') . '</span>', '<span class="detail-label">' . esc_html__('Categories','woocommerce') . '</span>', count( $product->get_category_ids() ), 'woocommerce' ) . ' ', '</span>' );
		echo wp_kses_post($product_categories);
	?>

	<?php
		$product_tags = wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as detail-container">' . _n( '<span class="detail-label">' . esc_html__('Tag','woocommerce') . '</span>', '<span class="detail-label">' . esc_html__('Tags','woocommerce') . '</span>', count( $product->get_tag_ids() ), 'woocommerce' ) . ' ', '</span>' );
		echo wp_kses_post($product_tags);
	?>

	<?php do_action( 'woocommerce_product_meta_end' ); ?>
	</p>
</div>
