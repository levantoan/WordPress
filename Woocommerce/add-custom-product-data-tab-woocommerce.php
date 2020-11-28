<?php
/*
Author: levantoan.com
Add to functions.php
Demo image: https://photos.app.goo.gl/dWzzDHXxTXHHMcNX7
*/

add_filter('woocommerce_product_data_tabs','devvn_woocommerce_product_data_tabs_video');
function devvn_woocommerce_product_data_tabs_video($tabs){
    if(!isset($tabs['videos'])){
        $tabs['videos'] = array(
            'label'    => __( 'Videos', 'devvn' ),
            'target'   => 'videos_product_data',
            'class'    => array( '' ),
            'priority' => 12,
        );
    }
    return $tabs;
}

add_action('woocommerce_product_data_panels', 'devvn_woocommerce_product_data_panels_videos');
function devvn_woocommerce_product_data_panels_videos(){
    global $post;
    ?>
    <style>
        .form-field.videos_tab_content_field {
            padding: 5px 20px 5px 162px!important;
            margin: 9px 0;
        }
    </style>
    <div id="videos_product_data" class="panel woocommerce_options_panel hidden">
        <div class="options_group">
            <?php
            $videos_tab_title = get_post_meta($post->ID, 'videos_tab_title', true);
            woocommerce_wp_text_input(
                array(
                    'id'        => 'videos_tab_title',
                    'label'     => __( 'Tiêu đề tab', 'devvn'),
                    'value'     =>  ($videos_tab_title) ? $videos_tab_title : 'Videos'
                )
            );
            ?>
            <div class="form-field videos_tab_content_field">
                <label for="videos_tab_content">Nội dung tab</label>
                <?php
                $content   = get_post_meta($post->ID, 'videos_tab_content', true);
                $editor_id = 'videos_tab_content';
                wp_editor( $content, $editor_id );
                ?>
            </div>
        </div>
    </div>
    <?php
}

function devvn_save_videos_tabs( $post_id ) {

    if ( isset( $_POST['videos_tab_title'] ) ) :
        update_post_meta( $post_id, 'videos_tab_title', sanitize_text_field( $_POST['videos_tab_title'] ) );
    endif;
    if ( isset( $_POST['videos_tab_content'] ) ) :
        update_post_meta( $post_id, 'videos_tab_content', sanitize_textarea_field( $_POST['videos_tab_content'] ) );
    endif;

}
add_action( 'woocommerce_process_product_meta_simple', 'devvn_save_videos_tabs'  );
add_action( 'woocommerce_process_product_meta_variable', 'devvn_save_videos_tabs'  );

add_filter( 'woocommerce_product_tabs', 'devvn_custom_videos_tab', 98 );
function devvn_custom_videos_tab( $tabs ) {

    global $product;

    $videos_tab_title = get_post_meta($product->get_id(), 'videos_tab_title', true);
    $videos_tab_content = get_post_meta($product->get_id(), 'videos_tab_content', true);

    if($videos_tab_content) {
        $tabs['videos_tab'] = array(
            'title' => $videos_tab_title,
            'priority' => 50,
            'callback' => 'devvn_videos_tab_content'
        );
    }

    return $tabs;
}

function devvn_videos_tab_content() {

    global $product;
    $videos_tab_content = get_post_meta($product->get_id(), 'videos_tab_content', true);

    echo apply_filters('widget_text_content', $videos_tab_content);
}

