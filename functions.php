<?php
function ss_sidebar_table_rows() {
	global $wp_registered_sidebars;
	
	$_sidebars = $wp_registered_sidebars;
	
	$alt = true;
	
	foreach ( (array)$_sidebars as $id => $info ) { ?>
		
		<?php
			$is_editable = isset( $info['editable'] ) && $info['editable'] ? true : false;
		?>
		
		<tr <?php if ( $alt ) { echo 'class="alternate"'; $alt = false; } else { $alt = true; } ?>>
			<td class="name column-name">
				<?php
					if ( $is_editable ) {
						printf( '<a class="row-title" href="%s" title="Edit %s">%s</a>', admin_url('admin.php?page=simple-sidebars&amp;action=edit&amp;id=' . esc_html( $id ) ), esc_html( $info['name'] ), esc_html( $info['name'] ) );
					} else {
						printf( '<strong class="row-title">%s</strong>', esc_html( $info['name'] ) );
					}
				?>
				
				<?php if ( $is_editable ) : ?>
				<br />
				<div class="row-actions">
					<span class="edit"><a href="<?php echo admin_url('admin.php?page=simple-sidebars&amp;action=edit&amp;id=' . esc_html( $id ) ); ?>"><?php _e('Edit', 'ss'); ?></a> | </span>
					<span class="delete"><a class="delete-tag" href="<?php echo wp_nonce_url( admin_url( 'admin.php?page=simple-sidebars&amp;action=delete&amp;id=' . esc_html( $id ) ), 'simple-sidebars-action_delete-sidebar' ); ?>"><?php _e('Delete', 'ss'); ?></a></span>
				</div>
				<?php endif; ?>
				
			</td>
			<td class="slug column-slug"><?php echo esc_html( $id ); ?></td>
			<td class="description column-description"><?php echo esc_html( $info['description'] )?></td>
		</tr>

	<?php
	}
	
}

function ss_create_sidebar( $args = array() ) {

	if ( empty( $args['name'] ) || empty( $args['id'] ) ) {
		wp_die( ss_error_message(1) );
		exit;
	}
	
	//	nonce verification
	check_admin_referer('simple-sidebars-action_create-sidebar');
	
	// WP changes a numeric sidebar id to sidebar-id which makes it inaccessible to the user
	if ( is_numeric( $args['id'] ) )
		$args['id'] = sanitize_title_with_dashes( $args['name'] );
	
	$db = (array)get_option(SS_SETTINGS_FIELD);
	$new = array( 
		sanitize_title_with_dashes( $args['id'] ) => array(
			'name' => esc_html( $args['name'] ),
			'description' => esc_html( $args['description'] )
		)
	);
	
	if ( array_key_exists( $args['id'], $db ) ) {
		wp_die( ss_error_message(2) );
		exit;
	}
		
	$_sidebars = wp_parse_args( $new, $db );
	
	update_option( SS_SETTINGS_FIELD, $_sidebars );
	wp_redirect( admin_url('admin.php?page=simple-sidebars&created=true') );
	exit;
	
}

function ss_edit_sidebar( $args = array() ) {

	if ( empty( $args['name'] ) || empty( $args['id'] ) ) {
		wp_die( ss_error_message(3) );
		exit;
	}
	
	//	nonce verification
	check_admin_referer('simple-sidebars-action_edit-sidebar');
	
	// WP changes a numeric sidebar id to sidebar-id which makes it inaccessible to the user
	if ( is_numeric( $args['id'] ) )
		$args['id'] = sanitize_title_with_dashes( $args['name'] );
	
	$db = (array)get_option(SS_SETTINGS_FIELD);
	$new = array( 
		sanitize_title_with_dashes( $args['id'] ) => array(
			'name' => esc_html( $args['name'] ),
			'description' => esc_html( $args['description'] )
		)
	);
	
	if ( !array_key_exists( $args['id'], $db ) ) {
		wp_die( ss_error_message(3) );
		exit;
	}
		
	$_sidebars = wp_parse_args( $new, $db );
	
	update_option( SS_SETTINGS_FIELD, $_sidebars );
	wp_redirect( admin_url('admin.php?page=simple-sidebars&edited=true') );
	exit;
	
}

function ss_delete_sidebar( $id = '' ) {
	
	if ( empty( $id ) ) {
		wp_die( ss_error_message(4) );
		exit;
	}
	
	//	nonce verification
	check_admin_referer('simple-sidebars-action_delete-sidebar');
	
	$_sidebars = (array)get_option( SS_SETTINGS_FIELD );
	
	if ( !isset( $_sidebars[$id] ) ) {
		wp_die( ss_error_message(4) );
		exit;
	}
	
	unset( $_sidebars[$id] );
	
	update_option( SS_SETTINGS_FIELD, $_sidebars );
	wp_redirect( admin_url('admin.php?page=simple-sidebars&deleted=true') );
	exit;
	
}

function ss_error_message( $error = false ) {
	
	if ( !$error ) return false;
	
	switch( (int)$error ) {
		
		case 1:
			return __('Oops! Please choose a valid Name and ID for this sidebar', 'ss');
			break;
		case 2:
			return __('Oops! That sidebar ID already exists', 'ss');
			break;
		case 3:
			return __('Oops! You are trying to edit a sidebar that does not exist, or is not editable', 'ss');
			break;	
		case 4:
			return __('Oops! You are trying to delete a sidebar that does not exist, or cannot be deleted', 'ss');
			break;
		default:
			return __('Oops! Something went wrong. Try again.', 'ss');
			
	}
	
}

add_action('admin_notices', 'ss_success_message');
function ss_success_message() {
	
	if ( !isset( $_REQUEST['page'] ) || $_REQUEST['page'] != 'simple-sidebars' ) {
		return;
	}
	
	$format = '<div id="message" class="updated"><p><strong>%s</strong></p></div>';
	
	if ( isset( $_REQUEST['created'] ) && $_REQUEST['created'] === 'true' ) {
		printf( $format, __('New sidebar successfully created!', 'ss') );
		return;
	}
	
	if ( isset( $_REQUEST['edited'] ) && $_REQUEST['edited'] === 'true' ) {
		printf( $format, __('Sidebar successfully edited!', 'ss') );
		return;
	}
	
	if ( isset( $_REQUEST['deleted'] ) && $_REQUEST['deleted'] === 'true' ) {
		printf( $format, __('Sidebar successfully deleted.', 'ss') );
		return;
	}
	
	return;
	
}