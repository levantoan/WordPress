<?php
/*
* sắp xếp local_pickup (nhận tại cửa hàng) xuống cuối cùng của các hình thức vận chuyển
* sort local_pickup to the bottom of the shipping method
* Author: levantoan.com
*/

add_filter('woocommerce_package_rates', 'custom_shipping_methods_order', 100);
function custom_shipping_methods_order($rates) {
    $local_pickup_id = array();
    foreach ( $rates as $rate_id => $rate ) {
        if($rate->get_method_id() == 'local_pickup'){
            $local_pickup_id[] = $rate_id;
        }
    }
    if ($local_pickup_id) {
        foreach ($local_pickup_id as $item){
            $local_pickup = $rates[$item];
            unset($rates[$item]);
            $rates[$item] = $local_pickup;
        }
    }
    return $rates;
}
