 <div class="clear"></div>
</div>
<footer id="footer" role="contentinfo">
    <img class="header-rule" src="<?php echo get_template_directory_uri(); ?>/images/header-bar.png"/>


    <div id="copyright">   <p>
        &copy; <?php echo date("Y"); ?> <?php bloginfo('name'); ?><br/> Powered by <a href="http://wordpress.org/">WordPress</a> | Theme by <a href="http://gilliantunney.com">Gillian Tunney</a> | <a href="http://pyromancy.org/pyromancy-logo-materials/">Logo Files</a> | <a href="http://pyromancy.org/sitemap.xml">sitemap</a> |  <?php wp_loginout(); ?>
    </p>
<?php

   // echo sprintf( __( '%1$s %2$s %3$s. All Rights Reserved.', 'blankslate' ), '&copy;', date( 'Y' ), esc_html( get_bloginfo( 'name' ) ) ); echo sprintf( __( ' Theme By: %1$s.', 'blankslate' ), '<a href="http://tidythemes.com/">TidyThemes</a>' ); ?>
</div>
</footer>
</div>


<script>
    /*var navigation = responsiveNav(".nav-collapse", {
        insert: "before"
    });*/

    jQuery(document).ready(function() {
        jQuery("body").tooltip({ 'data-animation': true,selector: '[data-toggle=tooltip]' });
    });


</script>


<?php wp_footer(); ?></body>
</html>

