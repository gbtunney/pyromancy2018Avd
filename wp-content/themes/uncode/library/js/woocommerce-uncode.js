(function($) {
    "use strict";

    function get_cart() {
        if (window.wc_add_to_cart_params != undefined) {
            $.post({
                url: wc_add_to_cart_params.ajax_url,
                dataType: 'JSON',
                data: {
                    action: 'woomenucart_ajax'
                },
                success: function(data, textStatus, XMLHttpRequest) {
                    $('.uncode-cart-dropdown').html(data.cart);
                    if (data != '') {
                        if ($('.uncode-cart .badge, .mobile-shopping-cart .badge').length) {
                            if (data.articles > 0) {
                                $('.uncode-cart .badge, .mobile-shopping-cart .badge').html(data.articles);
                                $('.uncode-cart .badge, .mobile-shopping-cart .badge').show();
                            } else {
                                $('.uncode-cart .badge, .mobile-shopping-cart .badge').hide();
                            }
                        } else $('.uncode-cart .cart-icon-container').append('<span class="badge">' + data.articles + '</span>'); //$('.uncode-cart .badge').html(data.articles);
                    }
                }
            });
        }

    }

    function remove_from_cart() {
        $(document).on('click', '.uncode-cart-dropdown a.remove', function(e){

            var $remove = $(this),
                product_id = $remove.attr("data-product_id"),
                item_key = $remove.attr("data-item_key"),
                $li = $remove.parents('.cart-item-list').eq(0).addClass('removing-item').animate({
                    opacity: .5
                },150);

            $.post({
                dataType: 'json',
                url: wc_add_to_cart_params.ajax_url,
                data: { 
                    action: "woomenucart_remove_ajax",
                    item_key: item_key
                },
                success: function(html){
                    $li.slideUp(200, function(){
                        get_cart();
                    });
                }
            });

            return false;
        });
    }

    function change_images(event, variation) {
        if (variation.image_src !== '') {
            var get_href = $('a.woocommerce-main-image'),
                image_variable = $('img', get_href),
                getLightbox = UNCODE.lightboxArray[get_href.data('lbox')];
            get_href.data('options', "thumbnail: '" + variation.image_src + "'");
            image_variable.attr('src', variation.image_src);
            if (image_variable.hasClass('async-done')) {
                image_variable.attr('data-path', variation.uncode_image_path);
                image_variable.attr('data-guid', variation.uncode_image_guid);
                image_variable.removeClass('async-done').addClass('adaptive-async');
                UNCODE.adaptive();
            }
            if (getLightbox != undefined) getLightbox.refresh();
            $(window).trigger('focus');
        }
    }
    $(document).ready(function() {
        remove_from_cart();
        $('body').bind("added_to_cart", get_cart);
        $('body').bind("wc_fragments_refreshed", get_cart);
        $('.variations_form').bind("show_variation", change_images);
    });

    $( '.woocommerce-product-gallery' ).each( function() {

        var $slider = $(this);

        if ($.fn.flexslider) { 
            var $plcholder = $('#woocommerce-product-single-plchold'); 
            $slider.flexslider({
                start: function(){
                    $('img[data-large_image]', $slider).each(function(){
                        var $img = $(this),
                            url = $img.attr('data-large_image');
                    });
                    setTimeout(function(){
                        $plcholder.remove();
                    },600);
                }
            });
            $(window).on('load', function(){
                $('.woocommerce-product-gallery__wrapper', $slider).css({'max-width': 'none'})
            });
        }

        if ($.fn.zoom && $('body').hasClass('wc-zoom-enabled') ) { 
            var $zoomTrgt = $('.woocommerce-product-gallery__image', $slider);

            if ( $('.thumbnails', $slider).length )
                $zoomTrgt = $zoomTrgt.first();

            $zoomTrgt.trigger( 'zoom.destroy' );
            $zoomTrgt.zoom();

            var checkForZoom = function(){
                var galleryWidth = $zoomTrgt.width(),
                    zoom_options = {
                        touch: false
                    };

                if ( 'ontouchstart' in window ) {
                    zoom_options.on = 'click';
                }

                $zoomTrgt.trigger( 'zoom.destroy' );
                $zoomTrgt.each(function(){
                    var $thisTrgt = $(this),
                        $img = $('img', $thisTrgt);
                    
                    if ( $img.data( 'large_image_width' ) > galleryWidth )
                        $thisTrgt.zoom(zoom_options);

                });
            };
            checkForZoom();

            var setCheckForZoom;
            $(window).on( 'resize', function(){
                clearTimeout(setCheckForZoom);
                setCheckForZoom = setTimeout( checkForZoom, 150 );
            });
        }

    } );
})(jQuery);