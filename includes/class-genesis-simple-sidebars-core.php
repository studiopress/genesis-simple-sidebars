<?php
/**
 * Genesis Simple Sidebars Core.
 *
 * @package genesis-simple-sidebars
 */

/**
 * Controls the core functions of registration and output of Sidebars
 *
 * @since 2.1.0
 */
class Genesis_Simple_Sidebars_Core {

	/**
	 * The created sidebars.
	 *
	 * @var array
	 */
	private $sidebars;

	/**
	 * Public taxonomies.
	 *
	 * @var array
	 */
	private $public_taxonomies;

	/**
	 * Initialize the class.
	 */
	public function init() {

		add_action( 'after_setup_theme', array( $this, 'backward_compatibility' ) );
		add_action( 'widgets_init', array( $this, 'register_sidebars' ) );
		add_filter( 'sidebars_widgets', array( $this, 'sidebars_widgets_filter' ) );

	}

	/**
	 * For backward compatibility, we need to use old functions to hook in sidebar output.
	 *
	 * @since 2.1.0
	 */
	public function backward_compatibility() {

		remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
		add_action( 'genesis_sidebar', 'ss_do_sidebar' );
		remove_action( 'genesis_sidebar_alt', 'genesis_do_sidebar_alt' );
		add_action( 'genesis_sidebar_alt', 'ss_do_sidebar_alt' );

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

		// Cycle through created sidebars, register them as widget areas.
		foreach ( (array) $sidebars as $id => $info ) {

			if ( ! isset( $info['name'] ) || ! isset( $info['description'] ) ) {
				continue;
			}

			genesis_register_sidebar(
				array(
					'name'        => esc_html( $info['name'] ),
					'id'          => $id,
					'description' => esc_html( $info['description'] ),
					'editable'    => 1,
				)
			);

		}

	}

	/**
	 * Filter the widgets in each widget area.
	 *
	 * @param array $widgets Widgets.
	 *
	 * @since 2.1.0
	 */
	public function sidebars_widgets_filter( $widgets ) {

		$sidebars = array();

		if ( is_singular() || is_home() && isset( get_queried_object()->ID ) ) {

			$post_id = get_queried_object()->ID;

			$sidebars = array(
				'sidebar'      => genesis_get_custom_field( '_ss_sidebar', $post_id ),
				'sidebar-alt'  => genesis_get_custom_field( '_ss_sidebar_alt', $post_id ),
				'header-right' => genesis_get_custom_field( '_ss_header', $post_id ),
			);

		}

		if ( is_tax() || is_category() || is_tag() ) {

			$sidebars = array(
				'sidebar'      => get_term_meta( get_queried_object()->term_id, '_ss_sidebar', true ),
				'sidebar-alt'  => get_term_meta( get_queried_object()->term_id, '_ss_sidebar_alt', true ),
				'header-right' => get_term_meta( get_queried_object()->term_id, '_ss_header', true ),
			);

		}

		$widgets = $this->swap_widgets( $widgets, $sidebars );

		return $widgets;

	}

	/**
	 * Take the $widgets array and swap the contents of each widget area with a custom widget area, if specified.
	 *
	 * @param array $widgets Widgets.
	 * @param array $sidebars Sidebars.
	 *
	 * @since 2.1.0
	 */
	public function swap_widgets( $widgets, $sidebars ) {

		if ( is_admin() ) {
			return $widgets;
		}

		foreach ( (array) $sidebars as $old_sidebar => $new_sidebar ) {

			if ( ! is_registered_sidebar( $old_sidebar ) ) {
				continue;
			}

			if ( $new_sidebar && ! empty( $widgets[ $new_sidebar ] ) ) {
				$widgets[ $old_sidebar ] = $widgets[ $new_sidebar ];
			}
		}

		return $widgets;

	}

	/**
	 * Get all custom registered sidebars.
	 *
	 * @param bool $cache Indicates if should get from cache.
	 *
	 * @since 2.1.0
	 */
	public function get_sidebars( $cache = true ) {

		if ( $cache && ! is_null( $this->sidebars ) ) {
			return $this->sidebars;
		}

		$sidebars = stripslashes_deep( get_option( Genesis_Simple_Sidebars()->settings_field ) );

		/**
		 * Sidebars without a name are invalid. Filtering these prevents
		 * corrupted sidebar data from throwing errors, as seen in:
		 * https://wordpress.org/support/topic/php-8-2-error-6/#post-17472734
		 */
		$this->sidebars = array_filter(
			(array) $sidebars,
			function( $sidebar ) {
				return isset( $sidebar['name'] );
			}
		);

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
			$this->public_taxonomies = get_taxonomies(
				array(
					'show_ui' => true,
					'public'  => true,
				)
			);
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
