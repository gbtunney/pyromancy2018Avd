<?php
/**
 * Additional Information tab
 *
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

$heading = apply_filters( 'woocommerce_product_additional_information_heading', esc_html__( 'Additional information', 'woocommerce' ) );

?>

<div class="product-tab">
<?php if ( $heading ): ?>
	<h5 class="product-tab-title"><?php echo esc_html($heading); ?></h5>
<?php endif; ?>

<?php do_action( 'woocommerce_product_additional_information', $product ); ?>
</div>
