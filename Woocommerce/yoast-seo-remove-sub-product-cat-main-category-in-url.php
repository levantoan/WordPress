<?php
/*
* Edit public_html/wp-content/plugins/woocommerce/includes/wc-product-functions.php
* Line 211
*/
if(is_plugin_active( 'wordpress-seo/wp-seo.php' )) {
    $primary_cat_id = get_post_meta($post->ID, '_yoast_wpseo_primary_product_cat', true);

    if($primary_cat_id) {
        $category_object = get_term($primary_cat_id, 'product_cat');
        $product_cat = $category_object->slug;
    }else{
        $category_object = apply_filters('wc_product_post_type_link_product_cat', $terms[0], $terms, $post);
        $product_cat = $category_object->slug;

        if ($category_object->parent) {
            $ancestors = get_ancestors($category_object->term_id, 'product_cat');
            foreach ($ancestors as $ancestor) {
                $ancestor_object = get_term($ancestor, 'product_cat');
                $product_cat     = $ancestor_object->slug;
            }
        }
    }
}else {

    $category_object = apply_filters('wc_product_post_type_link_product_cat', $terms[0], $terms, $post);
    $product_cat = $category_object->slug;

    if ($category_object->parent) {
        $ancestors = get_ancestors($category_object->term_id, 'product_cat');
        foreach ($ancestors as $ancestor) {
            $ancestor_object = get_term($ancestor, 'product_cat');
            $product_cat = $ancestor_object->slug . '/' . $product_cat;
            //$product_cat     = $ancestor_object->slug;
        }
    }
}
