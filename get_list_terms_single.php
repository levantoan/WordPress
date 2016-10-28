<?php
function get_terms_single($term_slug = 'category'){
	global $post;
	
	$terms = get_the_terms( $post->ID, $term_slug ); 
	
	if ( $terms && ! is_wp_error( $terms ) ) :	 
    	$draught_links = array(); 
	    foreach ( $terms as $term ) {
	        $draught_links[] = $term->name;
	    } 
    	$on_draught = join( ", ", $draught_links );
    	return $on_draught;
    endif;
    return false;
}
