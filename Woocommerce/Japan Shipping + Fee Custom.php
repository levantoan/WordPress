<?php
/*
* Add to functions.php
* Author: levantoan.com
*/
/*Phụ phí*/
add_action( 'woocommerce_cart_calculate_fees', 'phuphi_func' );
function phuphi_func($cart) {
    $state = $cart->get_customer()->get_shipping_state();

    if ( in_array($state, array('JP01', 'JP47')) ) {
        WC()->cart->add_fee( 'Phụ phí', 50 );
    }
}

/*Japan Shipping*/
function japan_shipping_method_init()
{
    class WC_Japan_Shipping_Method extends WC_Shipping_Method
    {

        public function __construct()
        {

            $this->id = 'japan_shipping_method';
            $this->method_title = __('Japan shipping');
            $this->method_description = __('Tính phí vận chuyển đến japan');

            $this->init();

            $this->enabled = $this->settings['enabled'];
            $this->title = $this->settings['title'];

        }

        function init()
        {
            // Load the settings API
            $this->init_form_fields();
            $this->init_settings();

            // Save settings in admin if you have any defined
            add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
        }

        function init_form_fields()
        {
            $this->form_fields = array(
                'enabled' => array(
                    'title' => __('Kích hoạt', 'devvn-japan'),
                    'type' => 'checkbox',
                    'label' => __('Kích hoạt tính phí vận chuyển bằng GHN', 'devvn-japan'),
                    'default' => 'yes',
                ),
                'title' => array(
                    'title' => __( 'Tiêu đề', 'devvn-japan' ),
                    'type' => 'text',
                    'description' => __( 'Mô tả cho phương thức vận chuyển', 'devvn-japan' ),
                    'default' => __( 'Shipping', 'devvn-japan' )
                ),
            );
        } // End init_form_fields()

        function convert_weight_to_gram( $weight ) {
            switch(get_option( 'woocommerce_weight_unit' )){
                case 'kg':
                    $weight = $weight * 1000;
                    break;
                case 'lbs':
                    $weight = $weight * 453.59237;
                    break;
                case 'oz':
                    $weight = $weight * 28.34952;
                    break;
            }
            return $weight; //return gram
        }

        function get_cart_contents_weight( $package = array() ) {
            $weight = 0;
            if(isset($package['contents']) && !empty($package['contents'])) {
                foreach ($package['contents'] as $cart_item_key => $values) {
                    $weight += (float)$values['data']->get_weight() * $values['quantity'];
                }
                $weight = $this->convert_weight_to_gram($weight);
            }
            return apply_filters( 'wc_devvn_cart_contents_weight', $weight );
        }

        public function calculate_shipping($package = array())
        {

            $payment_methob = WC()->session->get('chosen_payment_method');
            $cod_amout = isset($package['cart_subtotal']) ? (float) $package['cart_subtotal'] : 0;
            $state = isset($package['destination']['state']) ? $package['destination']['state'] : '';
            $weight = $this->get_cart_contents_weight($package);

            if($weight< 10000){
                $weight += 1000;
            }elseif ($weight >= 10000 && $weight < 25000){
                $weight += 2000;
            }else{
                $weight += 3000;
            }

            $rate = array(
                'id' => $this->id . "_" . $this->instance_id,
                'label' => $this->title,
                'cost' => 0,
                'taxes' => '',
                'calc_tax' => 'per_order'
            );

            if($cod_amout > 9900){
                if($weight <= 20000) {
                    $rate['label'] = 'Miễn phí vận chuyển';
                    $rate['cost'] = 0;
                }elseif ($weight > 20000 && $weight <= 30000) {
                    $rate['cost'] = 100;
                }elseif ($weight > 30000 && $weight <= 50000) {
                    $rate['cost'] = 200;
                }elseif ($weight > 50000) {
                    $rate['cost'] = 300;
                }
            }else{
                if($weight <= 3000) {
                    $rate['cost'] = 6.5;
                }elseif ($weight > 3000 && $weight <= 5000) {
                    $rate['cost'] = 8.5;
                }elseif ($weight > 5000 && $weight <= 10000) {
                    $rate['cost'] = 10.5;
                }elseif ($weight > 10000 && $weight <= 20000) {
                    $rate['cost'] = 13.5;
                }elseif ($weight > 20000 && $weight <= 30000) {
                    $rate['cost'] = 18.5;
                }elseif ($weight > 30000 && $weight <= 50000) {
                    $rate['cost'] = 22.5;
                }elseif ($weight > 50000) {
                    $rate['cost'] = 35;
                }
            }

            $this->add_rate($rate);

        }

    }
}
add_action( 'woocommerce_shipping_init', 'japan_shipping_method_init' );

function add_japan_shipping_method( $methods ) {
    $methods['japan_shipping_method'] = 'WC_Japan_Shipping_Method';
    return $methods;
}
add_filter( 'woocommerce_shipping_methods', 'add_japan_shipping_method' );
