<?php
/*
Plugin Name: Genesis Simple Sidebars
Plugin URI: http://www.studiopress.com/plugins/simple-sidebars
Description: Genesis Simple Sidebars allows you to easily create and use new sidebar widget areas.
Version: 0.9.1
Author: Nathan Rice
Author URI: http://www.nathanrice.net/
*/

// require Genesis 1.2 upon activation
register_activation_hook(__FILE__, 'ss_activation_check');
function ss_activation_check() {

		$latest = '1.2';
		
		$theme_info = get_theme_data(TEMPLATEPATH.'/style.css');
	
        if( basename(TEMPLATEPATH) != 'genesis' ) {
	        deactivate_plugins(plugin_basename(__FILE__)); // Deactivate ourself
			wp_die( sprintf( __('Sorry, you can\'t activate unless you have installed <a href="%s">Genesis</a>', 'ss'), 'http://www.studiopress.com/themes/genesis' ) );
		}

		if( version_compare( $theme_info['Version'], $latest, '<' ) ) {
                deactivate_plugins(plugin_basename(__FILE__)); // Deactivate ourself
				wp_die( sprintf( __('Sorry, you cannot activate without <a href="%s">Genesis %s</a> or greater', 'ss'), 'http://www.studiopress.com/support/showthread.php?t=19576', $latest ) );
        }

}

// Define our constants
define('SS_SETTINGS_FIELD', 'ss-settings');
define('SS_PLUGIN_DIR', dirname(__FILE__));

// Include files
require_once(SS_PLUGIN_DIR . '/admin.php');
require_once(SS_PLUGIN_DIR . '/functions.php');
require_once(SS_PLUGIN_DIR . '/inpost.php');
require_once(SS_PLUGIN_DIR . '/term.php');

/**
 * This function registers the created sidebars
 */
add_action('widgets_init', 'ss_register_sidebars');
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
add_action('get_header', 'ss_sidebars_init');
function ss_sidebars_init() {
	remove_action('genesis_sidebar', 'genesis_do_sidebar');
	remove_action('genesis_sidebar_alt', 'genesis_do_sidebar_alt');
	add_action('genesis_sidebar', 'ss_do_sidebar');
	add_action('genesis_sidebar_alt', 'ss_do_sidebar_alt');
}

function ss_do_sidebar() {
	
	if ( is_singular() && $_sidebar = genesis_get_custom_field('_ss_sidebar') ) {
		if ( dynamic_sidebar($_sidebar) ) return;
	}
	
	if ( is_category() ) {
		$term = get_term( get_query_var('cat'), 'category' );
		if( isset( $term->meta['_ss_sidebar'] ) && dynamic_sidebar( $term->meta['_ss_sidebar'] ) ) return;
	}
	
	if ( is_tag() ) {
		$term = get_term( get_query_var('tag_id'), 'post_tag' );
		if( isset( $term->meta['_ss_sidebar'] ) && dynamic_sidebar( $term->meta['_ss_sidebar'] ) ) return;
	}
	
	genesis_do_sidebar();
	
}
function ss_do_sidebar_alt() {
	
	if ( is_singular() && $_sidebar_alt = genesis_get_custom_field('_ss_sidebar_alt') ) {
		if ( dynamic_sidebar($_sidebar_alt) ) return;
	}
	
	if ( is_category() ) {
		$term = get_term( get_query_var('cat'), 'category' );
		if( isset( $term->meta['_ss_sidebar_alt'] ) && dynamic_sidebar( $term->meta['_ss_sidebar_alt'] ) ) return;
	}
	
	if ( is_tag() ) {
		$term = get_term( get_query_var('tag_id'), 'post_tag' );
		if( isset( $term->meta['_ss_sidebar_alt'] ) && dynamic_sidebar( $term->meta['_ss_sidebar_alt'] ) ) return;
	}
	
	genesis_do_sidebar_alt();
	
}