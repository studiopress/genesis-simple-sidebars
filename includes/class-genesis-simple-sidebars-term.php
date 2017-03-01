<?php

class Genesis_Simple_Sidebars_Term {

	public function init() {

		add_action( 'init', array( $this, 'add_term_fields' ) );

	}

	public function add_term_fields() {

		$taxonomies = Genesis_Simple_Sidebars()->core->get_public_taxonomies();

		if ( ! empty( $taxonomies ) && is_admin() && is_array( $taxonomies ) ) {

			foreach ( $taxonomies as $tax ) {
				add_action( "{$tax}_edit_form", array( $this, 'term_sidebar_form' ), 9, 2 );
			}

		}

	}

	public function term_sidebar_form( $tag, $taxonomy ) {

		require_once( Genesis_Simple_Sidebars()->plugin_dir_path . 'includes/views/term-edit-sidebar-form.php' );

	}

}
