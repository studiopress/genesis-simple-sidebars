<?php

class Genesis_Simple_Sidebars {

	/**
	 * Plugin version
	 */
	public $plugin_version = '2.1.0';

	/**
	 * Minimum WordPress version.
	 */
	public $min_wp_version = '4.7.2';

	/**
	 * Minimum Genesis version.
	 */
	public $min_genesis_version = '2.4.2';

	/**
	 * The plugin textdomain, for translations.
	 */
	public $plugin_textdomain = 'genesis-simple-sidebars';

	/**
	 * The url to the plugin directory.
	 */
	public $plugin_dir_url;

	/**
	 * The path to the plugin directory.
	 */
	public $plugin_dir_path;

	/**
	 * The main settings field for this plugin.
	 */
	public $settings_field = 'ss-settings';

	/**
	 * Core functions of sidebar registration and output.
	 */
	public $core;

	/**
	 * Admin menu and settings page.
	 */
	public $admin;

	/**
	 * Entry settings and metadata.
	 */
	public $entry;

	/**
	 * Constructor.
	 *
	 * @since 2.1.0
	 */
	public function __construct() {

		$this->plugin_dir_url  = plugin_dir_url( __FILE__ );
		$this->plugin_dir_path = plugin_dir_path( __FILE__ );

		// For backward compatibility
		define( 'SS_PLUGIN_DIR', $this->plugin_dir_path );

	}

	/**
	 * Initialize.
	 *
	 * @since 2.1.0
	 */
	public function init() {

		$this->load_plugin_textdomain();

		add_action( 'admin_notices', array( $this, 'requirements_notice' ) );

		$this->includes();
		$this->instantiate();

	}

	/**
	 * Show admin notice if minimum requirements aren't met.
	 *
	 * @since 2.1.0
	 */
	public function requirements_notice() {

		if ( ! defined( 'PARENT_THEME_VERSION' ) || ! version_compare( PARENT_THEME_VERSION, $this->min_genesis_version, '>=' ) ) {

			$action = defined( 'PARENT_THEME_VERSION' ) ? __( 'upgrade to', 'genesis-simple-sidebars' ) : __( 'install and activate', 'genesis-simple-sidebars' );

			$message = sprintf( __( 'Genesis Simple Sidebars requires WordPress %s and Genesis %s, or greater. Please %s the latest version of <a href="%s" target="_blank">Genesis</a> to use this plugin.', 'genesis-simple-sidebars' ), $this->min_wp_version, $this->min_genesis_version, $action, 'http://my.studiopress.com/?download_id=91046d629e74d525b3f2978e404e7ffa' );
			echo '<div class="notice notice-warning"><p>' . $message . '</p></div>';

		}

	}

	/**
	 * Load the plugin textdomain, for translation.
	 *
	 * @since 2.1.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( $this->plugin_textdomain, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * All general includes.
	 *
	 * @since 2.1.0
	 */
	public function includes() {

		require_once( $this->plugin_dir_path . 'includes/functions.php' );
		require_once( $this->plugin_dir_path . 'includes/deprecated.php' );

	}

	/**
	 * Include the class file, instantiate the classes, create objects.
	 *
	 * @since 2.1.0
	 */
	public function instantiate() {

		add_action( 'genesis_setup', array( $this, 'genesis_dependencies' ) );

	}

	/**
	 * Load and instantiate any Genesis dependencies.
	 *
	 * @since 2.1.0
	 */
	public function genesis_dependencies() {

		require_once( $this->plugin_dir_path . 'includes/class-genesis-simple-sidebars-core.php' );
		$this->core = new Genesis_Simple_Sidebars_Core;
		$this->core->init();

		// Anything beyond this point should only be loaded if in the admin.
		if ( ! is_admin() ) {
			return;
		}

		require_once( $this->plugin_dir_path . 'includes/class-genesis-simple-sidebars-entry.php' );
		$this->entry = new Genesis_Simple_Sidebars_Entry;
		$this->entry->init();

		require_once( $this->plugin_dir_path . 'includes/class-genesis-simple-sidebars-term.php' );
		$this->term = new Genesis_Simple_Sidebars_Term;
		$this->term->init();

		require_once( $this->plugin_dir_path . 'includes/class-genesis-simple-sidebars-admin.php' );
		$this->admin = new Genesis_Simple_Sidebars_Admin;
		$this->admin->admin_menu();

		// For backward compatibility
		global $_genesis_simple_sidebars;
		$_genesis_simple_sidebars = $this->admin;

	}

}

/**
 * Helper function to retrieve the static object without using globals.
 *
 * @since 2.1.0
 */
function Genesis_Simple_Sidebars() {

	static $object;

	if ( null == $object ) {
		$object = new Genesis_Simple_Sidebars;
	}

	return $object;
}
/**
 * Initialize the object on	`plugins_loaded`.
 */
add_action( 'plugins_loaded', array( Genesis_Simple_Sidebars(), 'init' ) );
