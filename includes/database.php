<?php
/**
 * Helper functions of the plugin.
 *
 * @author  Marco Di Bella
 * @package tailwind-safelist-generator-plugin
 */

namespace tw_safelist_generator;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;




/**
 * Creates or updates the database entry
 *
 * @since 0.0.1
 *
 * @param WP_POST $post           The post object
 * @param string  $classes_string The classes
 */

function save_to_database( $post, $classes_string ) {

    global $wpdb;

    $table_name  = $wpdb->prefix . TABLE_CLASSES;
    $table_entry = $wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE post_id LIKE " . $post->ID );

    if ( $table_entry !== NULL ) {

        $sql = '';

        if ( ! empty( $table_entry ) ) {

            // Bail out if there are no changes
            if ( $table_entry['css_classes'] == $classes_string ) {
                return false;
            }

            $sql = $wpdb->prepare(
                "UPDATE $table_name SET css_classes = %s, post_type = %s WHERE post_id = %s;",
                $classes_string,
                $post->post_type,
                $post->ID
            );

        } else {
            $sql = $wpdb->prepare(
                "INSERT INTO $table_name (post_id, post_type, css_classes) VALUES (%s, %s, %s);",
                $post->ID,
                $post->post_type,
                $classes_string
            );
        }

        // check for db error
        $wpdb->query( $sql );

        return true;
    }

    return false;
}
