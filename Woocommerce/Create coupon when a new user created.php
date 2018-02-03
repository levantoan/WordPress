<?php
/*
 * Create coupon when a new user created
 * Author: levantoan.com
 * */

add_action('woocommerce_created_customer', 'devvn_woocommerce_created_customer', 10, 3);
function devvn_woocommerce_created_customer($customer_id, $new_customer_data, $password_generated){
    $user_email =  $new_customer_data['user_email'];

    //Creat coupon
    $coupon_code = wp_generate_password(6, false);
    $amount = '10';
    $discount_type = 'percent'; // Type: fixed_cart, percent, fixed_product

    $coupon = array(
        'post_title' => $coupon_code,
        'post_content' => '',
        'post_status' => 'publish',
        'post_author' => 1,
        'post_type'		=> 'shop_coupon'
    );

    $new_coupon_id = wp_insert_post( $coupon );

    // Add meta
    update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
    update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
    update_post_meta( $new_coupon_id, 'individual_use', 'no' );
    update_post_meta( $new_coupon_id, 'product_ids', '' );
    update_post_meta( $new_coupon_id, 'exclude_product_ids', '' );
    update_post_meta( $new_coupon_id, 'usage_limit', 1 );
    update_post_meta( $new_coupon_id, 'expiry_date', '' );
    update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
    update_post_meta( $new_coupon_id, 'free_shipping', 'no' );
    update_post_meta( $new_coupon_id, 'customer_email', $user_email );

    //Send email
    $to = $user_email;
    $subject = 'Mã giảm giá 10% - '. get_bloginfo('name');
    $body = 'Cảm ơn bạn đã đăng ký <br>';
    $body .= 'Đây là mã giảm giá 10% cho bạn. Mã được sử dụng 1 lần duy nhất với email bạn đã đăng ký thành viên.';
    $body .= '<p style="text-align: center;"><strong style="border: 1px dashed red; padding: 5px 10px; display: inline-block;">'.$coupon_code.'</strong></p>';
    $headers = array('Content-Type: text/html; charset=UTF-8');
    $sendmail = wp_mail( $to, $subject, $body, $headers );
}
