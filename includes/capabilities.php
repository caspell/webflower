<?php

add_filter( 'map_meta_cap', 'webflower_map_meta_cap', 10, 4 );

function webflower_map_meta_cap( $caps, $cap, $user_id, $args ) {
	$meta_caps = array(
		'webflower_edit_webflow' => WEBFLOWER_ADMIN_READ_WRITE_CAPABILITY,
		'webflower_edit_webflow' => WEBFLOWER_ADMIN_READ_WRITE_CAPABILITY,
		'webflower_read_webflow' => WEBFLOWER_ADMIN_READ_CAPABILITY,
		'webflower_delete_webflow' => WEBFLOWER_ADMIN_READ_WRITE_CAPABILITY,
		'webflower_manage_integration' => 'manage_options',
		'webflower_submit' => 'read',
	);

	$meta_caps = apply_filters( 'webflower_map_meta_cap', $meta_caps );

	$caps = array_diff( $caps, array_keys( $meta_caps ) );

	if ( isset( $meta_caps[$cap] ) ) {
		$caps[] = $meta_caps[$cap];
	}

	return $caps;
}
