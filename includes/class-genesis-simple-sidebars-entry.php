<?php

class Genesis_Simple_Sidebars_Entry {

	public function init() {

		add_action( 'admin_menu', array( $this, 'add_metaboxes' ) );
		add_action( 'save_post', array( $this, 'metabox_save' ), 1, 2 );

	}

	/**
	 * Cycle through supported post types and add metaboxes.
	 *
	 * @since 2.1.0
	 */
	public function add_metaboxes() {

		foreach ( (array) get_post_types( array( 'public' => true ) ) as $type ) {

			if ( post_type_supports( $type, 'genesis-simple-sidebars' ) || $type == 'post' || $type == 'page' ) {
				add_meta_box( 'ss_inpost_metabox', __( 'Sidebar Selection', 'genesis-simple-sidebars' ), array( $this, 'metabox_content' ), $type, 'side', 'low' );
			}

		}

	}

	/**
	 * Include the metabox content.
	 *
	 * @since 2.1.0
	 */
	public function metabox_content() {

		require_once( Genesis_Simple_Sidebars()->plugin_dir_path . 'includes/views/entry-metabox-content.php' );

	}

	/**
	 * Save the metabox fields when the entry is saved.
	 *
	 * @since 2.1.0
	 */
	public function metabox_save( $post_id, $post ) {

		if ( ! isset( $_POST['genesis_simple_sidebars'] ) ) {
			return;
		}

		$data = wp_parse_args( $_POST['genesis_simple_sidebars'], array(
			'_ss_header'      => '',
			'_ss_sidebar'     => '',
			'_ss_sidebar_alt' => '',
		) );

		genesis_save_custom_fields( $data, 'genesis-simple-sidebars-save-entry', 'genesis-simple-sidebars-save-entry-nonce', $post );

	}

}
