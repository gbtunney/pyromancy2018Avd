<?php get_header(); ?>
<section id="content" role="main">
    <div class="row">
        <div class="gtheme-sidebar one"><?php get_sidebar(); ?></div>
        <div class="gtheme-main two">
            <div class="container">
                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <?php get_template_part( 'entry' ); ?>
                <?php comments_template(); ?>
                <?php endwhile; endif; ?>

                <?php get_template_part( 'nav', 'below' ); ?>
                <div class="large-hrule article-margins"></div>
            </div>

        </div></div>

</section>
<?php get_footer(); ?>