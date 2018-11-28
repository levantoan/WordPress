<?php
/*
Add to functions.php in your theme
Then go to Woocommerce / Setting / Shipping / go to sub tab "Miễn phí vận chuyển"
Author: levantoan.com
*/
function devn_custom_ghtk_shipping_method_init() {
    if ( ! class_exists( 'DevVN_Custom_WC_GHTK_Shipping_Method' ) ) {
        class DevVN_Custom_WC_GHTK_Shipping_Method extends WC_Shipping_Method {
            public $ghtk_mess = '';
            public $_city = '';
            /**
             * Constructor for your shipping class
             *
             * @access public
             * @return void
             */
            public function __construct() {

                $this->id                 = 'devvn_custom_ghtk_shipping_method';
                $this->method_title       = __( 'Miễn phí vận chuyển' );
                $this->method_description = __( 'Tùy chọn miễn phí vận chuyển cho quận huyện' );

                $this->init();

                $this->enabled            = $this->settings['enabled'];
                $this->title              = $this->settings['title'];
                $this->_city              = $this->settings['_city'];

            }

            /**
             * Init your settings
             *
             * @access public
             * @return void
             */
            function init() {
                // Load the settings API
                $this->init_form_fields();
                $this->init_settings();

                // Save settings in admin if you have any defined
                add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
            }

            function init_form_fields() {
                $this->form_fields = array(
                    'enabled' => array(
                        'title'     => __( 'Kích hoạt', 'devvn-ghtk' ),
                        'type'      => 'checkbox',
                        'label'     => __( 'Kích hoạt miễn phí vận chuyển theo quận huyện', 'devvn-ghtk' ),
                        'default'   => 'yes',
                    ),
                    'title' => array(
                        'title' => __( 'Tiêu đề', 'devvn-ghtk' ),
                        'type' => 'text',
                        'description' => __( 'Mô tả cho phương thức vận chuyển', 'devvn-ghtk' ),
                        'default' => __( 'Miễn phí vận chuyển', 'devvn-ghtk' )
                    ),
                    '_city' => array(
                        'title' => __( 'Quận huyện', 'devvn-ghtk' ),
                        'type' => 'text',
                        'description' => __( 'Nhập ID của quận huyện cách nhau bằng dấu phẩy (,). Ví dụ 001,002,003<br> Xem mã quận huyện <a href="http://www.gso.gov.vn/dmhc2015/" target="_blank">tại đây</a>', 'devvn-ghtk' ),
                        'default' => __( '', 'devvn-ghtk' )
                    ),
                );
            } // End init_form_fields()

            /**
             * calculate_shipping function.
             *
             * @access public
             * @param mixed $package
             * @return void
             */
            public function calculate_shipping( $package = array() ) {
                $country = $package["destination"]["city"];
                $cart_subtotal = isset($package['cart_subtotal']) ? $package['cart_subtotal'] : '';
                $_city = explode(',',$this->_city);
                if(in_array($country, $_city) && $cart_subtotal >= 1000000) {
                    $this->add_rate(
                        array(
                            'label' => $this->title,
                            'cost' => 0,
                            'taxes' => false,
                            'package' => $package,
                        )
                    );
                }
            }

            function devvn_no_shipping_cart(){
                return $this->ghtk_mess;
            }
        }
    }
}
add_action( 'woocommerce_shipping_init', 'devn_custom_ghtk_shipping_method_init' );

function devvn_custom_add_your_shipping_method( $methods ) {
    $methods['devvn_custom_ghtk_shipping_method'] = 'DevVN_Custom_WC_GHTK_Shipping_Method';
    return $methods;
}
add_filter( 'woocommerce_shipping_methods', 'devvn_custom_add_your_shipping_method' );

function devvn_custom_hide_shipping_when_free_is_available( $rates ) {
    $free = array();
    foreach ( $rates as $rate_id => $rate ) {
        if ( 'devvn_custom_ghtk_shipping_method' === $rate->method_id ) {
            $free[ $rate_id ] = $rate;
            break;
        }
    }
    return ! empty( $free ) ? $free : $rates;
}
add_filter( 'woocommerce_package_rates', 'devvn_custom_hide_shipping_when_free_is_available', 100 );

add_filter('woocommerce_cart_shipping_method_full_label','devvn_woocommerce_cart_shipping_method_full_label', 10, 2);
function devvn_woocommerce_cart_shipping_method_full_label($label, $method){
    if($method->get_method_id() == 'devvn_custom_ghtk_shipping_method' ){
        return $method->get_label();
    }
    return $label;
}
