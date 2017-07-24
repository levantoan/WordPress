<?php
//Tìm kiếm chỉ ở Post
function devvn_SearchFilter($query) {
    if ($query->is_search) {
        $query->set('post_type', array('post'));
    }
    return $query;
}
add_filter('pre_get_posts','devvn_SearchFilter');

function devvn_array_combinations($array){

    $result=[];
    for ($i=0;$i<count($array)-1;$i++) {
        $result=array_merge($result, devvn_combinations(array_slice($array,$i)));
    }

    return $result;
}

function devvn_combinations($array){
    //get all the possible combinations no dublicates
    $combinations=[];

    $combinations[]=$array;
    for($i=1;$i<count($array);$i++){
        $tmp=$array;

        unset($tmp[$i]);
        $tmp=array_values($tmp);//fix the indexes after unset
        if(count($tmp)<2){
            break;
        }
        $combinations[]=$tmp;
    }


    return $combinations;
}

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
    $str_array = array();
    $strSearch = (isset($q['search_terms']) && !empty($q['search_terms'])) ? (array) $q['search_terms'] : array();
    if(count($strSearch) >= 2){
        $strSearch = devvn_array_combinations($strSearch);
        foreach ($strSearch as $str){
            $str_array[] = implode(" ", $str);
        }
    }else{
        $str_array = $strSearch;
    }
    foreach ((array)$str_array as $term) {
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
