<?php
add_filter('woocommerce_available_variation','devvn_woocommerce_available_variation', 10, 3);
function devvn_woocommerce_available_variation($args, $variable, $variation){
    $bienthe = $variation->get_attributes();
    $more_desc = array();
    if($bienthe && is_array($bienthe)){
        foreach ($bienthe as $k=>$item){
            $term = get_term_by('slug', $item, $k);
            if($term && !is_wp_error($term)){
                if($term->description) {
                    $more_desc[] = '<strong style="color: red">'.$term->description.'</strong>';
                }
            }
        }
    }
    if($more_desc){
        $args['variation_description'] = $args['variation_description'] . implode('<br>', $more_desc);
    }
    return $args;
}
