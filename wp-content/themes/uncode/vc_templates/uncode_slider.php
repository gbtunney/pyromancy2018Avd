<?php
$style = $is_header = $slider_type = $slider_interval = $slider_navspeed = $slider_loop = $slider_hide_arrows = $slider_hide_dots = $slider_dot_position = $slider_dot_width = $column_width_use_pixel = $slider_width_percent = $silder_width_pixel = $h_padding = $el_class = $limit_content = $top_padding = $bottom_padding = $h_padding = $slider_height = $output = $internal_width = $dots_class = '';
extract(shortcode_atts(array(
	'style' => '',
	'is_header' => '',
	'slider_type' => '',
	'slider_interval' => 0,
	'slider_navspeed' => 400,
	'slider_loop' => '',
	'slider_hide_arrows' => '',
	'slider_hide_dots' => '',
	'slider_dot_position' => '',
	'slider_dot_width' => '',
	'column_width_use_pixel' => '',
	'slider_width_percent' => '',
	'silder_width_pixel' => '',
	'h_padding' => 2,
	'el_class' => '',
	'limit_content' => '',
	'top_padding' => '',
	'bottom_padding' => '',
	'h_padding' => '',
	'slider_height' => '',
) , $atts));

/** send variable to inner columns **/
if ($limit_content === 'yes') {
	$content = str_replace('[vc_row_inner','[vc_row_inner limit_content="yes" override_padding="yes" top_padding="'.$top_padding.'" bottom_padding="'.$bottom_padding.'" ', $content);
} else {
	$content = str_replace('[vc_row_inner','[vc_row_inner override_padding="yes" top_padding="'.$top_padding.'" bottom_padding="'.$bottom_padding.'" h_padding="'.$h_padding.'" ', $content);
}

if ($slider_type === 'fade') $slider_type = ' data-fade="true"';

if ((int)$slider_interval === 0 || $slider_interval === '') {
	$slider_autoplay = 'false';
	$slider_timeout = '';
} else {
	$slider_autoplay = 'true';
	$slider_timeout = ' data-timeout="' . esc_attr( $slider_interval ) . '"';
}

$el_id = 'uslider_' . rand();

$el_class = $this->getExtraClass($el_class);
if ($el_class !== '') $el_class = $el_class . ' ';
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $el_class . 'uncode-slider ' , $this->settings['base'], $atts );

$style = $is_header !== 'yes' ? ' style-'.$style : '';

$data_nav = $slider_hide_arrows === '' ? 'true' : 'false';
if ( $slider_hide_dots === '' ) {
	$data_dots = 'true';
	$limit_width = $slider_dot_width !== '' ? 'true' : 'false';
} else {
	$data_dots = 'false';
	$limit_width = 'false';
}

$slider_dot_position = $slider_dot_position === '' ? 'center' : $slider_dot_position;

if ($column_width_use_pixel === 'yes' && $silder_width_pixel !== '') {
	$silder_width_pixel = preg_replace("/[^0-9,.]/", "", $silder_width_pixel);
	$silder_width_pixel = 12 * round(($silder_width_pixel) / 12);
	$internal_width = ' style="max-width:' . esc_attr( $silder_width_pixel ) . 'px;"';
} else {
	if (!empty($slider_width_percent) && $slider_width_percent !== '100')
	{
	  $internal_width = ' style="max-width:' . esc_attr( $slider_width_percent ) . '%;"';
	}
}

$dots_classes = array();
if ($slider_dot_width !== '') $dots_classes[] = 'limit-width';

switch ($h_padding) {
	case 0:
		$dots_classes[] = 'no-h-padding';
		break;
	case 1:
		$dots_classes[] = 'one-h-padding';
		break;
	case 3:
		$dots_classes[] = 'double-h-padding';
		break;
	case 4:
		$dots_classes[] = 'triple-h-padding';
		break;
	case 5:
		$dots_classes[] = 'quad-h-padding';
		break;
	case 6:
		$dots_classes[] = 'penta-h-padding';
		break;
	case 7:
		$dots_classes[] = 'exa-h-padding';
		break;
	case 2:
	default:
		$dots_classes[] = 'single-h-padding';
		break;
}

$output .= '<div class="owl-carousel-wrapper'.$style.'">';
$output .= '<div class="'.esc_attr($css_class).'owl-carousel-container owl-dots-inside owl-dots-align-'.esc_attr($slider_dot_position).'">';
$output .= '<div id="'.esc_attr($el_id).'" class="owl-carousel owl-element owl-dots-inside owl-height-'.esc_attr($slider_height).'"'.$slider_type.' data-loop="'.($slider_loop === 'yes' ? "true" : "false").'" data-autoheight="'.($slider_height === 'auto' ? 'true' : 'false').'"'.($is_header === 'yes' ? ' data-nav="' . esc_attr( $data_nav ) . '"' : ' data-dotsmobile="true"').' data-dots="' . esc_attr( $data_dots ) . '" data-navspeed="' . esc_attr( $slider_navspeed ) . '" data-autoplay="' . esc_attr( $slider_autoplay ) . '"' . $slider_timeout . ' data-lg="1" data-md="1" data-sm="1" data-limit-width="' . esc_attr( $limit_width ) . '">';
$output .= $content;
$output .= '</div>';
if ( $slider_hide_dots === '' && !empty($dots_classes) )
	$dots_classes[] = 'owl-dots-classes';
	$output .= '<div class="' . esc_attr(trim(implode( ' ', $dots_classes ))) . '"' . $internal_width . '></div>';
$output .= '</div>';
$output .= '</div>';

echo uncode_remove_wpautop($output);