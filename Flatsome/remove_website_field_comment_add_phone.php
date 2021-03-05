<?php
/*
Author: levantoan.com
*/
add_filter('comment_form_default_fields', 'website_remove');
function website_remove($fields)
{
    if(isset($fields['url']))
        unset($fields['url']);
    return $fields;
}

function devvn_array_insert_before($key, array &$array, $new_key, $new_value) {
    if (array_key_exists($key, $array)) {
        $new = array();
        foreach ($array as $k => $value) {
            if ($k === $key) {
                $new[$new_key] = $new_value;
            }
            $new[$k] = $value;
        }
        return $new;
    }
    return FALSE;
}

add_filter( 'comment_form_default_fields', 'add_phone_comment_form_defaults');
function add_phone_comment_form_defaults( $fields ) {
    $commenter = wp_get_current_commenter();
    $fields_phone = '<p class="comment-form-url">'.
        '<label for="phone">' . __( 'Số điện thoại' ) . '<span class="required">*</span></label>'.
        '<input id="phone" name="phone" type="text" size="30"  tabindex="4" required="required"/></p>';
    $fields[ 'author' ] = '<p class="comment-form-author">' . '<label for="author">' . __( 'Họ tên <span class="required">*</span>' ) . '</label> ' .
        '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" maxlength="245" /></p>';

    $fields_new = devvn_array_insert_before('cookies', $fields, 'phone', $fields_phone);
    if($fields_new) $fields = $fields_new;

    return $fields;
}

add_action( 'comment_post', 'save_comment_meta_data' );
function save_comment_meta_data( $comment_id ) {
    if ( ( isset( $_POST['phone'] ) ) && ( $_POST['phone'] != '') )
        $phone = wp_filter_nohtml_kses($_POST['phone']);
    add_comment_meta( $comment_id, 'phone', $phone );
}

add_filter( 'preprocess_comment', 'verify_comment_meta_data' );
function verify_comment_meta_data( $commentdata ) {
    if(!is_admin() && !is_user_logged_in()) {
        if (!isset($_POST['phone']))
            wp_die(__('Lỗi: Số điện thoại là bắt buộc'));

        $phone = $_POST['phone'];
        if (!(preg_match('/^0([0-9]{9,10})+$/D', $phone))) {
            wp_die(__('Lỗi: Số điện thoại không đúng định dạng'));
        }
        if ($commentdata['comment_author'] == '')
            wp_die('Lỗi: Xin hãy nhập tên của bạn');
    }
    return $commentdata;
}

add_filter( 'comment_text', 'modify_comment');
function modify_comment( $text ){
    $commentphone = get_comment_meta( get_comment_ID(), 'phone', true );
    if($commentphone  && is_admin() ) {
        $commentphone = '<br/>SĐT: <strong>' . esc_attr( $commentphone ) . '</strong>';
        $text = $text . $commentphone;
    }
    return $text;
}
