<?php
/**
 * Safelist file related functions of the plugin.
 *
 * @author  Marco Di Bella
 * @package tailwind-safelist-generator-plugin
 */

namespace tw_safelist_generator;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;




/**
 * ???
 *
 * @since 0.0.1
 */

function write_safelist() {
    global $wpdb;

    $table_name = $wpdb->prefix . TABLE_CLASSES;
    $results    = $wpdb->get_results( "SELECT css_classes FROM " . $table_name, ARRAY_A );

    if ( $results !== NULL ) {
        if ( ! empty( $results ) ) {
            // Collect all CSS classes
            $classes_string = '';

            foreach ($results as $result) {
                $classes_string .= ' ' . $result['css_classes'];
            }

            // Clean up collection
            $classes = array_unique( explode( ' ', $classes_string ) );
            asort( $classes );
            $classes_string = trim( implode( ' ', $classes ) );

            // Write safelist file
            // @todo: Get path from options
            $fp = PLUGIN_DIR . 'safelist.txt';
            $fh = fopen( $fp, 'w' );

            fwrite( $fh, $classes_string );
            fflush( $fh );
            fclose( $fh );
        }
    }
}
