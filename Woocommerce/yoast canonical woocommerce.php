<?php
add_filter( 'wpseo_canonical', 'devvn_canonical', 20, 2 );
function devvn_canonical($canonical, $presentation = null){
    if(is_shop()){
        return get_permalink( wc_get_page_id( 'shop' ) );
    }elseif (is_product_taxonomy()){
        return get_term_link(get_queried_object_id());
    }

    return $canonical;
}
