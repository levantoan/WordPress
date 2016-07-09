<?php
/*Chuyển hướng Custom Post type Rooms đến page có slug rooms*/
add_action('wp', 'redirect_ctps');
function redirect_ctps(){ 
    global $post;
    if( (is_post_type_archive('rooms')) ){ 
        wp_redirect( home_url('/rooms/') ); exit; 
    }
}
