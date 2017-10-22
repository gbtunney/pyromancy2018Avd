<?php
/*
 * Template Name: Slider
 * Description: A Page Template with a darker design.
 */

// Code to display Page goes here...

?>
<?php get_header(); ?>
<section id="content" role="main">


    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
            <li data-target="#carousel-example-generic" data-slide-to="1"></li>
            <li data-target="#carousel-example-generic" data-slide-to="2"></li>
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner">
            <div class="item active">
                <img src="<?php echo get_template_directory_uri(); ?>/images/fpo/P1.png" alt="...">
                <div class="carousel-caption">
Steampunk Festival                </div>
            </div>
            <div class="item">
                <img src="<?php echo get_template_directory_uri(); ?>/images/fpo/P2.png" alt="...">
                <div class="carousel-caption">
                    Niagara Celtic
                </div>
            </div>

            <div class="item">
                <img src="<?php echo get_template_directory_uri(); ?>/images/fpo/P3.png" alt="...">
                <div class="carousel-caption">
                    Albright Knox Mirror Mirror
                </div>
            </div>
        </div>

        <!-- Controls -->CONTROLS
        <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
        </a>
        <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
        </a>
    </div>


    <div class="row">    <div style="clear: both;"></div>

</section>
<?php get_footer(); ?>