<?php
/*Search in my ACF*/
function list_searcheable_acf(){
    $list_searcheable_acf = array("email_dangky", "sdt_dangky");
    return $list_searcheable_acf;
}
function advanced_custom_search( $where, $wp_query ) {
    global $wpdb;

    if ( empty( $where ))
        return $where;

    // get search expression
    $terms = $wp_query->query_vars[ 's' ];

    // explode search expression to get search terms
    $exploded = explode( ' ', $terms );
    if( $exploded === FALSE || count( $exploded ) == 0 )
        $exploded = array( 0 => $terms );

    // reset search in order to rebuilt it as we whish
    $where = '';

    // get searcheable_acf, a list of advanced custom fields you want to search content in
    $list_searcheable_acf = list_searcheable_acf();
    foreach( $exploded as $tag ) :
        $where .= " 
          AND (
            ({$wpdb->posts}.post_title LIKE '%$tag%')
            OR ({$wpdb->posts}.post_content LIKE '%$tag%')
            OR EXISTS (
              SELECT * FROM {$wpdb->postmeta}
                  WHERE post_id = {$wpdb->posts}.ID
                    AND (";
        foreach ($list_searcheable_acf as $searcheable_acf) :
            if ($searcheable_acf == $list_searcheable_acf[0]):
                $where .= " (meta_key LIKE '%" . $searcheable_acf . "%' AND meta_value LIKE '%$tag%') ";
            else :
                $where .= " OR (meta_key LIKE '%" . $searcheable_acf . "%' AND meta_value LIKE '%$tag%') ";
            endif;
        endforeach;
        $where .= ")
            )
            OR EXISTS (
              SELECT * FROM {$wpdb->comments}
              WHERE comment_post_ID = {$wpdb->posts}.ID
                AND comment_content LIKE '%$tag%'
            )
            OR EXISTS (
              SELECT * FROM {$wpdb->terms}
              INNER JOIN {$wpdb->term_taxonomy}
                ON {$wpdb->term_taxonomy}.term_id = {$wpdb->terms}.term_id
              INNER JOIN {$wpdb->term_relationships}
                ON {$wpdb->term_relationships}.term_taxonomy_id = {$wpdb->term_taxonomy}.term_taxonomy_id
              WHERE (
                taxonomy = 'post_tag'
                    OR taxonomy = 'category'                
                    OR taxonomy = 'myCustomTax'
                )
                AND object_id = {$wpdb->posts}.ID
                AND {$wpdb->terms}.name LIKE '%$tag%'
            )
        )";
    endforeach;
    return $where;
}
add_filter( 'posts_search', 'advanced_custom_search', 500, 2 );
/*#Search in my ACF*/
