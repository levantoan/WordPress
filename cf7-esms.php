<?php
/*
* Send price to sms - Cf7 - Esms
* Author: levantoan.com
*/

function process_contact_form_data( $cf7 ){
    if (!isset($cf7->posted_data) && class_exists('WPCF7_Submission')) {
        $submission = WPCF7_Submission::get_instance();
        if ($submission) {
            $post_data = $submission->get_posted_data();
            $tel = esc_html($post_data['tel-912']);
            $postID = esc_html($post_data['_wpcf7_container_post']);
            $_wpcf7ID = intval($post_data['_wpcf7']);
            if($_wpcf7ID == 534 && $tel){
                $prod = wc_get_product($postID);
                if($prod && !is_wp_error($prod)){
                    $price = $prod->get_price() . 'VND';

                    $YourPhone = $tel;
                    $APIKey = ''; //API key
                    $SecretKey = ''; //Secret Key
                    $smstype = 2;
                    $brandname = 'QCAO_ONLINE';

                    $is_unicode = 1;//1 không dấu; 0 - có đấu
                    $Content = 'Nội dung. Giá sp là ' . $price;
                    $sandbox = 0;

                    if($is_unicode == 1){
                        $Content = remove_accents($Content);
                        $is_unicode_convert = 0;
                    }else{
                        $is_unicode_convert = 1;
                    }

                    $SendContent = urlencode($Content);

                    $params = "Phone=$YourPhone&ApiKey=$APIKey&SecretKey=$SecretKey&Content=$SendContent&SmsType=$smstype&IsUnicode=$is_unicode_convert&Sandbox=$sandbox";

                    if(($smstype == 1 || $smstype == 2) && $brandname){
                        $params .= '&Brandname='.$brandname;
                    }

                    $data = "http://rest.esms.vn/MainService.svc/json/SendMultipleMessage_V4_get?".$params;

                    wp_remote_request(esc_url_raw($data));
                }
            }
        }
    }
    return true;
}
add_action( 'wpcf7_before_send_mail', 'process_contact_form_data' );
