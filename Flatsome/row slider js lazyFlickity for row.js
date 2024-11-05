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

/*php

add_action('wp_footer', function(){
    ?>
    <script>
        (function ($){
        	$(document).ready(function() {
        		$('.row-slider-image-box').each(function(){
        			$(this).find('style[scope="scope"]').remove();
        			$(this).lazyFlickity({
            			"cellAlign": "left",
                          "wrapAround": false,
                          "autoPlay": false,
                          "prevNextButtons": true,
                          "percentPosition": true,
                          "imagesLoaded": true,
                          "pageDots": true,
                          "rightToLeft": false,
                          "contain": true,
                          lazyLoad: 1,
        			});
        		});
        	});
        })(jQuery);
        </script>
    <?php
});
*/
