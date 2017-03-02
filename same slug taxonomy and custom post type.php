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
