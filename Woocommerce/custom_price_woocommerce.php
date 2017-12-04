<?php
/*
After:
Giá bán: 20 000 VNĐ (Lấy từ giá khuyến mại)
Giá hãng: 30 000 VNĐ (Lấy từ giá gốc)
*/

add_filter('woocommerce_get_price_html','devvn_woocommerce_get_price_html', 999, 2);
function devvn_woocommerce_get_price_html($price, $product){
    $gia_hang = $product->get_regular_price();
    $gia_ban = $product->get_sale_price();
    if($gia_hang || $gia_ban){
        $gia_ban = ($gia_ban && $gia_ban != 0) ? wc_price( $gia_ban ) . $product->get_price_suffix() : 'Liên hệ';
        $gia_hang = ($gia_hang && $gia_hang != 0) ? wc_price( $gia_hang ) . $product->get_price_suffix() : 'Liên hệ';
        $price = '<div class="gia_ban">Giá bán: '.$gia_ban.'</div>';
        $price .= '<div class="gia_hang">Giá hãng: '.$gia_hang.'</div>';
    }else{
        $price = '<div class="gia_ban">Giá bán: Liên hệ</div>';
    }
    return $price;
}
