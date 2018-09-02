<?php
/*
Insert this code in functions.php
Author: https://levantoan.com
*/

function devvn_post_link_category( $cat, $cats, $post ) {
    unset($cat->parent);
    return $cat;
}

add_filter( 'post_link_category', 'devvn_post_link_category', 20, 3 );
