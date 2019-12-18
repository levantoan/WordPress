<?php
/*
Author: levantoan.com

Work with permalink is /%category%/%postname%/

- Set sub category slug to post link
- Set main category in yoast seo if it in category of post to post link

*/

add_filter('register_taxonomy_args','devvn_remove_parent_slug_category', 10, 2);
function devvn_remove_parent_slug_category($args, $name){
    if($name == 'category'){
        $args['rewrite']['hierarchical'] = false;
    }
    return $args;
}

add_filter('post_link_category', 'devvn_post_link_category', 999, 3);
function devvn_post_link_category($category_object, $cats, $post){
    $_yoast_wpseo_primary_category = get_post_meta($post->ID, '_yoast_wpseo_primary_category', true);
    $cats_main = array();
    $cats_main_id = array();
    $cats_main_id_2 = array();

    foreach ($cats as $cat){
        if($cat->parent || $cat->category_parent){
            $cats_main[] = $cat;
            $cats_main_id[] = $cat->term_id;
        }else{
            $cats_main_id_2[] = $cat->term_id;
        }
    }

    if(empty($cats_main)) {
        $cats_main = $cats;
        $cats_main_id = $cats_main_id_2;
    }

    if($_yoast_wpseo_primary_category && in_array($_yoast_wpseo_primary_category, $cats_main_id)){
        $category_object = $_yoast_wpseo_primary_category;
    }else{
        $category_object = $cats_main[0];
    }

    $category_object = get_term( $category_object, 'category' );

    unset($category_object->category_count);
    unset($category_object->parent);

    return $category_object;
}
