<?php

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

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
