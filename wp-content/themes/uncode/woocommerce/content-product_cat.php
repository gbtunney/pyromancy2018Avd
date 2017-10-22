<?php
/**
 * The template for displaying product category thumbnails within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product_cat.php.
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
 * @version 2.6.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


global $wp_query;
$vars = $wp_query->query_vars;
$single_post_width = (isset($vars['single_post_width']) && $vars['single_post_width'] !== '') ? $vars['single_post_width'] : 4;

$item_thumb_id = '';

$stylesArray = array(
	'light',
	'dark'
);
$general_style = ot_get_option('_uncode_general_style');

$overlay_style = $stylesArray[!array_search($general_style, $stylesArray) ];
$overlay_back_color = 'style-' . $overlay_style . '-bg';

$item_thumb_id = get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true  );
if ($item_thumb_id == false) $item_thumb_id = '';

$category_title = $category->name;
if ( $category->count > 0 ) $category_title .= apply_filters( 'woocommerce_subcategory_count_html', ' <span class="count">(' . $category->count . ')</span>', $category );


$block_classes = array(
	'tmb'
);

$block_classes[] = 'tmb-' . $general_style;
$block_classes[] = 'tmb-content-center';
$block_classes[] = 'tmb-no-bg';
$block_classes[] = 'tmb-woocommerce';
$block_classes[] = 'tmb-overlay-anim';
$block_classes[] = 'tmb-overlay-text-anim';
$block_classes[] = 'tmb-iso-w' . $single_post_width;
$block_classes[] = implode(' ', get_post_class());

$media_items = array();
$block_data = array();
$tmb_data = array();
$title_classes = array('h6');
$layout = array();

$block_data['content'] = get_the_content();
$block_data['classes'] = $block_classes;
$block_data['tmb_data'] = $tmb_data;
$block_data['id'] = $category->term_id;
$block_data['media_id'] = $item_thumb_id;
$block_data['single_title'] = $category_title;
$block_data['single_width'] = $single_post_width;
$block_data['single_text'] = 'under';
$block_data['single_style'] = $general_style;
$block_data['overlay_style'] = $overlay_style;
$block_data['overlay_color'] = $overlay_back_color;
$block_data['overlay_opacity'] = '20';
$block_data['text_padding'] = 'half-block-padding';
$block_data['title_classes'] = $title_classes;
$block_data['link'] = get_term_link( $category, 'product_cat' );
$block_data['text_length'] = 300;
$block_data['product'] = true;


if ($item_thumb_id !== '') $layout['media'] = array();
else $layout['media'] = array('placeholder');
$layout['title'] = array();


echo uncode_create_single_block($block_data, rand() , 'masonry', $layout, false, 'no');