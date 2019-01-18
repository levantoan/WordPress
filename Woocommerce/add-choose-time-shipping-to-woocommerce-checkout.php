<?php
/*
Author levantoan.com
19.01.2019
============CSS
.devvn_shipping_time span.woocommerce-input-wrapper label {
    display: inline-block;
    float: left;
    vertical-align: middle;
    margin: 0 20px 0 0;
    cursor: pointer;
}
span.woocommerce-input-wrapper.shipping_time_other {
    clear: both;
}
.shipping_time_other{
    display: none;
}
===========JS
(function ($) {
    $(document).ready(function () {        
        $('.devvn_shipping_time input[name="shipping_time"]').on('change', function () {
            if($('.devvn_shipping_time input[name="shipping_time"]:checked').val() == 'Khác'){
                $('.shipping_time_other').show();
            }else{
                $('.shipping_time_other').hide();
            }
        });
    });
})(jQuery);

============ Add code to functions.php
*/

add_action('woocommerce_after_checkout_billing_form','devvn_time_checkout');
function devvn_time_checkout(){
    ?>
    <p class="form-row form-row-wide devvn_shipping_time">
        <label>Thời gian bạn có thể nhận hàng?</label>
        <span class="woocommerce-input-wrapper">
            <label>
                <input type="radio" name="shipping_time" value="Sáng" checked>
                <span>Sáng</span>
            </label>
            <label>
                <input type="radio" name="shipping_time" value="Chiều">
                <span>Chiều</span>
            </label>
            <label>
                <input type="radio" name="shipping_time" value="Tối">
                <span>Tối</span>
            </label>
            <label>
                <input type="radio" name="shipping_time" value="Khác">
                <span>Khác</span>
            </label>
        </span>
        <span class="woocommerce-input-wrapper shipping_time_other">
            <input name="shipping_time_other" class="input-text" type="text" value="" placeholder="Nhập thời gian cụ thể. Ví dụ: 15h 20/02/2019">
        </span>
    </p>
    <?php
}

add_action('woocommerce_checkout_process', 'devvn_time_checkout_process');
function devvn_time_checkout_process()
{
    $shipping_time = isset($_POST['shipping_time']) ? sanitize_text_field($_POST['shipping_time']) : '';
    $shipping_time_other = isset($_POST['shipping_time_other']) ? sanitize_text_field($_POST['shipping_time_other']) : '';
    if($shipping_time == 'Khác' && !$shipping_time_other){
        wc_add_notice(__('Hãy nhập thời gian bạn có thể nhận hàng') , 'error');
    }
}

add_action('woocommerce_checkout_update_order_meta', 'devvn_time_checkout_save');
function devvn_time_checkout_save($order_id)
{
    if (isset($_POST['shipping_time']) && !empty($_POST['shipping_time'])) {
        $shipping_time = sanitize_text_field($_POST['shipping_time']);
        $shipping_time_other = isset($_POST['shipping_time_other']) ? sanitize_text_field($_POST['shipping_time_other']) : '';
        if($shipping_time == 'Khác' && $shipping_time_other) $shipping_time = $shipping_time_other;
        update_post_meta($order_id, 'shipping_time', $shipping_time);
    }
}

add_action( 'woocommerce_admin_order_data_after_shipping_address', 'devvn_after_shipping_address_vat', 99);
function devvn_after_shipping_address_vat($order)
{
    $shipping_time = get_post_meta($order->get_id(), 'shipping_time', true);
    if($shipping_time){
        echo '<div class=""><strong>Thời gian có thể nhận hàng:</strong> '.$shipping_time.'</div>';
    }
}
