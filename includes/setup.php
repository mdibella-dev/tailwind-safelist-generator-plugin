<?php
/**
 * Functions to activate, initiate and deactivate the plugin.
 *
 * @author  Marco Di Bella
 * @package tailwind-safelist-generator-plugin
 */

namespace tw_safelist_generator;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/**
 * The init function for the plugin.
 *
 * @since 1.0.0
 */

function plugin_init() {
    // Load text domain, use relative path to the plugin's language folder
    load_plugin_textdomain( 'tw-safelist-generator', false, plugin_basename( PLUGIN_DIR ) . '/languages' );
}

add_action( 'init', __NAMESPACE__ . '\plugin_init' );



/**
 * The activation function for the plugin.
 *
 * @since 1.0.0
 */

function plugin_activation() {

    $version = get_option( 'tw-sg-plugin-version' );

    if ( 0 == $version ) {
        add_option( 'tw-sg-plugin-version', PLUGIN_VERSION );
    } else {
        update_option( 'tw-sg-plugin-version', PLUGIN_VERSION );
    }

    add_option( 'tw-sg-scannable-post-types', [ 'post', 'page' ] );
    add_option( 'tw-sg-filter-wp-prefixed-classes', true );
}

register_activation_hook( __FILE__, __NAMESPACE__ . '\plugin_activation' );



/**
 * The deactivation function for the plugin.
 *
 * @since 1.0.0
 */

function plugin_deactivation() {
    // Do something!
}

register_deactivation_hook( __FILE__, __NAMESPACE__ . '\plugin_deactivation' );



/**
 * The uninstall function for the plugin.
 *
 * @since 1.0.0
 */

function plugin_uninstall() {
    delete_option( 'tw-sg-scannable-post-types' );
    delete_option( 'tw-sg-filter-wp-prefixed-classes' );
    delete_option( 'tw-sg-plugin-version' );
}

register_uninstall_hook( __FILE__, __NAMESPACE__ . '\plugin_uninstall' );
