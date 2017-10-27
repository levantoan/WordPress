<?php
/*
Add readmore for content single product woocommerce - flatsoome theme
Author: Le Van Toan - levantoan.com
*/
add_action('wp_footer','devvn_readmore_flatsome');
function devvn_readmore_flatsome(){
    ?>
    <style>
        .single-product div#tab-description {
            overflow: hidden;
        }
        .devvn_readmore_flatsome {
            text-align: center;
            cursor: pointer;
            position: relative;
            z-index: 10;
        }
        .devvn_readmore_flatsome:before {
            height: 55px;
            margin-top: -45px;
            content: -webkit-gradient(linear,0% 100%,0% 0%,from(#fff),color-stop(.2,#fff),to(rgba(255,255,255,0)));
            display: block;
        }
        .devvn_readmore_flatsome a {
            color: #318A00;
            display: block;
        }
        .devvn_readmore_flatsome a:after {
            content: '';
            width: 0;
            right: 0;
            border-top: 6px solid #318A00;
            border-left: 6px solid transparent;
            border-right: 6px solid transparent;
            display: inline-block;
            vertical-align: middle;
            margin: -2px 0 0 5px;
        }
    </style>
    <script>
        (function($){
            $(document).ready(function(){
                $(window).load(function(){
                    if($('.single-product div#tab-description').length > 0){
                        var wrap = $('.single-product div#tab-description');
                        var current_height = wrap.height();
                        var your_height = 450;
                        if(current_height > your_height){
                            wrap.css('height', your_height+'px');
                            wrap.after(function(){
                                return '<div class="devvn_readmore_flatsome"><a title="Xem thêm" href="javascript:void(0);">Xem thêm</a></div>';
                            });
                            $('body').on('click','.devvn_readmore_flatsome', function(){
                                wrap.removeAttr('style');
                                $('.devvn_readmore_flatsome').remove();
                            });
                        }
                    }
                });
            })
        })(jQuery)
    </script>
    <?php
}