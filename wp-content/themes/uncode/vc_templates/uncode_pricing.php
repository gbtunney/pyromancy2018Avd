<?php
$el_class = $title = $price = $body = $output = $price_color = $col_elements = $most = $align = $button = $hover_fx = $css_animation = $animation_delay = $animation_speed = $title_html = $price_html = $el_color = $text_color = '';
extract(shortcode_atts(array(
	'el_class' => '',
	'title' => 'Title|Subtitle',
	'price' => '$50|per month',
	'body' => '',
	'price_color' => '',
	'col_elements' => '',
	'most' => '',
	'align' => '',
	'button' => '',
	'hover_fx' => '',
	'css_animation' => '',
	'animation_delay' => '',
	'animation_speed' => '',
) , $atts));

$lists_array = explode("\n", $body);
if ($price_color !== '') {
	if ($col_elements === '') {
		$text_color = ' text-' . $price_color . '-color';
		$button_color = $price_color;
	} else {
		$el_color = ' style-' .$price_color .'-bg style-dark';
		$button_color = 'default';
	}
} else $button_color = 'default';

$title_array = explode("|", $title);
$title_html = '<h3 class="' . esc_attr( trim($text_color) ) . '">'.$title_array[0].'</h3>';
if (isset($title_array[1])) $title_html .= '<span class="plan-title-data' . esc_attr( $text_color ) . '">' . wp_kses_post( $title_array[1] ) . '</span>';

$price_array = explode("|", $price);
$price_html = '<span class="price' . esc_attr( $text_color ) . '">' . wp_kses_post( $price_array[0] ) . '</span>';
if (isset($price_array[1])) $price_html .= '<span>'.$price_array[1].'</span>';

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'uncode-pricing', $this->settings['base'], $atts );

$div_data = array();
if ($css_animation !== '') {
	$css_class .= ' ' . $css_animation . ' animate_when_almost_visible';
	if ($animation_delay !== '') $div_data['data-delay'] = $animation_delay;
	if ($animation_speed !== '') $div_data['data-speed'] = $animation_speed;
}

$div_data_attributes = array_map(function ($v, $k) { return $k . '="' . $v . '"'; }, $div_data, array_keys($div_data));

$output .= '<div class="uncode-wrapper ' . esc_attr( $css_class ) . '" ' . implode( ' ', $div_data_attributes ) . '>';
$output .= '<div class="plan'.(($align ==='') ? ' plan-centered' : '' ).(($most ==='yes') ? ' plan-most-popular' : '' ).(($col_elements ==='tb') ? ' plan-colored' : '' ).'">';
$output .= '<div class="plan-container">';
$output .= '<div class="plan-title' . esc_attr( $el_color ) . '">';
$output .= $title_html;
$output .= '</div>';
$output .= '<div class="plan-price">';
$output .= $price_html;
$output .= '</div>';
$output .= '<div class="plan-list">';
$output .= '<ul class="item-list">';
if (count($lists_array) > 0) {
	foreach ($lists_array as $key => $value) {
		$list_array = explode("|", $value);
		$list_html = '<strong>'.$list_array[0].'</strong>';
		if (isset($list_array[1])) $list_html .= ' ' . $list_array[1];
		$output .= '<li>'.$list_html.'</li>';
	}
}
$output .= '</ul>';
if ($button !== '') {

	$button_class = '';

	// Hover effect
	$hover_fx = $hover_fx=='' ? ot_get_option('_uncode_button_hover') : $hover_fx;

	// Outlined and flat classes
	if ( $hover_fx == 'full-colored' ) {
		$button_class = ' btn-flat';
	}

	$button = ( $button == '||' ) ? '' : $button;
	$button = vc_build_link( $button );
	$a_href = $button['url'];
	$a_title = $button['title'];
	$a_target = $button['target'];
	if ($a_title !== '') $output .= '<div class="plan-button'.esc_attr($el_color).'"><a href="'.esc_url($a_href).'" class="btn btn-' . esc_attr($button_color) . esc_attr($button_class) . '" target="'.esc_attr($a_target).'">'.$a_title.'</a></div>';
}
$output .= '</div>';
$output .= '</div>';
$output .= '</div>';
$output .= '</div>';

echo uncode_remove_wpautop( wp_kses_post( $output ) );