<?php
/*
* Shortcode hiện nút nhận coupon giảm giá và chuyển hướng tới link aff
* Author: https://levantoan.com

* Add to functions.php in your theme

*/
add_shortcode('jsclick','devvn_jsclick');
function devvn_jsclick($atts){
    $atts = shortcode_atts( array(
        'coupon' => '',
        'linkto' => ''
    ), $atts, 'jsclick' );

    ob_start();?>
    <a href="javascript:void(0)" target="_blank" onclick="s=prompt('Copy và sử dụng coupon bên dưới khi thanh toán:','<?php echo $atts['coupon']?>'); window.open('<?php echo $atts['linkto']?>'); " class="coupon-code">Nhận mã</a>
    <?php
    return ob_get_clean();
}
