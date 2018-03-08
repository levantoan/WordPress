<?php
/*
 * Edit order style
 * Author: www.levantoan.com
 * */

class DevVN_Edit_Order_style {
    private $stt = 1;
    function __construct(){
        add_filter( 'manage_shop_order_posts_columns', array($this, 'devvn_shop_order_columns'), 20 );
        add_action( 'manage_shop_order_posts_custom_column', array($this, 'devvn_render_shop_order_columns') , 20 );
        add_filter('woocommerce_admin_order_date_format', array($this, 'devvn_woocommerce_admin_order_date_format') );
        add_action('admin_head', array($this, 'devvn_order_style') );
        add_filter( 'post_row_actions', array($this, 'devvn_page_row_actions'), 999, 2 );
    }
    function devvn_page_row_actions( $actions, $post )
    {
        if ( 'shop_order' == $post->post_type ) {
            return array();
        }
        return $actions;
    }
    function devvn_shop_order_columns($posts_columns){
        unset($posts_columns['order_status']);
        unset($posts_columns['wc_actions']);
        unset($posts_columns['order_title']);
        unset($posts_columns['billing_address']);
        unset($posts_columns['shipping_address']);
        unset($posts_columns['customer_message']);
        unset($posts_columns['order_notes']);
        unset($posts_columns['order_date']);
        unset($posts_columns['order_actions']);
        unset($posts_columns['order_number']);
        $posts_columns = $this->devvn_array_insert_after('cb', $posts_columns, 'devvn_order_title', 'Thông tin');
        $posts_columns = $this->devvn_array_insert_after('devvn_order_title', $posts_columns, 'devvn_details', 'Chi tiết');
        //$posts_columns = $this->devvn_array_insert_before('cb', $posts_columns, 'devvn_stt', 'STT');
        $posts_columns['order_date'] = 'Ngày đặt hàng';
        $posts_columns['devvn_message'] = 'Ghi chú';
        $posts_columns['devvn_order_status'] = 'Trạng thái';
        $posts_columns['devvn_actions'] = '';

        return $posts_columns;
    }
    function devvn_array_insert_before($key, array &$array, $new_key, $new_value) {
        if (array_key_exists($key, $array)) {
            $new = array();
            foreach ($array as $k => $value) {
                if ($k === $key) {
                    $new[$new_key] = $new_value;
                }
                $new[$k] = $value;
            }
            return $new;
        }
        return $array;
    }
    function devvn_array_insert_after($key, array &$array, $new_key, $new_value) {
        if (array_key_exists($key, $array)) {
            $new = array();
            foreach ($array as $k => $value) {
                $new[$k] = $value;
                if ($k === $key) {
                    $new[$new_key] = $new_value;
                }
            }
            return $new;
        }
        return $array;
    }
    function devvn_render_shop_order_columns($column){
        global $post, $the_order, $wp_query;
        if ( empty( $the_order ) || $the_order->get_id() !== $post->ID ) {
            $the_order = wc_get_order( $post->ID );
        }
        switch ( $column ) {
            case 'devvn_order_title' :
                if ( $the_order->get_customer_id() ) {
                    $user     = get_user_by( 'id', $the_order->get_customer_id() );
                    $username = '<a href="user-edit.php?user_id=' . absint( $the_order->get_customer_id() ) . '">';
                    $username .= esc_html( ucwords( $user->display_name ) );
                    $username .= '</a>';
                } elseif ( $the_order->get_billing_first_name() || $the_order->get_billing_last_name() ) {
                    /* translators: 1: first name 2: last name */
                    $username = trim( sprintf( _x( '%1$s %2$s', 'full name', 'woocommerce' ), $the_order->get_billing_first_name(), $the_order->get_billing_last_name() ) );
                } elseif ( $the_order->get_billing_company() ) {
                    $username = trim( $the_order->get_billing_company() );
                } else {
                    $username = __( 'Guest', 'woocommerce' );
                }
                /* translators: 1: order and number (i.e. Order #13) 2: user name */
                printf(
                    __( '%1$s <br> %2$s<br>', 'woocommerce' ),
                    'ID: <a href="' . admin_url( 'post.php?post=' . absint( $post->ID ) . '&action=edit' ) . '" class="row-title"><strong>#' . esc_attr( $the_order->get_order_number() ) . '</strong></a>',
                    $username
                );

                $shipping_phone = get_post_meta( $post->ID, '_shipping_phone', true );
                if ( ! wc_ship_to_billing_address_only() && $the_order->needs_shipping_address() && $shipping_phone) {
                    echo esc_html( $shipping_phone ) . '<br>';
                }elseif ( $the_order->get_billing_phone() ) {
                    echo esc_html( $the_order->get_billing_phone() ) . '<br>';
                }

                add_filter('woocommerce_order_formatted_shipping_address', array($this, 'devvn_woocommerce_formatted_address_replacements'), 10);
                add_filter('woocommerce_order_formatted_billing_address', array($this, 'devvn_woocommerce_formatted_address_replacements'), 10);
                if ( ! wc_ship_to_billing_address_only() && $the_order->needs_shipping_address() ) :
                    $address = $the_order->get_formatted_shipping_address();
                else:
                    $address = $the_order->get_formatted_billing_address();
                endif;
                if ( $address ) {
                    echo esc_html( preg_replace( '#<br\s*/?>#i', ', ', $address ) ) . '<br>';
                }
                remove_filter('woocommerce_order_formatted_billing_address', array($this, 'devvn_woocommerce_formatted_address_replacements'), 10);
                remove_filter('woocommerce_order_formatted_shipping_address', array($this, 'devvn_woocommerce_formatted_address_replacements'), 10);

                if ( $the_order->get_billing_email() ) {
                    echo esc_html( $the_order->get_billing_email() ) . '<br>';
                }
                if ( $the_order->get_shipping_method() ) {
                    echo '<small class="meta">' . __( 'Via', 'woocommerce' ) . ' ' . esc_html( $the_order->get_shipping_method() ) . '</small>';
                }
                echo '<button type="button" class="toggle-row"><span class="screen-reader-text">' . __( 'Show more details', 'woocommerce' ) . '</span></button>';
                break;
            case "devvn_details":
                echo '<a href="' . admin_url( 'post.php?post=' . absint( $post->ID ) . '&action=edit' ) . '">Chi tiết</a>';
                break;
            case "devvn_total":
                echo $the_order->get_formatted_order_total();
                break;
            case "devvn_stt":
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                $per_page = $wp_query->query_vars['posts_per_page'];
                echo ($this->stt++) + ( $per_page * ($paged - 1));
                break;
            case "devvn_order_status":
                ?>
                <select id="devvn_order_status" class="wc-enhanced-select">
                    <?php
                    $statuses = wc_get_order_statuses();
                    foreach ( $statuses as $status => $status_name ) {
                        echo '<option data-name="' . esc_attr(str_replace('wc-','',$status)) . '" value="' . esc_attr( $status ) . '" ' . selected( $status, 'wc-' . $the_order->get_status( 'edit' ), false ) . '>' . esc_html( $status_name ) . '</option>';
                    }
                    ?>
                </select>
                <?php
                break;
            case "devvn_actions":
                ?>
                <p>
                    <a class="button tips change_status" href="javascript:void(0)" data-tip="Lưu" data-href="<?php echo wp_nonce_url( admin_url( 'admin-ajax.php?action=woocommerce_mark_order_status&order_id=' . $post->ID ), 'woocommerce-mark-order-status' );?>">Lưu</a>
                    <a class="button tips delete_order" href="<?php echo get_delete_post_link($post->ID, '', true)?>" data-tip="Xóa">Xóa</a>
                </p>
                <?php
                break;
            case "devvn_notes":
                if ( $post->comment_count ) {
                    $latest_notes = wc_get_order_notes( array(
                        'order_id' => $post->ID,
                        //'limit'    => '10',
                        'orderby'  => 'date_created_gmt',
                        'type'  => 'customer'
                    ) );
                    $latest_note = current( $latest_notes );
                    $count_note = count($latest_notes);
                    if ( isset( $latest_note->content ) ) {
                        echo '<span class="note-on tips" data-tip="' . wc_sanitize_tooltip( $latest_note->content ) . '">' . sprintf( '%d tin nhắn', $count_note ) . '</span>';
                    } else {
                        /* translators: %d: notes count */
                        echo '<span class="note-on tips" data-tip="' . wc_sanitize_tooltip( sprintf( _n( '%d tin nhắn', '%d tin nhắn', $count_note, 'woocommerce' ), $count_note ) ) . '">' . sprintf( '%d tin nhắn', $count_note )  . '</span>';
                    }
                } else {
                    echo '<span class="na">0 tin nhắn</span>';
                }
                break;
            case "devvn_message":
                if ( $the_order->get_customer_note() ) {
                    echo '<span class="note-on tips" data-tip="' . wc_sanitize_tooltip( $the_order->get_customer_note() ) . '">1 ghi chú</span>';
                } else {
                    echo '<span class="na">0 ghi chú</span>';
                }
                break;
        }
        return $column;
    }
    function devvn_woocommerce_admin_order_date_format(){
        return 'h:i d/m/Y';
    }
    function devvn_woocommerce_formatted_address_replacements($address){
        unset($address['first_name']);
        unset($address['last_name']);
        return $address;
    }
    function devvn_order_style() {
        $current_screen = get_current_screen();
        if(isset($current_screen->post_type) && $current_screen->post_type == 'shop_order' && $current_screen->base == 'edit'):
            ?>
            <style>
                .post-type-shop_order .wp-list-table td, .post-type-shop_order .wp-list-table th {
                    width: inherit;
                }
                .post-type-shop_order .wp-list-table tbody td, .post-type-shop_order .wp-list-table tbody th {
                    padding: 5px;
                    line-height: 18px;
                }
                .post-type-shop_order .wp-list-table .check-column {
                    padding: 3px !important;
                    width: 23px;
                    text-align: center;
                }
                .post-type-shop_order .wp-list-table .check-column input[type="checkbox"] {
                    margin: 0;
                }
                table.wp-list-table .column-devvn_order_status span.select2 {
                    margin-bottom: 10px;
                }
                .widefat .type-shop_order td {
                    vertical-align: middle;
                }
                table.wp-list-table .column-customer_message, table.wp-list-table .column-devvn_message {
                    width: 60px;
                    padding: 5px !important;
                    text-align: center;
                }

                table.wp-list-table .column-order_date {
                    width: 145px;
                }
                table.wp-list-table .column-devvn_order_status {
                    width: 155px;
                    padding: 5px !important;
                    text-align: center;
                }
                .widefat .column-devvn_actions a.button {
                    float: left;
                    margin: 0 4px 2px 0;
                    cursor: pointer;
                    padding: 3px 4px;
                    height: auto;
                }
                .devvn_actions .button {
                    display: block;
                    text-indent: -9999px;
                    position: relative;
                    height: 1em;
                    width: 1em;
                    padding: 0!important;
                    height: 2em!important;
                    width: 2em;
                }
                .devvn_actions .change_status::after,
                .devvn_actions .delete_order::after{
                    font-family: "dashicons";
                    speak: none;
                    font-weight: 400;
                    text-transform: none;
                    -webkit-font-smoothing: antialiased;
                    text-indent: 0px;
                    position: absolute;
                    top: 0px;
                    left: 0px;
                    width: 100%;
                    height: 100%;
                    text-align: center;
                    content: "\f316";
                    line-height: 1.85;
                    font-variant: normal;
                    margin: 0px;
                }
                .devvn_actions .delete_order::after {
                    content: "\f182";
                }
                table.wp-list-table .column-devvn_actions,
                table.wp-list-table .column-devvn_details {
                    width: 65px;
                    padding: 5px !important;
                    text-align: center;
                }
                .post-type-shop_order table.wp-list-table.widefat {
                    border-collapse: collapse;
                }
                .post-type-shop_order table.wp-list-table.widefat .type-shop_order td,
                .post-type-shop_order table.wp-list-table.widefat th {
                    vertical-align: middle;
                    border: 1px solid #e5e5e5 !important;
                }
                table.wp-list-table .column-devvn_stt {
                    width: 25px;
                    text-align: center;
                    padding-left: 2px;
                    padding-right: 2px;
                }
                .post-type-shop_order .wp-list-table .column-order_total {
                    width: 105px;
                    padding: 5px !important;
                    text-align: center;
                }
                .post-type-shop_order .wp-list-table .column-order_date, .post-type-shop_order .wp-list-table .column-order_status {
                    width: 127px !important;
                    text-align: center;
                    padding: 5px !important;
                }
                .tablenav .alignleft.actions.bulkactions select {
                    max-width: 100px;
                }
            </style>
            <script type="text/javascript">
                (function($){
                    $(document).ready(function(){
                        $('.change_status').click(function(){
                            var thisTr = $(this).closest('tr');
                            var statusThis = $('#devvn_order_status option:selected',thisTr).data('name');
                            var thisURL = $(this).data('href');
                            var url = thisURL+"&status="+statusThis;
                            document.location = url;
                            return false;
                        });
                    })
                })(jQuery)
            </script>
            <?php
        endif;
    }
}
new DevVN_Edit_Order_style();
