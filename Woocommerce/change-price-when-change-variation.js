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
        if ($('.product-info.summary').length > 0) {
            $('.product-info.summary').each(function () {
                var oldPriceWrap = $('.price-wrapper', this);
                var thisOldPrice = oldPriceWrap.html();
                $('body .product-info.summary').on('change', '.variation_id', function () {
                    var thisVal = $(this).val();
                    var thisForm = $(this).closest('.variations_form.cart');
                    var thisVariation = thisForm.data('product_variations');
                    var have_variation_price = false;
                    if (thisVal) {
                        $(thisVariation).each(function (index, value) {
                            if (value.variation_id == thisVal && value.price_html) {
                                oldPriceWrap.html(value.price_html);
                                have_variation_price = true;
                            }
                        });
                        if(!have_variation_price){
                            oldPriceWrap.html(thisOldPrice)
                        }
                    } else {
                        oldPriceWrap.html(thisOldPrice);
                    }
                });
            });
        }
    });
})(jQuery);
