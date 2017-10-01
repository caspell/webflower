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

$questionDummyStyle = $qcount > 0 ? 'display:none;' : '';

$result_type = $post->result_type();

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
	<!--
	<?php
		if ( ! $post->initial() ) :
			$copy_nonce = wp_create_nonce( 'webflower-copy-webflower_' . $post_id );
	?>
		<input type="submit" name="webflower-copy" class="copy button" value="<?php echo esc_attr( __( 'Duplicate', 'webflower' ) ); ?>" <?php echo "onclick=\"this.form._wpnonce.value = '$copy_nonce'; this.form.action.value = 'copy'; return true;\""; ?> />
	<?php
	endif;
	?>
	-->

</div><!-- #minor-publishing-actions -->

<div id="misc-publishing-actions">
<?php do_action( 'webflower_admin_misc_pub_section', $post_id ); ?>
</div><!-- #misc-publishing-actions -->

<div id="major-publishing-actions">

<?php
	if ( ! $post->initial() ) :
		$delete_nonce = wp_create_nonce( 'webflower-delete-form_' . $post_id );
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
			<li><?php echo 'first, create page and that Shortcode write to page content area.'; ?></li>
		</ul>
	</div>
</div><!-- #informationdiv -->

</div><!-- #postbox-container-1 -->

<div id="postbox-container-2" class="postbox-container">
<div id="webflow-editor" class="rows">

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
		<label for="result_type" class="large-text">Result Type<br/>
			<select id="result_type" name="result_type">
				<option value='link' <?php echo $result_type == 'link' ? "selected='selected'" : ''; ?>>link</option>
				<option value='html' <?php echo $result_type == 'html' ? "selected='selected'" : ''; ?>>html</option>
			</select>
		</label>
	</div>

	<div class="webflow-editor-panel" id="question-panel" aria-labelledby="ui-id-3" role="tabpanel" aria-hidden="false" style="display: block;">
		<input type="hidden" id="qcount" name="qcount" value="<?php echo $qcount; ?>" />
		<script id="row-template" type="jquery/x-template">
		<tr>
			<th class="qnumber">{{num}}</th>
			<td><input type="text" name="qscore[]" value="0" class="td-100 question-score" /></td>
			<td><input type="text" name="q1[]" value="" class="td-100 question-q1" /></td>
			<td><input type="text" name="q2[]" value="" class="td-100 question-q2" /></td>
			<td><button type="button" class="btn-danger row-delete">X</button></td>
		</tr>
		</script>
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
				<tr class='dummy' style='<?php echo $questionDummyStyle; ?>'>
					<td colspan="5">please add item</td>
				</tr>

				<?php
				if ($post->qcount()>0) {
					$i = 0;foreach ( $post->questions() as $item)  {
					?>
					<tr>
						<th class="qnumber"><?php echo ++$i; ?></th>
						<td><input type="text" name="qscore[]" value="<?php echo $item['qscore'];?>" class="td-100 question-score" /></td>
						<td><input type="text" name="q1[]" value="<?php echo $item['q1'];?>" class="td-100 question-q1" /></td>
						<td><input type="text" name="q2[]" value="<?php echo $item['q2'];?>" class="td-100 question-q2" /></td>
						<td><button type="button" class="btn-danger row-delete">X</button></td>
					</tr>
					<?php
					}
				}
				?>

			</tbody>
		</table>
	</div>
<?php

$rcount = $post->rcount();

$resultDummyStyle = $rcount > 0 ?  'display:none;' : '';

?>
	<div class="webflow-editor-panel" id="result-panel" aria-labelledby="ui-id-3" role="tabpanel" aria-hidden="false" style="display: block;margin-top:20px;">
		<input type="hidden" id="rcount" name="rcount" value="<?php echo $rcount; ?>" />
		<script id="row-template" type="jquery/x-template">
		<tr>
			<th class="qnumber">{{num}}</th>
			<td><input type="text" name="r1[]" value="0" class="td-100 result_range" /></td>
			<td>~</td>
			<td><input type="text" name="r2[]" value="0" class="td-100 result_range" /></td>
			<td><input type="text" name="rmessage[]" value="" class="td-100 result_message" /></td>
			<td><button type="button" class="btn-danger row-delete">X</button></td>
		</tr>
		</script>
		<div class="config-error"></div>
		<h2>Result <input id="btn_add" type="button" class="button-primary" value="Add"></h2>
		<table id="result" class="wp-list-table table widefat fixed striped">
			<colgroup>
				<col width="3%" />
				<col width="10%" />
				<col width="2%" />
				<col width="10%" />
				<col width="70%" />
				<col width="5%" />
			</colgroup>
			<thead>
				<tr>
					<th>#</th>
					<th colspan="3">score range</th>
					<th>result message</th>
					<th>-</th>
				</tr>
			</thead>
			<tbody>
				<tr class='dummy' style='<?php echo $resultDummyStyle; ?>'>
					<td colspan="6" style="text-align:center;">please add result</td>
				</tr>

				<?php
				if ($post->rcount()>0) {
					$i = 0;foreach ( $post->results() as $item)  {
					?>
					<tr>
						<th class="qnumber"><?php echo ++$i; ?></th>
						<td><input type="text" name="r1[]" value="<?php echo $item['r1'];?>" class="td-100 result_range" /></td>
						<td>~</td>
						<td><input type="text" name="r2[]" value="<?php echo $item['r2'];?>" class="td-100 result_range" /></td>
						<td><textarea name="rmessage[]" class="td-100 result_message"><?php echo $item['rmessage'];?></textarea></td>
						<td><button type="button" class="btn-danger row-delete">X</button></td>
					</tr>
					<?php
					}
				}
				?>

			</tbody>
		</table>
	</div>

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
