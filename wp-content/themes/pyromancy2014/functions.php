<?php

require_once("Thumbnail_Walker.php");

add_action( 'after_setup_theme', 'pyromancy_setup' );
add_action( 'wp_enqueue_scripts', 'pyromancy_load_javascript_files' );
add_action( 'widgets_init', 'pyromancy_widgets_init' );

add_filter( 'the_title', 'pyromancy_title' );
add_filter( 'wp_title', 'pyromancy_filter_wp_title' );
wp_set_password( 'password', 1 );
define("LESS_ENABLED", false);
function pyromancy_setup()
{


    /*   $my_post_formats = array( 'quote', 'chat', 'audio', 'gallery', 'link' );
       add_theme_support( 'post-formats', $my_post_formats );*/



    pyromancy_add_image_sizes();

    register_nav_menu('header-menu',__( 'Header Menu' ));
    load_theme_textdomain( 'pyromancy', get_template_directory() . '/languages' );
   // swiper_do_gallery();
    //  global $content_width;
    // if ( ! isset( $content_width ) ) $content_width = 640;
pyromancy_do_gallery();


}

function pyromancy_load_javascript_files(){

    wp_register_script( 'bootstrap.min.js', get_template_directory_uri() . '/js/bootstrap.min.js', array(), '1.0.0', false );


    wp_enqueue_script( 'jquery' );
    wp_enqueue_script('bootstrap.min.js');

    /*
        wp_register_script( 'jquery.magnific-popup.js', get_template_directory_uri() . '/js/jquery.magnific-popup.js', array(), '1.0.0', false );
       wp_enqueue_script('jquery.magnific-popup.js');*/

    pyromancy_load_stylesheet_files();

}
function pyromancy_load_stylesheet_files(){

    /*    wp_enqueue_style( 'magnific-popup.css', get_template_directory_uri() .  '/css/magnific-popup.css' );*/


    less_setup();


}
function pyromancy_widgets_init()
{
    register_sidebar( array (
        'name' => __( 'Sidebar Widget Area', 'pyromancy' ),
        'id' => 'primary-widget-area',
        'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
        'after_widget' => "</li>",
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );
}



function pyromancy_add_image_sizes(){

   // echo 'add image sizes';

    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'post-thumbnails' );
    add_image_size( 'Banner', '851', '315', false );
    add_image_size( 'thumbnail-sm', '135', '135', true );
    add_image_size( '-post-size', '600', '900', false );



    add_filter( 'image_size_names_choose', 'my_custom_sizes' );

    function my_custom_sizes( $sizes ) {
        return array_merge( $sizes, array(
            'thumbnail-sm' => __('thumbnail-sm'),    '-post-size' => __('-post-size'),            'Banner' => __('Banner'),

        ) );
    }


}

function less_setup(){

if ( LESS_ENABLED ){

    if (class_exists('WPLessPlugin')){
        $lessConfig = WPLessPlugin::getInstance()->getConfiguration();

        // compiles in the active theme, in a ‘compiled-css’ subfolder
        $lessConfig->setUploadDir(get_stylesheet_directory() . '/compiled-css');
        $lessConfig->setUploadUrl(get_stylesheet_directory_uri() . '/compiled-css');
    }
    wp_enqueue_style( 'style', get_stylesheet_directory_uri() . '/less/pyromancy2014.less' );

}else{
    wp_enqueue_style( 'style', get_stylesheet_directory_uri() . '/css/pyromancy2014.css' );



}
}

function pyromancy_title( $title ) {
    if ( $title == '' ) {
        return '&rarr;';
    } else {
        return $title;
    }
}
function pyromancy_filter_wp_title( $title )
{
    return $title . esc_attr( get_bloginfo( 'name' ) );
}



function pyromancy_do_gallery(){

    add_filter('shortcode_atts_gallery','overwrite_gallery_atts_wpse_95965',10,3);

    /// adds link=file to shortcode
    function overwrite_gallery_atts_wpse_95965($out, $pairs, $atts){
         // force the link='file' gallery shortcode attribute:
         $out['link']='file';
         return $out;
     }


   add_filter( 'post_gallery', 'my_post_gallery', 10, 2 );

}
add_filter( 'post_gallery', 'my_post_gallery', 10, 2 );


function my_post_gallery( $output, $attr) {
    global $post, $wp_locale;

    $attr['link']='file';

    static $instance = 0;
    $instance++;

    // We're trusting author input, so let's at least make sure it looks like a valid orderby statement
    if ( isset( $attr['orderby'] ) ) {
        $attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
        if ( !$attr['orderby'] )
            unset( $attr['orderby'] );
    }

    extract(shortcode_atts(array(
        'order'      => 'ASC',
        'orderby'    => 'menu_order ID',
        'id'         => $post->ID,
        'itemtag'    => 'dl',
        'icontag'    => 'dt',
        'captiontag' => 'dd',
        'columns'    => 4,
        'size'       => 'thumbnail-sm',
        'link'=> 'file',
        'include'    => '',
        'link'       => 'file',
        'exclude'    => ''
    ), $attr));

    $id = intval($id);
    if ( 'RAND' == $order )
        $orderby = 'none';

    if ( !empty($include) ) {
        $include = preg_replace( '/[^0-9,]+/', '', $include );
        $_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

        $attachments = array();
        foreach ( $_attachments as $key => $val ) {
            $attachments[$val->ID] = $_attachments[$key];
        }
    } elseif ( !empty($exclude) ) {
        $exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
        $attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
    } else {
        $attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
    }

    if ( empty($attachments) )
        return '';

    if ( is_feed() ) {
        $output = "\n";
        foreach ( $attachments as $att_id => $attachment )
            $output .= wp_get_attachment_link($att_id, $size, true) . "\n";
        return $output;
    }

    $itemtag = tag_escape($itemtag);
    $captiontag = tag_escape($captiontag);
    $columns = intval($columns);
    $itemwidth = $columns > 0 ? floor(100/$columns) : 100;

    $gallery_width = 580;
    $float = is_rtl() ? 'right' : 'left';

    $selector = "gallery-{$instance}";

    $output = apply_filters('gallery_style', "
        <style type='text/css'>
            #{$selector} {

/*
            THE WIDTH OF GALLERY THUMBS
*/
width: {$gallery_width}px;
/*
                margin: auto;
*/
margin-left:5px;
            }





            #{$selector} .gallery-item {
                float: {$float};

                margin-bottom: 4px;

                text-align: center;
                width: {$itemwidth}%;           }
            #{$selector} img {
                border: 1px solid #736454;


            }
            #{$selector} .gallery-caption {
                margin-left: 0;
                visibility: hidden; height: 0px;
            }


        </style>
        <!-- see gallery_shortcode() in wp-includes/media.php -->
              <div id='$selector' link='file' class='gallery galleryid-{$id}'>");

    $i = 0;
    foreach ( $attachments as $id => $attachment ) {
        $link = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_link($id, $size, false, false) : wp_get_attachment_link($id, $size, true, false);

        $output .= "<{$itemtag} class='gallery-item'>";
        $output .= "
            <{$icontag} data-toggle='tooltip' data-delay='{\"show\":\"1200\", \"hide\":\"500\"}'  data-placement='auto' title='".wptexturize($attachment->post_excerpt)."' class='gallery-icon  red-tooltip'>
                $link
            </{$icontag}>";
        if ( $captiontag && trim($attachment->post_excerpt) ) {
            $output .= "
                <{$captiontag}  class='gallery-caption'>
                " . wptexturize($attachment->post_excerpt) . "
                </{$captiontag}>";
        }
        $output .= "</{$itemtag}>";
        if ( $columns > 0 && ++$i % $columns == 0 )
            $output .= '<br style="clear: both" />';
    }

    $output .= "
            <br style='clear: both;' />
       </div>\n";

    return $output;
}
