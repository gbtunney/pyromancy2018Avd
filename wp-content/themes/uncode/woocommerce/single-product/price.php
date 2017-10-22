<?php
/**
 * Single Product Price, including microdata for SEO
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version   3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

?>
<div class="price-container">

	<span class="price"><?php echo wp_kses_post($product->get_price_html()); ?></span>

</div>
