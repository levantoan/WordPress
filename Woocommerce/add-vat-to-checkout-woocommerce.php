<?php
/*
* Add VAT information to checkout field woocommerce
* Author: levantoan.com
* Add this code to functions.php in your theme
*/

add_action('woocommerce_checkout_shipping', 'devvn_xuat_hoa_don_do', 99);
function devvn_xuat_hoa_don_do(){
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
            font-size: 16px;
            display: inline;
            width: inherit;
            border: 0;
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
                    <label for="billing_vat_company" class="">Tên công ty</label>
                    <input type="text" class="input-text " name="billing_vat_company" id="billing_vat_company" placeholder="" value="">
                </p>
                <p class="form-row form-row-last" id="billing_vat_mst_field">
                    <label for="billing_vat_mst" class="">Mã số thuế</label>
                    <input type="text" class="input-text " name="billing_vat_mst" id="billing_vat_mst" placeholder="" value="">
                </p>
                <p class="form-row form-row-wide " id="billing_vat_companyaddress_field">
                    <label for="billing_vat_companyaddress" class="">Địa chỉ</label>
                    <span class="woocommerce-input-wrapper"><input type="text" class="input-text " name="billing_vat_companyaddress" id="billing_vat_companyaddress" placeholder="" value=""></span>
                </p>
            </fieldset>
            <fieldset>
                <legend>Thông tin nhận hóa đơn:</legend>
                <p class="form-row form-row-first" id="billing_vat_nameuser_field">
                    <label for="billing_vat_nameuser" class="">Họ và tên</label>
                    <input type="text" class="input-text " name="billing_vat_nameuser" id="billing_vat_nameuser" placeholder="" value="">
                </p>
                <p class="form-row form-row-last" id="billing_vat_phoneuser_field">
                    <label for="billing_vat_phoneuser" class="">Số điện thoại</label>
                    <input type="text" class="input-text " name="billing_vat_phoneuser" id="billing_vat_phoneuser" placeholder="" value="">
                </p>
                <p class="form-row form-row-wide " id="billing_vat_useraddress_field">
                    <label for="billing_vat_useraddress" class="">Địa chỉ</label>
                    <span class="woocommerce-input-wrapper"><input type="text" class="input-text " name="billing_vat_useraddress" id="billing_vat_useraddress" placeholder="" value=""></span>
                </p>
            </fieldset>
            <strong>*Lưu ý:</strong><br>
            - Vua Nội Trợ chỉ hỗ trợ xuất hóa đơn đối với giá trị đơn hàng từ 200.000vnđ (không
            tính kèm phí ship – nếu có)<br>
            - Hóa đơn đỏ của nhà cung cấp sẽ được xuất và gửi đến khách hàng sau khi đơn hàng
            được giao thành công<br>
            - Trường hợp khách hàng không điền thông tin xuất hóa đơn và thông tin nhận hóa
            đơn, Vua Nội Trợ sẽ xuất hóa đơn và gửi hóa đơn theo thông tin mua hàng.<br>
            - Vua Nội Trợ sẽ xuất hóa đơn theo đúng thỏa thuận trên và có quyền từ chối đối với
            những yêu cầu chỉnh sửa.
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

add_action('woocommerce_checkout_update_order_meta', 'customise_checkout_field_update_order_meta');

/**
 * Checkout Process
 */

//add_action('woocommerce_checkout_process', 'vat_checkout_field_process');
function vat_checkout_field_process()
{
    // if the field is set, if not then show an error message.
    if (!$_POST['customised_field_name']) wc_add_notice(__('Please enter value.') , 'error');
}


/**
 * Update value of field
 */

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
        if (isset($_POST['billing_vat_nameuser']) && !empty($_POST['billing_vat_nameuser'])) {
            update_post_meta($order_id, 'billing_vat_nameuser', sanitize_text_field($_POST['billing_vat_nameuser']));
        }
        if (isset($_POST['billing_vat_phoneuser']) && !empty($_POST['billing_vat_phoneuser'])) {
            update_post_meta($order_id, 'billing_vat_phoneuser', sanitize_text_field($_POST['billing_vat_phoneuser']));
        }
        if (isset($_POST['billing_vat_useraddress']) && !empty($_POST['billing_vat_useraddress'])) {
            update_post_meta($order_id, 'billing_vat_useraddress', sanitize_text_field($_POST['billing_vat_useraddress']));
        }
    }
}

add_action( 'woocommerce_admin_order_data_after_shipping_address', 'devvn_after_shipping_address_vat', 99);
function devvn_after_shipping_address_vat($order){
    $devvn_xuat_vat_input = get_post_meta($order->get_id(), 'devvn_xuat_vat_input', true);
    $billing_vat_company = get_post_meta($order->get_id(), 'billing_vat_company', true);
    $billing_vat_mst = get_post_meta($order->get_id(), 'billing_vat_mst', true);
    $billing_vat_companyaddress = get_post_meta($order->get_id(), 'billing_vat_companyaddress', true);
    $billing_vat_nameuser = get_post_meta($order->get_id(), 'billing_vat_nameuser', true);
    $billing_vat_phoneuser = get_post_meta($order->get_id(), 'billing_vat_phoneuser', true);
    $billing_vat_useraddress = get_post_meta($order->get_id(), 'billing_vat_useraddress', true);
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
            <strong>Thông tin nhận hóa đơn:</strong><br>
            Họ và tên: <?php echo $billing_vat_nameuser;?><br>
            Số điện thoại: <?php echo $billing_vat_phoneuser;?><br>
            Địa chỉ: <?php echo $billing_vat_useraddress;?><br>
        </p>
        <?php
    endif;
}
