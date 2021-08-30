<?php
function devvn_filter_wpcf7_is_tel_vietnam( $result, $tel ) {
    $result = preg_match( '/^0([0-9]{9})+$/D', $tel );
    return $result;
}
add_filter( 'wpcf7_is_tel', 'devvn_filter_wpcf7_is_tel_vietnam', 10, 2 );
