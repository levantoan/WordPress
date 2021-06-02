<?php
/*Style*/
/*

.single-product .devvn-flashsale{
    background-image: url(https://dienlanhsapho.com/wp-content/uploads/2021/06/bg-flashsale.jpg);
    margin-top: 20px;
}
.single-product .devvn-flashsale{
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: #fff;
    padding: 10px;
    border-radius: 4px;
}
.single-product .devvn-flashsale span.title {
    margin-top: 10px;
    margin-right: 25px;
}
 
.single-product .devvn-flashsale .ux-timer {
    margin: 0;
 
}
.single-product .devvn-flashsale .ux-timer span {
    font-size: 15px;
}
.single-product .devvn-flashsale .ux-timer span strong{
    font-size: 10px;
    font-weight: 400;
    text-transform: none;
    margin-top: 2px;    
}
 
.single-product .devvn--flashsale .ux-timer span  strong{color: #222}
*/

add_action('woocommerce_single_product_summary', 'add_flashsale_coundown_flatsome', 8);
function add_flashsale_coundown_flatsome(){
    global $product;
    $sale_price_dates_to    = ($date = get_post_meta($product->get_id(), '_sale_price_dates_to', true)) ? date_i18n('Y-m-d', $date) : '';
    $today = strtotime(date_i18n("Y/m/d"));
    $strsale_price_dates_to = strtotime($sale_price_dates_to);
    if ($sale_price_dates_to && $strsale_price_dates_to >= $today) {
        $date = DateTime::createFromFormat("Y-m-d", $sale_price_dates_to);
        $year_sale = $date->format('Y');
        $month_sale = $date->format('m');
        if ($date->format('d') < 31) {

            $day_sale = $date->format('d') + 1;
        } else {
            $day_sale = $date->format('d');
        }
        echo '<div class="devvn-flashsale">';
        echo '<span class="title"><svg height="21" width="108" class="flash-sale-logo flash-sale-logo--white"><g fill="currentColor" fill-rule="evenodd"><path d="M0 16.195h3.402v-5.233h4.237V8H3.402V5.037h5.112V2.075H0zm29.784 0l-.855-2.962h-4.335l-.836 2.962H20.26l4.723-14.12h3.576l4.724 14.12zM26.791 5.294h-.04s-.31 1.54-.563 2.43l-.797 2.744h2.74l-.777-2.745c-.252-.889-.563-2.43-.563-2.43zm7.017 9.124s1.807 2.014 5.073 2.014c3.13 0 4.898-2.034 4.898-4.384 0-4.463-6.259-4.147-6.259-5.925 0-.79.778-1.106 1.477-1.106 1.672 0 3.071 1.245 3.071 1.245l1.439-2.824s-1.477-1.6-4.47-1.6c-2.76 0-4.918 1.718-4.918 4.325 0 4.345 6.258 4.285 6.258 5.964 0 .85-.758 1.126-1.457 1.126-1.75 0-3.324-1.462-3.324-1.462zm12.303 1.777h3.402v-5.53h5.054v5.53h3.401V2.075h-3.401v5.648h-5.054V2.075h-3.402zm18.64-1.678s1.692 1.915 4.763 1.915c2.877 0 4.548-1.876 4.548-4.107 0-4.483-6.492-3.871-6.492-6.36 0-.987.914-1.678 2.08-1.678 1.73 0 3.052 1.224 3.052 1.224l1.088-2.073s-1.4-1.501-4.12-1.501c-2.644 0-4.627 1.738-4.627 4.068 0 4.305 6.512 3.87 6.512 6.379 0 1.145-.952 1.698-2.002 1.698-1.944 0-3.44-1.48-3.44-1.48zm19.846 1.678l-1.166-3.594h-4.84l-1.166 3.594H74.84L79.7 2.174h2.623l4.86 14.021zM81.04 4.603h-.039s-.31 1.382-.583 2.172l-1.224 3.752h3.615l-1.224-3.752c-.253-.79-.545-2.172-.545-2.172zm7.911 11.592h8.475v-2.192H91.46V2.173H88.95zm10.477 0H108v-2.192h-6.064v-3.772h4.645V8.04h-4.645V4.366h5.753V2.174h-8.26zM14.255.808l6.142.163-3.391 5.698 3.87 1.086-8.028 12.437.642-8.42-3.613-1.025z"></path></g></svg></span>';
        echo do_shortcode('[ux_countdown year="' . $year_sale . '" month="' . $month_sale . '" day="' . $day_sale . '" time="00:00" t_week="Tuần" t_day="Ngày" t_hour="Giờ" t_min="Phút" t_sec="Giây" t_week_p="Tuần" t_day_p="Ngày" t_hour_p="Giờ" t_min_p="Phút" t_sec_p="Giây"]');
        echo '</div>';
    }
}
