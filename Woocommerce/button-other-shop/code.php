<?php

add_action('woocommerce_after_single_product_summary', 'devvn_woocommerce_after_single_product_summary', 5);
function devvn_woocommerce_after_single_product_summary(){
    global $product;
    if(have_rows('shop_link')):
        ?>
        <style>
            .buy_other_shop {
                margin-bottom: 15px;
                background: #fff;
                padding: 15px;
                font-size: 16px;
            }
            .buy_other_shop_box {
                overflow: hidden;
                width: 100%;
            }
            .other_shop_box {
                width: 33.333%;
                float: left;
                margin: 0 0 20px 0;
                padding: 0 10px;
            }
            .other_shop_box a {
                background: #000;
                color: #fff;
                display: block;
                width: 100%;
                text-align: center;
                display: flex;
                align-items: center;
                justify-content: space-between;
                align-content: center;
                padding: 5px 15px;
                border-radius: 10px;
                font-weight: 700;
                height: 45px;
            }
            .other_shop_box img {
                width: auto;
                height: 35px;
                vertical-align: middle;
                line-height: 1;
                margin: 0 6px 0 0;
            }

            a.devvn_buy_now.devvn_buy_now_style {
                max-width: 100%;
            }
            .other_shop_box a.other_shop_shopee {
                background: orangered;
            }
            .other_shop_box a.other_shop_shopee:hover {
                background: #d93f06;
            }
            .other_shop_box a.other_shop_lazada {
                background: #0e146b;
            }
            .other_shop_box a.other_shop_lazada:hover {
                background: #1a1d4f;
            }
            .other_shop_box a.other_shop_tiki {
                background: #1a94ff;
            }
            .other_shop_box a.other_shop_tiki:hover {
                background: #0573d3;
            }

            .other_shop_box a.other_shop_tiktok {
                background: #000;
            }
            .other_shop_box a.other_shop_tiktok:hover {
                background: #000;
            }
            .other_shop_box a.other_shop_vuivui {
                background: #f05a94;
            }
            .other_shop_box a.other_shop_vuivui:hover {
                background: #d92c6f;
            }

            .only-mobile{
                display: none;
            }
            @media (max-width: 950px) {
                .other_shop_box {
                    width: 50%;
                }
            }
            @media (max-width: 767px) {
                .only-mobile{
                    display: block;
                }
            }
            @media (max-width: 700px) {
                .other_shop_box {
                    width: 100%;
                    margin: 0 0 10px 0;
                }
            }
        </style>
        <div class="buy_other_shop">
            <p>Bạn có thể mua "<?php echo $product->get_name(); ?>" trên sàn thương mại để nhận nhiều chương trình
                Khuyến mãi hơn</p>
            <div class="buy_other_shop_box">
                <?php while (have_rows('shop_link')): the_row();
                    $chon_san = get_sub_field('chon_san');
                    $link_to = get_sub_field('link_to');
                    $icon = get_sub_field('icon');
                    $name = get_sub_field('name');
                    $class = '';
                    switch ($chon_san) {
                        case 'shopee':
                            $icon = get_stylesheet_directory_uri() . '/images/shopee.png';
                            $name = 'Trên Shopee';
                            break;
                        case 'tiki':
                            $icon = get_stylesheet_directory_uri() . '/images/tiki.png';
                            $name = 'Trên Tiki';
                            break;
                        case 'lazada':
                            $icon = get_stylesheet_directory_uri() . '/images/lazada.png';
                            $name = 'Trên Lazada';
                            break;
                        case 'vuivui':
                            $icon = get_stylesheet_directory_uri() . '/images/vuivui.png';
                            $name = '';
                            break;
                        case 'tiktok':
                            $icon = get_stylesheet_directory_uri() . '/images/tiktok.png';
                            $name = '';
                            $class = 'only-mobile';
                            break;
                    }
                    ?>
                    <div class="other_shop_box <?php echo esc_attr($class);?>">
                        <a href="<?php echo esc_url($link_to);?>" class="other_shop_<?php echo esc_attr($chon_san);?>" title="Mua trên <?php echo $name;?>" target="_blank">
                            <span>
                                <img src="<?php echo esc_url($icon);?>" alt="">
                                <?php echo $name;?>
                            </span>
                            <span>Xem ngay</span>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    <?php
    endif;
}
