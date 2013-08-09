<?php
/*
Plugin Name: Genesis Simple Sidebars
Plugin URI: http://www.studiopress.com/plugins/simple-sidebars
Description: Genesis Simple Sidebars allows you to easily create and use new sidebar widget areas.
Version: 0.9.2
Author: Nathan Rice
Author URI: http://www.nathanrice.net/
Text Domain: ss
Domain Path: /languages/
*/

// require Genesis 1.2 upon activation
register_activation_hook(__FILE__, 'ss_activation_check');
function ss_activation_check() {

	$latest = '1.2';
		
	$theme_info = get_theme_data(TEMPLATEPATH.'/style.css');
	
        if( basename(TEMPLATEPATH) != 'genesis' ) {
        	load_plugin_textdomain( 'ss', false, 'genesis-simple-sidebars/languages' );
	        deactivate_plugins(plugin_basename(__FILE__)); // Deactivate ourself
			wp_die( sprintf( __('Sorry, you can\'t activate unless you have installed <a href="%s">Genesis</a>', 'ss'), 'http://www.studiopress.com/themes/genesis' ) );
		}

		if( version_compare( $theme_info['Version'], $latest, '<' ) ) {
                deactivate_plugins(plugin_basename(__FILE__)); // Deactivate ourself
				wp_die( sprintf( __('Sorry, you cannot activate without <a href="%s">Genesis %s</a> or greater', 'ss'), 'http://www.studiopress.com/support/showthread.php?t=19576', $latest ) );
        }

}

/**
 * Hook into Genesis
 */
add_action( 'genesis_init', 'ss_genesis_init', 12 );
function ss_genesis_init() {
	// Define our constants
	define( 'SS_SETTINGS_FIELD', 'ss-settings' );
	define( 'SS_PLUGIN_DIR', dirname( __FILE__ ) );

	// required hooks
	add_action( 'get_header', 'ss_sidebars_init' );
	add_action( 'widgets_init', 'ss_register_sidebars' );
	if ( !is_admin() )
		return;

	// Include admin files
       	load_plugin_textdomain( 'ss', false, 'genesis-simple-sidebars/languages' );
	require_once( SS_PLUGIN_DIR . '/admin.php' );
	require_once( SS_PLUGIN_DIR . '/functions.php' );
	require_once( SS_PLUGIN_DIR . '/inpost.php' );
	require_once( SS_PLUGIN_DIR . '/term.php' );
	// let the child theme hook the genesis_simple_sidebars_taxonomies filter before hooking term edit
	add_action( 'init', 'ss_term_edit_init' );
}
/**
 * This function registers the created sidebars
 */
function ss_register_sidebars() {
	
	$_sidebars = stripslashes_deep( get_option( SS_SETTINGS_FIELD ) );
	if ( !$_sidebars ) return;
	
	foreach ( (array)$_sidebars as $id => $info ) {
		
		register_sidebar(array(
			'name' => esc_html( $info['name'] ),
			'id' => $id,
			'description' => esc_html( $info['description'] ),
			'editable' => 1,
			
			'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-wrap">',
			'after_widget'  => "</div></div>\n",
			'before_title'  => '<h4 class="widgettitle">',
			'after_title'   => "</h4>\n"
		));
		
	}
	
}


/**
 * Remove the default sidebars, run some conditional logic,
 * use alternate sidebars if necessary, else fallback on default sidebars.
 */
function ss_sidebars_init() {
	remove_action('genesis_sidebar', 'genesis_do_sidebar');
	remove_action('genesis_sidebar_alt', 'genesis_do_sidebar_alt');
	add_action('genesis_sidebar', 'ss_do_sidebar');
	add_action('genesis_sidebar_alt', 'ss_do_sidebar_alt');
}

function ss_do_sidebar() {

	if ( ! ss_do_one_sidebar( '_ss_sidebar' ) )
		genesis_do_sidebar();

}
function ss_do_sidebar_alt() {

	if ( ! ss_do_one_sidebar( '_ss_sidebar_alt' ) )
		genesis_do_sidebar_alt();

}

function ss_do_one_sidebar( $bar = '_ss_sidebar' ) {
	static $taxonomies = null;
	
	if ( is_singular() && $_bar = genesis_get_custom_field( $bar ) ) {
		if ( dynamic_sidebar( $_bar ) ) return true;
	}
	
	if ( is_category() ) {
		$term = get_term( get_query_var('cat'), 'category' );
		if( isset( $term->meta[$bar] ) && dynamic_sidebar( $term->meta[$bar] ) ) return true;
	}
	
	if ( is_tag() ) {
		$term = get_term( get_query_var('tag_id'), 'post_tag' );
		if( isset( $term->meta[$bar] ) && dynamic_sidebar( $term->meta[$bar] ) ) return true;
	}
	
	if ( is_tax() ) {
		if( $taxonomies === null )
			$taxonomies = (array)apply_filters( 'genesis_simple_sidebars_taxonomies', array() );
			
		foreach( $taxonomies as $tax ) {
			if( $tax == 'post_tag' || $tax == 'category' )
				continue;
				
			if( is_tax( $tax ) ) {
				$obj = get_queried_object();
				$term = get_term( $obj->term_id, $tax );
				if( isset( $term->meta[$bar] ) && dynamic_sidebar( $term->meta[$bar] ) ) return true;
				break;
			}
		}
	}
	
	return false;
	
}
