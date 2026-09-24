<?php
/*
 * Plugin Name:         Safelist Generator for Tailwind CSS
 * Plugin URI:          https://github.com/mdibella-dev/tailwind-safelist-generator-plugin
 * Author:              Marco Di Bella
 * Author URI:          https://www.marcodibella.de
 * License:             MIT License
 * Requires at least:   6.6.0
 * Tested up to:        6.8.3
 * Requires PHP:        8
 * Version:             0.0.3
 * Text Domain:         tw-safelist-generator
 * Domain Path:         /languages
 */

namespace tw_safelist_generator;



/** Prevent direct access */
defined( 'ABSPATH' ) or exit;



/** Variables and definitions */
define( __NAMESPACE__ . '\PLUGIN_VERSION', '0.0.3' );
define( __NAMESPACE__ . '\PLUGIN_DIR', plugin_dir_path( __FILE__ ) );  // deprecated

define( __NAMESPACE__ . '\TABLE_CLASSES', 'tw_sg_classes' );



/** Include files */
require_once 'vendor/autoload.php';

require_once 'includes/database.php';
require_once 'includes/scanner.php';
require_once 'includes/safelist.php';



/** Add hooks */
register_activation_hook( __FILE__, __NAMESPACE__ . '\plugin_activation' );
register_uninstall_hook( __FILE__, __NAMESPACE__ . '\plugin_uninstall' );
add_action( 'init', __NAMESPACE__ . '\plugin_init', 9 );



/**
 * The init function for the plugin.
 *
 * @since   1.0.0
 *
 * @param   void
 *
 * @return  void
 */
function plugin_init() {
    load_plugin_textdomain( 'tw-safelist-generator', false, plugin_basename( __FILE__ ) . '/languages' );
}



/**
 * The activation function for the plugin.
 *
 * This function consists of three tasks:
 * - Creating a table in which all scanned css classes are stored.
 * - Adding or updating the necessary plugin options.
 * - Scanning all posts for the initial time.
 *
 * @since   1.0.0
 *
 * @param   void
 *
 * @return  void
 */
function plugin_activation() {

    if ( ! current_user_can( 'activate_plugins' ) ) {
        return;
    }

    /** Task 1: Creates tables */
    global $wpdb;

    $table_name    = $wpdb->prefix . TABLE_CLASSES;
    $table_collate = $wpdb->collate;

    if ( ! has_database_table() ) {
        $sql = "CREATE TABLE $table_name (
            post_id bigint(20) unsigned NOT NULL default '0',
            post_type varchar(20) NOT NULL default 'post',
            css_classes varchar(255) NOT NULL default '',
            PRIMARY KEY (post_id)
            )
            COLLATE $table_collate";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    /** Task 2: Add or update plugin options */
    $first_run  = empty( get_option( 'tw-sg-plugin-version' ) );

    if ( true === $first_run) {
        add_option( 'tw-sg-plugin-version' );
        add_option( 'tw-sg-filter-wp-prefixed-classes' );
        add_option( 'tw-sg-scannable-post-types' );
    }

    if ( empty( get_option( 'tw-sg-filter-wp-prefixed-classes' ) ) ) {
        update_option( 'tw-sg-plugin-version', true );
    }

    if ( empty( get_option( 'tw-sg-scannable-post-types' ) ) ) {
        update_option( 'tw-sg-plugin-version', ['post', 'page'] );
    }

    update_option( 'tw-sg-plugin-version', PLUGIN_VERSION );

    /** Task 3: First run */
    if ( true === $first_run ) {
        scan_all_posts_for_classes();
    }
}



/**
 * The uninstall function for the plugin.
 *
 * This function consists of two tasks:
 * - Removing the table in which all the scanned css classes are stored.
 * - Removing the plugin options from the WordPress database.
 *
 * @since   1.0.0
 *
 * @param   void
 *
 * @return  void
 */
function plugin_uninstall() {

    if ( ! current_user_can( 'delete_plugins' ) ) {
        return;
    }

    /** Task 1: Remove tables */
    global $wpdb;

    $table_name = $wpdb->prefix . TABLE_CLASSES;

    if ( has_database_table() ) {
        $sql = "DROP TABLE $table_name;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    /** Task 2: Remove options. */
    delete_option( 'tw-sg-scannable-post-types' );
    delete_option( 'tw-sg-filter-wp-prefixed-classes' );
    delete_option( 'tw-sg-plugin-version' );
}
