<?php

// add_action( 'admin_enqueue_scripts', 'webflower_script_enqueue' );

add_action('wp_head', '_ajaxurl');

function _ajaxurl() {

   echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
}

// add_action( 'wp_enqueue_scripts', 'webflower_script_enqueue' );
//
// function webflower_script_enqueue() {
//
//     wp_localize_script( 'ajax-script', 'ajax_object',
//             array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
// }

add_action('wp_ajax_question_answer', 'question_answer_fnc' );
add_action('wp_ajax_nopriv_question_answer', 'question_answer_fnc' );

function question_answer_fnc(){

    $args = $_REQUEST;

    $args = wp_parse_args( $args, array( 'post' => -1, ) );

    $total_score = (int)$args['total_score'];

    $post = WebFlower_Form::get_instance( $args['post'] );

    $post->set_total_score($total_score);

    $result_type = $post->result_type();

    if ( $result_type == 'link' ) {
        echo $post->answer_json();
    } else {
        $post->answer_html();
    }

    wp_die();

}


?>
