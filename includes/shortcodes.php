<?php

function webflower_func( $atts, $content = null, $code = '' ) {

    $atts = shortcode_atts(
        array(
            'id' => 0,
            'title' => '',
            'html_id' => '',
            'html_name' => '',
            'html_class' => '',
            'output' => 'form',
        ),
        $atts, 'webflower'
    );

    $id = (int) $atts['id'];

    $flower = WebFlower_Form::get_instance( $id );

	if ( ! $flower ) {
		return '[webflower 404 "Not Found"]';
	}

	// return $flower->form_html( $atts );
    return $flower->form_html();
}

function webflower_answer_func( $atts, $content = null ) {

    $atts = shortcode_atts(
        array(
            'id' => 0,
            'title' => '',
            'html_id' => '',
            'html_name' => '',
            'html_class' => '',
            'output' => 'form',
        ),
        $atts, 'webflower'
    );

    $id = (int) $atts['id'];

    $flower = WebFlower_Form::get_instance( $id );

	if ( ! $flower ) {
		return '[webflower 404 "Not Found"]';
	}

	// return $flower->form_html( $atts );
    return $flower->answer_html();
}

?>
