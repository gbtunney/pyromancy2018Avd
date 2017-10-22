<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
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
	exit;
}

global $post, $woocommerce, $product;

$shop_single = wc_get_image_size( 'shop_single' );
$shop_thumbnail = wc_get_image_size( 'shop_thumbnail' );
$crop = false;
if (isset($shop_single['crop']) && $shop_single['crop'] === 1) {
	$crop = true;
	$thumb_ratio = $shop_single['width'] / $shop_single['height'];
}
$th_crop = false;
if (isset($shop_thumbnail['crop']) && $shop_thumbnail['crop'] === 1) {
	$th_crop = true;
	$small_ratio = $shop_thumbnail['width'] / $shop_thumbnail['height'];
}

$_uncode_thumb_layout = ot_get_option('_uncode_product_image_layout');
$_uncode_thumb_layout = get_post_meta($post->ID, '_uncode_product_image_layout', 1) !== '' ? get_post_meta($post->ID, '_uncode_product_image_layout', 1) : $_uncode_thumb_layout;

$_uncode_product_thumb_cols = ot_get_option('_uncode_product_thumb_cols');
$_uncode_product_thumb_cols = get_post_meta($post->ID, '_uncode_thumb_cols', 1) !== '' ? get_post_meta($post->ID, '_uncode_thumb_cols', 1) : $_uncode_product_thumb_cols;

$col_size = ot_get_option('_uncode_product_media_size') == '' ? 6 : ot_get_option('_uncode_product_media_size');

$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', $_uncode_product_thumb_cols == '' ? 3 : $_uncode_product_thumb_cols );
$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
$image_title       = get_post_field( 'post_excerpt', $post_thumbnail_id );
$placeholder       = has_post_thumbnail() ? 'with-images' : 'without-images';
$wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
	'woocommerce-product-gallery',
	'woocommerce-product-gallery--' . $placeholder,
	'woocommerce-product-gallery--columns-' . absint( $columns ),
	'woocommerce-layout-images-' . $_uncode_thumb_layout,
	'images',
) );

?>
	<?php
		if ( has_post_thumbnail() ) {
			$media_id = get_post_thumbnail_id();
			$image_title = esc_attr( get_the_title( $media_id ) );
			$image_attributes = uncode_get_media_info($media_id);
			$image_metavalues = unserialize($image_attributes->metadata);
			if ($image_attributes->post_mime_type === 'image/gif' || $image_attributes->post_mime_type === 'image/url') $crop = false;
			$image_resized = uncode_resize_image($image_attributes->id, $image_attributes->guid, $image_attributes->path, $image_metavalues['width'], $image_metavalues['height'], $col_size, ($crop ? $col_size / $thumb_ratio : null), $crop);
			$small_image_resized = uncode_resize_image($image_attributes->id, $image_attributes->guid, $image_attributes->path, $image_metavalues['width'], $image_metavalues['height'], 2, ($th_crop ? 2 / $small_ratio : null), $th_crop);
			global $adaptive_images, $adaptive_images_async, $adaptive_images_async_blur;

			$image_link = $image_attributes->guid;

			$attributes = array(
				'src'					  => $image_resized['url'],
				//'title'                   => $image_title,
				'data-src'                => $image_link,
	            'data-caption'            => $image_title,
				'data-large_image'        => $image_link,
				'data-large_image_width'  => $image_metavalues['width'],
				'data-large_image_height' => $image_metavalues['height'],
			);
			if ($adaptive_images === 'on' && $adaptive_images_async === 'on') {
				$attributes['class'] = 'adaptive-async'.(($adaptive_images_async_blur === 'on') ? ' async-blurred' : '');
				$attributes['data-uniqueid'] = $media_id.'-'.big_rand();
				$attributes['data-guid'] = $image_attributes->guid;
				$attributes['data-path'] = $image_attributes->path;
				$attributes['data-width'] = $image_metavalues['width'];
				$attributes['data-height'] = $image_metavalues['height'];
				$attributes['data-singlew'] = $col_size;
				$attributes['data-singleh'] = ($crop ? $col_size / $thumb_ratio : null);
				$attributes['data-crop'] = $crop;
			}

			global $gallery_id;
			$gallery_id = big_rand();

			$html = '<div data-thumb="' . esc_url( $small_image_resized['url'] ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $image_link ) . '" itemprop="image" title="' . esc_attr( $image_title ) . '" class="woocommerce-main-image" data-options="thumbnail: \''.$image_resized['url'].'\'" data-lbox="ilightbox_gallery-' . $gallery_id . '">';
			$html .= get_the_post_thumbnail( $post->ID, 'shop_single', $attributes );
			$html .= '</a></div>';

			echo '<div id="woocommerce-product-single-plchold">' . get_the_post_thumbnail( $post->ID, 'shop_single', $attributes ) . '</div>';

		} else {

			$html  = '<div class="woocommerce-product-gallery__image--placeholder">';
			$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
			$html .= '</div>';

		}

	?>
<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" style="opacity: 0; transition: opacity .05s ease-in-out;">
	<figure class="woocommerce-product-gallery__wrapper">

	<?php echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, get_post_thumbnail_id( $post->ID ) ); ?>

	<?php do_action( 'woocommerce_product_thumbnails' ); ?>

	</figure>
</div>