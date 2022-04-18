
/*
* Thêm nút mua hàng tại shopee và tiki sau nút thêm vào giỏ hàng
* Author: levantoan.com
*/
add_action('woocommerce_after_add_to_cart_button', 'devvn_button_other_shop');
function devvn_button_other_shop(){
    global $product;
    $shopee = get_post_meta($product->get_id(), 'shopee_link', true);
    $tiki = get_post_meta($product->get_id(), 'tiki_link', true);
    $lazada = get_post_meta($product->get_id(), 'lazada_link', true);
    $tiktok = get_post_meta($product->get_id(), 'tiktok_link', true);
    if($shopee || $tiki || $lazada || $tiktok):
        ?>
        <style>
            .devvn_button_other_shop {
                clear: both;
            }
            .devvn_button_other_shop:after {
                content: "";
                display: block;
                clear: both;
            }
            .devvn_button_other_shop a {
                text-transform: uppercase;
                font-weight: 400;
                padding: 3px 10px;
                border-radius: 3px;
                -moz-border-radius: 3px;
                -webkit-border-radius: 3px;
                width: 100%;
                margin: 0 0 5px 0;
                text-align: center;
            }
            .devvn_button_other_shop a.btn-shopee {
                background: #f6422d;
                color: #fff;
            }
            .devvn_button_other_shop a.btn-tiki {
                background: #189eff;
                color: #fff;
            }
            .devvn_button_other_shop a.btn-lazada {
                background: #ef5405;
                color: #fff;
            }
            .devvn_button_other_shop a.btn-tiktok {
                background: #000;
                color: #fff;
            }
            a.devvn_buy_now.devvn_buy_now_style {
                max-width: 100%;
            }
        </style>
        <div class="devvn_button_other_shop">
            <?php if($shopee):?><a href="<?php echo esc_url($shopee);?>" title="Mua trên Shopee" target="_blank" class="button btn-shopee">Mua trên Shopee</a><?php endif;?>
            <?php if($tiki):?><a href="<?php echo esc_url($tiki);?>" title="Mua trên Tiki" target="_blank" class="button btn-tiki">Mua trên Tiki</a><?php endif;?>
            <?php if($lazada):?><a href="<?php echo esc_url($lazada);?>" title="Mua trên Lazada" target="_blank" class="button btn-lazada">Mua trên Lazada</a><?php endif;?>
            <?php if($tiktok):?><a href="<?php echo esc_url($tiktok);?>" title="Mua trên Tiktok" target="_blank" class="button btn-tiktok">Mua trên Tiktok</a><?php endif;?>
        </div>
    <?php
    endif;
}

add_action( 'add_meta_boxes', 'devvn_othershop_infor_meta_box' );
function devvn_othershop_infor_meta_box(){
    add_meta_box(
        'devvn-othershop',
        __( 'Thông tin mua hàng', 'devvn' ),
        'devvn_othershop_callback',
        'product',
        'side',
        'high'
    );
}
function devvn_othershop_callback($post){
    $shopee = get_post_meta($post->ID, 'shopee_link', true);
    $tiki = get_post_meta($post->ID, 'tiki_link', true);
    $lazada = get_post_meta($post->ID, 'lazada_link', true);
    $tiktok = get_post_meta($post->ID, 'tiktok_link', true);
    wp_nonce_field('othershop_meta_box_data','othershop_meta_box_nonce');
    ?>
    <p>
        <label>
            <span>Link mua hàng trên Shopee</span>
            <input type="text" value="<?php echo $shopee;?>" name="shopee_link" style="width: 100%"/>
        </label>
    </p>
    <p>
        <label>
            <span>Link mua hàng trên Tiki</span>
            <input type="text" value="<?php echo $tiki;?>" name="tiki_link" style="width: 100%"/>
        </label>
    </p>
    <p>
        <label>
            <span>Link mua hàng trên Lazada</span>
            <input type="text" value="<?php echo $lazada;?>" name="lazada_link" style="width: 100%"/>
        </label>
    </p>
    <p>
        <label>
            <span>Link mua hàng trên Tiktok</span>
            <input type="text" value="<?php echo $tiktok;?>" name="tiktok_link" style="width: 100%"/>
        </label>
    </p>
    <?php
}


add_action( 'save_post', 'devvn_othershop_save_meta_box_data' );
function devvn_othershop_save_meta_box_data($post_id){
    if ( ! isset( $_POST['othershop_meta_box_nonce'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( $_POST['othershop_meta_box_nonce'], 'othershop_meta_box_data' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( isset( $_POST['post_type'] ) && 'product' == $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }
    } else {
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }
    if ( ! isset( $_POST['shopee_link'] ) || ! isset( $_POST['tiki_link'] ) ) {
        return;
    }

    $shopee_link = sanitize_text_field($_POST['shopee_link']);
    $tiki_link = sanitize_text_field($_POST['tiki_link']);
    $lazada_link = sanitize_text_field($_POST['lazada_link']);
    $tiktok_link = sanitize_text_field($_POST['tiktok_link']);

    update_post_meta($post_id, 'shopee_link', $shopee_link);
    update_post_meta($post_id, 'tiki_link', $tiki_link);
    update_post_meta($post_id, 'lazada_link', $lazada_link);
    update_post_meta($post_id, 'tiktok_link', $tiktok_link);
}
