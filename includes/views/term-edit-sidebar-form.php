<?php
/**
 * Term Edit Form View.
 *
 * @package genesis-simple-sidebars
 */

$sidebars = Genesis_Simple_Sidebars()->core->get_sidebars();
?>

<h3><?php esc_html_e( 'Sidebar Options', 'genesis-simple-sidebars' ); ?></h3>
<table class="form-table">

<?php if ( is_registered_sidebar( 'header-right' ) ) : ?>
<tr class="form-field">
	<th scope="row" valign="top"><label for="genesis-meta[_ss_header]"><?php esc_html_e( 'Header Right', 'genesis-simple-sidebars' ); ?></label></th>
	<td>
		<select name="genesis-meta[_ss_header]" id="genesis-meta[_ss_header]" style="padding-right: 10px;">
			<option value=""><?php esc_html_e( 'Default', 'genesis-simple-sidebars' ); ?></option>
			<?php
			foreach ( (array) $sidebars as $sidebar_id => $info ) {
				printf( '<option value="%s" %s>%s</option>', esc_html( $sidebar_id ), selected( $sidebar_id, get_term_meta( $tag->term_id, '_ss_header', true ), false ), esc_html( $info['name'] ) );
			}
			?>
		</select>
	</td>
</tr>
<?php endif; ?>

<tr class="form-field">
	<th scope="row" valign="top"><label for="genesis-meta[_ss_sidebar]"><?php esc_html_e( 'Primary Sidebar', 'genesis-simple-sidebars' ); ?></label></th>
	<td>
		<select name="genesis-meta[_ss_sidebar]" id="genesis-meta[_ss_sidebar]" style="padding-right: 10px;">
			<option value=""><?php esc_html_e( 'Default', 'genesis-simple-sidebars' ); ?></option>
			<?php
			foreach ( (array) $sidebars as $sidebar_id => $info ) {
				printf( '<option value="%s" %s>%s</option>', esc_html( $sidebar_id ), selected( $sidebar_id, get_term_meta( $tag->term_id, '_ss_sidebar', true ), false ), esc_html( $info['name'] ) );
			}
			?>
		</select>
	</td>
</tr>

<?php if ( Genesis_Simple_Sidebars()->core->has_3_column_layout() ) : ?>
<tr class="form-field">
	<th scope="row" valign="top"><label for="genesis-meta[_ss_sidebar_alt]"><?php esc_html_e( 'Secondary Sidebar', 'genesis-simple-sidebars' ); ?></label></th>
	<td>
		<select name="genesis-meta[_ss_sidebar_alt]" id="genesis-meta[_ss_sidebar_alt]" style="padding-right: 10px;">
			<option value=""><?php esc_html_e( 'Default', 'genesis-simple-sidebars' ); ?></option>
			<?php
			foreach ( (array) $sidebars as $sidebar_id => $info ) {
				printf( '<option value="%s" %s>%s</option>', esc_html( $sidebar_id ), selected( $sidebar_id, get_term_meta( $tag->term_id, '_ss_sidebar_alt', true ), false ), esc_html( $info['name'] ) );
			}
			?>
		</select>
	</td>
</tr>
<?php endif; ?>
</table>
