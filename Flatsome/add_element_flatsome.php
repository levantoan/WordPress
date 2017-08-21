<?php
/*
Plugin Name: OT Flatsome Ultimate Addons
Plugin URI: https://ninewp.com
Description: OT Flatsome Ultimate Addons
Version: 1.0.0
Author: thinhbg59
Text Domain: OT_FL_Ultimate_Addons
Domain Path: /languages
*/
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
define('OT_FL_Ultimate_Addons_DIR', plugin_dir_path(__FILE__));
define('OT_FL_Ultimate_Addons_URL', plugins_url('/', __FILE__));
class OT_FL_Ultimate_Addons
{
    /**
     * OT_FL_Ultimate_Addons constructor.
     */
    public function __construct()
    {
        add_action('ux_builder_setup', array($this, 'ux_builder_element')); // ux_builder_init @hook
        $this->includes();
    }
    public function includes()
    {
        include(OT_FL_Ultimate_Addons_DIR . 'includes/functions.php');
        include(OT_FL_Ultimate_Addons_DIR . 'includes/shortcodes.php');
    }
    public function ux_builder_element()
    {
        add_ux_builder_shortcode('ot_popup', array(
            'name'      => __('OT Popup'),
            'category'  => __('Content'),
            'template'  => 'ot_popup.html',
            'thumbnail' => 'ot_popup.png',
            'info'      => '{{ text }}',
            'inline'    => true,
            'wrap'      => false,
            'priority'  => 1,
            'options' => array(
                'text' => array(
                    'type'       => 'textfield',
                    'holder'     => 'button',
                    'heading'    => 'Text',
                    'param_name' => 'text',
                    'focus'      => 'true',
                    'value'      => 'Button',
                    'default'    => '',
                    'auto_focus' => true,
                ),
                'letter_case' => array(
                    'type'    => 'radio-buttons',
                    'heading' => 'Letter Case',
                    'default' => '',
                    'options' => array(
                        ''          => array('title' => 'ABC'),
                        'lowercase' => array('title' => 'Abc'),
                    ),
                ),
                'class' => array(
                    'type'       => 'textfield',
                    'heading'    => 'Class',
                    'param_name' => 'class',
                    'default'    => '',
                ),
            ),
        ));
    }
}
function ot_fl_ultimate_addons_run()
{
    new OT_FL_Ultimate_Addons();
}
add_action('after_setup_theme', 'ot_fl_ultimate_addons_run');
