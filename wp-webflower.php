<?php
/*
Plugin Name: webflower
Plugin URI: https://outusual.com/
Description: ---
Author: caspell84
Author URI: http://outusual.com/
Text Domain: webflower
Domain Path: /
Version: 1.0
*/

define( 'WEBFLOWER_VERSION', '1.0' );

define( 'WEBFLOWER_REQUIRED_WP_VERSION', '4.7' );

define( 'WEBFLOWER_PLUGIN', __FILE__ );

define( 'WEBFLOWER_PLUGIN_BASENAME', plugin_basename( WEBFLOWER_PLUGIN ) );

define( 'WEBFLOWER_PLUGIN_NAME', trim( dirname( WEBFLOWER_PLUGIN_BASENAME ), '/' ) );

define( 'WEBFLOWER_PLUGIN_DIR', untrailingslashit( dirname( WEBFLOWER_PLUGIN ) ) );

define( 'WEBFLOWER_PLUGIN_MODULES_DIR', WEBFLOWER_PLUGIN_DIR . '/modules' );


if ( ! defined( 'WEBFLOWER_LOAD_JS' ) ) {
	define( 'WEBFLOWER_LOAD_JS', true );
}

if ( ! defined( 'WEBFLOWER_LOAD_CSS' ) ) {
	define( 'WEBFLOWER_LOAD_CSS', true );
}

if ( ! defined( 'WEBFLOWER_AUTOP' ) ) {
	define( 'WEBFLOWER_AUTOP', true );
}

if ( ! defined( 'WEBFLOWER_USE_PIPE' ) ) {
	define( 'WEBFLOWER_USE_PIPE', true );
}

if ( ! defined( 'WEBFLOWER_ADMIN_READ_CAPABILITY' ) ) {
	define( 'WEBFLOWER_ADMIN_READ_CAPABILITY', 'edit_posts' );
}

if ( ! defined( 'WEBFLOWER_ADMIN_READ_WRITE_CAPABILITY' ) ) {
	define( 'WEBFLOWER_ADMIN_READ_WRITE_CAPABILITY', 'publish_pages' );
}

if ( ! defined( 'WEBFLOWER_VERIFY_NONCE' ) ) {
	define( 'WEBFLOWER_VERIFY_NONCE', false );
}

if ( ! defined( 'WEBFLOWER_USE_REALLY_SIMPLE_CAPTCHA' ) ) {
	define( 'WEBFLOWER_USE_REALLY_SIMPLE_CAPTCHA', false );
}

if ( ! defined( 'WEBFLOWER_VALIDATE_CONFIGURATION' ) ) {
	define( 'WEBFLOWER_VALIDATE_CONFIGURATION', true );
}

// Deprecated, not used in the plugin core. Use WEBFLOWER_plugin_url() instead.
define( 'WEBFLOWER_PLUGIN_URL', untrailingslashit( plugins_url( '', WEBFLOWER_PLUGIN ) ) );

require_once WEBFLOWER_PLUGIN_DIR . '/settings.php';


?>
