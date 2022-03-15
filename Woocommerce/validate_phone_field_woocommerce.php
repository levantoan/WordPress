<?php
/////Code kiểm tra số điện thoại có đúng không
add_action('woocommerce_checkout_process', 'devvn_validate_phone_field_process');
function devvn_validate_phone_field_process() {
    $billing_phone = filter_input(INPUT_POST, 'billing_phone');
    /*if ( ! (preg_match('/^(09|08)([0-9]{8})+$|^01([0-9]{9})+$/D', $billing_phone )) ){*/
    if ( ! (preg_match('/^0([0-9]{9,10})+$/D', $billing_phone )) ){
        wc_add_notice( "Xin nhập đúng <strong>số điện thoại</strong> của bạn"  ,'error' );
    }
}

/////Code xác nhận lại sđt
add_filter('devvn_checkout_fields', 'store_checkout_fields', 9999999);
function store_checkout_fields($fields)
{
    $fields['billing']['billing_first_name']['class'] = array('form-row-first');

    $fields['billing']['billing_email']['class'] = array('form-row-last');
    $fields['billing']['billing_email']['priority'] = 19;

    $fields['billing']['valid_phone'] = array(
        'class' => array('form-row-last'),
        'label' => 'Xác nhận lại số điện thoại',
        'placeholder' => 'Nhập lại số điện thoại của bạn',
        'clear' => true,
        'required' => true,
        'priority' => 21,
        'type' => 'text',
        'custom_attributes' => array(
            'onpaste' => "return false;",
            'ondrop' => "return false;",
            'autocomplete' => "off",
        )
    );

    return $fields;
}

add_action('woocommerce_checkout_process', 'devvn_validate_billing_phone');
function devvn_validate_billing_phone() {

    $billing_phone = filter_input(INPUT_POST, 'billing_phone');

    if ( ! (preg_match('/^0([0-9]{9,10})+$/D', $billing_phone )) ){
        wc_add_notice( "Xin nhập đúng <strong>số điện thoại</strong> của bạn"  ,'error' );
    }

    if(isset($_POST['valid_phone']) && $billing_phone != $_POST['valid_phone']){
        wc_add_notice( __( 'Số điện thoại không trùng nhau' ), 'error' );
    }
}
