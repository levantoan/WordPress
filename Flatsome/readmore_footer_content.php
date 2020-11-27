<?php
/*
Author: levantoan.com
Add to functions.php in your theme
*/

add_action( 'after_setup_theme', 'devvn_after_setup_theme' );
function devvn_after_setup_theme()
{
    function devvn_flatsome_products_footer_content()
    {
        if (is_product_category() || is_product_tag()) {
            $queried_object = get_queried_object();
            $content = get_term_meta($queried_object->term_id, 'cat_meta');
            if (!empty($content[0]['cat_footer'])) {
                echo '<hr/>';
                echo '<div class="devvn_more_less">';
                echo do_shortcode($content[0]['cat_footer']);
                echo '</div>';
            }
        }
    }
    add_action('flatsome_products_after', 'devvn_flatsome_products_footer_content');

    remove_action('flatsome_products_after', 'flatsome_products_footer_content');
}

add_action('wp_footer','devvn_more_less_taxonomy_flatsome');
function devvn_more_less_taxonomy_flatsome(){
    if(is_product_category() || is_product_tag()):
        ?>
        <style>
            .devvn_more_less {
                overflow: hidden;
                position: relative;
                margin-bottom: 20px;
                padding-bottom: 25px;
            }
            .devvn_more_less_taxonomy_flatsome {
                text-align: center;
                cursor: pointer;
                position: absolute;
                z-index: 10;
                bottom: 0;
                width: 100%;
                background: #fff;
            }
            .devvn_more_less_taxonomy_flatsome:before {
                height: 55px;
                margin-top: -45px;
                content: "";
                background: -moz-linear-gradient(top, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 100%);
                background: -webkit-linear-gradient(top, rgba(255,255,255,0) 0%,rgba(255,255,255,1) 100%);
                background: linear-gradient(to bottom, rgba(255,255,255,0) 0%,rgba(255,255,255,1) 100%);
                filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff00', endColorstr='#ffffff',GradientType=0 );
                display: block;
            }
            .devvn_more_less_taxonomy_flatsome a {
                color: #318A00;
                display: block;
            }
            .devvn_more_less_taxonomy_flatsome a:after {
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
            .devvn_more_less_taxonomy_flatsome_less:before {
                display: none;
            }
            .devvn_more_less_taxonomy_flatsome_less a:after {
                border-top: 0;
                border-left: 6px solid transparent;
                border-right: 6px solid transparent;
                border-bottom: 6px solid #318A00;
            }
        </style>
        <script>
            (function($){
                $(document).ready(function(){
                    if($('.devvn_more_less').length > 0){
                        var wrap = $('.devvn_more_less');
                        var current_height = wrap.height();
                        var your_height = 300;
                        if(current_height > your_height){
                            wrap.css('height', your_height+'px');
                            wrap.append(function(){
                                return '<div class="devvn_more_less_taxonomy_flatsome devvn_more_less_taxonomy_flatsome_show"><a title="Xem thêm" href="javascript:void(0);">Xem thêm</a></div>';
                            });
                            wrap.append(function(){
                                return '<div class="devvn_more_less_taxonomy_flatsome devvn_more_less_taxonomy_flatsome_less" style="display: none"><a title="Thu gọn" href="javascript:void(0);">Thu gọn</a></div>';
                            });
                            $('body').on('click','.devvn_more_less_taxonomy_flatsome_show', function(){
                                wrap.removeAttr('style');
                                $('body .devvn_more_less_taxonomy_flatsome_show').hide();
                                $('body .devvn_more_less_taxonomy_flatsome_less').show();
                            });
                            $('body').on('click','.devvn_more_less_taxonomy_flatsome_less', function(){
                                wrap.css('height', your_height+'px');
                                $('body .devvn_more_less_taxonomy_flatsome_show').show();
                                $('body .devvn_more_less_taxonomy_flatsome_less').hide();
                            });
                        }
                    }
                })
            })(jQuery)
        </script>
    <?php
    endif;
}
