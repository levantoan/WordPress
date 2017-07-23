<?php
//Tìm kiếm chỉ ở Post
function devvn_SearchFilter($query) {
    if ($query->is_search) {
        $query->set('post_type', array('post'));
    }
    return $query;
}
add_filter('pre_get_posts','devvn_SearchFilter');

/*search only post title */
function __search_by_title_only( $search, $wp_query )
{
    global $wpdb;
    if(empty($search)) {
        return $search;
    }
    $q = $wp_query->query_vars;
    $search = '';
    $searchand = '';
    foreach ((array)$q['search_terms'] as $term) {
        $term = esc_sql($wpdb->esc_like($term));
        $search .= "{$searchand}($wpdb->posts.post_title LIKE '%{$term}%')";
        $searchand = ' OR ';
    }
    if (!empty($search)) {
        $search = " AND ({$search}) ";
        if (!is_user_logged_in())
            $search .= " AND ($wpdb->posts.post_password = '') ";
    }
    return $search;
}
add_filter('posts_search', '__search_by_title_only', 500, 2);
