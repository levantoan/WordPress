<?php
/**
 * Plugin Name: DevVN Shipping Discount
 * Plugin URI: http://www.wordpress.org
 * Description: Woocmmerce settings tab.
 * Author: Le Van Toan
 * Author URI: http://levantoan.com
 * Text Domain: devvn-shippingdiscount
 * Domain Path: /languages
 * WC requires at least: 3.0.0
 * WC tested up to: 3.4.2
 *
 */

class DevVN_Shipping_Discount_Class {


    protected $id = 'shipping_discount_tab';

    public function __construct() {
        add_filter( 'woocommerce_settings_tabs_array', array($this, 'add_settings_tab'), 50 );
        add_action( 'woocommerce_settings_tabs_' . $this->id , array($this, 'settings_tab') );
        add_action( 'woocommerce_update_options_' . $this->id, array($this, 'update_settings') );
        add_action( 'woocommerce_sections_' . $this->id, array($this, 'output_sections') );
    }

    public function add_settings_tab( $settings_tabs ) {
        $settings_tabs['shipping_discount_tab'] = __( 'Shipping Discount', 'devvn-shippingdiscount' );
        return $settings_tabs;
    }

    public function settings_tab() {
        woocommerce_admin_fields( self::get_settings() );
    }

    public function update_settings() {
        woocommerce_update_options( self::get_settings() );
    }

    public function get_sections() {
        $sections = array(
            '' => __( 'Test Link 1', 'devvn-shippingdiscount' ),
            'testlink2' => __( 'Test Link 2', 'devvn-shippingdiscount' ),
        );

        return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
    }

    public function output_sections() {
        global $current_section;

        $sections = $this->get_sections();

        if ( empty( $sections ) || 1 === sizeof( $sections ) ) {
            return;
        }

        echo '<ul class="subsubsub">';

        $array_keys = array_keys( $sections );

        foreach ( $sections as $id => $label ) {
            echo '<li><a href="' . admin_url( 'admin.php?page=wc-settings&tab=' . $this->id . '&section=' . sanitize_title( $id ) ) . '" class="' . ( $current_section == $id ? 'current' : '' ) . '">' . $label . '</a> ' . ( end( $array_keys ) == $id ? '' : '|' ) . ' </li>';
        }

        echo '</ul><br class="clear" />';
    }

    public function get_settings() {

        global $current_section;
        if('' == $current_section) {
            $settings = array(
                'section_title' => array(
                    'name' => __('Section Title', 'woocommerce-settings-tab-demo'),
                    'type' => 'title',
                    'desc' => '',
                    'id' => 'wc_' . $this->id . '_section_title'
                ),
                'title' => array(
                    'name' => __('Title', 'woocommerce-settings-tab-demo'),
                    'type' => 'text',
                    'desc' => __('This is some helper text', 'woocommerce-settings-tab-demo'),
                    'id' => 'wc_' . $this->id . '_title'
                ),
                'description' => array(
                    'name' => __('Description', 'woocommerce-settings-tab-demo'),
                    'type' => 'textarea',
                    'desc' => __('This is a paragraph describing the setting. Lorem ipsum yadda yadda yadda. Lorem ipsum yadda yadda yadda. Lorem ipsum yadda yadda yadda. Lorem ipsum yadda yadda yadda.', 'woocommerce-settings-tab-demo'),
                    'id' => 'wc_' . $this->id . '_description'
                ),
                'section_end' => array(
                    'type' => 'sectionend',
                    'id' => 'wc_' . $this->id . '_section_end'
                )
            );
        }elseif('testlink2' == $current_section){
            $settings = array(
                'section_title' => array(
                    'name' => __('Section Title', 'woocommerce-settings-tab-demo'),
                    'type' => 'title',
                    'desc' => '',
                    'id' => 'wc_' . $this->id . '_section_title'
                ),
                'title' => array(
                    'name' => __('Title', 'woocommerce-settings-tab-demo'),
                    'type' => 'text',
                    'desc' => __('This is some helper text', 'woocommerce-settings-tab-demo'),
                    'id' => 'wc_' . $this->id . '_title'
                ),
                'section_end' => array(
                    'type' => 'sectionend',
                    'id' => 'wc_' . $this->id . '_section_end'
                )
            );
        }

        return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings, $current_section );
    }

}

new DevVN_Shipping_Discount_Class;
