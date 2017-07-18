<?php
/*
 * Base product category same single product - multilang
 * Author: levantoan.com
 */
function devvn_product_category_base_same_shop_base_multilang( $flash = false ){
    global $wpdb;
    $terms = $wpdb->get_results("SELECT term_id FROM $wpdb->term_taxonomy WHERE taxonomy = 'product_cat'");

    $count = count($terms);
    if ( $count > 0 ){
        $term_id = array();
        foreach ( $terms as $term ) {
            $termID_en = (int) apply_filters( 'wpml_object_id', $term->term_id, 'product_cat', false, 'en'  );
            $term_id['en'][] = get_term_by('id',$termID_en,'product_cat');

            $termID_vi = (int) apply_filters( 'wpml_object_id', $term->term_id, 'product_cat', false, 'vi'  );
            $term_id['vi'][] = get_term_by('id',$termID_vi,'product_cat');
        }
        if ($term_id && !empty($term_id)) {
            $term_filter = array();
            foreach ($term_id as $k => $term_lang) {
                $term_filter[$k] = array_map("unserialize", array_unique(array_map("serialize", array_filter($term_lang))));
            }
            if($term_filter) {
                foreach ($term_filter as $k => $term_a) {
                    switch ($k){
                        case 'en':
                            $siteurl = esc_url(site_url('/en/'));
                            foreach ($term_a as $term) {
                                $term_slug = $term->slug;
                                $baseterm = str_replace($siteurl, '', get_term_link($term->term_id, 'product_cat'));
                                add_rewrite_rule($baseterm . '?$', 'index.php?product_cat=' . $term_slug .'&lang=en', 'top');
                                add_rewrite_rule($baseterm . 'page/([0-9]{1,})/?$', 'index.php?product_cat=' . $term_slug  .'&lang=en&paged=$matches[1]', 'top');
                                add_rewrite_rule($baseterm . '(?:feed/)?(feed|rdf|rss|rss2|atom)/?$', 'index.php?product_cat=' . $term_slug .'&lang=en&feed=$matches[1]', 'top');
                            }
                            break;
                        default:
                            $siteurl = esc_url(site_url('/'));
                            foreach ($term_a as $term) {
                                $term_slug = $term->slug;
                                $baseterm = str_replace($siteurl, '', get_term_link($term->term_id, 'product_cat'));
                                add_rewrite_rule($baseterm . '?$', 'index.php?product_cat=' . $term_slug, 'top');
                                add_rewrite_rule($baseterm . 'page/([0-9]{1,})/?$', 'index.php?product_cat=' . $term_slug . '&paged=$matches[1]', 'top');
                                add_rewrite_rule($baseterm . '(?:feed/)?(feed|rdf|rss|rss2|atom)/?$', 'index.php?product_cat=' . $term_slug . '&feed=$matches[1]', 'top');
                            }
                            break;
                    }
                }
            }
        }
    }

    if ($flash == true)
        flush_rewrite_rules(false);
}
add_filter( 'init', 'devvn_product_category_base_same_shop_base_multilang');

/*Sửa lỗi khi tạo mới taxomony bị 404*/
add_action( 'created_term', 'devvn_product_cat_same_shop_edit_success_multilang', 10, 2 );
add_action( 'created_product_cat', 'devvn_product_cat_same_shop_edit_success_multilang', 10, 2 );
function devvn_product_cat_same_shop_edit_success_multilang( $term_id, $taxonomy ) {
    devvn_product_category_base_same_shop_base_multilang(true);
}
