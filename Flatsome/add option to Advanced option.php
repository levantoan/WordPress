<?php

/*
* Add to functions.php
* Author levantoan.com
*/

if(!function_exists('array_insert_after')) {
    function array_insert_after($key, array &$array, $new_key, $new_value)
    {
        if (array_key_exists($key, $array)) {
            $new = array();
            foreach ($array as $k => $value) {
                $new[$k] = $value;
                if ($k === $key) {
                    $new[$new_key] = $new_value;
                }
            }
            return $new;
        }
        return FALSE;
    }
}

/*
 * Cách gọi giá trị
 * get_theme_mod( 'devvn_notice' )
 * get_theme_mod( 'devvn_notice_content' )
 * */

add_action( 'init', 'devvn_of_options', 20 );
function devvn_of_options(){
    global $of_options;
    
    if(!$of_options) return;
    
    $new_option = array(
        'name' => 'Hiển thị thông báo',
        'id'   => 'devvn_notice',
        'desc' => 'Hiển thị thông báo dưới header trên toàn trang',
        'std'  => 0,
        'folds' => 1,
        'type' => 'checkbox',
    );
    $of_options = array_insert_after(0, $of_options, 'devvn_notice', $new_option);


    $new_option = array(
        'name' => 'Nội dung thông báo',
        'desc' => 'Add custom scripts inside HEAD tag. You need to have a SCRIPT tag around scripts.',
        'id'   => 'devvn_notice_content',
        'std'  => '',
        'fold' => 'devvn_notice',
        'type' => 'textarea',
    );
    $of_options = array_insert_after('devvn_notice', $of_options, 'devvn_notice_content', $new_option);

}

