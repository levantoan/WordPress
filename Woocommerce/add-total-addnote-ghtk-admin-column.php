<?php
/*
Demo https://lh3.googleusercontent.com/pw/ACtC-3eThIzTOIRtF1vreHHIQE5ICgANYp4GKqFE8HlxoNPfzCVBNIz8Irk6N5H8--jsFUP6y3qkSwVgV-j7GniM-0qDvp9FWnlWhhPX3oZUKmznwuZAPnE1MO2EjtChXWz5NdUAFzXNtGrx1G7_voCY5wVZMQ=w1175-h243-no?authuser=0
*/
//Thêm cột tổng và add noted tới GHTK
add_filter('devvn_ghtk_manage_shop_order_posts_columns', 'custom_column_order_total', 10, 2);
function custom_column_order_total($posts_columns, $thisFunc){
    if(isset($posts_columns['order_total'])) unset($posts_columns['order_total']);
    $posts_columns = $thisFunc->devvn_array_insert_after('devvn_products', $posts_columns, 'devvn_order_total', 'Tổng');
    $posts_columns = $thisFunc->devvn_array_insert_after('order_date', $posts_columns, 'devvn_order_mess', 'Ghi chú');
    return $posts_columns;
}

add_action( 'manage_shop_order_posts_custom_column', 'devvn_render_devvn_order_title_columns', 20 );
function devvn_render_devvn_order_title_columns($column){
    global $post, $the_order, $wp_query;
    if ( empty( $the_order ) || $the_order->get_id() !== $post->ID ) {
        $the_order = wc_get_order( $post->ID );
    }
    switch ( $column ) {
        case 'devvn_order_total' :
            ?>
            <div class="wc-order-totals">
                <div>
                    <span class="label"><?php esc_html_e( 'Tiền hàng:', 'woocommerce' ); ?></span>
                    <span class="total">
                        <?php echo wc_price( $the_order->get_subtotal(), array( 'currency' => $the_order->get_currency() ) ); // WPCS: XSS ok. ?>
                    </span>
                </div>
                <?php if ( 0 < $the_order->get_total_discount() ) : ?>
                    <div>
                        <span class="label"><?php esc_html_e( 'Giảm giá:', 'woocommerce' ); ?></span>
                        <span class="total">-
                            <?php echo wc_price( $the_order->get_total_discount(), array( 'currency' => $the_order->get_currency() ) ); // WPCS: XSS ok. ?>
                        </span>
                    </div>
                <?php endif; ?>

                <?php if ( $the_order->get_shipping_methods() ) : ?>
                    <div>
                        <span class="label"><?php esc_html_e( 'Tiền SHIP:', 'woocommerce' ); ?></span>
                        <span class="total">
                            <?php echo wc_price( $the_order->get_shipping_total(), array( 'currency' => $the_order->get_currency() ) ); // WPCS: XSS ok. ?>
                        </span>
                    </div>
                <?php endif; ?>
                <hr>
                <div>
                    <span class="label"><?php esc_html_e( 'Tổng', 'woocommerce' ); ?>:</span>
                    <span class="total">
                        <?php echo wc_price( $the_order->get_total(), array( 'currency' => $the_order->get_currency() ) ); // WPCS: XSS ok. ?>
                    </span>
                </div>

            </div>
            <?php
            break;

        case 'devvn_order_mess':
            $args = array(
                'order_id' => $the_order->get_id(),
                'type'  =>  'internal'
            );
            $notes = wc_get_order_notes( $args );
            ?>
            <div class="devvn_list_ghichu_wrap">
            <ul class="devvn_list_ghichu">
                <?php if($notes):?>
                    <?php foreach ( $notes as $note ) {?>
                        <li>
                            <strong><?php
                            echo esc_html( sprintf( __( '%1$s %2$s', 'woocommerce' ), $note->date_created->date_i18n( 'd/m/Y' ), $note->date_created->date_i18n('H:i') ) );
                            ?></strong>
                            <?php echo wpautop( wptexturize( wp_kses_post( $note->content ) ) ); ?>
                        </li>
                    <?php }?>
                <?php endif;?>
            </ul>
            </div>
            <textarea name="devvn_ghtk_note" class="devvn_ghtk_note"></textarea>
            <a href="" class="devvn_add_ghichu" data-id="<?php echo $the_order->get_id();?>" data-nonce="<?php echo wp_create_nonce( 'add-order-note' );?>">Thêm ghi chú</a>
            <?php
            break;
    }
}

add_action('admin_head', 'devvn_ghtk_custom_css');
function devvn_ghtk_custom_css() {
    $current_screen = get_current_screen();
    if(isset($current_screen->post_type) && $current_screen->post_type == 'shop_order' && $current_screen->base == 'edit'):
    ?>
    <style>
        ul.devvn_list_ghichu {
            list-style: none;
            margin: 0;
            padding: 0;
        }
        ul.devvn_list_ghichu li {
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 5px;
        }
        textarea.devvn_ghtk_note {
            width: 100%;
            height: 30px;
        }
        .devvn_list_ghichu_wrap {
            max-height: 150px;
            overflow-x: hidden;
            overflow-y: auto;
            margin-bottom: 5px;
        }
    </style>
    <script type="text/javascript">
        (function ($) {
            $(document).ready(function () {
                $('body').on('click', 'a.devvn_add_ghichu', function () {
                    var thisWrap = $(this).closest('.devvn_order_mess');
                    var orderID = $(this).data('id');
                    var nonce = $(this).data('nonce');

                    if ( ! $( 'textarea.devvn_ghtk_note', thisWrap ).val() ) {
                        return false;
                    }

                    thisWrap.block({
                        message: null,
                        overlayCSS: {
                            background: '#fff',
                            opacity: 0.6
                        }
                    });

                    var data = {
                        action:    'devvn_ghtk_addnote',
                        post_id:   orderID,
                        note:      $( 'textarea.devvn_ghtk_note', thisWrap ).val(),
                        note_type: '',
                        security:  nonce
                    };

                    $.post( woocommerce_admin_meta_boxes.ajax_url, data, function( response ) {
                        $( 'ul.devvn_list_ghichu', thisWrap ).prepend( response );
                        thisWrap.unblock();
                        $( 'textarea.devvn_ghtk_note', thisWrap ).val( '' );
                    });

                    return false;
                });
            });
        })(jQuery);
    </script>
    <?php
    endif;
}

add_action( 'wp_ajax_devvn_ghtk_addnote', 'devvn_ghtk_addnote_func' );
function devvn_ghtk_addnote_func() {
    check_ajax_referer( 'add-order-note', 'security' );

    if ( ! current_user_can( 'edit_shop_orders' ) || ! isset( $_POST['post_id'], $_POST['note'], $_POST['note_type'] ) ) {
        wp_die( -1 );
    }

    $post_id   = absint( $_POST['post_id'] );
    $note      = wp_kses_post( trim( wp_unslash( $_POST['note'] ) ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
    $note_type = wc_clean( wp_unslash( $_POST['note_type'] ) );

    $is_customer_note = ( 'customer' === $note_type ) ? 1 : 0;

    if ( $post_id > 0 ) {
        $order      = wc_get_order( $post_id );
        $comment_id = $order->add_order_note( $note, $is_customer_note, true );
        $note       = wc_get_order_note( $comment_id );
        ?>
        <li>
            <strong><?php
                echo esc_html( sprintf( __( '%1$s %2$s', 'woocommerce' ), $note->date_created->date_i18n( 'd/m/Y' ), $note->date_created->date_i18n('H:i') ) );
                ?></strong>
            <?php echo wpautop( wptexturize( wp_kses_post( $note->content ) ) ); ?>
        </li>
        <?php
    }
    wp_die();
}
