<?php
/*
 * Page 1 show 18 post
 * Each orther page show with the default `posts_per_page` setting from WP Dashboard > Settings > Reading
 */
function itsme_category_offset( $query ) {
    $ppp = get_option( 'posts_per_page' );
    $first_page_ppp = 18;
    $paged = $query->query_vars[ 'paged' ];

    if( $query->is_category() && $query->is_main_query() ) {
        if( !is_paged() ) {

            $query->set( 'posts_per_page', $first_page_ppp );

        } else {

            // Not going to explain the simple math involved here
            $paged_offset = $first_page_ppp + ( ($paged - 2) * $ppp );
            $query->set( 'offset', $paged_offset );

            /*
             * As we are not adding a custom `$query->set( 'posts_per_page', ... );`,
             * the default `posts_per_page` setting from WP Dashboard > Settings > Reading
             * will be applied here.
             */

        }
    }
}
add_action( 'pre_get_posts', 'itsme_category_offset' );

function itsme_adjust_category_offset_pagination( $found_posts, $query ) {
    $ppp = get_option( 'posts_per_page' );
    $first_page_ppp = 18;

    if( $query->is_category() && $query->is_main_query() ) {
        if( !is_paged() ) {

            return( $found_posts );

        } else {

            return( $found_posts - ($first_page_ppp - $ppp) );

        }
    }
    return $found_posts;
}
add_filter( 'found_posts', 'itsme_adjust_category_offset_pagination', 10, 2 );
