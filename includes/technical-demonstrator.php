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

                /** Filter for WordPress (wp-) prefixed classes */

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
 * Action hook to scan a post content for CSS classes.
 *
 * @since 0.0.1
 *
 * @param int     $post_id The post ID
 * @param WP_POST $post    The post object
 * @param bool    $update  Whether this is an existing post being updated
 */

function scan_for_classes_action( $post_id, $post, $update ) {

    // Bail out if this is an autosave
	if ( defined( 'DOING_AUTOSAVE' ) and DOING_AUTOSAVE ) {
		return;
	}

	// Bail out if we don't have any scannable post types
	$post_types = get_option( 'tw-sg-scannable-post-types' );

    if ( is_array( $post_types ) and ( 0 == count( $post_types ) ) ) {
        return;
    }

    // Bail out if this post isn't of one of the scannable post types
    if ( ! in_array( $post->post_type, $post_types ) ) {
        return;
    }


   	/** Perform a scan for CSS classes */

    $classes = [];

    scan_for_classes( $post->post_content, $classes );

    $classes = array_unique( $classes );

    asort( $classes );

    $classes_string = implode( ' ', $classes );


    // Do something with the classes
    save_to_database( $post, $classes_string );
}




/**
 * A basic safelist generator.
 *
 * @since 0.0.1
 *
 * @return bool the outcome of the function
 */

function safelist_generator() {

	$post_types = get_option( 'tw-sg-scannable-post-types' );

    if ( is_array( $post_types ) and ( 0 == count( $post_types ) ) ) {
        return false;
    }

    $posts = get_posts( [
        'post_type'   => $post_types,
        'numberposts' => -1,
    ] );


   	/** Perform a scan for CSS classes */

    $classes = [];

    foreach ( $posts as $mypost ) {
        scan_for_classes( $mypost->post_content, $classes );
    }

    $classes = array_unique( $classes );

    asort( $classes );

    $classes_string = implode( ' ', $classes );


    // Do something with the classes
    save_to_database( $post, $classes_string );

    return true;
}
