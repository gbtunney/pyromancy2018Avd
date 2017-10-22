<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php

	global $limit_content_width, $style, $show_body_title;

	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	ob_start();
		echo get_the_password_form();
		$the_content = ob_get_clean();
		echo uncode_get_row_template($the_content, ' limit-width', '', ot_get_option('_uncode_general_style'), '', 'quad', true, 'quad', 'style="max-width:801px; margin: auto"');
	 	return;
	 }

	$_uncode_thumb_layout = ot_get_option('_uncode_product_image_layout');
	$_uncode_thumb_layout = get_post_meta($post->ID, '_uncode_product_image_layout', 1) !== '' ? get_post_meta($post->ID, '_uncode_product_image_layout', 1) : $_uncode_thumb_layout;

	$sticky_col = ot_get_option('_uncode_product_sticky_desc');
	$sticky_col = get_post_meta($post->ID, '_uncode_product_sticky_desc', 1) !== '' ? get_post_meta($post->ID, '_uncode_product_sticky_desc', 1) : $sticky_col;

	$col_size = ot_get_option('_uncode_product_media_size') == '' ? 6 : ot_get_option('_uncode_product_media_size');
	$col_size = ( get_post_meta($post->ID, '_uncode_product_media_size', 1) !== '' && get_post_meta($post->ID, '_uncode_product_media_size', 1) != 0 ) ? get_post_meta($post->ID, '_uncode_product_media_size', 1) : $col_size;

	$col_class = $_uncode_thumb_layout === 'stack' && $sticky_col === 'on' ? ' sticky-element sticky-sidebar' : '';

?>


<div class="row-container">
	<div class="row row-parent col-std-gutter double-top-padding double-bottom-padding <?php echo esc_attr($limit_content_width); ?>">
		<div class="row-inner">
			<div class="col-lg-<?php echo intval($col_size); ?>">
				<div class="uncol">
					<div class="uncoltable">
						<div class="uncell">
							<div class="uncont">
								<?php
								do_action( 'woocommerce_before_single_product_summary' );
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-<?php echo ( 12 - intval($col_size) ); ?>">
				<div class="uncol<?php echo esc_attr($col_class); ?>">
					<div class="uncoltable">
						<div class="uncell">
							<div class="uncont">
								<?php
								if ($show_body_title === false) remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
								do_action( 'woocommerce_single_product_summary' );
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
	global $limit_content_width;
	ob_start();
	woocommerce_output_product_data_tabs();
	woocommerce_upsell_display();
	$the_content = ob_get_clean();
	echo uncode_get_row_template($the_content, '', '', '', '', false, false, false);

	ob_start();
	woocommerce_output_related_products();
	$the_content = ob_get_clean();

	$page_content_block_after = (isset($metabox_data['_uncode_specific_content_block_after'][0])) ? $metabox_data['_uncode_specific_content_block_after'][0] : '';
	if ($page_content_block_after === '') {
		$generic_content_block_after = ot_get_option('_uncode_product_content_block_after');
		$content_block_after = $generic_content_block_after !== '' ? $generic_content_block_after : false;
	} else {
		$content_block_after = $page_content_block_after !== 'none' ? $page_content_block_after : false;
	}

	if ($content_block_after === false)
		echo uncode_get_row_template($the_content, '', $limit_content_width, '', ' row-related', false, true, false);