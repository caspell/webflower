<?php


function webflower_messages() {
	$messages = array(
		'mail_sent_ok' => array(
			'description'
				=> __( "Sender's message was sent successfully", 'webflower' ),
			'default'
				=> __( "Thank you for your message. It has been sent.", 'webflower' ),
		),
	);

	return apply_filters( 'webflower_messages', $messages );
}

?>
