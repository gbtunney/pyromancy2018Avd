<?php
$user_id = '';
$avatar = $custom_avatar = $avatar_position = $avatar_style = $avatar_size = $avatar_border = $avatar_style = $avatar_back_color = '';
$author_name_linked = $heading_semantic = $text_size = $text_height = $text_height = $text_space = $text_font = $text_weight = $text_transform = $text_italic = $text_color = $separator = $separator_double = $author_bio = $sub_lead = $sub_reduced = $social = '';
$desktop_visibility = $medium_visibility = $mobile_visibility = $css_animation = $animation_delay = $animation_speed = $output = $el_class = $sub_class = '';
$display_button = $button_content = $button_link = $link = $button_color = $size = $hover_fx = $text_skin = $outline = $icon = $icon_position = $radius = $onclick = $rel = $btn_class = '';
extract( shortcode_atts( array(
	'user_id' => '',
	'avatar' => 'gravatar',
	'custom_avatar' => '',
	'avatar_position' => 'left',
	'avatar_style' => 'img-circle',
	'avatar_size' => '100',
	'avatar_border' => '',
	'avatar_skin' => 'light',
	'avatar_back_color' => '',
	'author_name_linked' => 'yes',
	'heading_semantic' => 'h2',
	'text_size' => 'h2',
	'text_height' => '',
	'text_space' => '',
	'text_font' => '',
	'text_weight' => '',
	'text_transform' => '',
	'text_italic' => '',
	'text_color' => '',
	'separator' => '',
	'separator_double' => '',
	'author_bio' => 'yes',
	'sub_lead' => '',
	'sub_reduced' => '',
	'social' => 'yes',
	'display_button' => '',
	'button_content' => esc_html__('All author posts', 'uncode') ,
	'url' => '',
	'button_link' => '',
	'link' => '',
	'button_color' => 'default',
	'size' => '',
	'hover_fx' => '',
	'text_skin' => '',
	'icon' => '',
	'icon_position' => 'left',
	'radius' => '',
	'outline' => '',
	'onclick' => '',
	'rel' => '',
	'desktop_visibility' => '',
	'medium_visibility' => '',
	'mobile_visibility' => '',
	'css_animation' => '',
	'animation_delay' => '',
	'animation_speed' => '',
	'el_class' => '',
	'btn_class' => '',
), $atts ) );

/***********
* General
***********/
$cont_classes = array('author-profile el-author-profile');
$cont_classes[] = 'author-profile-box-' . $avatar_position;
$div_data = array();
if ($css_animation !== '') {
	$cont_classes[] = $css_animation . ' animate_when_almost_visible';
	if ($animation_delay !== '') $div_data['data-delay'] = $animation_delay;
	if ($animation_speed !== '') $div_data['data-speed'] = $animation_speed;
}

$user_id = $user_id === '' ? get_the_author_meta( 'ID' ) : $user_id;

if ( !$user_id && !is_author() ) { //the user doesn't have posts or we aren't on profile page
	return;
} elseif ( !$user_id && is_author() ) { //the user doesn't have posts but we are on his/her profile page
	$user = get_user_by( 'slug', get_query_var( 'author_name' ) );
	$user_id = $user->ID;
}

$user_info = get_userdata( $user_id );
if ( !$user_info ) {
	$user_id = get_the_author_meta('ID');
	$user_info = get_userdata( $user_id );
}

$user_name = $user_info->display_name;
$user_email = $user_info->user_email;

$a_href = get_author_posts_url( $user_id );
$a_title = sprintf(esc_html__('%1$s post page', 'uncode'), $user_name);
$a_target = '_self';

/***********
* Avatar
***********/

if ( $avatar !== 'hide' ) {
	$cont_classes[] = 'has-thumb';

	$media_width = preg_replace("/[^0-9,.]/", "", $avatar_size);
	$single_width = $media_width;
	$actual_width = $avatar_size. 'px';
	$single_fixed = 'width';

	$block_data = array();
	$block_classes = array('tmb');
	$tmb_data = array();
	$title_classes = array();

	$shape = $tmb_shape = ($avatar_style != '') ? ' ' . $avatar_style : '';

	if ($avatar_border === 'yes') {
		$shape .= ' img-thumbnail';
		$tmb_shape .= ' tmb-bordered';
	}

	$block_classes[] = $tmb_shape;
	$block_classes[] = 'tmb-media-first';
	$block_classes[] = 'tmb-' . $avatar_skin;

	$custom_avatar = $avatar == 'gravatar' ? $user_email : $custom_avatar;

	$block_data['classes'] = $block_classes;
	$block_data['tmb_data'] = $tmb_data;
	$block_data['media_id'] = $custom_avatar;
	$block_data['images_size'] = '1:1';
	$block_data['single_style'] = $avatar_skin;
	$block_data['single_text'] = '';
	$block_data['single_elements_click'] = 'yes';
	$block_data['overlay_color'] = false;
	$block_data['overlay_opacity'] = false;
	$block_data['single_back_color'] = $avatar_back_color;
	$block_data['single_width'] = $single_width;
	$block_data['single_height'] = $single_width;
	$block_data['single_fixed'] = $single_fixed;
	$block_data['single_icon'] = '';
	$block_data['title_classes'] = $title_classes;
	$block_data['text_padding'] = 'half-block-padding';//the value doesn't matter, it's just to use the single_media layout

	$lbox_id = $user_id;

	$layout = array('media' => array());

	$media_html = uncode_create_single_block($block_data, 'single-' . $lbox_id, 'masonry', $layout, '', '');

	$media_string = '<div class="uncode-single-media-wrapper single-advanced">' . $media_html . '</div>';

	$avatar_output= '<div class="uncode-avatar-wrapper single-media uncode-single-media" style="width: ' . esc_attr( $actual_width ) . '">';
	$avatar_output.= '<div class="single-wrapper" style="max-width: ' . esc_attr( $actual_width ) . '">';
	$avatar_output.= $media_string;
	$avatar_output.= '</div>';
	$avatar_output.= '</div>';
}

/***********
* Content
***********/
$content_out = '<div class="author-profile-content">';

$separator_classes = array();
if ($separator !== '') {
	$separator_classes[] = 'separator-break';
	if ($separator_double === 'yes') $separator_classes[] = 'separator-double-padding';
}

if ($separator === 'over') $content_out .= '<hr class="' . esc_attr(trim(implode( ' ', $separator_classes ))) . '" />';

$content_classes = array();
if ($text_font !== '') $content_classes[] = $text_font;
if ($text_size !== '') {
	$content_classes[] = $text_size;
	if ($text_size === 'bigtext') $cont_classes[] = 'heading-bigtext';
}
if ($text_height !== '') $content_classes[] = $text_height;
if ($text_space !== '') $content_classes[] = $text_space;
if ($text_weight !== '') $content_classes[] = 'font-weight-' . $text_weight;
if ($text_color !== '') $content_classes[] = 'text-' . $text_color . '-color';
if ($text_transform !== '') $content_classes[] = 'text-' . $text_transform;

$content_out .= '<' . $heading_semantic . ' class="' . esc_attr(trim(implode( ' ', $content_classes ))) . '">';
if ( $author_name_linked === 'yes' ) {
	$title = ($a_title !== '') ? ' title="' . esc_attr( $a_title ) . '"' : '';
	$target = (trim($a_target) !== '') ? ' target="' . esc_attr( trim($a_target) ) . '"' : '';
	$content_out .= '<a href="' . esc_url($a_href) . '" ' . $title . $target . '>';
}
if ($text_italic === 'yes') $content_out .= '<i>';
$content_out .= '<span>';
$user_name = trim($user_name);
$title_lines = explode("\n", $user_name);
$lines_counter = count($title_lines);
if ($lines_counter > 1) {
	foreach ($title_lines as $key => $value) {
		$value = trim($value);
		$content_out .= $value;
		if ($value !== '' && ($lines_counter - 1 !== $key)) $content_out .= '</span><span>';
	}
} else {
	$content_out .= $user_name;
}
$content_out .= '</span>';
if ($text_italic === 'yes') $content_out .= '</i>';
if ( $author_name_linked === 'yes' ) {
	$content_out .= '</a>';
}
$content_out .= '</' . $heading_semantic . '>';

if ($separator === 'yes') $content_out .= '<hr class="' . esc_attr(trim(implode( ' ', $separator_classes ))) . '" />';

$user_bio = $user_info->description;
if ( $user_bio !== '' && $author_bio === 'yes' ) {
	if ($sub_lead === 'yes') $sub_lead = ' text-lead';
	if ($sub_reduced === 'yes') $sub_reduced = ' text-top-reduced';
	if ($sub_lead !== '' || $sub_reduced !== '') $sub_class = esc_attr(trim($sub_lead.$sub_reduced));

	$content_out .= '<div class="author-profile-bio ' . esc_attr( $sub_class ) . '">' . uncode_remove_wpautop($user_bio, true) . '</div>';
}

if ($separator === 'under') $content_out .= '<hr class="' . esc_attr(trim(implode( ' ', $separator_classes ))) . '" />';

if ( $social === 'yes' ) {

	$content_out .= uncode_get_allowed_contact_methods( $user_id );

}

/***********
* Button
***********/
//parse link
if ( $button_link==='custom' ) {
	$link = ( $link == '||' ) ? '' : $link;
	$link = vc_build_link( $link );
	$a_href = $link['url'];
	$a_title = $link['title'];
	$a_target = $link['target'];
}

// Prepare button classes
$btn_wrapper_class = array('btn-container', '');
$btn_classes = array('btn');
$button_data = array();

// Size class
if ($size) {
	if ($size === 'link') unset($btn_classes[0]);
	else $btn_classes[] = $size;
}

// Additional classes
if ($btn_class) $btn_classes[] = $btn_class;

// Color class
if ($button_color === '') $button_color = 'default';
if ($button_color !== 'default') {
	if ($text_skin === 'yes') $btn_classes[] = 'btn-text-skin';
}
if ($size !== 'btn-link' && $size !== 'link') $btn_classes[] = 'btn-' . $button_color;
else $btn_classes[] = 'text-' . $button_color . '-color';

// Radius class
if ($radius) $btn_classes[] = $radius;

// Hover effect
$hover_fx = $hover_fx=='' ? ot_get_option('_uncode_button_hover') : $hover_fx;

// Outlined and flat classes
if ( $hover_fx == '' || $hover_fx == 'outlined' ) {
	if ($outline === 'yes' ) 
		$btn_classes[] = 'btn-outline';
} else {
	$btn_classes[] = 'btn-flat';
}

// Prepare icon
if ($icon !== '') {
	$icon = '<i class="' . esc_attr($icon) . '"></i>';
}
else $icon = '';

if ( $display_button === 'yes' ) {

	$content = trim($button_content);

	if ($icon_position === 'right')
	{
		$content = $content . $icon;
		$btn_classes[] = 'btn-icon-right';
	} else {
		$content = $icon . $content;
		$btn_classes[] = 'btn-icon-left';
	}

	$btn_class = $this->getExtraClass( $btn_class );

	// Prepare onclick action
	$onclick = ($onclick !== '') ? ' onClick="' . esc_attr( $onclick ) . '"' : '';

	$title = ($a_title !== '') ? ' title="' . esc_attr( $a_title ) . '"' : '';
	$target = (trim($a_target) !== '') ? ' target="' . esc_attr( trim($a_target) ) . '"' : '';
	$width = ''; //because it can't have a fixed width ever

	$button_data_attributes = array_map(function ($v, $k) { return $k . '="' . $v . '"'; }, $button_data, array_keys($button_data));

	$content_out .= '<span class="' . esc_attr(trim(implode($btn_wrapper_class, ' '))) . '" '.implode(' ', $button_data_attributes).'><a href="' . esc_url($a_href) . '" class="custom-link ' . esc_attr(trim(implode($btn_classes, ' '))) . '"' . $title . $target . $onclick . $rel . $width . '>' . do_shortcode($content) . '</a></span>';

}

$content_out .= '</div>';//.author-profile-content

/***********
* Output
***********/
$div_data_attributes = array_map(function ($v, $k) { return $k . '="' . $v . '"'; }, $div_data, array_keys($div_data));

$output = '<div class="' . esc_attr(trim(implode( ' ', $cont_classes ))) . '" '.implode(' ', $div_data_attributes).'>';
switch ($avatar_position) {
	case 'right':
		$output .= $content_out . $avatar_output;
		break;
	default:
		$output .= $avatar_output . $content_out;
		break;
}
$output .= '</div>';

echo uncode_remove_wpautop($output);