<?php
/**
 * Single Product Thumbnails
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-thumbnails.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product, $woocommerce, $gallery_id;

$_uncode_thumb_layout = ot_get_option('_uncode_product_image_layout');
$_uncode_thumb_layout = get_post_meta($post->ID, '_uncode_product_image_layout', 1) !== '' ? get_post_meta($post->ID, '_uncode_product_image_layout', 1) : $_uncode_thumb_layout;

$col_size = ot_get_option('_uncode_product_media_size') == '' ? 6 : ot_get_option('_uncode_product_media_size');

$attachment_ids = $product->get_gallery_image_ids();

if ( $_uncode_thumb_layout == 'stack' ) {
	$th_size = 'shop_single';
	$th_val = $col_size;
} else {
	if ( uncode_woocommerce_single_product_slider_enabled(true) )
		$th_size = 'shop_single';
	else
		$th_size = 'shop_thumbnail';
	$th_val = 2;
}

$shop_thumbnail = wc_get_image_size( $th_size );
$crop = false;
if (isset($shop_thumbnail['crop']) && $shop_thumbnail['crop'] === 1) {
	$crop = true;
	$thumb_ratio = $shop_thumbnail['width'] / $shop_thumbnail['height'];
}

$th_shop_thumbnail = wc_get_image_size( 'shop_thumbnail' );
$th_crop = false;
if (isset($th_shop_thumbnail['crop']) && $th_shop_thumbnail['crop'] === 1) {
	$th_crop = true;
	$small_ratio = $th_shop_thumbnail['width'] / $th_shop_thumbnail['height'];
}

if ( $attachment_ids ) {
	if ( !uncode_woocommerce_single_product_slider_enabled(true) && $_uncode_thumb_layout === '' )
		echo '<div class="thumbnails">';

		foreach ( $attachment_ids as $attachment_id ) {

			$classes = array( 'zoom' );

			$image_link = wp_get_attachment_url( $attachment_id );

			if ( ! $image_link )
				continue;

			$image_attributes = uncode_get_media_info($attachment_id);
			$image_metavalues = unserialize($image_attributes->metadata);
			if ($image_attributes->post_mime_type === 'image/gif' || $image_attributes->post_mime_type === 'image/url') $crop = false;
			$image_resized = uncode_resize_image($image_attributes->id, $image_attributes->guid, $image_attributes->path, $image_metavalues['width'], $image_metavalues['height'], $th_val, ($crop ? $th_val / $thumb_ratio : null), $crop);
			$small_image_resized = uncode_resize_image($image_attributes->id, $image_attributes->guid, $image_attributes->path, $image_metavalues['width'], $image_metavalues['height'], 2, ($th_crop ? 2 / $small_ratio : null), $th_crop);
			global $adaptive_images, $adaptive_images_async, $adaptive_images_async_blur;
			$media_class = '';
			$media_data = '';
			if ($adaptive_images === 'on' && $adaptive_images_async === 'on') {
				$media_class = ' adaptive-async'.(($adaptive_images_async_blur === 'on') ? ' async-blurred' : '');
				$media_data = ' data-uniqueid="'.$attachment_id.'-'.big_rand().'" data-guid="'.$image_attributes->guid.'" data-path="'.$image_attributes->path.'" data-width="'.$image_metavalues['width'].'" data-height="'.$image_metavalues['height'].'" data-singlew="' . $th_val . '" data-singleh="'.($crop ? $th_val / $thumb_ratio : null).'" data-crop="'.$crop.'"';
			}
			$image_link = $image_attributes->guid;
			//$image = '<img'.$media_class.' src="'.$image_resized['url'].'" width="'.$image_resized['width'].'" height="'.$image_resized['height'].'" alt=""'.$media_data.' />';

			$image_class = esc_attr( implode( ' ', $classes ) );
			$image_title = esc_attr( get_the_title( $attachment_id ) );

			$attributes = array(
				'src'					  => $image_resized['url'],
				'title'                   => $image_title,
				'data-caption'            => esc_attr( get_post_field( 'post_excerpt', $attachment_id ) ),
				'data-src'                => $image_link,
				'data-large_image'        => $image_link,
				'data-large_image_width'  => $image_metavalues['width'],
				'data-large_image_height' => $image_metavalues['height'],
				'sizes'					  => 'false',
			);

			if ($adaptive_images === 'on' && $adaptive_images_async === 'on') {
				$attributes['class'] = 'adaptive-async'.(($adaptive_images_async_blur === 'on') ? ' async-blurred' : '');
				$attributes['data-uniqueid'] = $attachment_id.'-'.big_rand();
				$attributes['data-guid'] = $image_attributes->guid;
				$attributes['data-path'] = $image_attributes->path;
				$attributes['data-width'] = $image_metavalues['width'];
				$attributes['data-height'] = $image_metavalues['height'];
				$attributes['data-singlew'] = $col_size;
				$attributes['data-singleh'] = ($crop ? $col_size / $thumb_ratio : null);
				$attributes['data-crop'] = $crop;
			}

			$html  = '<div data-thumb="' . esc_url( $small_image_resized['url'] ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $image_link ) . '" class="' . esc_attr( $image_class ) . '" title="' . esc_attr( $image_title ) . '" data-options="thumbnail: \''.$small_image_resized['url'].'\'" data-lbox="ilightbox_gallery-' . $gallery_id . '">';
			$html .= wp_get_attachment_image( $attachment_id, $th_size, false, $attributes );
	 		$html .= '</a></div>';

			echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $attachment_id );

		}

	if ( !uncode_woocommerce_single_product_slider_enabled(true) && $_uncode_thumb_layout === '' )
		echo '</div>';//.thumbnails
}
