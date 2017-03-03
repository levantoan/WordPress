<?php
/*
Same slug taxonomy & post type
My web: http://levantoan.com
*/
// rewrite urls
function taxonomy_slug_rewrite($wp_rewrite) {
    $rules = array();
    $taxonomies = get_taxonomies(array('_builtin' => false), 'objects');
    $post_types = get_post_types(array('public' => true, '_builtin' => false), 'objects');
    foreach ($post_types as $post_type) {
        foreach ($taxonomies as $taxonomy) {
            foreach ($taxonomy->object_type as $object_type) {
                if ($object_type == $post_type->rewrite['slug']) {
                    $terms = get_categories(array('type' => $object_type, 'taxonomy' => $taxonomy->name, 'hide_empty' => 0));
                    foreach ($terms as $term) {
                        $rules[$object_type . '/' . $term->slug . '/?$'] = 'index.php?' . $term->taxonomy . '=' . $term->slug;
                    }
                }
            }
        }
    }
    $wp_rewrite->rules = $rules + $wp_rewrite->rules;
}
add_filter('generate_rewrite_rules', 'taxonomy_slug_rewrite');

/*
base product category same base shop Page for woocommerce
Author: http://levantoan.com/cach-cai-dat-base-cua-danh-muc-san-pham-giong-voi-base-cua-trang-san-pham/
*/

add_filter( 'rewrite_rules_array', function( $rules )
{
    $new_rules = array();
    $terms = get_terms( array(
        'taxonomy' => 'product_cat',
        'post_type' => 'product',
        'hide_empty' => false,
    ));
    if($terms && !is_wp_error($terms)){
        $siteurl = esc_url(home_url('/'));
        foreach ($terms as $term){
            $term_slug = $term->slug;
            $baseterm = str_replace($siteurl,'',get_term_link($term->term_id,'product_cat'));

            $new_rules[$baseterm.'?$'] = 'index.php?product_cat='.$term_slug;
            $new_rules[$baseterm.'page/([0-9]{1,})/?$'] = 'index.php?product_cat='.$term_slug.'&paged=$matches[1]';
        }
    }
    return $new_rules + $rules;
} );
