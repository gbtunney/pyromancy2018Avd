<?php
global $row_cols_md_counter, $row_cols_sm_counter;

$el_class = $back_image = $back_repeat = $back_attachment = $back_position = $back_size = $back_color = $overlay_color = $overlay_alpha = $parallax = $output = $row_style = $background_div = $row_inline_style = $desktop_visibility = $medium_visibility = $mobile_visibility = '';

extract(shortcode_atts(array(
	'el_class' => '',
	'back_image' => '',
	'back_repeat' => '',
	'back_attachment' => 'scroll',
	'back_position' => 'center center',
	'back_size' => '',
	'back_color' => '',
	'overlay_color' => '',
	'overlay_alpha' => '',
	'parallax' => '',
	'desktop_visibility' => '',
  	'medium_visibility' => '',
  	'mobile_visibility' => '',
	'is_header' => '',
) , $atts));

$row_classes = array(
	'row'
);
$row_cont_classes = array();

$row_cont_classes[] = $this->getExtraClass($el_class);

if (!empty($back_color)) {
	$row_cont_classes[] = 'style-' . $back_color . '-bg';
}

/** BEGIN - background construction **/
if (!empty($back_image) || $overlay_color !== '') {

	if ($parallax === 'yes') {
		$back_attachment = '';
		$back_size = 'cover';
	} else {
		if ($back_size === '') $back_size = 'cover';
	}

	if ($back_repeat === '') $back_repeat = 'no-repeat';

	$back_array = array (
		'background-image' => $back_image,
		'background-color' => $back_color,
		'background-repeat' => $back_repeat,
		'background-position' => $back_position,
		'background-size' => $back_size,
		'background-attachment' => $back_attachment,
	);

	$back_result_array = uncode_get_back_html($back_array, $overlay_color, $overlay_alpha, '', 'row');
	$background_div = $back_result_array['back_html'];
}

/** END - background construction **/

$row_classes[] = 'no-top-padding';
$row_classes[] = 'no-bottom-padding';
$row_classes[] = 'no-h-padding';

$boxed = ot_get_option('_uncode_boxed');

if ($boxed !== 'on')
	$row_classes[] = 'full-width';

$row_classes[] = 'row-parent';
$row_cont_classes[] = 'row-container';
if ($parallax === 'yes') $row_cont_classes[] = 'with-parallax';
if ($is_header === 'yes') $row_classes[] = 'row-header';

if ($desktop_visibility === 'yes') $row_cont_classes[] = 'desktop-hidden';
if ($medium_visibility === 'yes') $row_cont_classes[] = 'tablet-hidden';
if ($mobile_visibility === 'yes') $row_cont_classes[] = 'mobile-hidden';

global $uncode_row_parent;
$uncode_row_parent = 12;
$css_class = preg_replace( '/\s+/', ' ', apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, implode( ' ', array_filter( $row_cont_classes ) ), $this->settings['base'], $atts ) );
$output.= '<section data-parent="true" class="' . esc_attr(trim($css_class)) . '"' . $row_style . '>';
$output.= $background_div;
$output.= '<div class="' . esc_attr(trim(implode(' ', $row_classes))) . '"' . $row_inline_style . '>';
$output.= $content;
echo uncode_remove_wpautop($output);
$output = '';
$output.= '</div>';
$output.= '</section>';

echo uncode_remove_wpautop($output);
