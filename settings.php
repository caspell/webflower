<?php

require_once WEBFLOWER_PLUGIN_DIR . '/includes/functions.php';
require_once WEBFLOWER_PLUGIN_DIR . '/includes/shortcodes.php';
require_once WEBFLOWER_PLUGIN_DIR . '/includes/webflower-template.php';
require_once WEBFLOWER_PLUGIN_DIR . '/includes/webflower-form.php';
require_once WEBFLOWER_PLUGIN_DIR . '/includes/capabilities.php';
require_once WEBFLOWER_PLUGIN_DIR . '/includes/ajax/actions.php';

if ( is_admin() ) {
	require_once WEBFLOWER_PLUGIN_DIR . '/admin/admin.php';
} else {
	require_once WEBFLOWER_PLUGIN_DIR . '/includes/controller.php';
}

add_action( 'plugins_loaded', 'webflower' );
add_action( 'init', 'webflower_init' );
add_action( 'admin_init', 'webflower_upgrade' );

function webflower_init() {

	wp_enqueue_style( 'web-flowform-front',
		webflower_plugin_url( 'includes/css/styles.css' ),
		array(),
		WEBFLOWER_VERSION, 'all' );

	wp_enqueue_script( 'web-flowform-front',
		webflower_plugin_url( 'includes/js/scripts.js' ),
		array( 'jquery', 'jquery-ui-tabs' ),
		WEBFLOWER_VERSION, true );
// 'boostrap'
	// wp_enqueue_style( 'web-flowform-front-bootstrap',
	// 	webflower_plugin_url( 'includes/bootstrap/css/bootstrap.min.css' ),
	// 	array( ),
	// 	WEBFLOWER_VERSION, 'all' );

	wp_enqueue_style( 'web-flowform-front-bootstrap',
		webflower_plugin_url( 'includes/bootstrap/css/bootstrap.min.css' ),
		array( ),
		WEBFLOWER_VERSION, 'all' );


	// wp_enqueue_script( 'web-flowform-bootstrap',
	// 	webflower_plugin_url( 'includes/bootstrap/js/popper.js' ),
	// 	array(  ),
	// 	WEBFLOWER_VERSION, true );
	//
	// wp_enqueue_script( 'web-flowform-bootstrap',
	// 	webflower_plugin_url( 'includes/bootstrap/js/bootstrap.min.js' ),
	// 	array(  ),
	// 	WEBFLOWER_VERSION, true );

}

function webflower_upgrade(){

}

function webflower_install(){

}

function webflower() {

	/* Shortcodes */
	add_shortcode( 'webflower', 'webflower_func' );

	add_shortcode( 'webflower-answer', 'webflower_answer_func' );

}


?>
