<?php
/*
Author: levantoan.com
*/

add_action( 'wp_ajax_change_gallery_woo_to_acf', 'change_gallery_woo_to_acf_func' );
function change_gallery_woo_to_acf_func() {
    $all_products = new WP_Query(array(
        'post_type' =>  'product',
        'posts_per_page' => -1
    ));
    if($all_products->have_posts()):
        while($all_products->have_posts()):$all_products->the_post();
            $old_gallery = get_post_meta(get_the_ID(),'_product_image_gallery', true);
            if(!is_array($old_gallery)){
                $t = explode(',', $old_gallery);
                update_post_meta(get_the_ID(), '_product_image_gallery', $t);
                add_post_meta(get_the_ID(), '__product_image_gallery', 'field_5c4c7e9559d47'); // Change field_5c4c7e9559d47 to your field name in ACF
                echo $old_gallery . ' => ' . maybe_serialize($t) . '<br>';
            }
        endwhile;
    endif; wp_reset_query();
    die();
}
