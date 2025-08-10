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
    load_plugin_textdomain( 'tw-safelist-generator', false, plugin_basename( PLUGIN_DIR ) . '/languages' );
}

add_action( 'init', __NAMESPACE__ . '\plugin_init' );



/**
 * The activation function for the plugin.
 *
 * This function consists of two tasks:
 * - Creating a table in which all scanned css classes are stored.
 * - Setting or updating the necessary plugin options.
 * - Scanning all posts for the initial time.
 *
 * @since 1.0.0
 */

function plugin_activation() {

    if ( ! current_user_can( 'activate_plugins' ) ) {
        return;
    }

    /** Task 1 */

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

    /** Task 2 */

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

    /** Task 3 */

    if ( true === $first_run ) {
        scan_all_posts_for_classes();
    }
}

register_activation_hook( __FILE__, __NAMESPACE__ . '\plugin_activation' );



/**
 * The uninstall function for the plugin.
 *
 * This function consists of two tasks:
 * - Removing the table in which all  the scanned css classes are stored.
 * - Removing the plugin options from the WordPress database.
 *
 * @since 1.0.0
 */

function plugin_uninstall() {

    if ( ! current_user_can( 'delete_plugins' ) ) {
        return;
    }

    /** Task 1 */

    global $wpdb;

    $table_name = $wpdb->prefix . TABLE_CLASSES;

    if ( has_database_table() ) {
        $sql = "DROP TABLE $table_name;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    /** Task 2 */

    delete_option( 'tw-sg-scannable-post-types' );
    delete_option( 'tw-sg-filter-wp-prefixed-classes' );
    delete_option( 'tw-sg-plugin-version' );
}

register_uninstall_hook( __FILE__, __NAMESPACE__ . '\plugin_uninstall' );
