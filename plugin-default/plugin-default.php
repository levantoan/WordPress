<?php
/*
 * Plugin Name: Plugin Default
 * Plugin URI: http://levantoan.com
 * Version: 1.0
 * Description: This is description
 * Author: Le Van Toan
 * Author URI: http://levantoan.com
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

register_activation_hook(   __FILE__, array( 'Default_Plugin_Class', 'on_activation' ) );
register_deactivation_hook( __FILE__, array( 'Default_Plugin_Class', 'on_deactivation' ) );
register_uninstall_hook(    __FILE__, array( 'Default_Plugin_Class', 'on_uninstall' ) );

add_action( 'plugins_loaded', array( 'Default_Plugin_Class', 'init' ) );
class Default_Plugin_Class
{
    protected static $instance;
    
	public $_version = '1.0';

    public static function init(){
        is_null( self::$instance ) AND self::$instance = new self;
        return self::$instance;
    }
    
	public function __construct(){
    	add_action( 'wp_enqueue_scripts', array($this, 'load_plugins_scripts') );
        # INIT the plugin: Hook your callbacks
    }

    public static function on_activation(){
        if ( ! current_user_can( 'activate_plugins' ) )
            return;
        $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
        check_admin_referer( "activate-plugin_{$plugin}" );

        # Uncomment the following line to see the function in action 
        //add_option( 'test','yes' );       
    }

    public static function on_deactivation(){
        if ( ! current_user_can( 'activate_plugins' ) )
            return;
        $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
        check_admin_referer( "deactivate-plugin_{$plugin}" );

        # Uncomment the following line to see the function in action
        //delete_option( 'test' );
    }

    public static function on_uninstall(){
        if ( ! current_user_can( 'activate_plugins' ) )
            return;

        # Uncomment the following line to see the function in action
        //delete_option( 'test' );
    }
	function load_plugins_scripts(){
		wp_enqueue_style( 'Default_Plugin-style', plugins_url('/Default_Plugin.js',__FILE__), array(), $this->_version, 'all');
		wp_enqueue_script( 'Default_Plugin-script', plugins_url('/Default_Plugin.js',__FILE__), array('jquery'), $this->_version, true );	    	
	    $array = array(
	    	'ajaxurl'	=>	admin_url('admin-ajax.php')
	    );
	    wp_localize_script('Default_Plugin-script', 'Default_Plugin_array', $array);
	}
}