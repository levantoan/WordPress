<?php
add_action('user_register', 'devvn_woocommerce_created_customer', 10, 2);
function devvn_woocommerce_created_customer($customer_id, $new_customer_data){
    $user_email =  $new_customer_data['user_email'];

    if($user_email) {
        //Creat coupon
        $coupon_code = wp_generate_password(6, false);
        $amount = '0';
        $discount_type = 'fixed_cart'; // Type: fixed_cart, percent, fixed_product

        $coupon = array(
            'post_title' => $coupon_code,
            'post_content' => '',
            'post_status' => 'publish',
            'post_author' => 1,
            'post_type' => 'shop_coupon'
        );

        $new_coupon_id = wp_insert_post($coupon);

        // Add meta
        update_post_meta($new_coupon_id, 'discount_type', $discount_type);
        update_post_meta($new_coupon_id, 'coupon_amount', $amount);
        update_post_meta($new_coupon_id, 'individual_use', 'no');
        update_post_meta($new_coupon_id, 'product_ids', '');
        update_post_meta($new_coupon_id, 'exclude_product_ids', '');
        update_post_meta($new_coupon_id, 'usage_limit', 1);
        update_post_meta($new_coupon_id, 'expiry_date', '');
        update_post_meta($new_coupon_id, 'apply_before_tax', 'yes');
        update_post_meta($new_coupon_id, 'free_shipping', 'yes');
        update_post_meta($new_coupon_id, 'customer_email', $user_email);

        //Send email
        $subject = 'Tặng mã FREESHIP';
        $finalmsg = '<p>Bạn đã được tặng mã FREESHIP khi tạo tài khoản mới tạo.</p>' ;
        $finalmsg .= 'Mã Freeship là: <strong style="border: 1px dashed red; padding: 5px 10px; display: inline-block;">' . $coupon_code . '</strong>';
        $finalmsg .= '<br>Hạn mức sử dụng: 1 lần duy nhất';

        $headers = "MIME-Version: 1.0\r\n" ;
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n" ;
        $headers .= "From: " . get_option( 'woocommerce_email_from_name' ) . " <" . get_option( 'woocommerce_email_from_address' ) . ">\r\n" ;
        $headers .= "Reply-To: " . get_option( 'woocommerce_email_from_name' ) . " <" . get_option( 'woocommerce_email_from_address' ) . ">\r\n" ;

        ob_start() ;
        wc_get_template( 'emails/email-header.php' , array( 'email_heading' => $subject ) ) ;
        echo $finalmsg ;
        //wc_get_template( 'emails/email-footer.php' ) ;

        $woo_temp_msg = ob_get_clean() ;

        $mailer = WC()->mailer() ;
        $mailer->send( $user_email , $subject , $woo_temp_msg , $headers ) ;

    }
}
