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
		add_action( 'get_header', array( $this, 'swap_sidebars' ) );

	}

	/**
	 * Take created sidebars and register them with WordPress.
	 *
	 * @since 2.1.0
	 */
	public function register_sidebars() {

		$sidebars = Genesis_Simple_Sidebars()->core->get_sidebars();

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
	 * Remove default sidebars and inject custom sidebars.
	 *
	 * @since 2.1.0
	 */
	public function swap_sidebars() {

		remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
		remove_action( 'genesis_sidebar_alt', 'genesis_do_sidebar_alt' );
		add_action( 'genesis_sidebar', array( $this, 'do_primary_sidebar' ) );
		add_action( 'genesis_sidebar_alt', array( $this, 'do_secondary_sidebar' ) );

	}

	/**
	 * Output custom primary sidebar, if one is set. Otherwise output default.
	 *
	 * @since 2.1.0
	 */
	public function do_primary_sidebar() {

		if ( ! $this->do_sidebar( '_ss_sidebar' ) ) {
			genesis_do_sidebar();
		}

	}

	/**
	 * Output custom secondary sidebar, if one is set. Otherwise output default.
	 *
	 * @since 2.1.0
	 */
	public function do_secondary_sidebar() {

		if ( ! $this->do_sidebar( '_ss_sidebar_alt' ) ) {
			genesis_do_sidebar_alt();
		}

	}

	/**
	 * Show widgets in a particular sidebar.
	 *
	 * @param string $key sidebar id you wish to output.
	 *
	 * @since 2.1.0
	 */
	public function do_sidebar( $key ) {

		if ( is_singular() && $key = genesis_get_custom_field( $key ) ) {

			if ( dynamic_sidebar( $key ) ) {
				return true;
			}

		}

		if ( is_tax() || is_category() || is_tag() ) {

			if ( $key = get_term_meta( get_queried_object()->term_id, $key, true ) ) {
				dynamic_sidebar( $key );
				return true;
			}

		}

		return false;

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
