<?php 
function get_term_by_postID($postID = null, $taxonomy = 'category', $sep = ' '){
    global $post;
    $on_draught = '';
    if(!$postID) $postID = $post->ID;
    $terms = get_the_terms( $postID, $taxonomy );
    if ( $terms && ! is_wp_error( $terms ) ) :
        $draught_links = array();
        foreach ( $terms as $term ) {
            $draught_links[] = $term->slug;
        }
        $on_draught = join( $sep, $draught_links );
    endif;
    return $on_draught;
}
