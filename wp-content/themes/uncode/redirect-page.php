<?php

/**
 * The coming soon template file.
 *
 * @package uncode
 */

get_header();

$redirect_page = ot_get_option('_uncode_redirect_page');
$redirect_page = apply_filters( 'wpml_object_id', $redirect_page, 'post' );

$media = get_post_meta($redirect_page, '_uncode_featured_media', 1);
$featured_image = get_post_thumbnail_id($redirect_page);

if ($featured_image === '') $featured_image = $media;

/** Collect header data **/
if (isset($metabox_data['_uncode_header_type'][0]) && $metabox_data['_uncode_header_type'][0] !== '') {
	$page_header_type = $metabox_data['_uncode_header_type'][0];
	if ($page_header_type !== 'none') {
		$meta_data = uncode_get_specific_header_data($metabox_data, $post_type, $featured_image);
		$metabox_data = $meta_data['meta'];
		$show_title = $meta_data['show_title'];
	}
} else {
	$page_header_type = ot_get_option('_uncode_'.$post_type.'_header');
	if ($page_header_type !== '' && $page_header_type !== 'none') {
		$metabox_data['_uncode_header_type'] = array($page_header_type);
		$meta_data = uncode_get_general_header_data($metabox_data, $post_type, $featured_image);
		$metabox_data = $meta_data['meta'];
		$show_title = $meta_data['show_title'];
	}
}

/** Build header **/
if ($page_header_type !== '' && $page_header_type !== 'none') {
	$page_header = new unheader($metabox_data, $post->post_title);

	$header_html = $page_header->html;
	if ($header_html !== '') {
		echo '<div id="page-header">';
		echo uncode_remove_wpautop( $page_header->html );
		echo '</div>';
	}

	if (!empty($page_header->poster_id) && $page_header->poster_id !== false && $media !== '') {
		$media = $page_header->poster_id;
	}
	echo '<script type="text/javascript">UNCODE.initHeader();</script>';
}


/** Get general datas **/
if (isset($metabox_data['_uncode_specific_style'][0]) && $metabox_data['_uncode_specific_style'][0] !== '') {
	$style = $metabox_data['_uncode_specific_style'][0];
	if (isset($metabox_data['_uncode_specific_bg_color'][0]) && $metabox_data['_uncode_specific_bg_color'][0] !== '') {
		$bg_color = $metabox_data['_uncode_specific_bg_color'][0];
	}
} else {
	$style = ot_get_option('_uncode_general_style');
	if (isset($metabox_data['_uncode_specific_bg_color'][0]) && $metabox_data['_uncode_specific_bg_color'][0] !== '') {
		$bg_color = $metabox_data['_uncode_specific_bg_color'][0];
	} else $bg_color = ot_get_option('_uncode_general_bg_color');
}
$bg_color = ($bg_color == '') ? ' style-'.$style.'-bg' : ' style-'.$bg_color.'-bg';


$the_content = get_post_field('post_content', $redirect_page);
if (has_shortcode($the_content, 'vc_row'))
{
	$the_content = '<div class="post-content">' . $the_content . '</div>';
}
else
{
	$the_content = apply_filters('the_content', $the_content);
	$the_content = '<div class="post-content">' . uncode_get_row_template($the_content, '', '', $style, '', 'double', true, 'double') . '</div>';
}
/** Display post html **/
echo 	'<article id="post-'. get_the_ID().'" class="'.implode(' ', get_post_class('page-body style-'.$bg_color.'-bg')) .'">
				<div class="post-wrapper">
					<div class="post-body">' . do_shortcode($the_content) . '</div>
				</div>
			</article>';
get_footer(); ?>