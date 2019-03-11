<?php
add_action('woocommerce_before_add_to_cart_button', 'custom_data_hidden_fields');
function custom_data_hidden_fields() {
    echo '<div class="imput_fields custom-imput-fields">
        <label class="price_prod">student_fname: <br><input type="text" id="student_fname" name="student_fname" value="" /></label>
        <label class="price_prod">price: <br><input type="text" id="price_prod" name="price_prod" value="" /></label>
        <label class="quantity_prod">quantity: <br>
            <select name="quantity_prod" id="quantity_prod">
                <option value="1" selected="selected">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
        </label>
    </div><br>';
}
function wet_add_product_text_to_cart_item( $cart_item_data, $product_id, $variation_id ) {
    $student_fname = filter_input( INPUT_POST, 'student_fname');
    $student_lname = filter_input( INPUT_POST, 'student_lname');
    $price_prod = filter_input( INPUT_POST, 'price_prod');
    $cart_item_data['student-name'] = $student_fname . ' ' . $student_lname;
    $cart_item_data['gender'] =  $_SESSION['gender'];
    $cart_item_data['kid-size'] =  $_SESSION['belt_size_kidSize'];
    $cart_item_data['adult-size'] = $_SESSION['pa_belt_adult_size'];
    $cart_item_data['price_prod'] = $price_prod;
    //$cart_item_data['quantity'] = $_SESSION['quantity'];
    return $cart_item_data;
}
add_filter( 'woocommerce_add_cart_item_data', 'wet_add_product_text_to_cart_item', 10, 3 );

function wet_display_product_text_cart( $item_data, $cart_item ) {
    $item_data[] = array(
        'key'     => __( 'Student Name', 'wet' ),
        'value'   => wc_clean( $cart_item['student-name'] ),
        'display' => '',
    );
    $item_data[] = array(
        'key'     => __( 'Gender', 'wet' ),
        'value'   => wc_clean( $cart_item['gender'] ),
        'display' => '',
    );
    $item_data[] = array(
        'key'     => __( 'Kid Size', 'wet' ),
        'value'   => wc_clean( $cart_item['kid-size'] ),
        'display' => '',
    );
    $item_data[] = array(
        'key'     => __( 'Adult Size', 'wet' ),
        'value'   => wc_clean( $cart_item['adult-size'] ),
        'display' => '',
    );
    return $item_data;
}
add_filter( 'woocommerce_get_item_data', 'wet_display_product_text_cart', 10, 2 );

function wet_add_product_text_to_order_items( $item, $cart_item_key, $values, $order ) {

    $item->add_meta_data( __( 'Student Name', 'wet' ), $values['student-name'] );
    $item->add_meta_data( __( 'Gender', 'wet' ), $values['gender'] );
    $item->add_meta_data( __( 'Kid Size', 'wet' ), $values['kid-size'] );
    $item->add_meta_data( __( 'Adult Size', 'wet' ), $values['adult-size'] );
}
add_action( 'woocommerce_checkout_create_order_line_item', 'wet_add_product_text_to_order_items', 10, 4 );

add_action( 'woocommerce_before_calculate_totals', 'add_custom_price', 9999999, 1);
function add_custom_price( $cart_obj ) {

    // This is necessary for WC 3.0+
    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;

    // Avoiding hook repetition (when using price calculations for example)
    if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
        return;

    // Loop through cart items
    foreach ( $cart_obj->get_cart() as $cart_item ) {
        //print_r($cart_item);
        $cart_item['data']->set_price( 40 );
    }
}

add_action( 'woocommerce_before_mini_cart',  'tm_recalculate_total'  );

function tm_recalculate_total(){
    WC()->cart->calculate_totals();
}
