<?php


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
}
