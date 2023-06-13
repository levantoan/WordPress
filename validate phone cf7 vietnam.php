<?php
//field tel
function devvn_filter_wpcf7_is_tel_vietnam( $result, $tel ) {
    $result = preg_match( '/^0([0-9]{9})+$/D', $tel );
    return $result;
}
add_filter( 'wpcf7_is_tel', 'devvn_filter_wpcf7_is_tel_vietnam', 10, 2 );

//Field text để làm phone
add_filter('wpcf7_validate_text', 'custom_validate_sdt', 10, 2);
add_filter('wpcf7_validate_text*', 'custom_validate_sdt', 10, 2);
function custom_validate_sdt($result, $tag) {
    $name = $tag->name;
    if ($name === 'sdt') {
        $sdt = isset($_POST[$name]) ? $_POST[$name] : '';
        if (!preg_match('/^0([0-9]{9,10})+$/D', $sdt)) {
            $result->invalidate($tag, 'Số điện thoại không hợp lệ.');
        }
    }
    return $result;
}
