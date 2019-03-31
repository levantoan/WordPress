<?php
function devvn_product_category_base_same_shop_base( $flash = false ){
    global $sitepress;
    $languages = icl_get_languages('skip_missing=0&orderby=code');
    if($languages && !empty($languages)){
        $original_lang = ICL_LANGUAGE_CODE;
        foreach($languages as $key=>$lang) {
            $new_lang = $key;
            $sitepress->switch_lang($new_lang);
            $terms = get_terms(array(
                'taxonomy' => 'product_cat',
                'post_type' => 'product',
                'hide_empty' => false,
            ));
            if ($terms && !is_wp_error($terms)) {
                $siteurl = apply_filters( 'wpml_home_url', get_home_url('/'));
                $siteurl = ($sitepress->get_default_language() == $key) ? $siteurl.'/' : $siteurl;
                foreach ($terms as $term) {
                    $term_slug = $term->slug;
                    $baseterm = str_replace($siteurl, '', get_term_link($term->term_id, 'product_cat'));
                    add_rewrite_rule($baseterm . '?$', 'index.php?product_cat=' . $term_slug, 'top');
                    add_rewrite_rule($baseterm . 'page/([0-9]{1,})/?$', 'index.php?product_cat=' . $term_slug . '&paged=$matches[1]', 'top');
                    add_rewrite_rule($baseterm . '(?:feed/)?(feed|rdf|rss|rss2|atom)/?$', 'index.php?product_cat=' . $term_slug . '&feed=$matches[1]', 'top');
                }
            }
            $sitepress->switch_lang($original_lang);
        }

    }

    if ($flash == true)
        flush_rewrite_rules(false);
}
add_filter( 'init', 'devvn_product_category_base_same_shop_base');

add_action( 'create_term', 'devvn_product_cat_same_shop_edit_success', 10, 2 );
function devvn_product_cat_same_shop_edit_success( $term_id, $taxonomy ) {
    devvn_product_category_base_same_shop_base(true);
}
