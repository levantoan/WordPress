$('.woocommerce-pagination').on('click', '.devvn_load_more_product', function () {
  var oldThis = $(this);
  oldThis.addClass('devvn_loading2');
  var parentThis = $(this).closest('.woocommerce-pagination');
  var productsWrap = $('.woocommerce_main_left > ul.products');
  var nextPage = $('.next', parentThis).attr('href');
  $.post( nextPage, function( data ) {
    var pagination = $(data).find('.woocommerce-pagination').html();
    var products = $(data).find('.woocommerce_main_left > ul.products').html();
    parentThis.html(pagination);
    productsWrap.append(products);
    owl_changed();
    window.history.pushState("", "", nextPage);
  }).done(function() {
    //alert( "second success" );
  }).fail(function() {
    alert( "Có lỗi xảy ra" );
    oldThis.removeClass('devvn_loading2');
  });
  return false;
});
