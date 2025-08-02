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
 * Creates, change or removes a database entry.
 *
 * @since 0.0.1
 *
 * @param WP_POST $post           The post object
 * @param string  $classes_string The classes
 */

function update_database( $post, $classes_string ) {

    global $wpdb;

    $table_name = $wpdb->prefix . TABLE_CLASSES;
    $results    = $wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE post_id LIKE " . $post->ID, ARRAY_A );

    if ( $results !== NULL ) {

        $sql = '';

        if ( ! empty( $results ) ) {

            if ( empty( trim( $classes_string ) ) ) {
                $sql = $wpdb->prepare(
                    "DELETE FROM $table_name WHERE post_id = %s;",
                    $post->ID
                );
            } else {
                $sql = $wpdb->prepare(
                    "UPDATE $table_name SET css_classes = %s, post_type = %s WHERE post_id = %s;",
                    $classes_string,
                    $post->post_type,
                    $post->ID
                );
            }
        } else {
            $sql = $wpdb->prepare(
                "INSERT INTO $table_name (post_id, post_type, css_classes) VALUES (%s, %s, %s);",
                $post->ID,
                $post->post_type,
                $classes_string
            );
        }

        // @todo check for db error
        $wpdb->query( $sql );

        return true;
    }

    return false;
}
