<?php
/**
 * Main file.
 * @wordpress-plugin
 * Plugin Name:       AddWebSoution - Resource list with filter 
 * Plugin URI:        https://jahnav-vaghela.github.io/
 * Description:       Recource listing plugin AddWebSoluton prectical task 14th May 2023. This plugin show Resource custom post with custom texonomys. Furthermor it provde filter on archive page.
 * Version:           1.0.0
 * Author:            Jahnav Vaghela
 * Author URI:        https://jahnav-vaghela.github.io/
 * Text Domain:       add-web
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'ADDWEB_PATH' ) ) {
	define( 'ADDWEB_PATH', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'ADDWEB_URL' ) ) {
	define( 'ADDWEB_URL', plugins_url( '/', __FILE__ ) );
}
if ( ! defined( 'ADDWEB_BASENAME' ) ) {
	define( 'ADDWEB_BASENAME', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'ADDWEB_FILEPATH' ) ) {
	define( 'ADDWEB_FILEPATH', __FILE__ );
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/
require_once( ADDWEB_PATH.'includes/class-CPT-tax.php' );
$addweb_cpt = ADDWEB_CPT::getInstance();
require_once( ADDWEB_PATH.'includes/class-Resource-Archive-Filter.php' );
$res_arch_filter = Resource_Archive_Filter::getInstance();
