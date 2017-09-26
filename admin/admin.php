<?php
/*
add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );

// positions
2 Dashboard
4 Separator
5 Posts
10 Media
15 Links
20 Pages
25 Comments
59 Separator
60 Appearance
65 Plugins
70 Users
75 Tools
80 Settings
99 Separator
*/

add_action('admin_menu', 'webflower_admin_menu');

function webflower_admin_menu() {
	global $_wp_last_object_menu;

	$_wp_last_object_menu++;

    // manage_options

    add_menu_page(__('WebFlower','webflower')
    ,__('WebFlow','webflower')
    ,'webflower_read_webflow' ,'webflower'
    ,'fnc_webflower_admin_management_page',''
    , $_wp_last_object_menu);
    // webflower_read_forms

	$edit = add_submenu_page( 'webflower',
		__( 'Edit Web Flow', 'webflower' ),
		__( 'Flows', 'webflower' ),
		'webflower_read_webflow', 'webflower',
		'fnc_webflower_admin_management_page' );
        // wpcf7_admin_management_page

	add_action( 'load-' . $edit, 'webflower_load_admin' );

	$addnew = add_submenu_page( 'webflower',
		__( 'Add New WebFlow', 'webflower' ),
		__( 'Add New', 'webflower' ),
		'webflower_edit_webflow', 'webflower-new',
		'fnc_webflower_admin_add_new_page' );
        // wpcf7_admin_add_new_page

	add_action( 'load-' . $addnew, 'webflower_load_admin' );

}


add_filter( 'set-screen-option', 'webflower_set_screen_options', 10, 3 );

function webflower_set_screen_options( $result, $option, $value ) {
	$webflower_screens = array(
		'_webflower_per_page' );

	if ( in_array( $option, $webflower_screens ) ) {
		$result = $value;
	}

	return $result;
}

add_action( 'admin_enqueue_scripts', 'webflower_admin_enqueue_scripts' );

function webflower_admin_enqueue_scripts($hook_suffix){
    if ( false === strpos( $hook_suffix, 'webflower' ) ) {
        return;
    }

	wp_enqueue_style( 'web-flowform-admin',
		webflower_plugin_url( 'admin/css/styles.css' ),
		array(),
		WEBFLOWER_VERSION, 'all' );

	wp_enqueue_script( 'web-flowform-admin',
		webflower_plugin_url( 'admin/js/scripts.js' ),
		array( 'jquery', 'jquery-ui-tabs' ),
		WEBFLOWER_VERSION, true );
// 'boostrap'
	wp_enqueue_style( 'web-flowform-bootstrap',
		webflower_plugin_url( 'includes/bootstrap/css/bootstrap.min.css' ),
		array( 'bootstrap' ),
		WEBFLOWER_VERSION, 'all' );
	//
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



function webflower_current_action() {
	if ( isset( $_REQUEST['action'] ) && -1 != $_REQUEST['action'] ) {
		return $_REQUEST['action'];
	}

	if ( isset( $_REQUEST['action2'] ) && -1 != $_REQUEST['action2'] ) {
		return $_REQUEST['action2'];
	}

	return false;
}


function fnc_webflower_admin_management_page() {

	if ($post = webflower_get_current_form()) {
		$post_id = $post->initial() ? -1 : $post->id();

		require_once WEBFLOWER_PLUGIN_DIR . '/admin/includes/editor.php';
		require_once WEBFLOWER_PLUGIN_DIR . '/admin/flow-editor.php';

		return;
	}

	require_once WEBFLOWER_PLUGIN_DIR . '/admin/flow-list.php';

	$list_table = new WebFlower_List_Table();
	$list_table->prepare_items();

	?>
	<div class="wrap">

	<h1 class="wp-heading-inline"><?php
		echo esc_html( __( 'Web Flows', 'webflower' ) );
	?></h1>

	<?php
		if ( current_user_can( 'webflower_edit_webflow' ) ) {
			echo sprintf( '<a href="%1$s" class="add-new-h2">%2$s</a>',
				esc_url( menu_page_url( 'webflower-new', false ) ),
				esc_html( __( 'Add New', 'webflower' ) ) );
		}

		if ( ! empty( $_REQUEST['s'] ) ) {
			echo sprintf( '<span class="subtitle">'
				. __( 'Search results for &#8220;%s&#8221;', 'webflower' )
				. '</span>', esc_html( $_REQUEST['s'] ) );
		}
	?>

	<hr class="wp-header-end">

	<?php do_action( 'webflower_admin_warnings' ); ?>

	<?php do_action( 'webflower_admin_notices' ); ?>

	<form method="get" action="">
		<input type="hidden" name="page" value="<?php echo esc_attr( $_REQUEST['page'] ); ?>" />
		<?php $list_table->search_box( __( 'Search WebFlower', 'webflower' ), 'webflower' ); ?>
		<?php $list_table->display(); ?>
	</form>

	</div>

	<?php

}


function fnc_webflower_admin_add_new_page() {

	$post = webflower_get_current_contact_form();

	if ( ! $post ) {
		$post = WebFlower_Form::get_template();
	}

	$post_id = -1;

	require_once WEBFLOWER_PLUGIN_DIR . '/admin/includes/editor.php';
	require_once WEBFLOWER_PLUGIN_DIR . '/admin/flow-editor.php';

}

function webflower_load_admin() {
	global $plugin_page;

	$action = webflower_current_action();

	if ( 'save' == $action ) {
		$id = isset( $_POST['post_ID'] ) ? $_POST['post_ID'] : '-1';

		check_admin_referer( 'webflower-save-form_' . $id );

		if ( ! current_user_can( 'webflower_edit_webflow', $id ) ) {
			wp_die( __( 'You are not allowed to edit this item.', 'webflower' ) );
		}

		$args = $_REQUEST;

		$subtitle = $args['subtitle'];
		$qcount = (int)$args['qcount'];
		$qscores = $args['qscore'];

		$q1s = $args['q1'];
		$q2s = $args['q2'];


		$args = wp_parse_args( $args, array(
			'id' => -1,
			'title' => null,
			'locale' => null,
			'form' => null,
		) );

		$query = array();

		if ($id == -1) {

			$post_id = wp_insert_post( array(
				'post_type' => 'webflower',
				'post_status' => 'publish',
				'post_title' => $args['post_title'],
			) );

			$metas = get_post_meta( $post_id );

			if (!add_post_meta($post_id, '_subtitle', $subtitle, true)) {
				update_post_meta( $post_id, '_' . '_subtitle', $subtitle );
			}

			if (!add_post_meta($post_id, '_qcount', $qcount, true)) {
				update_post_meta( $post_id, '_' . '_qcount', $qcount );
			}

			for ($i = 0 ; $i < $qcount ; $i++){
				$qscore = $qscores[$i];
				$q1 = $q1s[$i];
				$q2 = $q2s[$i];
				add_post_meta($post_id, '_qscore_'. $i, $qscore);
				add_post_meta($post_id, '_q1_'. $i, _normalize_newline_deep($q1));
				add_post_meta($post_id, '_q2_'. $i, _normalize_newline_deep($q2));
			}

			$query['id'] = $post_id;

		} else {

			$post_id = wp_update_post( array(
				'ID' => (int) $id,
				'post_status' => 'publish',
				'post_title' => $args['post_title'],
			) );

			$metas = get_post_meta( $post_id );

			foreach($metas as $key=>$value)  {
				delete_post_meta($post_id, $key);
			}

			if (!add_post_meta($post_id, '_subtitle', $subtitle, true)) {
				update_post_meta( $post_id, '_' . '_subtitle', $subtitle );
			}

			if (!add_post_meta($post_id, '_qcount', $qcount, true)) {
				update_post_meta( $post_id, '_' . '_qcount', $qcount );
			}

			for ($i = 0 ; $i < $qcount ; $i++){
				$qscore = $qscores[$i];
				$q1 = $q1s[$i];
				$q2 = $q2s[$i];
				add_post_meta($post_id, '_qscore_'. $i, $qscore);
				add_post_meta($post_id, '_q1_'. $i, _normalize_newline_deep($q1));
				add_post_meta($post_id, '_q2_'. $i, _normalize_newline_deep($q2));
			}

			$query['id'] = $post_id;

		}

		$redirect_to = add_query_arg( $query, menu_page_url( 'webflower', false ) );
		wp_safe_redirect( $redirect_to );
		exit();

	}

	if ( 'delete' == $action ) {

		if ( ! empty( $_POST['post_ID'] ) ) {
			check_admin_referer( 'webflower-delete-form_' . $_POST['post_ID'] );
		} elseif ( ! is_array( $_REQUEST['post'] ) ) {
			check_admin_referer( 'webflower-delete-form_' . $_REQUEST['post'] );
		}

		$query = array();

		if ( wp_delete_post( $this->id, true ) ) {
			$id = 0;
			$query['deleted'] = true;
		} else {
			$query['deleted'] = false;
		}

		$redirect_to = add_query_arg( $query, menu_page_url( 'webflower', false ) );

		wp_safe_redirect( $redirect_to );
		exit();

	}

	$_GET['post'] = isset( $_GET['post'] ) ? $_GET['post'] : '';

	$post = null;

	if ( 'webflower-new' == $plugin_page ) {
		$post = WebFlower_Form::get_template( array('locale' => isset( $_GET['locale'] ) ? $_GET['locale'] : null ) );
	} elseif ( ! empty( $_GET['post'] ) ) {
		$post = WebFlower_Form::get_instance( $_GET['post'] );
	}

	$current_screen = get_current_screen();

	if ( $post && current_user_can( 'webflower_edit_webflow', $post->id() ) ) {

	} else {

		if ( ! class_exists( 'WebFlower_List_Table' ) ) {
			require_once WEBFLOWER_PLUGIN_DIR . '/admin/includes/class-webflower-list-table.php';
		}

		add_filter( 'manage_' . $current_screen->id . '_columns',
			array( 'WebFlower_List_Table', 'define_columns' ) );

		add_screen_option( 'per_page', array(
			'default' => 20,
			'option' => 'cfseven_contact_forms_per_page' ) );
	}

}




?>
