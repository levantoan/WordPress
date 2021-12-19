<?php
/*
Search by ID
*/
function devvn_search_by_id($query) {
    $s = $query->get('s');
    if(is_numeric($s)){
        $query->set( 'p', $s );
        $query->set( 's', '' );
    }
}
add_action( 'pre_get_posts', 'devvn_search_by_id' );
