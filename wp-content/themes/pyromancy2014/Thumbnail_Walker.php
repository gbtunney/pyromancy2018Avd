<?php
/**
 * Created by JetBrains PhpStorm.
 * User: gbtunney
 * Date: 8/27/14
 * Time: 12:31 AM
 * To change this template use File | Settings | File Templates.
 */
/*
 * Create HTML list of nav menu items.
 * Replacement for the native Walker, using the description.
 *
 * @see    http://wordpress.stackexchange.com/q/14037/
 * @author toscho, http://toscho.de
 *
 *
 *
 * USAGE:   $mytheme_walker = new Thumbnail_Walker();
            wp_nav_menu( array(
                'walker' => $mytheme_walker
            ) );
 */
class Thumbnail_Walker extends Walker_Nav_Menu
{
    /**
     * Start the element output.
     *
     * @param  string $output Passed by reference. Used to append additional content.
     * @param  object $item   Menu item data object.
     * @param  int $depth     Depth of menu item. May be used for padding.
     * @param  array $args    Additional strings.
     * @return void
     */
    function start_el(&$output, $item, $depth, $args)
    {
      //  echo "THUMBNAIL WALKER CALLED";
        $classes     = empty ( $item->classes ) ? array () : (array) $item->classes;

        $class_names = join(
            ' '
            ,   apply_filters(
                'nav_menu_css_class'
                ,   array_filter( $classes ), $item
            )
        );

        ! empty ( $class_names )
            and $class_names = ' class="'. esc_attr( $class_names ) . '"';

        $output .= "<li id='menu-item-$item->ID' $class_names>";

        $attributes  = '';

        ! empty( $item->attr_title )
            and $attributes .= ' title="'  . esc_attr( $item->attr_title ) .'"';
        ! empty( $item->target )
            and $attributes .= ' target="' . esc_attr( $item->target     ) .'"';
        ! empty( $item->xfn )
            and $attributes .= ' rel="'    . esc_attr( $item->xfn        ) .'"';
        ! empty( $item->url )
            and $attributes .= ' href="'   . esc_attr( $item->url        ) .'"';


         $attributes .= ' data-toggle="'   . 'tooltip' .'"';
        $attributes .= ' class="'   . 'red-tooltip' .'"';
        $attributes .= ' data-placement="'   . "left" .'"';



        $title = apply_filters( 'the_title', $item->title, $item->ID );

        $attributes .= ' data-original-title="'   . esc_attr( $title)  .'"';



        // insert thumbnail
        // you may change this
        /*
        $thumbnail = '';
        if( $id = has_post_thumbnail( (int)$item->object_id ) ) {
            $thumbnail = get_the_post_thumbnail( $id );
        }*/
        $thumbnail = '';

    //    echo "OBJECT ID".  $item->object_id;
        if ( has_post_thumbnail( $item->object_id ) ) {


            $default_attr = array(

                'class'	=> "img-circle", //, "attachment-$size"],

            );
/*
            <dt class="gallery-icon  red-tooltip" title="" data-placement="left" data-toggle="tooltip" data-original-title="Silo City photo by MJM Photos ( https://www.facebook.com/mary.machnica )">
                <a rel="lightbox[1493]" href="http://localhost/pyromancy/wp-content/uploads/2013/11/1147687_10151777551653903_962436297_o.jpg"><img width="150" height="150" alt="Silo City photo by MJM Photos ( https://www.facebook.com/mary.machnica )" class="attachment-thumbnail" src="http://localhost/pyromancy/wp-content/uploads/2013/11/1147687_10151777551653903_962436297_o-150x150.jpg"></a>
            </dt>*/


            $thumbnail =            get_the_post_thumbnail( $item->object_id , 'thumbnail',$default_attr );      // Thumbnail
            $title = apply_filters( 'the_title', $item->title, $item->ID );

            $item_output = $args->before
                . "<a $attributes>"
                . $args->link_before
            //    . $title
                . $args->link_after
                . $thumbnail

                . '</a> '
                . $args->after;

            // Since $output is called by reference we don't need to return anything.
            $output .= apply_filters(
                'walker_nav_menu_start_el'
                ,   $item_output
                ,   $item
                ,   $depth
                ,   $args
            );

        }



    }
}
