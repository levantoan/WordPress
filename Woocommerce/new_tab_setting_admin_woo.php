<?php
/**
 * Plugin Name: WooCommerce Settings Tab
 * Plugin URI: http://www.wordpress.org
 * Description: Woocmmerce settings tab.
 * Author: Author Name
 * Author URI: http://www.wordpress.org
 * Version: 1.0
 *
 */

class DevVN_Discount_Shipping_Class {
    public static function init() {
        add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
        add_action( 'woocommerce_settings_tabs_settings_tab_demo', __CLASS__ . '::settings_tab' );
        add_action( 'woocommerce_update_options_settings_tab_demo', __CLASS__ . '::update_settings' );
        add_action( 'woocommerce_sections',__CLASS__ . '::get_sections');
    }

    public static function add_settings_tab( $settings_tabs ) {
        $settings_tabs['settings_tab_demo'] = __( 'Settings Demo Tab', 'woocommerce-settings-tab-demo' );
        return $settings_tabs;
    }

    public static function settings_tab() {
        woocommerce_admin_fields( self::get_settings() );
    }

    public static function update_settings() {
        woocommerce_update_options( self::get_settings() );
    }

    public function get_sections() {
        $sections = array(
            '' => __( 'Test Link 1', 'woocommerce' ),
            'testlink2' => __( 'Test Link 2', 'woocommerce' ),
        );

        return apply_filters( 'woocommerce_sections', $sections );
    }

    public static function get_settings() {

        $settings = array(
            'section_title' => array(
                'name'     => __( 'Section Title', 'woocommerce-settings-tab-demo' ),
                'type'     => 'title',
                'desc'     => '',
                'id'       => 'wc_settings_tab_demo_section_title'
            ),
            'title' => array(
                'name' => __( 'Title', 'woocommerce-settings-tab-demo' ),
                'type' => 'text',
                'desc' => __( 'This is some helper text', 'woocommerce-settings-tab-demo' ),
                'id'   => 'wc_settings_tab_demo_title'
            ),
            'description' => array(
                'name' => __( 'Description', 'woocommerce-settings-tab-demo' ),
                'type' => 'textarea',
                'desc' => __( 'This is a paragraph describing the setting. Lorem ipsum yadda yadda yadda. Lorem ipsum yadda yadda yadda. Lorem ipsum yadda yadda yadda. Lorem ipsum yadda yadda yadda.', 'woocommerce-settings-tab-demo' ),
                'id'   => 'wc_settings_tab_demo_description'
            ),
            'section_end' => array(
                'type' => 'sectionend',
                'id' => 'wc_settings_tab_demo_section_end'
            )
        );

        return apply_filters( 'wc_settings_tab_demo_settings', $settings );
    }

}

DevVN_Discount_Shipping_Class::init();
