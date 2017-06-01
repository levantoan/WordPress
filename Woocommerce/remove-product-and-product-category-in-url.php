<?php
/*
 * Cài đặt đường dẫn woocommerce dạng
 * Link danh mục cha: http://domain.com/hoa-hong/
 * Link danh mục con: http://domain.com/hoa-hong/hoa-hong-uc/
 * Link Sản phẩm: http://domain.com/hoa-hong/hoa-hong-uc/a-shropshire-lad/
 * */
/*Bỏ slug product (ở tiếng việt là cua-hang) + slug danh mục sản phẩm*/
function devvn_remove_slug( $post_link, $post ) {
    if ( !in_array( get_post_type($post), array( 'product' ) ) || 'publish' != $post->post_status ) {
        return $post_link;
    }
    if('product' == $post->post_type){
        $post_link = str_replace( '/cua-hang/', '/', $post_link ); //Thay cua-hang bằng slug hiện tại của bạn
    }else{
        $post_link = str_replace( '/' . $post->post_type . '/', '/', $post_link );
    }
    return $post_link;
}
add_filter( 'post_type_link', 'devvn_remove_slug', 10, 2 );
/*Sửa lỗi 404 sau khi đã remove slug product or cua-hang*/
function devvn_woo_rewrite_rules() {
    global $wp_post_types, $wpdb;
    $siteLink = esc_url(home_url('/'));
    foreach ($wp_post_types as $type=>$custom_post) {
        if($type == 'product'){
            if ($custom_post->_builtin == false) {
                $querystr = "SELECT {$wpdb->posts}.post_name, {$wpdb->posts}.ID
                            FROM {$wpdb->posts} 
                            WHERE {$wpdb->posts}.post_status = 'publish' 
                            AND {$wpdb->posts}.post_type = '{$type}'
                            AND {$wpdb->posts}.post_date < NOW()";
                $posts = $wpdb->get_results($querystr, OBJECT);
                foreach ($posts as $post) {
                    $current_slug = get_permalink($post->ID);
                    $base_product = str_replace($siteLink,'',$current_slug);
                    add_rewrite_rule($base_product.'?$', "index.php?{$custom_post->query_var}={$post->post_name}", 'top');
                }
            }
        }
    }
}
add_action('init', 'devvn_woo_rewrite_rules');

/*Xóa product-category trong URL (Tiếng Việt là danh-muc)*/
add_filter( 'term_link', 'devvn_product_cat_permalink', 10, 3 );
function devvn_product_cat_permalink( $url, $term, $taxonomy ){
    switch ($taxonomy):
        case 'product_cat':
            $taxonomy_slug = 'danh-muc'; //Thay bằng slug hiện tại của bạn. Mặc định là product-category
            if(strpos($url, $taxonomy_slug) === FALSE) break;
            $url = str_replace('/' . $taxonomy_slug, '', $url);
            break;
    endswitch;
    return $url;
}
// Add our custom product cat rewrite rules
add_filter('rewrite_rules_array', 'devvn_product_category_rewrite_rules');
function devvn_product_category_rewrite_rules($rules) {
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
            $new_rules[$baseterm.'(?:feed/)?(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?product_cat='.$term_slug.'&feed=$matches[1]';
        }
    }
    return $new_rules + $rules;
}
