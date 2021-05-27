<?php
/*
Yoast Seo Custom Field
*/
function devvn_get_name_term($term_id = ''){
    if($term_id && is_numeric($term_id)){
        $term = get_term_by('id', $term_id, 'escorts');
        if($term && !is_wp_error($term)){
            return $term->name;
        }
    }
    return $term_id;
}

function filter_wpseo_replacements( $replacements ) {
    global $gender_a, $ethnicity_a;
    if( isset( $replacements['%%cf_country%%'] ) ){
        $replacements['%%cf_country%%'] = devvn_get_name_term($replacements['%%cf_country%%']);
    }
    if( isset( $replacements['%%cf_state%%'] ) ){
        $replacements['%%cf_state%%'] = devvn_get_name_term($replacements['%%cf_state%%']);
    }
    if( isset( $replacements['%%cf_city%%'] ) ){
        $replacements['%%cf_city%%'] = devvn_get_name_term($replacements['%%cf_city%%']);
    }
    if( isset( $replacements['%%cf_ethnicity%%'] ) ){
        $replacements['%%cf_ethnicity%%'] = $ethnicity_a[$replacements['%%cf_ethnicity%%']];
    }
    if( isset( $replacements['%%cf_gender%%'] ) ){
        $replacements['%%cf_gender%%'] = $gender_a[$replacements['%%cf_gender%%']];
    }
    return $replacements;
};
add_filter( 'wpseo_replacements', 'filter_wpseo_replacements', 10, 1 );

//meta value is table
function services_filter_wpseo_replacements( $replacements ) {
    global $post;
    //if( isset( $replacements['%%cf_services%%'] ) ){
        $services = get_field('services', $post->ID);
        if($services){
            $out = array();
            foreach ($services as $item){
                $out[] = isset($item['label']) ? sanitize_text_field($item['label']) : '';
            }
        }
        $replacements['%%cf_services%%'] = implode(', ', $out);
    //}
    return $replacements;
};
add_filter( 'wpseo_replacements', 'services_filter_wpseo_replacements', 10, 1 );
