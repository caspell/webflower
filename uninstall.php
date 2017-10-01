<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}



// function webflower_delete_plugin() {
// 	global $wpdb;
//
// 	delete_option( 'webflower' );
//
// 	$posts = get_posts(
// 		array(
// 			'numberposts' => -1,
// 			'post_type' => 'webflower',
// 			'post_status' => 'any',
// 		)
// 	);
//
// 	foreach ( $posts as $post ) {
// 		wp_delete_post( $post->ID, true );
// 	}
//
// }
//
// webflower_delete_plugin();
