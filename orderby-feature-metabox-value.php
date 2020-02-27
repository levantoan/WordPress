<?php
/*Query*/

$args = array(
    'post_type' => $taxonomy_profile_url,
    'orderby' => 'rand',
    'meta_query' => array( array('key' => 'premium', 'value' => '1', 'compare' => '=', 'type' => 'NUMERIC') ),
    'posts_per_page' => get_option("frontpageshowpremiumcols") * 5,
    'devvn_query'   =>  1
);
$premium_profiles = new WP_Query( $args );
                
/*functions.php*/
function get_featured_posts(){
    global $taxonomy_profile_url;
    $featured_args = array(
        'post_type' => $taxonomy_profile_url,
        'orderby' => 'rand',
        'meta_query' => array( array('key' => 'featured', 'value' => '1', 'compare' => '=', 'type' => 'NUMERIC') ),
        'posts_per_page' => get_option("frontpageshowpremiumcols") * 5
    );
    $featured_profiles = get_posts( $featured_args );
    $featured_posts = array();
    if($featured_profiles) {
        foreach ($featured_profiles as $post) {
            $featured_posts[] = $post->ID;
        }
    }
    return $featured_posts;
}

function devvn_order_by_featured_posts( $order_by, $query ){
    global $wpdb;

    remove_filter(current_filter(), __FUNCTION__);

    $featured_posts = get_featured_posts();

    //Array ( [0] => 91169 [1] => 91239 [2] => 90805 [3] => 92718 [4] => 95570 [5] => 92027 [6] => 96158 [7] => 92316 [8] => 91857 [9] => 92012 [10] => 95072 [11] => 94726 [12] => 93083 [13] => 95903 [14] => 91427 [15] => 95124 [16] => 93165 [17] => 91092 [18] => 93612 [19] => 96071 )

    //$featured_posts = array('91169', '91239', '90805');

    if( is_array( $featured_posts ) && !empty($featured_posts)  && isset( $query->query['devvn_query'] ) && absint( $query->query['devvn_query'] ))
    {

        if( empty( $order_by ) ) {
            $order_by =  "FIELD(".$wpdb->posts.".ID,'".implode("','",$featured_posts)."') DESC ";
        }else{
            $order_by =  "FIELD(".$wpdb->posts.".ID,'".implode("','",$featured_posts)."') DESC, " . $order_by;
        }

    }
    return $order_by;

}

add_filter('posts_orderby', 'devvn_order_by_featured_posts', 99, 2 );

