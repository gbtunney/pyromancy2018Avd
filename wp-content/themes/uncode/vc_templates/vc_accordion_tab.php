<?php
global $history_tab;
$output = $title = $id = $active = $tab_id = $slug = '';

extract(shortcode_atts(array(
	'title' => esc_html__("Section", "uncode"),
	'id' => '',
	'active' => '',
	'tab_id' => '',
	'slug' => ''
), $atts));

if ( $tab_id === '' )
	$create_id = preg_replace('/[^A-Za-z0-9\-]/', '', sanitize_title($title)) . '-' . big_rand();
else
	$create_id = $tab_id;

$hash = $slug !== '' ? sanitize_title( $slug ) : $create_id;

$history_rend = $history_tab !== '' ? ' data-tab-history="true" data-tab-history-changer="push" data-tab-history-update-url="true"' : '';
$history_tag = $history_tab !== '' ? 'data-id' : 'id';

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'panel panel-default', $this->settings['base'], $atts );
$output .= '<div class="'.esc_attr(trim($css_class)).'">';
$output .= '<div class="panel-heading" role="tab">';
$output .= '<p class="panel-title'.($active ? ' active' : '').'"><a data-toggle="collapse" data-parent="#'.$id.'" href="#'.$hash.'"' . $history_rend . '><span>'.$title.'</span></a></p>';
$output .= '</div>';
$output .= '<div ' . esc_attr( $history_tag ) . '="' . esc_attr( $hash ) . '" class="panel-collapse collapse'.($active ? ' in' : '').'" role="tabpanel">';
$output .= '<div class="panel-body">';
$output .= ($content=='' || $content==' ') ? esc_html__("Empty section. Edit page to add content here.", "uncode") : "\n\t\t\t\t\t\t" . $content;
$output .= '</div>';
$output .= '</div>';
$output .= '</div>';

echo uncode_remove_wpautop($output);