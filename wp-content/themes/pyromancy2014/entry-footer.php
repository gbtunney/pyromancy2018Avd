<footer class="entry-footer">
    <?php if ( is_page($post->ID )){ ?>
    <span class="cat-links"></span><span><?php edit_post_link('Edit', '', '  '); ?> </span>



    <?php  }else{  ?>
 <span class="cat-links"><?php _e( 'Posted in: ', 'blankslate' ); ?><?php the_category( ', ' ); ?> </span><span><?php edit_post_link('Edit', '', '  '); ?> </span>

    <?php  } ?>


<?php
   // if ( comments_open() ) {
//echo '<span class="meta-sep"></span> <span class="comments-link"><a href="' . get_comments_link() . '">' . sprintf( __( 'Comments', 'blankslate' ) ) . '</a></span>';
//}

    ?>
</footer> 