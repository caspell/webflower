<?php

function webflower_array_flatten( $input ) {
	if ( ! is_array( $input ) ) {
		return array( $input );
	}

	$output = array();

	foreach ( $input as $value ) {
		$output = array_merge( $output, webflower_array_flatten( $value ) );
	}

	return $output;
}

function webflower_plugin_path( $path = '' ) {
	return path_join( WEBFLOWER_PLUGIN_DIR, trim( $path, '/' ) );
}

function webflower_plugin_url( $path = '' ) {
	$url = plugins_url( $path, WEBFLOWER_PLUGIN );

	if ( is_ssl() && 'http:' == substr( $url, 0, 5 ) ) {
		$url = 'https:' . substr( $url, 5 );
	}

	return $url;
}

function webflower_verify_nonce( $nonce, $action = 'wp_rest' ) {
	return wp_verify_nonce( $nonce, $action );
}

function webflower_create_nonce( $action = 'wp_rest' ) {
	return wp_create_nonce( $action );
}

function webflower_get_current_contact_form() {
	if ( $current = WebFlower_Form::get_current() ) {
		return $current;
	}
}


function webflower_format_atts( $atts ) {
	$html = '';

	$prioritized_atts = array( 'type', 'name', 'value' );

	foreach ( $prioritized_atts as $att ) {
		if ( isset( $atts[$att] ) ) {
			$value = trim( $atts[$att] );
			$html .= sprintf( ' %s="%s"', $att, esc_attr( $value ) );
			unset( $atts[$att] );
		}
	}

	foreach ( $atts as $key => $value ) {
		$key = strtolower( trim( $key ) );

		if ( ! preg_match( '/^[a-z_:][a-z_:.0-9-]*$/', $key ) ) {
			continue;
		}

		$value = trim( $value );

		if ( '' !== $value ) {
			$html .= sprintf( ' %s="%s"', $key, esc_attr( $value ) );
		}
	}

	$html = trim( $html );

	return $html;
}


function _normalize_newline( $text, $to = "\n" ) {
	if ( ! is_string( $text ) ) {
		return $text;
	}

	$nls = array( "\r\n", "\r", "\n" );

	if ( ! in_array( $to, $nls ) ) {
		return $text;
	}

	return str_replace( $nls, $to, $text );
}

function _normalize_newline_deep( $arr, $to = "\n" ) {
	if ( is_array( $arr ) ) {
		$result = array();

		foreach ( $arr as $key => $text ) {
			$result[$key] = _normalize_newline_deep( $text, $to );
		}

		return $result;
	}

	return _normalize_newline( $arr, $to );
}



function webflower_get_current_form() {
	if ( $current = WebFlower_Form::get_current() ) {
		return $current;
	}
}


?>
