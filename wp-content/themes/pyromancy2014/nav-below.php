<?php global $wp_query; if ( $wp_query->max_num_pages > 1 ) { ?>
<nav id="nav-below" class="navigation article-post" role="navigation">
    <div class="large-hrule"></div>

    <div class="nav-btns">
<div class="nav-previous"><?php next_posts_link(sprintf( __( '%s older', 'blankslate' ), '<span class="meta-nav">&larr;</span>' ) ) ?></div>
<div class="nav-next"><?php previous_posts_link(sprintf( __( 'newer %s', 'blankslate' ), '<span class="meta-nav">&rarr;</span>' ) ) ?></div>
    </div></nav>
<?php } ?>