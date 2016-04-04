<?php
/*
Plugin Name: WP Front End Profile By DevVN
Plugin URI: http://devvn.com
Description: This plugin allows users to easily edit their profile information on the front end rather than having to go into the dashboard to make changes to password, email address and other user meta data.
Version:     0.1
Author:      Le Van Toan
Author URI:  http://levantoan.com
Text Domain: devvn
License:     GPL v1 or later
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

define( pluginVersion ,'0.1');

require_once dirname( __FILE__ ) . '/functions/scripts.php';
require_once dirname( __FILE__ ) . '/functions/login.php';
require_once dirname( __FILE__ ) . '/functions/func.php';

