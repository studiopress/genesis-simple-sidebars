<?php
/**
 * Admin edit.
 *
 * @package genesis-simple-sidebars
 */

$sidebars = Genesis_Simple_Sidebars()->core->get_sidebars();

// phpcs:disable WordPress.Security.NonceVerification.NoNonceVerification
if ( isset( $_REQUEST['id'] ) && array_key_exists( sanitize_text_field( wp_unslash( $_REQUEST['id'] ) ), (array) $sidebars ) ) {
	$sidebar = stripslashes_deep( $sidebars[ sanitize_text_field( wp_unslash( $_REQUEST['id'] ) ) ] );
} else {
	wp_die( esc_html__( 'Nice try, partner. But that sidebar doesn\'t exist. Click back and try again.', 'genesis-simple-sidebars' ) );
}
// phpcs:enable
?>
<h1><?php esc_html_e( 'Edit Sidebar', 'genesis-simple-sidebars' ); ?></h1>

<form method="post" action="<?php echo esc_url( admin_url( 'admin.php?page=simple-sidebars&amp;action=edit' ) ); ?>">
<?php wp_nonce_field( 'simple-sidebars-action_edit-sidebar' ); ?>

<table class="form-table">

	<tr class="form-field">
		<th scope="row" valign="top"><label for="edit_sidebar[name]"><?php esc_html_e( 'Name', 'genesis-simple-sidebars' ); ?></label></th>
		<td><input name="edit_sidebar[name]" id="edit_sidebar[name]" type="text" value="<?php echo esc_html( $sidebar['name'] ); ?>" size="40" />
		<p class="description"><?php esc_html_e( 'A recognizable name for your new sidebar widget area', 'genesis-simple-sidebars' ); ?></p></td>
	</tr>

	<tr class="form-field">
		<th scope="row" valign="top"><label for="edit_sidebar[id]"><?php esc_html_e( 'ID', 'genesis-simple-sidebars' ); ?></label></th>
		<td>
			<?php // phpcs:disable WordPress.Security.NonceVerification.NoNonceVerification ?>
		<input type="text" value="<?php echo esc_html( sanitize_text_field( wp_unslash( $_REQUEST['id'] ) ) ); ?>" size="40" readonly />
		<input name="edit_sidebar[id]" id="edit_sidebar[id]" type="hidden" value="<?php echo esc_attr( sanitize_text_field( wp_unslash( $_REQUEST['id'] ) ) ); ?>" size="40" />
		<p class="description"><?php esc_html_e( 'The unique ID is used to register the sidebar widget area (cannot be changed)', 'genesis-simple-sidebars' ); ?></p></td>
	</tr>

	<tr class="form-field">
		<th scope="row" valign="top"><label for="edit_sidebar[description]"><?php esc_html_e( 'Description', 'genesis-simple-sidebars' ); ?></label></th>
		<td><textarea name="edit_sidebar[description]" id="edit_sidebar[description]" rows="3" cols="50" style="width: 97%;"><?php echo esc_html( $sidebar['description'] ); ?></textarea></td>
	</tr>

</table>

<p class="submit"><input type="submit" class="button-primary" name="submit" value="<?php esc_attr_e( 'Update', 'genesis-simple-sidebars' ); ?>" /></p>

</form>
