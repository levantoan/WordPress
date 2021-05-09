<?php
/*
 * Thêm lựa chọn xuất hóa đơn VAT vào checkout
 * Author: https://levantoan.com
 * Thêm vào functions.php của theme
 * */
add_action('woocommerce_after_checkout_billing_form','devvn_xuat_hoa_don_vat');
function devvn_xuat_hoa_don_vat(){
    if(get_option('devvn_vat_info', 'yes') == 'no') return false;
    ?>
    <style>
        .devvn_xuat_hoa_don_do {
            background: #eee;
            padding: 10px;
            border-radius: 3px;
        }
        .devvn_xuat_vat_wrap {
            display: none;
        }
        label.devvn_xuat_vat_input_label {
            display: block;
            cursor: pointer;
            margin-bottom: 0;
        }
        .devvn_xuat_vat_wrap fieldset {
            margin: 10px 0;
            padding: 10px;
            background: transparent;
            border: 1px solid #b0aeae;
        }
        .devvn_xuat_vat_wrap fieldset legend {
            background: transparent;
            padding: 0 5px;
            margin: 0 0 0 10px;
            font-size: 14px;
            display: inline;
            width: inherit;
            border: 0;
            text-transform: none;
            color: #000;
        }
        .devvn_xuat_vat_wrap fieldset p {
            margin-bottom: 10px;
        }
        .devvn_xuat_vat_wrap fieldset p:last-child {
            margin-bottom: 0;
        }
        .vat_active .devvn_xuat_vat_wrap {
            display: block;
        }
    </style>
    <div class="devvn_xuat_hoa_don_do">
        <label class="devvn_xuat_vat_input_label">
            <input class="devvn_xuat_vat_input" type="checkbox" name="devvn_xuat_vat_input" value="1">
            Xuất hóa đơn VAT
        </label>
        <div class="devvn_xuat_vat_wrap">
            <fieldset>
                <legend>Thông tin xuất hóa đơn:</legend>
                <p class="form-row form-row-first" id="billing_vat_company_field">
                    <label for="billing_vat_company" class="">Tên công ty <abbr class="required" title="bắt buộc">*</abbr></label>
                    <input type="text" class="input-text " name="billing_vat_company" id="billing_vat_company" placeholder="" value="">
                </p>
                <p class="form-row form-row-last" id="billing_vat_mst_field">
                    <label for="billing_vat_mst" class="">Mã số thuế <abbr class="required" title="bắt buộc">*</abbr></label>
                    <input type="text" class="input-text " name="billing_vat_mst" id="billing_vat_mst" placeholder="" value="">
                </p>
                <p class="form-row form-row-wide " id="billing_vat_companyaddress_field">
                    <label for="billing_vat_companyaddress" class="">Địa chỉ <abbr class="required" title="bắt buộc">*</abbr></label>
                    <span class="woocommerce-input-wrapper"><input type="text" class="input-text " name="billing_vat_companyaddress" id="billing_vat_companyaddress" placeholder="" value=""></span>
                </p>
            </fieldset>
        </div>
    </div>
    <script type="text/javascript">
        (function ($) {
            $(document).ready(function () {
                function check_vat(){
                    var parentVAT = $('input.devvn_xuat_vat_input').closest('.devvn_xuat_hoa_don_do');
                    if($('input.devvn_xuat_vat_input').is(":checked")){
                        parentVAT.addClass('vat_active');
                    }else{
                        parentVAT.removeClass('vat_active');
                    }
                    $('body').trigger('update_checkout');
                }
                check_vat();
                $('input.devvn_xuat_vat_input').on('change', function () {
                    check_vat();
                });
            });
        })(jQuery);
    </script>
    <?php
}
add_action('woocommerce_checkout_process', 'vat_checkout_field_process');
function vat_checkout_field_process()
{
    if (isset($_POST['devvn_xuat_vat_input']) && !empty($_POST['devvn_xuat_vat_input'])) {
        if (empty($_POST['billing_vat_company'])) {
            wc_add_notice(__('Hãy nhập tên công ty') , 'error');
        }
        if (empty($_POST['billing_vat_mst'])) {
            wc_add_notice(__('Hãy nhập mã số thuế') , 'error');
        }
        if (empty($_POST['billing_vat_companyaddress'])) {
            wc_add_notice(__('Hãy nhập địa chỉ công ty') , 'error');
        }
    }
}
add_action('woocommerce_checkout_update_order_meta', 'vat_checkout_field_update_order_meta');
function vat_checkout_field_update_order_meta($order_id)
{
    if (isset($_POST['devvn_xuat_vat_input']) && !empty($_POST['devvn_xuat_vat_input'])) {
        update_post_meta($order_id, 'devvn_xuat_vat_input', intval($_POST['devvn_xuat_vat_input']));
        if (isset($_POST['billing_vat_company']) && !empty($_POST['billing_vat_company'])) {
            update_post_meta($order_id, 'billing_vat_company', sanitize_text_field($_POST['billing_vat_company']));
        }
        if (isset($_POST['billing_vat_mst']) && !empty($_POST['billing_vat_mst'])) {
            update_post_meta($order_id, 'billing_vat_mst', sanitize_text_field($_POST['billing_vat_mst']));
        }
        if (isset($_POST['billing_vat_companyaddress']) && !empty($_POST['billing_vat_companyaddress'])) {
            update_post_meta($order_id, 'billing_vat_companyaddress', sanitize_text_field($_POST['billing_vat_companyaddress']));
        }
    }
}
add_action( 'woocommerce_admin_order_data_after_shipping_address', 'devvn_after_shipping_address_vat', 99);
function devvn_after_shipping_address_vat($order){
    $devvn_xuat_vat_input = get_post_meta($order->get_id(), 'devvn_xuat_vat_input', true);
    $billing_vat_company = get_post_meta($order->get_id(), 'billing_vat_company', true);
    $billing_vat_mst = get_post_meta($order->get_id(), 'billing_vat_mst', true);
    $billing_vat_companyaddress = get_post_meta($order->get_id(), 'billing_vat_companyaddress', true);
    ?>
    <p><strong>Xuất hóa đơn:</strong> <?php echo ($devvn_xuat_vat_input) ? 'Có' : 'Không';?></p>
    <?php
    if($devvn_xuat_vat_input):
        ?>
        <p>
            <strong>Thông tin xuất hóa đơn:</strong><br>
            Tên công ty: <?php echo $billing_vat_company;?><br>
            Mã số thuế: <?php echo $billing_vat_mst;?><br>
            Địa chỉ: <?php echo $billing_vat_companyaddress;?><br>
        </p>
    <?php
    endif;
}

add_action('woocommerce_before_calculate_totals', 'save_vat_fee_to_session');
function save_vat_fee_to_session(){
    if (isset($_POST['post_data']) && !empty($_POST['post_data'])) {
        parse_str(wp_unslash($_POST['post_data']), $post_data);
        if(isset($post_data['devvn_xuat_vat_input']) && !empty($post_data['devvn_xuat_vat_input'])) {
            WC()->session->set( 'devvn_xuat_vat_input', true );
        }else{
            WC()->session->set( 'devvn_xuat_vat_input', false );
        }
    }
}

add_action( 'woocommerce_cart_calculate_fees','vat_fee' );
function vat_fee() {
    global $woocommerce;

    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;

    $percentage = floatval(get_option('devvn_vat_fee', 10)) / 100;
    $vat_fee = $woocommerce->cart->cart_contents_total * $percentage;

    if(get_option('devvn_vat_enable', 'no') == 'yes' || WC()->session->get( 'devvn_xuat_vat_input')){
        $woocommerce->cart->add_fee('VAT(10%)', $vat_fee, true, '');
    }

}

add_action('woocommerce_email_customer_details', 'vat_to_email', 5);
function vat_to_email($order){
    $devvn_xuat_vat_input = get_post_meta($order->get_id(), 'devvn_xuat_vat_input', true);
    $billing_vat_company = get_post_meta($order->get_id(), 'billing_vat_company', true);
    $billing_vat_mst = get_post_meta($order->get_id(), 'billing_vat_mst', true);
    $billing_vat_companyaddress = get_post_meta($order->get_id(), 'billing_vat_companyaddress', true);

    ?>
    <div style="margin-bottom: 40px;">
        <p><strong>Xuất hóa đơn VAT:</strong> <?php echo ($devvn_xuat_vat_input) ? 'Có' : 'Không';?></p>
        <?php if($devvn_xuat_vat_input):?>
            <strong>Thông tin xuất hóa đơn:</strong><br>
            Tên công ty: <?php echo $billing_vat_company;?><br>
            Mã số thuế: <?php echo $billing_vat_mst;?><br>
            Địa chỉ: <?php echo $billing_vat_companyaddress;?><br>
        <?php endif;?>
    </div>
    <?php
}

add_filter('woocommerce_get_settings_advanced', 'devvn_vat_woocommerce_settings_pages');
function devvn_vat_woocommerce_settings_pages($settings){
    $one_page_settings = array(
        array(
            'title' => __( 'Cài đặt VAT', 'devvn-vat' ),
            'desc'  => __( 'Cài đặt VAT ở trang checkout', 'devvn-vat' ),
            'type'  => 'title',
            'id'    => 'devvn_vat_page_options',
        ),
        array(
            'title'           => __( 'Bắt buộc tính VAT', 'devvn-vat' ),
            'desc'            => __( 'Nếu chọn mục này thì khách có chọn xuất VAT hay không thì vẫn tính phí VAT', 'devvn-vat' ),
            'id'              => 'devvn_vat_enable',
            'default'         => 'no',
            'type'            => 'checkbox',
        ),
        array(
            'title'           => __( 'Mức phí VAT (%)', 'devvn-vat' ),
            'desc'            => __( 'Mức phí VAT. Mặc định là 10%', 'devvn-vat' ),
            'id'              => 'devvn_vat_fee',
            'default'         => 10,
            'type'            => 'number',
        ),
        array(
            'title'           => __( 'Hiển thị trường thông tin VAT', 'devvn-vat' ),
            'desc'            => __( 'Hiển thị hoặc không trường thông tin cho phép khách chọn xuất hoặc không xuất VAT', 'devvn-vat' ),
            'id'              => 'devvn_vat_info',
            'default'         => 'yes',
            'type'            => 'checkbox',
        ),
        array(
            'type' => 'sectionend',
            'id'   => 'devvn_vat_page_options',
        ),
    );
    $settings = array_merge($one_page_settings, $settings);
    return $settings;
}
