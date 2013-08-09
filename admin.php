<?php
/**
 * This registers the settings field and adds empty array to the options table
 */
add_action('admin_init', 'register_ss_settings');
function register_ss_settings() {
	register_setting(SS_SETTINGS_FIELD, SS_SETTINGS_FIELD);
	add_option(SS_SETTINGS_FIELD, array(), '', 'yes');
}

/**
 * This function adds our "Simple Sidebars" submenu item
 */
add_action('admin_menu', 'ss_settings_init', 15);
function ss_settings_init() {
	
	add_submenu_page('genesis', __('Simple Sidebars','ss'), __('Simple Sidebars','ss'), 'manage_options', 'simple-sidebars', 'ss_settings_admin');
	
}

add_action('admin_init', 'ss_action_functions');
function ss_action_functions() {
	
	if ( !isset( $_REQUEST['page'] ) || $_REQUEST['page'] != 'simple-sidebars' ) {
		return;
	}
	
	/**
	 * This section handles the data if a new sidebar is created
	 */
	if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'create' ) {
		ss_create_sidebar( $_POST['new_sidebar'] );
	}

	/**
	 * This section will handle the data if a sidebar is deleted
	 */
	if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'delete' && isset( $_REQUEST['id'] ) ) {
		ss_delete_sidebar( $_REQUEST['id'] );
	}

	/**
	 * This section will handle the data if a sidebar is to be modified
	 */
	if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'edit' && !isset( $_REQUEST['id'] ) ) {	
		ss_edit_sidebar( $_POST['edit_sidebar'] );
	}
}

/**
 * This function is what actually gets output to the page.
 * It handles the markup, builds the form, etc.
 */
function ss_settings_admin() { ?>
		
	<div class="wrap">	
		
		<?php
		if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'edit' ) :
		
			$_sidebars = get_option( SS_SETTINGS_FIELD );
		
			if ( array_key_exists( $_REQUEST['id'], (array)$_sidebars ) ) {
				$_sidebar = stripslashes_deep( $_sidebars[$_REQUEST['id']] );
			} else {
				wp_die( __('Nice try, partner. But that sidebar doesn\'t exist. Click back and try again.', 'ss') );
			}
		
		?>
			
		<?php screen_icon('themes'); ?>
		<h2><?php _e('Edit Sidebar', 'ss'); ?></h2>
		
		<form method="post" action="<?php echo admin_url( 'admin.php?page=simple-sidebars&amp;action=edit' ); ?>">
		<?php wp_nonce_field('simple-sidebars-action_edit-sidebar'); ?>
		<table class="form-table">

			<tr class="form-field"> 
				<th scope="row" valign="top"><label for="edit_sidebar[name]"><?php _e('Name', 'ss'); ?></label></th> 
				<td><input name="edit_sidebar[name]" id="edit_sidebar[name]" type="text" value="<?php echo esc_html( $_sidebar['name'] ); ?>" size="40" /> 
				<p class="description"><?php _e('A recognizable name for your new sidebar widget area', 'ss'); ?></p></td>
			</tr>
			
			<tr class="form-field"> 
				<th scope="row" valign="top"><label for="edit_sidebar[id]"><?php _e('ID', 'ss'); ?></label></th> 
				<td>
				<input type="text" value="<?php echo esc_html( $_REQUEST['id'] ); ?>" size="40" disabled="disabled" />
				<input name="edit_sidebar[id]" id="edit_sidebar[id]" type="hidden" value="<?php echo esc_html( $_REQUEST['id'] ); ?>" size="40" /> 
				<p class="description"><?php _e('The unique ID is used to register the sidebar widget area (cannot be changed)', 'ss'); ?></p></td> 
			</tr>

			<tr class="form-field"> 
				<th scope="row" valign="top"><label for="edit_sidebar[description]"><?php _e('Description', 'ss'); ?></label></th> 
				<td><textarea name="edit_sidebar[description]" id="edit_sidebar[description]" rows="3" cols="50" style="width: 97%;"><?php echo esc_html( $_sidebar['description'] ); ?></textarea></td> 
			</tr> 

		</table>
		
		<p class="submit"><input type="submit" class="button-primary" name="submit" value="<?php _e('Update', 'ss'); ?>" /></p> 
		
		</form>
			
		<?php else : ?>
			
		<?php screen_icon('themes'); ?>
		<h2><?php _e('Genesis - Simple Sidebars', 'ss'); ?></h2>
		
		<div id="col-container"> 

		<div id="col-right"> 
		<div class="col-wrap"> 

		<h3><?php _e('Current Sidebars', 'ss'); ?></h3>
		<table class="widefat tag fixed" cellspacing="0"> 
			<thead> 
			<tr> 
			<th scope="col" id="name" class="manage-column column-name"><?php _e('Name', 'ss'); ?></th> 
			<th scope="col" class="manage-column column-slug"><?php _e('ID', 'ss'); ?></th> 
			<th scope="col" id="description" class="manage-column column-description"><?php _e('Description', 'ss'); ?></th> 
			</tr> 
			</thead> 

			<tfoot> 
			<tr> 
			<th scope="col" class="manage-column column-name"><?php _e('Name', 'ss'); ?></th> 
			<th scope="col" class="manage-column column-slug"><?php _e('ID', 'ss'); ?></th> 
			<th scope="col" class="manage-column column-description"><?php _e('Description', 'ss'); ?></th> 
			</tr> 
			</tfoot>

			<tbody id="the-list" class="list:tag">
				
				<?php ss_sidebar_table_rows(); ?>
			
			</tbody> 
		</table>

		</div> 
		</div><!-- /col-right -->
		
		<div id="col-left"> 
		<div class="col-wrap"> 


		<div class="form-wrap"> 
		<h3><?php _e('Add New Sidebar', 'ss'); ?></h3>
		
		<form method="post" action="<?php echo admin_url( 'admin.php?page=simple-sidebars&amp;action=create' ); ?>">
		<?php wp_nonce_field('simple-sidebars-action_create-sidebar'); ?>	

		<div class="form-field form-required"> 
			<label for="sidebar-name"><?php _e('Name', 'ss'); ?></label> 
			<input name="new_sidebar[name]" id="sidebar-name" type="text" value="" size="40" aria-required="true" /> 
			<p><?php _e('A recognizable name for your new sidebar widget area', 'ss'); ?></p> 
		</div>
		
		<div class="form-field"> 
			<label for="sidebar-id"><?php _e('ID', 'ss'); ?></label> 
			<input name="new_sidebar[id]" id="sidebar-id" type="text" value="" size="40" /> 
			<p><?php _e('The unique ID is used to register the sidebar widget area', 'ss'); ?></p> 
		</div>
			
		<div class="form-field"> 
			<label for="sidebar-description"><?php _e('Description', 'ss'); ?></label> 
			<textarea name="new_sidebar[description]" id="sidebar-description" rows="5" cols="40"></textarea> 
		</div> 

		<p class="submit"><input type="submit" class="button" name="submit" id="submit" value="<?php _e('Add New Sidebar', 'ss'); ?>" /></p> 
		</form></div> 

		</div> 
		</div><!-- /col-left -->

		</div><!-- /col-container -->
		
		<?php endif; ?>
		
	</div><!-- /wrap -->

<?php
}