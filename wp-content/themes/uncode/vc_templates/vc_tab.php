<?php
global $history_tab;
/** @var $this WPBakeryShortCode_VC_Tab */
$output = $title = $tab_id = $no_margin = $first = $slug = '';
extract( shortcode_atts( array(
	'title' => '',
	'tab_id' => 0,
	'slug' => '',
	'no_margin' => '',
	'first' => ''
), $atts ) );

$history_tag = $history_tab !== '' ? 'data-id' : 'id';

if ($first) $first = ' in active';
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'tab-pane fade' . $first, $this->settings['base'], $atts );
if ($no_margin === 'yes') $css_class .= ' remove-top-margin';
$tab_id = empty($tab_id) ? sanitize_title( $title ) : $tab_id;
$hash = $slug !== '' ? sanitize_title( $slug ) : 'tab-' . $tab_id;
$output .= '<div ' . esc_attr( $history_tag ) . '="' . esc_attr( $hash ) .'" class="' . esc_attr(trim($css_class)) . '">';
$output .= ($content=='' || $content==' ') ? esc_html__("Empty tab. Edit page to add content here.", "uncode") : "\n\t\t\t\t" . $content;
$output .= '</div> ';

echo uncode_remove_wpautop($output);