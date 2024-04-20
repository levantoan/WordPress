if($('.devvn_slider_prod').length){
    $('.devvn_slider_prod').each(function(){
        $(this).lazyFlickity({
      "cellAlign": "left",
      "wrapAround": false,
      "autoPlay": false,
      "prevNextButtons": true,
      "percentPosition": true,
      "imagesLoaded": true,
      "pageDots": true,
      "rightToLeft": false,
      "contain": true
        })
    });
}
