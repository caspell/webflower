<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

function webflower_admin_save_button( $post_id ) {
	static $button = '';

	if ( ! empty( $button ) ) {
		echo $button;
		return;
	}

	$nonce = wp_create_nonce( 'webflower-save-form_' . $post_id );

	$onclick = sprintf(
		"this.form._wpnonce.value = '%s';"
		. " this.form.action.value = 'save';"
		. " return true;",
		$nonce );

	$button = sprintf(
		'<input type="submit" class="button-primary" name="webflower-save" value="%1$s" onclick="%2$s" />',
		esc_attr( __( 'Save', 'webflower' ) ),
		$onclick );

	echo $button;
}

?><div class="wrap">

<h1 class="wp-heading-inline"><?php
	if ( $post->initial() ) {
		echo esc_html( __( 'Add New Web Flower', 'webflower' ) );
	} else {
		echo esc_html( __( 'Edit Web Flower', 'webflower' ) );
	}
?></h1>

<?php
	if ( ! $post->initial() && current_user_can( 'webflower_edit_webflows' ) ) {
		echo sprintf( '<a href="%1$s" class="add-new-h2">%2$s</a>',
			esc_url( menu_page_url( 'webflower-new', false ) ),
			esc_html( __( 'Add New', 'webflower' ) ) );
	}
?>

<hr class="wp-header-end">

<?php do_action( 'webflower_admin_warnings' ); ?>
<?php do_action( 'webflower_admin_notices' ); ?>

<?php
if ( $post ) :

	if ( current_user_can( 'webflower_edit_webflow', $post_id ) ) {
		$disabled = '';
	} else {
		$disabled = ' disabled="disabled"';
	}

$qcount = $post->qcount();

if ( $qcount > 0 ) {
	$dummystyle = 'display:none;';
}

?>

<form method="post" action="<?php echo esc_url( add_query_arg( array( 'post' => $post_id ), menu_page_url( 'webflower', false ) ) ); ?>" id="webflower-admin-form-element"<?php do_action( 'webflower_post_edit_form_tag' ); ?>>
<?php
	if ( current_user_can( 'webflower_edit_webflow', $post_id ) ) {
		wp_nonce_field( 'webflower-save-form_' . $post_id );
	}
?>
<input type="hidden" id="post_ID" name="post_ID" value="<?php echo (int) $post_id; ?>" />
<input type="hidden" id="webflower-locale" name="webflower-locale" value="<?php echo esc_attr( $post->locale() ); ?>" />
<input type="hidden" id="hiddenaction" name="action" value="save" />
<input type="hidden" id="qcount" name="qcount" value="<?php echo $qcount; ?>" />

<div id="poststuff">
<div id="post-body" class="metabox-holder columns-2">
<div id="post-body-content">
<div id="titlediv">
<div id="titlewrap">
	<label class="screen-reader-text" id="title-prompt-text" for="title">
		<?php echo esc_html( __( 'Enter title here', 'webflower' ) ); ?></label>
<?php
	$posttitle_atts = array(
		'type' => 'text',
		'name' => 'post_title',
		'size' => 30,
		'value' => $post->initial() ? '' : $post->title(),
		'id' => 'title',
		'spellcheck' => 'true',
		'autocomplete' => 'off',
		'disabled' => current_user_can( 'webflower_edit_webflow', $post_id )? '' : 'disabled'
	);

	echo sprintf( '<input %s />', webflower_format_atts( $posttitle_atts ) );

?>
</div><!-- #titlewrap -->

<div class="inside">
<?php
	if ( ! $post->initial() ) :
?>
	<p class="description">
	<label for="webflower-shortcode"><?php echo esc_html( __( "Copy this shortcode and paste it into your post, page, or text widget content:", 'webflower' ) ); ?></label>
	<span class="shortcode wp-ui-highlight"><input type="text" id="webflower-shortcode" onfocus="this.select();" readonly="readonly" class="large-text code" value="<?php echo esc_attr( $post->shortcode() ); ?>" /></span>
	</p>
<?php
		if ( $old_shortcode = $post->shortcode( array( 'use_old_format' => true ) ) ) :
?>
	<p class="description">
	<label for="webflower-shortcode-old"><?php echo esc_html( __( "You can also use this old-style shortcode:", 'webflower' ) ); ?></label>
	<span class="shortcode old"><input type="text" id="webflower-shortcode-old" onfocus="this.select();" readonly="readonly" class="large-text code" value="<?php echo esc_attr( $old_shortcode ); ?>" /></span>
	</p>
<?php
		endif;
	endif;
?>
</div>
</div><!-- #titlediv -->
</div><!-- #post-body-content -->

<div id="postbox-container-1" class="postbox-container">
<?php if ( current_user_can( 'webflower_edit_webflow', $post_id ) ) : ?>
<div id="submitdiv" class="postbox">
<h3><?php echo esc_html( __( 'Status', 'webflower' ) ); ?></h3>
<div class="inside">
<div class="submitbox" id="submitpost">

<div id="minor-publishing-actions">

<div class="hidden">
	<input type="submit" class="button-primary" name="webflower-save" value="<?php echo esc_attr( __( 'Save', 'webflower' ) ); ?>" />
</div>

<?php
	if ( ! $post->initial() ) :
		$copy_nonce = wp_create_nonce( 'webflower-copy-webflower_' . $post_id );
?>
	<input type="submit" name="webflower-copy" class="copy button" value="<?php echo esc_attr( __( 'Duplicate', 'webflower' ) ); ?>" <?php echo "onclick=\"this.form._wpnonce.value = '$copy_nonce'; this.form.action.value = 'copy'; return true;\""; ?> />
<?php endif; ?>
</div><!-- #minor-publishing-actions -->

<div id="misc-publishing-actions">
<?php do_action( 'webflower_admin_misc_pub_section', $post_id ); ?>
</div><!-- #misc-publishing-actions -->

<div id="major-publishing-actions">

<?php
	if ( ! $post->initial() ) :
		$delete_nonce = wp_create_nonce( 'webflower-delete-webflower_' . $post_id );
?>
<div id="delete-action">
	<input type="submit" name="webflower-delete" class="delete submitdelete" value="<?php echo esc_attr( __( 'Delete', 'webflower' ) ); ?>" <?php echo "onclick=\"if (confirm('" . esc_js( __( "You are about to delete this Web Flower.\n  'Cancel' to stop, 'OK' to delete.", 'webflower' ) ) . "')) {this.form._wpnonce.value = '$delete_nonce'; this.form.action.value = 'delete'; return true;} return false;\""; ?> />
</div><!-- #delete-action -->
<?php endif; ?>

<div id="publishing-action">
	<span class="spinner"></span>
	<?php webflower_admin_save_button( $post_id ); ?>
</div>
<div class="clear"></div>
</div><!-- #major-publishing-actions -->
</div><!-- #submitpost -->
</div>
</div><!-- #submitdiv -->
<?php endif; ?>

<div id="informationdiv" class="postbox">
	<h3><?php echo esc_html( __( 'Information', 'webflower' ) ); ?></h3>
	<div class="inside">
		<ul>
			<li><?php echo 'test1'; ?></li>
		</ul>
	</div>
</div><!-- #informationdiv -->

</div><!-- #postbox-container-1 -->

<div id="postbox-container-2" class="postbox-container">
<div id="webflow-editor" class="rows">
	<script id="question-template" type="jquery/x-template">
	<tr>
		<th class="qnumber">{{num}}</th>
		<td><input type="text" name="qscore[]" value="" class="td-100 question-score" /></td>
		<td><input type="text" name="q1[]" value="" class="td-100 question-q1" /></td>
		<td><input type="text" name="q2[]" value="" class="td-100 question-q2" /></td>
		<td><button type="button" class="btn-danger row-delete">X</button></td>
	</tr>
	</script>

	<div class=" webflow-editor-panel " id="question-subtitle" aria-labelledby="ui-id-3" role="tabpanel" aria-hidden="false" style="display: block;margin-bottom:20px;">
		<label for="subtitle" class="large-text">Subtitle<br/>
	<?php

		$subposttitle_atts = array(
			'type' => 'text',
			'name' => 'subtitle',
			'value' => $post->subtitle(),
			'id' => 'subtitle',
			'spellcheck' => 'false',
			'autocomplete' => 'off',
		);

		echo sprintf( '<input class="large-text" %s />', webflower_format_atts( $subposttitle_atts ) );
	?>
		</label>
	</div>

	<div class="webflow-editor-panel" id="question-panel" aria-labelledby="ui-id-3" role="tabpanel" aria-hidden="false" style="display: block;">
		<div class="config-error"></div>
		<h2>Questions <input id="btn_add" type="button" class="button-primary" value="Add"></h2>
		<table id="question" class="wp-list-table table widefat fixed striped">
			<colgroup>
				<col width="5%" />
				<col width="10%" />
				<col width="40%" />
				<col width="40%" />
				<col width="5%" />
			</colgroup>
			<thead>
				<tr>
					<th>#</th>
					<th>score</th>
					<th>Yes Question</th>
					<th>No Question</th>
					<th>-</th>
				</tr>
			</thead>
			<tbody>
				<tr class='dummy' style='<?php echo $dummystyle; ?>'>
					<td colspan="5">please add item</td>
				</tr>

				<?php $i = 0;foreach ( $post->questions() as $item)  { ?>
				<tr>
					<th class="qnumber"><?php echo ++$i; ?></th>
					<td><input type="text" name="qscore[]" value="<?php echo $item['qscore'];?>" class="td-100 question-score" /></td>
					<td><input type="text" name="q1[]" value="<?php echo $item['q1'];?>" class="td-100 question-q1" /></td>
					<td><input type="text" name="q2[]" value="<?php echo $item['q2'];?>" class="td-100 question-q2" /></td>
					<td><button type="button" class="btn-danger row-delete">X</button></td>
				</tr>
				<?php } ?>

			</tbody>
		</table>
	</div>

<script type="text/javascript">
(function($){

	function recount_number(){
		var $rows = $("#question tbody tr:not(.dummy)");
		var length = $rows.length + 1

		$("#qcount").val($rows.length);
		$rows.find(".qnumber").each(function(i, o){
			$(this).text(i + 1);
		});
	}

	$("#btn_add").click(function(){
		var row = $("#question-template").html();
		var $rows = $("#question tbody tr:not(.dummy)");
		$("#question tbody tr.dummy").hide();
		var length = $rows.length + 1;
		row = row.replace('{{num}}', length);

		$("#qcount").val(length);
		$("#question tbody").append(row);
	});

	$("#webflow-editor").delegate(".row-delete", "click", function(){
		$(this).closest('tr').remove();
		recount_number();
	});

})(jQuery);
</script>

</div><!-- #webflower-editor -->

<?php if ( current_user_can( 'webflower_edit_webflow', $post_id ) ) : ?>
<p class="submit"><?php webflower_admin_save_button( $post_id ); ?></p>
<?php endif; ?>

</div><!-- #postbox-container-2 -->

</div><!-- #post-body -->
<br class="clear" />
</div><!-- #poststuff -->
</form>

<?php endif; ?>

</div><!-- .wrap -->
