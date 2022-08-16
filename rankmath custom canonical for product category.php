<?php
add_filter('rank_math/frontend/canonical', 'custom_canonical');
function custom_canonical($canonical){
    if(is_product_category()){
        $canonical = get_term_link(get_queried_object());
    }
    return $canonical;
}
