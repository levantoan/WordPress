if($('body span.added-to-cart').length > 0){
    var e = $.magnificPopup.open ? 0 : 300;
    e && $.magnificPopup.close(),
        setTimeout(function () {
            if (matchMedia('only screen and (max-width: 849px)').matches) {
                $('body .header-cart-link.off-canvas-toggle').trigger('click');
            }
        }, e);
}
