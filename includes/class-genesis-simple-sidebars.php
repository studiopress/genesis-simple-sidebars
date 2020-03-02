<?php
/**
 * Genesis Simple Sidebars main class.
 *
 * @package genesis-simple-sidebars
 */

/**
 * Genesis Simple Sidebars class.
 */
class Genesis_Simple_Sidebars {

	/**
	 * Minimum WordPress version.
	 *
	 * @var string
	 */
	public $min_wp_version = '4.7.2';

	/**
	 * Minimum Genesis version.
	 *
	 * @var string
	 */
	public $min_genesis_version = '2.4.2';

	/**
	 * The plugin textdomain, for translations.
	 *
	 * @var string
	 */
	public $plugin_textdomain = 'genesis-simple-sidebars';

	/**
	 * The main settings field for this plugin.
	 *
	 * @var string
	 */
	public $settings_field = 'ss-settings';

	/**
	 * Core functions of sidebar registration and output.
	 *
	 * @var Genesis_Simple_Sidebars_Core
	 */
	public $core;

	/**
	 * Admin menu and settings page.
	 *
	 * @var Genesis_Simple_Sidebars_Admin
	 */
	public $admin;

	/**
	 * Entry settings and metadata.
	 *
	 * @var Genesis_Simple_Sidebars_Entry
	 */
	public $entry;

	/**
	 * Constructor.
	 *
	 * @since 2.1.0
	 */
	public function __construct() {

		// For backward compatibility.
		define( 'SS_PLUGIN_DIR', GENESIS_SIMPLE_SIDEBARS_PLUGIN_DIR );

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
	            $allowed_HTML = array(
	                'a' => array(
	                'href' => array(),
	                'title' => array()
	                )
	            );
	            $action = defined( 'PARENT_THEME_VERSION' ) ? __( 'upgrade to', 'genesis-simple-sidebars' ) : __( 'install and activate', 'genesis-simple-sidebars' );
	            // translators: %1$s is WordPress minimum version, %2$s is Genesis minimum version, %3$s is action and %4$s is link.
	            $message = sprintf( __( 'Genesis Simple Sidebars requires WordPress %1$s and Genesis %2$s, or greater. Please %3$s the latest version of <a href="%4$s" target="_blank">Genesis</a> to use this plugin.', 'genesis-simple-sidebars' ), $this->min_wp_version, $this->min_genesis_version, $action, 'http://my.studiopress.com/?download_id=91046d629e74d525b3f2978e404e7ffa' );
	            echo '<div class="notice notice-warning"><p>' . wp_kses( $message, $allowed_HTML ) . '</p></div>';
	            
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

		require_once GENESIS_SIMPLE_SIDEBARS_PLUGIN_DIR . '/includes/functions.php';
		require_once GENESIS_SIMPLE_SIDEBARS_PLUGIN_DIR . '/includes/deprecated.php';

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

		require_once GENESIS_SIMPLE_SIDEBARS_PLUGIN_DIR . '/includes/class-genesis-simple-sidebars-core.php';
		$this->core = new Genesis_Simple_Sidebars_Core();
		$this->core->init();

		// Anything beyond this point should only be loaded if in the admin.
		if ( ! is_admin() ) {
			return;
		}

		require_once GENESIS_SIMPLE_SIDEBARS_PLUGIN_DIR . '/includes/class-genesis-simple-sidebars-entry.php';
		$this->entry = new Genesis_Simple_Sidebars_Entry();
		$this->entry->init();

		require_once GENESIS_SIMPLE_SIDEBARS_PLUGIN_DIR . '/includes/class-genesis-simple-sidebars-term.php';
		$this->term = new Genesis_Simple_Sidebars_Term();
		$this->term->init();

		require_once GENESIS_SIMPLE_SIDEBARS_PLUGIN_DIR . '/includes/class-genesis-simple-sidebars-admin.php';
		$this->admin = new Genesis_Simple_Sidebars_Admin();
		$this->admin->admin_menu();

		// For backward compatibility.
		global $_genesis_simple_sidebars;
		$_genesis_simple_sidebars = $this->admin;

	}

}
