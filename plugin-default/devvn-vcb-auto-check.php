<?php
/*
Plugin Name: VCB Auto Check
Plugin URI: https://levantoan.com
Description: Plugin tự động check số dư của Vietcombank (VCB) và chuyển trạng thái order sang đã hoàn thành nếu nội dung chuyển khoản đúng với cú pháp cho trước.
Version: 1.0.0
Author: Lê Văn Toản
Author URI: https://levantoan.com
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!class_exists('DevVN_VCB_Auto_Check'))
{
    class DevVN_VCB_Auto_Check
    {
        protected static $instance;
        public $_version = '1.0.0';
        public $_optionName = 'tragop_options';
        public $_optionGroup = 'tragop-options-group';
        public $_defaultOptions = array(
            'luu_y' =>	'',
        );

        public static function init()
        {
            is_null(self::$instance) AND self::$instance = new self;
            return self::$instance;
        }
        public function __construct()
        {
            $this->define_constants();
            global $vcb_settings;
            $vcb_settings  = $this->get_options();

            add_action( 'plugins_loaded', array($this,'load_textdomain') );

            add_filter( 'plugin_action_links_' . DEVVN_VCB_BASENAME, array( $this, 'add_action_links' ), 10, 2 );

            add_action( 'admin_menu', array( $this, 'admin_menu' ) );
            add_action( 'admin_init', array( $this, 'register_mysettings') );
        }
        public function define_constants() {
            if ( !defined( 'DEVVN_VCB_VERSION_NUM' ) )
                define( 'DEVVN_VCB_VERSION_NUM', $this->_version );
            if ( !defined( 'DEVVN_VCB_URL' ) )
                define( 'DEVVN_VCB_URL', plugin_dir_url( __FILE__ ) );
            if ( !defined( 'DEVVN_VCB_BASENAME' ) )
                define( 'DEVVN_VCB_BASENAME', plugin_basename( __FILE__ ) );
            if ( !defined( 'DEVVN_VCB_PLUGIN_DIR' ) )
                define( 'DEVVN_VCB_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
        }
        public static function activate()
        {
            
        }
        public static function deactivate()
        {
        }
        function load_textdomain() {
            load_textdomain('devvn-textdefault', dirname(__FILE__) . '/languages/devvn-textdefault-' . get_locale() . '.mo');
        }
        public function add_action_links( $links, $file ) {
            if ( strpos( $file, 'devvn-vcb-auto-check.php' ) !== false ) {
                $settings_link = '<a href="' . admin_url( 'options-general.php?page=setting-default' ) . '" title="'.__('View Settings','devvn-textdefault').'">' . __( 'Settings', 'devvn-textdefault' ) . '</a>';
                array_unshift( $links, $settings_link );
            }
            return $links;
        }
        function admin_menu() {
            add_options_page(
                __('Setting Default','devvn-textdefault'),
                __('Setting Default','devvn-textdefault'),
                'manage_options',
                'setting-default',
                array(
                    $this,
                    'devvn_settings_page'
                )
            );
        }
        function get_options(){
            return wp_parse_args(get_option($this->_optionName),$this->_defaultOptions);
        }
        function register_mysettings() {
            register_setting( $this->_optionGroup, $this->_optionName );
        }
        function  devvn_settings_page() {
            global $vcb_settings;
            ?>
            <div class="wrap">
                <h1>Plugins Setting Default</h1>
                <form method="post" action="options.php" novalidate="novalidate">
                    <?php settings_fields( $this->_optionGroup );?>
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <th scope="row"><label for="luu_y">Nội dung Lưu ý</label></th>
                            <td>
                                <?php
                                $settings = array(
                                    'textarea_name' => $this->_optionName.'[luu_y]',
                                );
                                wp_editor( $vcb_settings['luu_y'], 'luu_y', $settings );?>
                            </td>
                        </tr>
                        <?php do_settings_fields($this->_optionGroup, 'default'); ?>
                        </tbody>
                    </table>
                    <?php do_settings_sections($this->_optionGroup, 'default'); ?>
                    <?php submit_button();?>
                </form>
            </div>
            <?php
        }
    }
}
if(class_exists('DevVN_VCB_Auto_Check'))
{
    register_activation_hook(__FILE__, array('DevVN_VCB_Auto_Check', 'activate'));
    register_deactivation_hook(__FILE__, array('DevVN_VCB_Auto_Check', 'deactivate'));
    $vcb_auto_check = new DevVN_VCB_Auto_Check();
}