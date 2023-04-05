<?php
add_filter( 'wpseo_canonical', 'devvn_rank_math_canonical_url' );
function devvn_rank_math_canonical_url($canonical_url){
    if(is_shop()){
        $canonical_url = get_permalink( wc_get_page_id( 'shop' ) );
    }elseif (is_product_taxonomy() || is_category() || is_tag()){
        $canonical_url = get_term_link(get_queried_object_id());
    }elseif (is_home()){
       $canonical_url = get_permalink(get_option( 'page_for_posts' ));
    }
    return $canonical_url;
}
