<?php
//Tìm kiếm chỉ ở Post
function devvn_SearchFilter($query) {
    if ($query->is_search) {
        $query->set('post_type', array('post'));
    }
    return $query;
}
add_filter('pre_get_posts','devvn_SearchFilter');

/*Combinations Array*/
function devvn_getCombinations($base,$n){

    $baselen = count($base);
    if($baselen == 0){
        return;
    }
    if($n == 1){
        $return = array();
        foreach($base as $b){
            $return[] = array($b);
        }
        return $return;
    }else{
        //get one level lower combinations
        $oneLevelLower = devvn_getCombinations($base,$n-1);

        //for every one level lower combinations add one element to them that the last element of a combination is preceeded by the element which follows it in base array if there is none, does not add
        $newCombs = array();

        foreach($oneLevelLower as $oll){

            $lastEl = $oll[$n-2];
            $found = false;
            foreach($base as  $key => $b){
                if($b == $lastEl){
                    $found = true;
                    continue;
                    //last element found

                }
                if($found == true){
                    //add to combinations with last element
                    if($key < $baselen){

                        $tmp = $oll;
                        $newCombination = array_slice($tmp,0);
                        $newCombination[]=$b;
                        $newCombs[] = array_slice($newCombination,0);
                    }

                }
            }
        }
    }
    return $newCombs;
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
    $strSearch = (isset($q['search_terms']) && !empty($q['search_terms'])) ? (array)$q['search_terms'] : array();
    $str_array = array();
    for($i = 1; $i<=3 ;$i++){
        $comb = devvn_getCombinations($strSearch,$i);
        foreach($comb as $c){
            $str_array[] = implode(" ",$c);
        }
    }
    $str_array = array_reverse($str_array);
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
