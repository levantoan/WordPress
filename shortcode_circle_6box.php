<?php
/*
*** Insert code to functions.php
View example: https://lh3.googleusercontent.com/F_VEn_ivUA8U2AjRbeO2Vicn4KuD_o3u6S4xeeSa2rlGdXxGcvnhxRI3xn76FFUuFl904A8Eoqyx681t8gd1-YIHOR8gCz99Pp5kLKTofp4zzHh4yZxO3MdqfZBaWM-CGjzB_R4jhA81PeDdPHiHXbefAAmdaXKO3gxXgIpieKK4OFcWWA1YlvnIm-cs69Mrluuya9nVtWd2NnOSDmVa_p0DXTLKiAQ2-01xUddcxJ5M9od6noHqSd3g_4mqk2GbS-ppdpLKBZKuPGQitXWA3dPcn1lWMic8t7TKwGJO9bmtTRXZ2Pf1pAVqfBRAylTqxRIV-Q4wdgIfW6mJ-PKIHswnuKY2_zWper3CZr1jpH4ftEjt_4NTMo2_yIuP42BccrYCDgE3ide5mqKqAauIJABvy5eYNkm4sDBLmhwl0F93OHPpruuj_6JUAsXW1V9e2NlN6EQ3qDeCvJtCqwlNR1gJwG6ipaIeTEyCcJi-je4FyKU-gDnhu5CHjMTbv5ikGPHGyYaojjJ_drW4y9KyCBBmLG0-rg8CtZr0Lxvbe9hJZNCvE90-HTRdPCyPaJeHyTbzzSo7a_ACxM8T-C7CF0ViHpHwsxxIjRWEmwsVOi9hOHp6xiTX=w432-h434-no
*** Use shortcode: 
[toancircle main="" icon1="" icon2="" icon3="" icon4="" icon5="" icon6=""]
*** Option
main:  text|url_link|href_link|color
icon1: text|url_link|href_link|color
icon2: text|url_link|href_link|color
icon3: text|url_link|href_link|color
icon4: text|url_link|href_link|color
icon5: text|url_link|href_link|color
icon6: text|url_link|href_link|color
*** Full Example:
[toancircle main="WALLET|http://toan-plugins.com/wp-content/uploads/wallet-gamedlc.svg|#|#f27022" icon1="Orders|http://toan-plugins.com/wp-content/uploads/wallet-gamedlc.svg|#|#008000" icon2="Orders|http://toan-plugins.com/wp-content/uploads/wallet-gamedlc.svg|#|#008000" icon3="Orders|http://toan-plugins.com/wp-content/uploads/wallet-gamedlc.svg|#|#008000" icon4="Orders|http://toan-plugins.com/wp-content/uploads/wallet-gamedlc.svg|#|#008000"]
*/
add_shortcode('toancircle','toancircle_func');
function toancircle_func($atts){
    extract(shortcode_atts(array(
        'main'  =>  '',
        'icon1' =>  '',
        'icon2' =>  '',
        'icon3' =>  '',
        'icon4' =>  '',
        'icon5' =>  '',
        'icon6' =>  '',
    ),$atts,'toancircle'));
    $main = explode("|",$main);
    $icon1 = explode("|",$icon1);
    $icon2 = explode("|",$icon2);
    $icon3 = explode("|",$icon3);
    $icon4 = explode("|",$icon4);
    $icon5 = explode("|",$icon5);
    $icon6 = explode("|",$icon6);
    $list = array($icon1,$icon2,$icon3,$icon4,$icon5,$icon6);
    ob_start();
    if($main && is_array($main)):
        ?>
        <div class="devvn_selector_wrap">
            <div class="devvn_selector">
                <?php
                $text = (isset($main[0]))?esc_attr($main[0]):'';
                $img = (isset($main[1]))?esc_url($main[1]):'';
                $link = (isset($main[2]))?esc_url($main[2]):'';
                $color = (isset($main[3]))?esc_url($main[3]):'#000';
                ?>
                <button class="toan_circle_main_img">
                    <a href="<?php echo $link;?>" style="color: <?php echo $color;?>;">
                        <img src="<?php echo $img;?>" class="papa"><br>
                        <strong><?php echo $text;?></strong>
                    </a>
                </button>
                <?php if($list && is_array($list)):?>
                    <ul class="toan_circle_main_list">
                        <?php foreach($list as $icon):
                            $text = (isset($icon[0]))?esc_attr($icon[0]):'';
                            $img = (isset($icon[1]))?esc_url($icon[1]):'';
                            $link = (isset($icon[2]))?esc_url($icon[2]):'';
                            $color = (isset($icon[3]))?esc_url($icon[3]):'#000';
                            if($text || $img):
                                ?>
                                <li>
                                    <label>
                                        <a href="<?php echo $link;?>" style="color: <?php echo $color;?>">
                                            <img src="<?php echo $img;?>" class="papa"><br>
                                            <?php echo $text;?>
                                        </a>
                                    </label>
                                </li>
                            <?php endif;?>
                        <?php endforeach;?>
                    </ul>
                <?php endif;?>
            </div>
        </div>
        <style>
            .devvn_selector_wrap *,.devvn_selector_wrap{
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
            }
            .devvn_selector_wrap{
                width: 400px;
                height: 400px;
                display: block;
                position: relative;
            }
            .devvn_selector {
                position: absolute;
                width: 160px;
                height: 160px;
                z-index: 9;
                text-align: center;
                left: 50%;
                top: 50%;
                margin-top: -80px;
                margin-left: -80px;
            }
            .devvn_selector .toan_circle_main_img {
                position: relative;
                width: 100%;
                height: 100%;
                padding: 10px;
                background: #ffffff;
                border-radius: 50%;
                border: 0;
                color: #ff9900;
                font-size: 20px;
                cursor: pointer;
                transition: all .1s;
                box-shadow: 0 8px 2px rgba(0, 0, 0, 0.3);
                z-index: 99;
                outline: none;
            }
            .devvn_selector ul {
                position: absolute;
                list-style: none;
                padding: 0;
                margin: 0;
                top: -20px;
                right: -20px;
                bottom: -20px;
                left: -20px;
                z-index: 9;
            }
            .devvn_selector li {
                position: absolute;
                width: 0;
                height: 100%;
                margin: 0 50%;
                -webkit-transform: rotate(-360deg);
                -moz-transform: rotate(-360deg);
                transform: rotate(-360deg);
                transition: all 1.5s ease-in-out;
                -moz-transition: all 1.5s ease-in-out;
                -webkit-transition: all 1.5s ease-in-out;
                z-index: 9;
            }
            .devvn_selector li label {
                position: absolute;
                left: 100%;
                bottom: 100%;
                width: 0;
                height: 0;
                line-height: 1px;
                margin-left: 0;
                background: #fff;
                border-radius: 50%;
                -moz-border-radius: 50%;
                -webkit-border-radius: 50%;
                text-align: center;
                font-size: 1px;
                cursor: pointer;
                box-shadow: none;
                transition: all 0.8s ease-in-out, color 0.1s, background 0.1s;
                color: #333333;
                z-index: 9;
                max-width: inherit;
                display: block;
            }
            .devvn_selector.open li label {
                width: 100px;
                height: 100px;
                margin-left: -40px;
                box-shadow: 0 8px 2px rgba(0, 0, 0, 0.3);
                font-size: 14px;
                z-index: 9;
                overflow: hidden;
            }
            .devvn_selector .papa {
                width: 70px;
                display: inline-block !important;
            }
            .devvn_selector ul li .papa {
                width: 55px;
                margin: 8px auto 5px;
            }
            .devvn_selector a {
                text-decoration: none;
                border: 0;
            }
            .devvn_selector a:hover,.devvn_selector a:focus {
                text-decoration: none;
                outline: none;
            }

            @media (max-width: 499px) {
                .devvn_selector_wrap {
                    width: 100%;
                    height: inherit;
                }
                .devvn_selector {
                    position: relative;
                    top: 0;
                    margin-top: 0;
                    width: 220px;
                    height: 220px;
                    margin-left: -110px;
                }
                .devvn_selector ul {
                    position: relative;
                    top: inherit;
                    right: inherit;
                    left: 0;
                    bottom: inherit;
                }
                .devvn_selector li {
                    position: relative;
                    transform: inherit !important;
                    -moz-transform: inherit !important;
                    -webkit-transform: inherit !important;
                    height: inherit;
                    margin: 0 0 20px 0 !important;
                    width: 50%;
                    float: left;
                }
                .devvn_selector li label {
                    position: relative;
                    left: inherit;
                    bottom: inherit;
                    line-height: inherit;
                    display: block;
                    width: 100px;
                    height: 100px;
                    box-shadow: 0 8px 2px rgba(0, 0, 0, 0.3);
                    font-size: 14px;
                    z-index: 9;
                    overflow: hidden;
                    transform: inherit !important;
                    -moz-transform: inherit !important;
                    -webkit-transform: inherit !important;
                }
                .devvn_selector li label,.devvn_selector.open li label {
                    position: relative;
                    left: inherit;
                    bottom: inherit;
                    line-height: inherit;
                    font-size: inherit;
                    display: block;
                    width: 100px;
                    height: 100px;
                    margin-left: 0;
                }
            }
        </style>
        <script>
            (function ($) {
                $(document).ready(function () {
                    $(window).load(function () {
                        var angleStart = -360;
                        function rotate(li,d) {
                            $({d:angleStart}).animate({d:d}, {
                                step: function(now) {
                                    $(li)
                                        .css({
                                            transform: 'rotate('+(now-3)+'deg)',
                                            WebkitTransition : 'rotate('+(now-3)+'deg)',
                                            MozTransition    : 'rotate('+(now-3)+'deg)',
                                            MsTransition     : 'rotate('+(now-3)+'deg)',
                                            OTransition      : 'rotate('+(now-3)+'deg)',
                                        })
                                        .find('label')
                                        .css({
                                            transform: 'rotate('+(-(now-3))+'deg)',
                                            WebkitTransition : 'rotate('+(-(now-3))+'deg)',
                                            MozTransition    : 'rotate('+(-(now-3))+'deg)',
                                            MsTransition     : 'rotate('+(-(now-3))+'deg)',
                                            OTransition      : 'rotate('+(-(now-3))+'deg)',
                                        });
                                }, duration: 0
                            });
                        }
                        function toggleOptions(s) {
                            if(!$(s).hasClass('open')) {
                                $(s).toggleClass('open');
                                var li = $(s).find('li');
                                var deg = $(s).hasClass('half') ? 180 / (li.length - 1) : 360 / li.length;
                                for (var i = 0; i < li.length; i++) {
                                    var d = $(s).hasClass('half') ? (i * deg) - 90 : i * deg;
                                    $(s).hasClass('open') ? rotate(li[i], d) : rotate(li[i], angleStart);
                                }
                            }
                        }
                        function toan_load_element(){
                            var thisTop = $('.devvn_selector_wrap').offset().top;
                            var winHeight = $(window).height();
                            var scrollTop = $(document).scrollTop();
                            if((scrollTop+winHeight) >= (thisTop+300)){
                                if (matchMedia('only screen and (min-width: 500px)').matches) {
                                    toggleOptions('.devvn_selector');
                                }
                            }
                        }
                        toan_load_element();
                        $(window).resize(function () {
                            toan_load_element();
                        });
                        $(window).scroll(function () {
                            toan_load_element();
                        });
                    })
                })
            })(jQuery);
        </script>
        <?php
    endif;//#end main
    return ob_get_clean();
}