<?php
remove_action('woocommerce_after_single_product_summary','woocommerce_upsell_display', 15);
add_action('woocommerce_single_product_summary','devvn_woocommerce_upsell_display', 60);
function devvn_woocommerce_upsell_display(){
    global $product, $woocommerce;

    if ( ! $product || !$product->is_in_stock() || ! $product->is_purchasable()) {
        return;
    }

    $upsells = wc_products_array_orderby( array_filter( array_map( 'wc_get_product', $product->get_upsell_ids() ), 'wc_products_array_filter_visible' ));

    if ( $upsells ) : ?>
        <?php
        $devvn_upsells = array();
        $devvn_total = 0;
        if($product->get_type() == 'variable'){
            $price = $product->get_variation_price();
        }else{
            $price = $product->get_price();
        }
        if($price) {
            $devvn_upsells[0]['id'] = $product->get_id();
            $devvn_upsells[0]['title'] = esc_attr($product->get_title());
            $devvn_upsells[0]['image'] = $product->get_image();
            $devvn_upsells[0]['price'] = $price;
            $devvn_upsells[0]['pricehtml'] = $product->get_price_html();
            $devvn_total += $price;
        }
        $devvn_count = 1;
        foreach ( $upsells as $upsell ) :
            if($upsell->get_type() == 'variable'){
                $price = $upsell->get_variation_price();
            }else{
                $price = $upsell->get_price();
            }
            if($price) {
                $devvn_upsells[$devvn_count]['id'] = ($upsell->get_parent_id()) ? $upsell->get_parent_id() : $upsell->get_id();
                $devvn_upsells[$devvn_count]['attr_id'] = $upsell->get_id();
                $devvn_upsells[$devvn_count]['title'] = esc_attr($upsell->get_title());
                $devvn_upsells[$devvn_count]['image'] = $upsell->get_image();
                $devvn_upsells[$devvn_count]['price'] = $price;
                $devvn_upsells[$devvn_count]['pricehtml'] = $upsell->get_price_html();
                $devvn_total += $price;
                $devvn_count++;
            }
        endforeach;
        ?>
        <style type="text/css">
            .devvn_upsells h3 {
                font-size: 14px;
                margin-bottom: 14px;
                color: #4a4a4a;
                font-weight: 400;
                text-transform: uppercase;
            }
            .devvn_list_product_upsell {
                display: flex;
            }
            .devvn_upsells_item {
                display: flex;
                align-items: center;
                margin-bottom: 10px;
            }
            .devvn_list_product_upsell .devvn_upsells_item:nth-child(n+2):before {
                content: "+";
                font-size: 22px;
                color: #909091;
                font-weight: 300;
                margin: 0 10px;
            }
            .devvn_upsells_item a {
                border: 1px solid #d8d8d8;
                border-radius: 4px;
                overflow: hidden;
                text-align: center;
                padding: 3px;
                display: inline-block;
            }
            .devvn_upsells_item a img {
                width: 84px;
                height: 84px;
            }
            .devvn_list_upsell_choose label.devvn_upsell_choose {
                display: block;
                cursor: pointer;
                font-size: 13px;
                font-weight: 400;
                color: #9b9b9b;
                margin: 5px 0;
            }
            label.devvn_upsell_choose input {
                opacity: 0;
                filter: alpha(opacity=0);
                display: none;
            }
            .devvn_list_upsell_choose label.devvn_upsell_choose input[type=checkbox]+span.devvn_upsells_icon {
                width: 18px;
                height: 18px;
                display: inline-block;
                background-image: url(<?php echo get_stylesheet_directory_uri();?>/images/check-off.svg);
                vertical-align: middle;
                color: #a6a6a6;
                margin-right: 2px;
                user-select: none;
            }
            .devvn_list_upsell_choose label.devvn_upsell_choose input[type=checkbox]:checked+span.devvn_upsells_icon {
                background-image: url(<?php echo get_stylesheet_directory_uri();?>/images/check-on.svg);
            }
            span.devvn_upsells_text a, span.devvn_upsells_text a span.amount {
                color: #9b9b9b;
            }
            .devvn_list_upsell_choose label.devvn_upsell_choose input[type=checkbox]:checked+span.devvn_upsells_icon+.devvn_upsells_text a,
            .devvn_list_upsell_choose label.devvn_upsell_choose input[type=checkbox]:checked+span.devvn_upsells_icon+.devvn_upsells_text a span.amount{
                color: #4a4a4a;
            }
            .devvn_list_upsell_choose label.devvn_upsell_choose .devvn_upsells_name {
                max-width: 250px;
                display: inline-block;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                vertical-align: text-top;
            }
            .devn_upsells_tocart p, .devn_upsells_tocart, .devn_upsells_tocart * {
                font-size: 14px;
            }
            span.total-price.price, span.total-price.price * {
                color: #ff313d;
            }
            button.devvn-add-combo {
                background: #ff313d;
                color: #fff;
                border: 0;
                font-size: 12px;
                font-weight: 400;
                border-radius: 3px;
                padding: 3px 14px;
                position: relative;
            }
            @-webkit-keyframes spin2 {
                0% {
                    -webkit-transform: rotate(0deg);
                    transform: rotate(0deg)
                }

                100% {
                    -webkit-transform: rotate(360deg);
                    transform: rotate(360deg)
                }
            }

            @keyframes spin2 {
                0% {
                    -webkit-transform: rotate(0deg);
                    transform: rotate(0deg)
                }

                100% {
                    -webkit-transform: rotate(360deg);
                    transform: rotate(360deg)
                }
            }
            button.devvn-add-combo.sloading:after {
                -webkit-animation: spin2 500ms infinite linear;
                animation: spin2 500ms infinite linear;
                border: 2px solid #fff;
                border-radius: 32px;
                border-right-color: transparent !important;
                border-top-color: transparent !important;
                content: "";
                display: block;
                height: 16px;
                top: 50%;
                margin-top: -8px;
                left: 50%;
                margin-left: -8px;
                position: absolute;
                width: 16px;
            }
            button.devvn-add-combo.sloading {
                opacity: 1 !important;
                position: relative;
                color: rgba(255,255,255,0.55);
                pointer-events: none !important;
            }
            .devvn_upsells_item.disabled {
                opacity: 0.3;
            }
            .devvn_upsells_mess, .devvn_upsells_mess * {
                font-size: 13px;
            }
            .devvn_upsells_mess {
                margin: 10px 0 0 0;
            }
            strong.devvn_upsells_price del {
                display: none;
            }
            strong.devvn_upsells_price ins {
                text-decoration: none;
            }
            span.devvn_upsells_text a:hover{
                text-decoration: none;
            }
        </style>
        <script type="text/javascript">
            (function($){
                $(document).ready(function(){
                    function devvn_number_format (number, decimals, dec_point, thousands_sep) {
                        // Strip all characters but numerical ones.
                        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
                        var n = !isFinite(+number) ? 0 : +number,
                            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                            s = '',
                            toFixedFix = function (n, prec) {
                                var k = Math.pow(10, prec);
                                return '' + Math.round(n * k) / k;
                            };
                        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
                        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
                        if (s[0].length > 3) {
                            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
                        }
                        if ((s[1] || '').length < prec) {
                            s[1] = s[1] || '';
                            s[1] += new Array(prec - s[1].length + 1).join('0');
                        }
                        return s.join(dec);
                    }
                    $('.devvn-add-combo').click(function(e){
                        e.preventDefault();
                        devvn_combo();
                    });
                    $(".devvn_list_upsell_choose .devvn_upsell_choose a").on("click", function(e) {
                        e.stopPropagation()
                    });
                    $(".devvn_list_upsell_choose .devvn_upsell_choose").on("click", function(t) {
                        var a = $(this).find("input[type=checkbox]");
                        if (1 === $('.devvn_list_upsell_choose .devvn_upsell_choose input[type="checkbox"]:checked').length && a.prop("checked")) {
                            return t.preventDefault()
                        }
                        void t.stopPropagation();
                        $(".devvn_list_upsell_choose .devvn_upsell_choose input[name=" + a.attr("name") + "]").prop("checked", !a.prop("checked"));
                        $("#" + a.attr("name")).toggleClass("disabled", !a.prop("checked"));
                    });
                    $('#devvn_combo').on('change submit',function(e){
                        e.preventDefault();
                        var checked = $('.devvn_list_upsell_choose input[type="checkbox"]:checked');
                        var countProd = checked.length;
                        $('.devvn_upsells_count').text(countProd);
                        var price = 0;
                        checked.each(function(index){
                            price += $(this).data('price');
                        });
                        $('.devn_upsells_tocart .amount').html(devvn_number_format(price.toFixed(0),0,'','.') + '<span class="woocommerce-Price-currencySymbol">₫</span>');
                    });
                    var loading = false;
                    function devvn_combo(){
                        var buttonS = $('.devvn-add-combo');
                        var nonce = buttonS.data('nonce');
                        var dataproid = [];
                        var checked = $('.devvn_list_upsell_choose input[type="checkbox"]:checked');
                        checked.each(function(index){
                            dataproid.push({
                                proid: $(this).data('id'),
                                price: $(this).data('price'),
                                availableid: $(this).data('availableid'),
                                qty: $(this).data('qty')
                            });
                        });
                        if(!loading) {
                            $.ajax({
                                type: "post",
                                dataType: "json",
                                url: '<?php echo admin_url('admin-ajax.php');?>',
                                data: {action: "devvn_add_combo", dataproid: dataproid, nonce: nonce},
                                context: this,
                                beforeSend: function () {
                                    loading = true;
                                    buttonS.addClass('sloading');
                                },
                                success: function (response) {
                                    buttonS.removeClass('sloading');
                                    $('.devvn_upsells_mess').html('Thêm '+response.data+' sp thành công. <a href="<?php echo wc_get_cart_url();?>" title="">Xem giỏ hàng</a>');
                                    loading = false;
                                },
                                error: function(jqXHR,textStatus, errorThrown ){
                                    buttonS.removeClass('sloading');
                                    loading = false;
                                }
                            });
                        }
                        return false;
                    }
                });
            })(jQuery);
        </script>
        <div class="devvn_upsells">
            <h3><?php _e( 'Thường được mua cùng', 'woocommerce' ) ?></h3>
            <div class="devvn_list_product_upsell">
                <?php foreach ( $devvn_upsells as $k=>$upsell ) : ?>
                    <?php
                    if($k == 0){
                        $href = 'javascript:void(0)';
                    }else{
                        $href = esc_url( get_permalink( $upsell['id'] ) );;
                    }
                    ?>
                    <div class="devvn_upsells_item" id="combo_<?php echo $upsell['id'];?>">
                        <a href="<?php echo $href;?>" title="<?php echo $upsell['title']; ?>" target="_blank">
                            <?php echo $upsell['image']; ?>
                        </a>
                    </div>
                <?php endforeach;?>
            </div>
            <form id="devvn_combo">
                <div class="devvn_list_upsell_choose">
                    <?php foreach ( $devvn_upsells as $k=>$upsell ) : ?>
                        <?php
                        if($k == 0){
                            $href = 'javascript:void(0)';
                        }else{
                            $href = esc_url( get_permalink( $upsell['id'] ) );;
                        }
                        ?>
                        <label class="devvn_upsell_choose">
                            <input type="checkbox"
                                   name="combo_<?php echo $upsell['id'];?>"
                                   data-id="<?php echo $upsell['id'];?>"
                                   data-price="<?php echo $upsell['price']; ?>"
                                   data-qty="1"
                                   data-availableid="<?php echo $upsell['attr_id'];?>"
                                   data-available=""
                                   checked>
                            <span class="devvn_upsells_icon"></span>
                            <span class="devvn_upsells_text">
                            <a href="<?php echo $href; ?>" title="<?php echo $upsell['title']; ?>" target="_blank">
                                <span class="devvn_upsells_name"><?php echo ($k == 0) ? '<strong>Bạn đang xem: </strong>':''?><?php echo $upsell['title']; ?></span> -
                                <strong class="devvn_upsells_price"><?php echo $upsell['pricehtml']; ?></strong>
                            </a>
                        </span>
                        </label>
                    <?php endforeach;?>
                </div>
                <div class="devn_upsells_tocart">
                    <p>Tổng tiền: <span class="total-price price"><?php echo wc_price($devvn_total);?></span></p>
                    <button class="devvn-add-combo" data-nonce="<?php echo wp_create_nonce('devvn_add_combo');?>">Thêm <span class="devvn_upsells_count"><?php echo $devvn_count?></span> sp vào giỏ hàng</button>
                    <div class="devvn_upsells_mess"></div>
                </div>
            </form>
        </div>
    <?php endif;
    wp_reset_postdata();
}

add_action( 'wp_ajax_devvn_add_combo', 'devvn_add_combo_func' );
add_action( 'wp_ajax_nopriv_devvn_add_combo', 'devvn_add_combo_func' );
function devvn_add_combo_func() {
    if ( !wp_verify_nonce( $_REQUEST['nonce'], "devvn_add_combo")) {
        exit("No naughty business please");
    }
    global $woocommerce;
    if(isset($_POST['dataproid'])) {
        $data = $_POST['dataproid'];
        if($data && is_array($data) && !empty($data)) {
            $count = 0;
            ob_start();
            foreach ($data as $k=>$v) {
                $proid = $v['proid'];
                $qty = $v['qty'];
                $availableid = $v['availableid'];
                if($woocommerce->cart->add_to_cart($proid, $qty, $availableid, null, null)){
                    $count++;
                }
            }
            ob_end_clean();
            wp_send_json_success($count);
        }
    }
    wp_send_json_error();
    die();
}

add_action('woocommerce_single_product_summary','devvn_custom_text_after_title', 6);
function devvn_custom_text_after_title(){
    global $product;
    $custom_text  = get_field('customtext_aftertitle', $product->get_id());
    if($custom_text){
        echo '<div class="item-bestseller">'.$custom_text.'</div>';
    }
}