<?php
/**
 * Genesis Simple Sidebars Entry file.
 *
 * @package genesis-simple-sidebars
 */

/**
 * Genesis Simple Sidebars Entry class.
 */
class Genesis_Simple_Sidebars_Entry {

	/**
	 * Init function.
	 */
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

			if ( post_type_supports( $type, 'genesis-simple-sidebars' ) || 'post' === $type || 'page' === $type ) {
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

		require_once GENESIS_SIMPLE_SIDEBARS_PLUGIN_DIR . '/includes/views/entry-metabox-content.php';

	}

	/**
	 * Save the metabox fields when the entry is saved.
	 *
	 * @param string $post_id Post Id.
	 * @param array  $post Post.
	 * @since 2.1.0
	 */
	public function metabox_save( $post_id, $post ) {

		// phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification
		$genesis_simple_sidebars = isset( $_POST['genesis_simple_sidebars'] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST['genesis_simple_sidebars'] ) ) : '';

		if ( empty( $genesis_simple_sidebars ) ) {
			return;
		}

		$data = wp_parse_args(
			$genesis_simple_sidebars,
			array(
				'_ss_header'      => '',
				'_ss_sidebar'     => '',
				'_ss_sidebar_alt' => '',
			)
		);

		genesis_save_custom_fields( $data, 'genesis-simple-sidebars-save-entry', 'genesis-simple-sidebars-save-entry-nonce', $post );

	}

}
