<?php
/**
 * Plugin Name:         ph_PLUGIN-TITLE
 * Plugin URI:          ph_PLUGIN-URI
 * Description:         ph_PLUGIN-DESCRIPTION.
 * Author:              Marco Di Bella
 * Author URI:          https://www.marcodibella.de
 * License:             MIT License
 * Requires at least:   ph_WORDPRESS-MINIMUM-VERSION
 * Tested up to:        ph_WORDPRESS-VERSION
 * Requires PHP:        ph_PHP-VERSION
 * Version:             0.0.1
 * Text Domain:         ph_PLUGIN-TEXTDOMAIN
 * Domain Path:         /languages
 *
 * @author  Marco Di Bella
 * @package ph_PLUGIN-PACKAGE
 */

namespace ph_PLUGIN_NAMESPACE;


/** Prevent direct access */

defined( 'ABSPATH' ) or exit;



/** Variables and definitions */

define( __NAMESPACE__ . '\PLUGIN_VERSION', '0.0.1' );
define( __NAMESPACE__ . '\PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( __NAMESPACE__ . '\PLUGIN_URL', plugin_dir_url( __FILE__ ) );



/** Include files */

require_once PLUGIN_DIR . 'includes/setup.php';
require_once PLUGIN_DIR . 'includes/backend.php';
