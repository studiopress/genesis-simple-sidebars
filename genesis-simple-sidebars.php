<?php
/**
 * Genesis Simple Sidebars constants.
 *
 * @package genesis-simple-sidebars
 */

/**
 * Load the plugin file.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'GENESIS_SIMPLE_SIDEBARS_SETTINGS_FIELD', 'genesis_simple_sidebars_settings' );
define( 'GENESIS_SIMPLE_SIDEBARS_VERSION', '2.2.3' );
define( 'GENESIS_SIMPLE_SIDEBARS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'GENESIS_SIMPLE_SIDEBARS_PLUGIN_URL', plugins_url( '', __FILE__ ) );

require_once plugin_dir_path( __FILE__ ) . 'includes/class-genesis-simple-sidebars.php';



/**
 * Helper function to retrieve the static object without using globals.
 *
 * @since 2.1.0
 */
function genesis_simple_sidebars() {

	static $object;

	if ( null === $object ) {
		$object = new Genesis_Simple_Sidebars();
	}

	return $object;
}
/**
 * Initialize the object on `plugins_loaded`.
 */
add_action( 'plugins_loaded', array( Genesis_Simple_Sidebars(), 'init' ) );
