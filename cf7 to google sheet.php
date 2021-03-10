<?php
function save_cf7_to_google_sheet( $cf7 ){
    if (!isset($cf7->posted_data) && class_exists('WPCF7_Submission')) {
        $submission = WPCF7_Submission::get_instance();
        $form_id = $cf7->id();
        if ($submission) {
            $post_data = $submission->get_posted_data();

            if($form_id == 11877){

                $google_form_url = '';
                wp_remote_post($google_form_url, array(
                    'body' => array(
                        'entry.157340497'   => sanitize_text_field($post_data['your-name']),
                        'entry.1700623371' => sanitize_text_field($post_data['your-tel']),
                        'entry.854830020' => sanitize_email($post_data['your-email']),
                        'entry.1608506188' => sanitize_text_field($post_data['loaixe']),
                        'entry.559997465' => (isset($post_data['phienban_xe']) && is_array($post_data['phienban_xe']) && $post_data['phienban_xe']) ? implode(',', $post_data['phienban_xe']) : sanitize_text_field($post_data['phienban_xe']),
                        'entry.1855524876' => (isset($post_data['phienban_xe']) && is_array($post_data['noi_dangky']) && $post_data['noi_dangky']) ? implode(',', $post_data['noi_dangky']) : sanitize_text_field($post_data['noi_dangky']),
                    ))
                );

            }
        }
    }
    return true;
}
add_action( 'wpcf7_before_send_mail', 'save_cf7_to_google_sheet' );
