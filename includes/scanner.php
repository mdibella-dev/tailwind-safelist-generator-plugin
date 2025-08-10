<?php
/**
 * Class scanner rellated functions of the plugin.
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

    /**
     * Bail out
     * - if this hook was called due to an autosave
     * - if we don't have any scannable post types
     * - if this post isn't of one of the scannable post types
     */

	if ( defined( 'DOING_AUTOSAVE' ) and DOING_AUTOSAVE ) {
		return;
	}

	$post_types = get_option( 'tw-sg-scannable-post-types' );

    if ( is_array( $post_types ) and ( 0 == count( $post_types ) ) ) {
        return;
    }

    if ( ! in_array( $post->post_type, $post_types ) ) {
        return;
    }


   	/** Perform a scan for CSS classes */

    $classes = [];

    scan_for_classes( $post->post_content, $classes );

    $classes_string = implode( ' ', array_unique( $classes ) );


    // Do something with the classes
    if ( true == update_database_table( $post, $classes_string ) ) {
        write_safelist();
    }
}

add_action( "save_post", __NAMESPACE__ . '\scan_for_classes_action', 20, 3 );
