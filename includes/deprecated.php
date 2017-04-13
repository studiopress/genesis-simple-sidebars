<?php

/**
 * Deprecated.
 *
 * @since 0.9.0
 * @deprecated 2.1.0
 */
function ss_activation_check() {
	_deprecated_function( __FUNCTION__, '2.1.0' );
}

/**
 * Deprecated.
 *
 * @since 1.0.0
 * @deprecated 2.1.0
 */
function ss_deactivate() {
	_deprecated_function( __FUNCTION__, '2.1.0' );
}

/**
 * Deprecated.
 *
 * @since 0.9.0
 * @deprecated 2.1.0
 */
function ss_genesis_init() {
	_deprecated_function( __FUNCTION__, '2.1.0' );
}

/**
 * Deprecated.
 *
 * @since 0.9.0
 * @deprecated 2.1.0
 */
function ss_register_sidebars() {
	_deprecated_function( __FUNCTION__, '2.1.0' );
}

/**
 * Deprecated.
 *
 * @since 0.9.0
 * @deprecated 2.1.0
 */
function ss_sidebars_init() {
	_deprecated_function( __FUNCTION__, '2.1.0' );
}

/**
 * Deprecated.
 *
 * @since 0.9.0
 * @deprecated 2.1.0
 */
function ss_do_one_sidebar( $sidebar_key = '' ) {

	_deprecated_function( __FUNCTION__, '2.1.0', __( 'dynamic_sidebar() with sidebars_widget filter', 'genesis-simple-sidebars' ) );

	if ( '_ss_sidebar' == $sidebar_key ) {
		genesis_do_sidebar();
		return true;
	}

	if ( '_ss_sidebar_alt' == $sidebar_key ) {
		genesis_do_sidebar_alt();
		return true;
	}

}

/**
 * Deprecated.
 *
 * @since 0.9.0
 * @deprecated 2.1.0
 */
function ss_get_taxonomies() {
	_deprecated_function( __FUNCTION__, '2.1.0' );
}

/**
 * Deprecated.
 *
 * @since 0.9.2
 * @deprecated 2.1.0
 */
function ss_has_3_column_layouts() {
	_deprecated_function( __FUNCTION__, '2.1.0' );
}

/**
 * Deprecated.
 *
 * @since 1.0.0
 * @deprecated 2.1.0
 */
function simplesidebars_settings_menu() {
	_deprecated_function( __FUNCTION__, '2.1.0' );
}

/**
 * Deprecated.
 *
 * @since 0.9.0
 * @deprecated 2.1.0
 */
function ss_add_inpost_metabox() {
	_deprecated_function( __FUNCTION__, '2.1.0' );
}

/**
 * Deprecated.
 *
 * @since 0.9.0
 * @deprecated 2.1.0
 */
function ss_inpost_metabox() {
	_deprecated_function( __FUNCTION__, '2.1.0' );
}

/**
 * Deprecated.
 *
 * @since 0.9.0
 * @deprecated 2.1.0
 */
function ss_inpost_metabox_save() {
	_deprecated_function( __FUNCTION__, '2.1.0' );
}

/**
 * Deprecated.
 *
 * @since 0.9.0
 * @deprecated 2.1.0
 */
function ss_term_edit_init() {
	_deprecated_function( __FUNCTION__, '2.1.0' );
}

/**
 * Deprecated.
 *
 * @since 0.9.0
 * @deprecated 2.1.0
 */
function ss_term_sidebar() {
	_deprecated_function( __FUNCTION__, '2.1.0' );
}
