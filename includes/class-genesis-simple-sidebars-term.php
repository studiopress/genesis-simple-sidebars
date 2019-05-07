<?php
/**
 * Genesis Simple Sidebars Term.
 *
 * @package genesis-simple-sidebars
 */

/**
 * Class Genesis Simple Sidebars Term.
 */
class Genesis_Simple_Sidebars_Term {

	/**
	 * Init function.
	 */
	public function init() {

		add_action( 'init', array( $this, 'add_term_fields' ), 12 );

	}

	/**
	 * Adds term fields.
	 */
	public function add_term_fields() {

		$taxonomies = Genesis_Simple_Sidebars()->core->get_public_taxonomies();

		if ( ! empty( $taxonomies ) && is_admin() && is_array( $taxonomies ) ) {

			foreach ( $taxonomies as $tax ) {
				add_action( "{$tax}_edit_form", array( $this, 'term_sidebar_form' ), 9, 2 );
			}
		}

	}

	/**
	 * Import the sidebar form.
	 *
	 * @param  string $tag      Tag.
	 * @param  string $taxonomy Taxonomy.
	 */
	public function term_sidebar_form( $tag, $taxonomy ) {

		require_once GENESIS_SIMPLE_SIDEBARS_PLUGIN_DIR . '/includes/views/term-edit-sidebar-form.php';

	}

}
