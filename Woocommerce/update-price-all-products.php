<?php
add_action( 'wp_ajax_update_all_prods', 'update_all_prods_func' );
add_action( 'wp_ajax_nopriv_update_all_prods', 'update_all_prods_func' );

function update_all_prods_func() {

    $products = get_posts(array(
        'post_type' => 'product',
        'posts_per_page'    => -1
    ));
    if($products){
        foreach ($products as $item) {

            $product = wc_get_product($item->ID);

            if ($product && !is_wp_error($product)) {

                $price = intval(get_post_meta($product->get_id(), 'tour_price', true));
                $tour_sale_question = intval(get_post_meta($product->get_id(), 'tour_sale_question', true));
                $tour_sale_percent = intval(get_post_meta($product->get_id(), 'tour_sale_percent', true));
                $sale_price = '';

                if ($tour_sale_question && $tour_sale_percent) {
                    $sale_price = $price - ($price * $tour_sale_percent * 0.01);
                }

                echo $product->get_id() . ' -  ' . $product->get_name() . ' => ' . wc_price($price) . ' => ' . wc_price($sale_price) . ' => OKIE<br>';

                $product->set_regular_price($price);
                if ($sale_price) {
                    $product->set_sale_price($sale_price);
                    $product->set_price($sale_price);
                } else {
                    $product->set_price($price);
                }

                $product->save();

            }
        }
    };


    die(date_i18n('Y-m-d H:i:s'));
}
