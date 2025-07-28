<?php
/**
 * Demonstrates the functionality of the plugin.
 *
 * @author  Marco Di Bella
 * @package tailwind-safelist-generator-plugin
 */

namespace tw_safelist_generator;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;




/**
 * Returns an array with all CSS classes contained in an HTML code.
 *
 * @since 0.0.1
 *
 * @param string $html    The HTML code
 * @param array  $classes The list of found classes
 */

function scan_for_classes( $html, &$classes ) {

    $matches = [];

    preg_match_all( '/class\s*=\s*"([^"]+)"/i', $html, $matches );

    foreach ( $matches[1] as $class_string ) {
        foreach ( explode( ' ', $class_string ) as $class ) {
            $class = trim( $class );

            if ( ! empty( $class ) ) {

                if ( true == get_option( 'tw-sg-filter-wp-prefixed-classes' ) ) {
                    $check = strpos( $class, 'wp-' );

                    if ( ( false !== $check ) and ( 0 == $check ) ) {
                        break;
                    }
                }

                $classes[] = $class;
            }
        }
    }
}



/**
 * The safelist generator
 *
 * @since 0.0.1
 *
 * @return bool the outcome of the function
 */

function safelist_generator() {

    $post_types = get_option( 'tw-sg-scannable-post-types' );

    error_log(print_r($post_types, true));

    if ( 0 == count( $post_types ) ) {
        return false;
    }

    // Get posts
    $args = [
        'post_type'   => $post_types,
        'numberposts' => -1,
    ];

    $posts = get_posts( $args );


    // Scan for CSS classes
    $classes = [];

    foreach ( $posts as $mypost ) {
        scan_for_classes( $mypost->post_content, $classes );
    }

    // Removes double entries
    $classes = array_unique( $classes );

    // Sorts the array
    asort( $classes );

    text_log( implode( ' ', $classes ) );

    return true;
}
