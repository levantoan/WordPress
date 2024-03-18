<?php
add_filter('wp_mail_from', 'new_mail_from');
function new_mail_from($old) {
    if (strpos($old, 'wordpress@') !== false) {
        $old = get_option('admin_email');
    }
    return $old;
}

add_filter('wp_mail_from_name', 'new_mail_from_name');
function new_mail_from_name($old) {
    if($old == 'WordPress'){
        $old = get_option('blogname');
    }
    return $old;
}
