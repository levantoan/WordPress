/*
Change range price when change variation
Author: https://levantoan.com
Add this CSS to your style.css

.single_variation_wrap div.woocommerce-variation .woocommerce-variation-price {
    display: none !important;
}

*/

(function ($) {
    $(document).ready(function () {
        var old_price = $('.summary.entry-summary > .price');
        var old_price_val = old_price.html();
        $('.variations_form.cart').each(function () {
            $(this).on('check_variations', function () {
                var thisForm = $(this);
                var variation_wrap = $('.woocommerce-variation.single_variation');
                var sync_price = $('body .single_variation_wrap div.woocommerce-variation > .woocommerce-variation-price > .price');
                if (variation_wrap.is(':visible')) {
                    old_price.html(sync_price.html());
                } else {
                    old_price.html(old_price_val);
                }
                setTimeout( function() {
                    var thisNoMatching = $('.wc-no-matching-variations', thisForm).length;
                    if(thisNoMatching) {
                        old_price.html(old_price_val);
                    }
                }, 201 );
            });
        });
    });
})(jQuery);
