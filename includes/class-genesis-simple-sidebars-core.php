<?php

/**
 * Controls the core functions of registration and output of Sidebars
 *
 * @since 2.1.0
 */
class Genesis_Simple_Sidebars_Core {

	/**
	 * The created sidebars.
	 */
	private $sidebars;

	/**
	 * Public taxonomies.
	 */
	private $public_taxonomies;

	/**
	 * Initialize the class.
	 */
	public function init() {

		add_action( 'widgets_init', array( $this, 'register_sidebars' ) );
		add_filter( 'sidebars_widgets', array( $this, 'sidebars_widgets_filter' ) );

	}

	/**
	 * Take created sidebars and register them with WordPress.
	 *
	 * @since 2.1.0
	 */
	public function register_sidebars() {

		$sidebars = $this->get_sidebars();

		if ( ! $sidebars ) {
			return;
		}

		// Cycle through created sidebars, register them as widget areas
		foreach ( (array) $sidebars as $id => $info ) {

			if ( ! isset( $info['name'] ) || ! isset( $info['description'] ) ) {
				continue;
			}

			genesis_register_sidebar( array(
				'name'        => esc_html( $info['name'] ),
				'id'          => $id,
				'description' => esc_html( $info['description'] ),
				'editable'    => 1,
			) );

		}

	}

	/**
	 * Filter the widgets in each widget area.
	 *
	 * @since 2.1.0
	 */
	public function sidebars_widgets_filter( $widgets ) {

		$sidebars = array(
			'sidebar'      => '_ss_sidebar',
			'sidebar-alt'  => '_ss_sidebar_alt',
			'header-right' => '_ss_header',
		);

		/**
		 * Swappable widget areas.
		 *
		 * An array of original widget area => GSS key for new sidebar. Can be used to add or remove widget areas from being
		 * swappable in the Genesis Simple Sidebars admin.
		 *
		 * @since 2.1.0
		 *
		 * @param array $sidebars Array of widget areas that can be swapped, with the keys used to find the ID of the new widget area to swap in.
		 */
		$sidebars = apply_filters( 'genesis_simple_sidebars_widget_areas', $sidebars );

		$widgets = $this->swap_widgets( $widgets, $sidebars );

		return $widgets;

	}

	/**
	 * Take the $widgets array and swap the contents of each widget area with a custom widget area, if specified.
	 *
	 * @since 2.1.0
	 */
	public function swap_widgets( $widgets, $sidebars ) {

		if ( is_admin() ) {
			return $widgets;
		}

		foreach ( (array) $sidebars as $old_sidebar => $new_sidebar_key ) {

			if ( ! is_registered_sidebar( $old_sidebar ) ) {
				continue;
			}

			if ( is_singular() ) {

				$new_sidebar = genesis_get_custom_field( $new_sidebar_key );

				if ( $new_sidebar && ! empty( $widgets[ $new_sidebar ] ) ) {
					$widgets[ $old_sidebar ] = $widgets[ $new_sidebar ];
				}

			}

			if ( is_tax() || is_category() || is_tag() ) {

				$new_sidebar = get_term_meta( get_queried_object()->term_id, $new_sidebar_key, true );

				if ( $new_sidebar && ! empty( $widgets[ $new_sidebar ] ) ) {
					$widgets[ $old_sidebar ] = $widgets[ $new_sidebar ];
				}

			}

		}

		return $widgets;

	}

	/**
	 * Get all custom registered sidebars.
	 *
	 * @since 2.1.0
	 */
	public function get_sidebars( $cache = true ) {

		if ( ! $cache ) {
			return stripslashes_deep( get_option( Genesis_Simple_Sidebars()->settings_field ) );
		}

		if ( is_null( $this->sidebars ) ) {
			$this->sidebars = stripslashes_deep( get_option( Genesis_Simple_Sidebars()->settings_field ) );
		}

		return $this->sidebars;

	}

	/**
	 * Return taxonomy ids.
	 *
	 * Helper function to return the array keys from a taxonomy query.
	 *
	 * @since 2.1.0
	 */
	public function get_public_taxonomies() {

		if ( is_null( $this->public_taxonomies ) ) {
			$this->public_taxonomies = get_taxonomies( array(
				'show_ui' => true,
				'public'  => true,
			) );
		}

		$this->public_taxonomies = apply_filters( 'genesis_simple_sidebars_taxonomies', array_keys( $this->public_taxonomies ) );

		return $this->public_taxonomies;

	}

	/**
	 * Determines if this site has disabled 3 column layouts or not.
	 *
	 * @since 2.1.0
	 */
	public function has_3_column_layout() {

		$layouts = genesis_get_layouts();

		$three_column_layouts = array(
			'content-sidebar-sidebar',
			'sidebar-content-sidebar',
			'sidebar-sidebar-content',
		);

		foreach ( $three_column_layouts as $layout ) {
			if ( array_key_exists( $layout, $layouts ) ) {
				return true;
			}
		}

		return false;

	}

}
