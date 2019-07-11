<?php

add_action('woocommerce_after_single_product','devvn_fixed_bar_add_to_cart');
function devvn_fixed_bar_add_to_cart(){
    global $product;
    ob_start();?>
    <style>
        .devvn_woo_fixed_bar {
            bottom: 0;
            left: 50%;
            background: #fff;
            width: 100%;
            max-width: 1000px;
            position: fixed;
            z-index: 99;
            box-shadow: 0 1px 1px #e8e8e8;
            -webkit-box-shadow: 0 1px 1px #e8e8e8;
            -moz-box-shadow: 0 1px 1px #e8e8e8;
            transform: translate3d(-50%,0,0);
            -moz-transform: translate3d(-50%,0,0);
            -webkit-transform: translate3d(-50%,0,0)
        }

        .devvn_woo_fixed_bar ul {
            width: 100%;
            position: relative;
            display: table;
            margin: 0;
            padding: 0;
            list-style: none
        }

        .devvn_woo_fixed_bar ul li {
            display: table-cell;
            list-style: none;
            margin: 0;
            background: #00bfa5;
            vertical-align: middle;
            text-align: center;
            position: relative
        }

        .devvn_woo_fixed_bar ul li:first-child {
            width: 100px
        }

        i.fixed_icon svg {
            width: 20px;
            height: auto
        }

        .devvn_woo_fixed_bar ul li a {
            display: block;
            padding: 5px 10px;
            text-align: center;
            color: #fff;
            text-decoration: none;
            line-height: 1
        }

        .devvn_woo_fixed_bar ul li a span {
            display: block
        }

        .devvn_woo_fixed_bar ul li.fixed_add_cart i.fixed_icon {
            display: inline-block;
            vertical-align: middle;
            margin-right: 10px
        }

        .devvn_woo_fixed_bar ul li.fixed_add_cart i.fixed_icon svg {
            width: 35px
        }

        .devvn_woo_fixed_bar ul li.fixed_add_cart span {
            display: inline-block;
            vertical-align: middle;
            line-height: 1.3
        }

        .devvn_woo_fixed_bar ul li.fixed_add_cart {
            background-color: #f52e2e
        }
        .devvn_woo_fixed_bar .fa-phone {
            font-size: 20px;
            display: block;
            margin: 0 0 5px 0;
        }
        .single-product .show_phone_mobile {
            display: none !important;
        }
        .devvn_woo_fixed_bar {
            display: none
        }
        @media screen and (max-width: 849px) {
            .menuStick .devvn_woo_fixed_bar {
                display: block;
            }
        }
    </style>
    <div class="devvn_woo_fixed_bar">
        <ul>
            <li>
                <a href="tel:0902785760" title="Gọi ngay">
                    <i class="fa fa-phone"></i>
                    <span>Gọi điện</span>
                </a>
            </li>
            <li class="fixed_add_cart">
                <a href="javascript:void(0)" title="Mua ngay" class="button_fixed_add_cart devvn_buy_now" data-id="<?php echo $product->get_id();?>">
                    <i class="fixed_icon fixed_icon_home"><svg viewBox="0 0 24 23" class="navbar-icon-cart__icon"><g fill="#fff" stroke="none" stroke-width="1" fill-rule="evenodd"><g transform="translate(-297.000000, -53.000000)"><g transform="translate(0.000000, 40.000000)"><g transform="translate(295.000000, 13.000000)"><g transform="translate(2.000000, 0.000000)"><path d="M18.5002885,23 C17.6728217,23 17,22.3273077 17,21.5 C17,20.6726923 17.6728217,20 18.5002885,20 C19.3271783,20 20,20.6726923 20,21.5 C20,22.3273077 19.3271783,23 18.5002885,23 L18.5002885,23 Z"></path><path d="M9.50028852,23 C8.6728217,23 8,22.3273077 8,21.5 C8,20.6726923 8.6728217,20 9.50028852,20 C10.3271783,20 11,20.6726923 11,21.5 C11,22.3273077 10.3271783,23 9.50028852,23 L9.50028852,23 Z"></path><path d="M7.9934261,18 C7.5,17.9999997 7.44766941,17.9045091 7.37992981,17.6538769 L3.4182231,0.995818455 L0.499919604,0.995818455 C0.223821631,0.995818455 0,0.764854702 0,0.497909228 C0,0.222921554 0.215802421,0 0.499919604,0 L3.52239128,5.32907052e-14 C3.64097266,5.32907052e-14 4,5.32907052e-14 4.31735235,0.474991444 L5.3999939,5 L23.6021522,5 C23.8760941,4.97909228 24.0502466,5.18813168 23.9870265,5.46390611 L22.8991683,9.42796956 C22.1654968,12.1014069 20.6981536,17.4482814 20.6981536,17.4482814 C20.6367719,17.7160367 20.2726916,17.9999997 20,17.9999997 C20,17.9999997 8.05057801,18 7.9934261,18 Z M5.70001221,6.00619897 L8.08934998,16.5134247 C8.15228879,16.7902011 8.42315514,17 8.69843471,17 L19.3015653,17 C19.5786357,17 19.8591915,16.7821528 19.9322042,16.5134247 L22.6547866,6.49277423 C22.7299859,6.21599786 22.5662854,6.00619897 22.2940352,6.00619897 L5.70001221,6.00619897 Z"></path></g></g></g></g></g></svg></i>
                    <span>
                        <strong>Mua ngay</strong><br>
                        (Hàng đến trả tiền)
                    </span>
                </a>
            </li>
        </ul>
    </div>
    <?php
    echo ob_get_clean();
}
