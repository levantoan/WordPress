<?php

add_action( 'woocommerce_cart_calculate_fees', 'phuphi_donggoi_func' );
function phuphi_donggoi_func($cart) {

    $product_list = array(76, 83);

    $weight_rule = array(
        '1-3' => '28000',
        '3-6' => '38000',
        '6-15' => '49000',
    );

    $weight = 0;
    $fee = 0;

    if( WC()->cart->get_cart_contents_count() > 1 ) {

        $items = WC()->cart->get_cart();
        foreach ($items as $item => $values) {
            $prod_id = $values['data']->get_id();
            if(in_array($prod_id, $product_list)) {
                $weight += (float)$values['data']->get_weight() * $values['quantity'];
            }
        }

        if($weight && $weight_rule){
            foreach ($weight_rule as $w=>$price){
                $w_arg = explode('-', $w);
                if($w_arg) {
                    $first = array_shift($w_arg);
                    $end = end($w_arg);
                    if(
                        ($first && $weight >= $first && $end && $weight <= $end)
                        || ($first && $weight == $first && !$end)
                    ){
                        $fee = $price;
                        break;
                    }
                }
            }
        }

        if ($fee) {
            WC()->cart->add_fee('Phí đóng gói', $fee);
        }
    }
}
