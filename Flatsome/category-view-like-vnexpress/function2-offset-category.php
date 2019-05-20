<?php
/*
 * Offset posts by 10 on 'Techonology (tech)' category archive
 */
function itsme_category_offset( $query ) {
    $offset = 10;
    $ppp = get_option( 'posts_per_page' );
    $paged = $query->query_vars[ 'paged' ];

    if( $query->is_category( 'tech' ) && $query->is_main_query() ) {
        if( !is_paged() ) {

            $query->set( 'offset', $offset );

        } else {

            $paged_offset = $offset + ( ($paged - 1) * $ppp );
            $query->set( 'offset', $paged_offset );

        }
    }
}
add_action( 'pre_get_posts', 'itsme_category_offset' );

function itsme_adjust_category_offset_pagination( $found_posts, $query ) {
    $offset = 10;

    if( $query->is_category( 'tech' ) && $query->is_main_query() ) {
        return( $found_posts - $offset );
    }
}
add_filter( 'found_posts', 'itsme_adjust_category_offset_pagination', 10, 2 );
