<?php
function devvn_get_term_base( $term, $taxonomy = '' ) {
    if ( !is_object($term) ) {
        if ( is_int( $term ) ) {
            $term = get_term( $term, $taxonomy );
        } else {
            $term = get_term_by( 'slug', $term, $taxonomy );
        }
    }
    if ( !is_object($term) )
        $term = new WP_Error('invalid_term', __('Empty Term'));
    if ( is_wp_error( $term ) )
        return $term;
    $taxonomy = $term->taxonomy;
    $slug = $term->slug;
    $t = get_taxonomy($taxonomy);
    if ( $t->rewrite['hierarchical'] ) {
        $hierarchical_slugs = array();
        $ancestors = get_ancestors( $term->term_id, $taxonomy, 'taxonomy' );
        foreach ( (array)$ancestors as $ancestor ) {
            $ancestor_term = get_term($ancestor, $taxonomy);
            $hierarchical_slugs[] = $ancestor_term->slug;
        }
        $hierarchical_slugs = array_reverse($hierarchical_slugs);
        $hierarchical_slugs[] = $slug;
        $term_slug = implode('/', $hierarchical_slugs);
    } else {
        $term_slug = $slug;
    }
    return $term_slug;
}
