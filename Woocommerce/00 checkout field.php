<?php

add_filter('woocommerce_checkout_fields', 'dms_custom_override_checkout_fields', 9999999);
function dms_custom_override_checkout_fields($fields)
{
    //billing
    $fields['billing']['billing_first_name'] = array(
        'label' => __('Họ và tên', 'devvn'),
        'placeholder' => _x('Họ và tên', 'placeholder', 'devvn'),
        'required' => true,
        'class' => array('form-row-first'),
        'clear' => true,
        'priority' => 10
    );
    unset($fields['billing']['billing_last_name']);
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_city']);
    unset($fields['billing']['billing_address_2']);
    $fields['billing']['billing_phone']['priority'] = 20;
    $fields['billing']['billing_phone']['class'] = array('form-row-last');
    $fields['billing']['billing_phone']['placeholder'] = _x('Số điện thoại', 'placeholder', 'devvn');
    $fields['billing']['billing_address_1']['class'] = array('form-row-wide');
    $fields['billing']['billing_address_1']['priority'] = 22;


    $fields['billing']['billing_email']['priority'] = 25;
    $fields['billing']['billing_email']['class'] = array('form-row-wide');
    $fields['billing']['billing_email']['required'] = false;

    //shipping
    $fields['shipping']['shipping_first_name'] = array(
        'label' => __('Họ và tên', 'devvn'),
        'placeholder' => _x('Họ và tên', 'placeholder', 'devvn'),
        'required' => true,
        'class' => array('form-row-first'),
        'clear' => true,
        'priority' => 10
    );
    unset($fields['shipping']['shipping_last_name']);
    unset($fields['shipping']['shipping_company']);
    unset($fields['shipping']['shipping_country']);
    unset($fields['shipping']['shipping_state']);
    unset($fields['shipping']['shipping_postcode']);
    unset($fields['shipping']['shipping_city']);
    unset($fields['shipping']['shipping_address_2']);
    $fields['shipping']['shipping_address_1']['class'] = array('form-row-wide');
    $fields['shipping']['shipping_phone'] = array(
        'label' => __('Số điện thoại', 'devvn'),
        'placeholder' => _x('Số điện thoại', 'placeholder', 'devvn'),
        'required' => true,
        'class' => array('form-row-last'),
        'clear' => true,
        'priority'  =>  20
    );

    uasort($fields['billing'], 'dms_sort_fields_by_order');
    uasort($fields['shipping'], 'dms_sort_fields_by_order');

    return $fields;
}

if(!function_exists('dms_sort_fields_by_order')) {
    function dms_sort_fields_by_order($a, $b)
    {
        if (!isset($b['priority']) || !isset($a['priority']) || $a['priority'] == $b['priority']) {
            return 0;
        }
        return ($a['priority'] < $b['priority']) ? -1 : 1;
    }
}


add_action( 'woocommerce_admin_order_data_after_shipping_address', 'my_custom_checkout_field_display_admin_order_meta', 10, 1 );
function my_custom_checkout_field_display_admin_order_meta($order){
    echo '<p><strong>'.__('Số ĐT người nhận').':</strong> <br>' . get_post_meta( $order->id, '_shipping_phone', true ) . '</p>';
}
