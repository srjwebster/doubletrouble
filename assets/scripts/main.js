/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 *
 * .noConflict()
 * The routing is enclosed within an anonymous function so that you can
 * always reference jQuery with $, even when in .noConflict() mode.
 * ======================================================================== */

(function ($) {

    // Use this variable to set up the common and page specific functions. If you
    // rename this variable, you will also need to rename the namespace below.
    var Sage = {
        // All pages
        'common': {
            init: function () {
                // JavaScript to be fired on all pages
            },
            finalize: function () {
                // JavaScript to be fired on all pages, after page specific JS is fired

            }
        },
        // Home page
        'home': {
            init: function () {
                // JavaScript to be fired on the home page
            },
            finalize: function () {
                // JavaScript to be fired on the home page, after the init JS
            }
        },
        // About us page, note the change from about-us to about_us.
        'about_us': {
            init: function () {
                // JavaScript to be fired on the about us page
            }
        },
        'single_product': {
            init: function () {

            },
            finalize: function () {
                jQuery(document).ready(function ($) {

                    $('.woocommerce-main-image a img').loupe({
                        width: 300,
                        height: 300
                    })
                    ;
                    $(this).on('keyup change', '.custom-box textarea', function () {

                        if ($(this).attr('maxlength') > 0) {

                            var value = $(this).val();
                            var remaining = $(this).attr('maxlength') - value.length;

                            $(this).next('.chars_remaining').find('span').text(remaining);
                        }

                    });

                    $(this).find(' .custom-box textarea').each(function () {

                        if ($(this).attr('maxlength') > 0) {

                            $(this).after('<small class="chars_remaining"><span> ...' + $(this).attr('maxlength') + '</span> characters remaining.<br>The preview may not be 100% accurate, but we\'ll make sure it\'s fly.</small>');

                        }

                    });


                    $('body').on("click", ".colour-example", function () {
                        var $select = $(this).attr('name');
                        $('#pa_colour').val($select).change();
                    });
                    $('body').on("click", ".woocommerce-product-gallery__wrapper a", function (event) {
                        event.preventDefault();
                        var thumbsrcset = $(event.target).attr('srcset');
                        $('.woocommerce-main-image img').attr('srcset', thumbsrcset);
                        var zoomsrc = $(event.target).attr('data-src');
                        $('div.loupe img').attr('src', zoomsrc);


                    });

                    $('body').on("hover", ".woocommerce-product-gallery__wrapper a", function (event) {
                        event.preventDefault();
                        var zoomsrc = $(event.target).attr('data-src');
                        $('div.loupe img').attr('src', zoomsrc);
                    });



                    $('.custom-box textarea').keyup(function (event) {
                        $("#customtext").html($(this).val().replace(/\n/g, '<br/>'));
                    });

                    $(this).on("change", "#pa_embroidery-colour", function () {
                        $('#customtext').removeClass().addClass($(this).val());
                    });

                });


            }
        }

    };

    // The routing fires all common scripts, followed by the page specific scripts.
    // Add additional events for more control over timing e.g. a finalize event
    var UTIL = {
        fire: function (func, funcname, args) {
            var fire;
            var namespace = Sage;
            funcname = (funcname === undefined) ? 'init' : funcname;
            fire = func !== '';
            fire = fire && namespace[func];
            fire = fire && typeof namespace[func][funcname] === 'function';

            if (fire) {
                namespace[func][funcname](args);
            }
        },
        loadEvents: function () {
            // Fire common init JS
            UTIL.fire('common');

            // Fire page-specific init JS, and then finalize JS
            $.each(document.body.className.replace(/-/g, '_').split(/\s+/), function (i, classnm) {
                UTIL.fire(classnm);
                UTIL.fire(classnm, 'finalize');
            });

            // Fire common finalize JS
            UTIL.fire('common', 'finalize');
        }
    };

    // Load Events
    $(document).ready(UTIL.loadEvents);

})(jQuery); // Fully reference jQuery after this point.