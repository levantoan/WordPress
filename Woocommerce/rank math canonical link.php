<?php
add_filter('rank_math/frontend/canonical', 'devvn_rank_math_canonical_url');
function devvn_rank_math_canonical_url($canonical_url){
    if(is_shop()){
        $canonical_url = get_permalink( wc_get_page_id( 'shop' ) );
    }elseif (is_product_taxonomy()){
        return get_term_link(get_queried_object_id());
    }
    return $canonical_url;
}
