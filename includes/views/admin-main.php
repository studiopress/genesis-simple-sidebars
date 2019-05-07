<?php
/**
 * Main Admin View.
 *
 * @package genesis-simple-sidebar
 */

?>

<h1><?php esc_html_e( 'Genesis - Simple Sidebars', 'genesis-simple-sidebars' ); ?></h1>

<div id="col-container">

<div id="col-right">
<div class="col-wrap">

<h3><?php esc_html_e( 'Current Sidebars', 'genesis-simple-sidebars' ); ?></h3>
<table class="widefat tag fixed" cellspacing="0">
	<thead>
	<tr>
	<th scope="col" id="name" class="manage-column column-name"><?php esc_html_e( 'Name', 'genesis-simple-sidebars' ); ?></th>
	<th scope="col" class="manage-column column-slug"><?php esc_html_e( 'ID', 'genesis-simple-sidebars' ); ?></th>
	<th scope="col" id="description" class="manage-column column-description"><?php esc_html_e( 'Description', 'genesis-simple-sidebars' ); ?></th>
	</tr>
	</thead>

	<tfoot>
	<tr>
	<th scope="col" class="manage-column column-name"><?php esc_html_e( 'Name', 'genesis-simple-sidebars' ); ?></th>
	<th scope="col" class="manage-column column-slug"><?php esc_html_e( 'ID', 'genesis-simple-sidebars' ); ?></th>
	<th scope="col" class="manage-column column-description"><?php esc_html_e( 'Description', 'genesis-simple-sidebars' ); ?></th>
	</tr>
	</tfoot>

	<tbody id="the-list" class="list:tag">

		<?php $this->table_rows(); ?>

	</tbody>
</table>

</div>
</div><!-- /col-right -->

<div id="col-left">
<div class="col-wrap">


<div class="form-wrap">
<h3><?php esc_html_e( 'Add New Sidebar', 'genesis-simple-sidebars' ); ?></h3>

<form method="post" action="<?php echo esc_attr( esc_url( admin_url( 'admin.php?page=simple-sidebars&amp;action=create' ) ) ); ?>">
<?php wp_nonce_field( 'simple-sidebars-action_create-sidebar' ); ?>

<div class="form-field form-required">
	<label for="sidebar-name"><?php esc_html_e( 'Name', 'genesis-simple-sidebars' ); ?></label>
	<input name="new_sidebar[name]" id="sidebar-name" type="text" value="" size="40" aria-required="true" />
	<p><?php esc_html_e( 'A recognizable name for your new sidebar widget area', 'genesis-simple-sidebars' ); ?></p>
</div>

<div class="form-field">
	<label for="sidebar-id"><?php esc_html_e( 'ID', 'genesis-simple-sidebars' ); ?></label>
	<input name="new_sidebar[id]" id="sidebar-id" type="text" value="" size="40" />
	<p><?php esc_html_e( 'The unique ID is used to register the sidebar widget area', 'genesis-simple-sidebars' ); ?></p>
</div>

<div class="form-field">
	<label for="sidebar-description"><?php esc_html_e( 'Description', 'genesis-simple-sidebars' ); ?></label>
	<textarea name="new_sidebar[description]" id="sidebar-description" rows="5" cols="40"></textarea>
</div>

<p class="submit"><input type="submit" class="button" name="submit" id="submit" value="<?php esc_attr_e( 'Add New Sidebar', 'genesis-simple-sidebars' ); ?>" /></p>
</form></div>

</div>
</div><!-- /col-left -->

</div><!-- /col-container -->
