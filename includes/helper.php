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



function text_log( $text ) {
    $fp = PLUGIN_DIR . 'safelist.txt';
    $fh = fopen( $fp, 'w' );

    fwrite( $fh, $text );
    fflush( $fh );
    fclose( $fh );
}
