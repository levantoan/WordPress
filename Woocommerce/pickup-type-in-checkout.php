<?php
/*
Author: levantoan.com
Khi ở trang cart ấn pickup sẽ nhảy sang trang thanh toán.
Lúc này trang thanh toán sẽ chỉ còn các trường họ tên và số điện thoại. còn lại sẽ bị ẩn hết và bỏ phương thức vận chuyển đi
*/

add_filter('woocommerce_checkout_fields','devvn_custom_checkout_fields', 999999);
function devvn_custom_checkout_fields($fields){
    $type = isset($_GET['type']) ? sanitize_text_field(wp_unslash($_GET['type'])) : '';

    $pickup_checkout = (isset($_REQUEST['pickup']) && $_REQUEST['pickup'] == 1) ? true : false;
    $wc_ajax = (isset($_REQUEST['wc-ajax']) && $_REQUEST['wc-ajax'] == 'checkout') ? true : false;

    if(($type && $type == 'pickup') || ($wc_ajax && $pickup_checkout)){
        add_filter('woocommerce_cart_needs_shipping', '__return_false');
        unset($fields['billing']['billing_last_name']);
        unset($fields['billing']['billing_company']);
        unset($fields['billing']['billing_country']);
        unset($fields['billing']['billing_postcode']);
        unset($fields['billing']['billing_state']);
        unset($fields['billing']['billing_city']);
        unset($fields['billing']['billing_address_2']);
        unset($fields['billing']['billing_email']);
        unset($fields['billing']['billing_address_1']);
    }
    return $fields;
}

add_action('woocommerce_checkout_after_customer_details', 'devvn_custom_checkout_fields_hidden');
function devvn_custom_checkout_fields_hidden(){
    $type = isset($_GET['type']) ? sanitize_text_field(wp_unslash($_GET['type'])) : '';
    if(($type && $type == 'pickup')) {
        ?>
        <input type="hidden" value="1" name="pickup">
        <?php
    }
}

add_action('woocommerce_checkout_update_order_review', 'disable_shipping_pickup');
function disable_shipping_pickup($post_data){
    parse_str($post_data, $params);
    $pickup = (isset($params['pickup']) && $params['pickup'] == 1) ? true : false;
    if($pickup){
        add_filter('woocommerce_cart_needs_shipping', '__return_false');
    }
}

add_action('woocommerce_after_cart_totals', 'devvn_button_pickup');
function devvn_button_pickup(){
    ?>
    <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>?type=pickup" class="checkout-button button alt wc-forward">
        <?php esc_html_e( 'Pickup', 'devvn' ); ?>
    </a>
    <?php
}
