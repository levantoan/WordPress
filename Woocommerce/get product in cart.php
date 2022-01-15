<?php
foreach ( WC()->cart->get_cart() as $cart_item ) {
    $product = $cart_item['data'];
    if(in_array($product->get_id(), array('12345'))){
        return;
    }
}
