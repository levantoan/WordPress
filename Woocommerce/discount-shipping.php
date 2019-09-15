<?php
/*
 * Discount shipping
 */
add_filter( 'woocommerce_package_rates', 'devvn_discount_shipping', 1000, 2 );
add_action('woocommerce_after_shipping_rate', 'devvn_woocommerce_after_shipping_rate', 10, 2);

function devvn_discount_shipping( $rates, $package ) {

    $country = isset($package['destination']['country']) ? $package['destination']['country'] : '';
    $state = isset($package['destination']['state']) ? $package['destination']['state'] : '';
    $district = isset($package['destination']['city']) ? $package['destination']['city'] : '';

    $sub_total = WC()->cart->get_subtotal();

    foreach ($rates as $rate) {

        if ( 'free_shipping' === $rate->method_id )  continue;

        if($sub_total >= 300000) {

            $shipping_amount = $rate->cost;
            $max_discount = 40000;
			$percent = 50; //percent
			$discount = ($shipping_amount * $percent)/100;
			if($discount >= $max_discount)  $discount = $max_discount;

			$rate->cost = $shipping_amount - $discount;
			$discount_amout = $discount;            

            $rate->add_meta_data('devvn_discount_amount_percent', $discount_amout);
        }
    }

    return $rates;
}


function devvn_woocommerce_after_shipping_rate($method, $index){
    $meta_data = $method->get_meta_data();
    if(isset($meta_data['devvn_discount_amount_percent']) && $meta_data['devvn_discount_amount_percent']) {
        if($meta_data['devvn_discount_amount_percent'] == -1){
            ?>
            <p class="devvn_discount_amount"><?php _e('Hỗ trợ hoàn toàn phí ship', 'devvn-shippingdiscount');?></p>
            <?php
        }else {
            ?>
            <p class="devvn_discount_amount"><?php printf(__('Đã giảm %s tiền ship', 'devvn-shippingdiscount'), wc_price($meta_data['devvn_discount_amount_percent'])); ?></p>
            <?php
        }
    }
}