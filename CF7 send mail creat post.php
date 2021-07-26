<?php
//Post with CF7
function process_contact_form_data( $cf7 ){
    if (!isset($cf7->posted_data) && class_exists('WPCF7_Submission')) {
        $submission = WPCF7_Submission::get_instance();
        if ($submission) {
            $post_data = $submission->get_posted_data();

            $title = esc_html($post_data['your-name']);
            $email = esc_html($post_data['your-email']);
            $message = esc_html($post_data['your-message']);

            $_wpcf7ID = intval($cf7->id());
            if($_wpcf7ID == 232){
                $my_post = array(
                    'post_type'		=>	'testimonial',
                    'post_title'    =>	'New Testimonial',
                    'post_status'   =>	'pending'
                );
                $postID = wp_insert_post( $my_post );
                if($postID){
                    update_post_meta($postID, 'message', $message);
                    update_post_meta($postID, 'email', $email);
                    update_post_meta($postID, 'name', $title);

                    $my_post2 = array(
                        'ID'           => $postID,
                        'post_title'   => $message,
                    );
                    wp_update_post( $my_post2 );
                }
            }
        }
    }
    return true;
}
//remove_all_filters ('wpcf7_before_send_mail');
add_action( 'wpcf7_before_send_mail', 'process_contact_form_data' );
