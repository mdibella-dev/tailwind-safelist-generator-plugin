<?php
/**
 * Plugin Name:         Safelist Generator for Tailwind CSS
 * Plugin URI:          https://github.com/mdibella-dev/tailwind-safelist-generator-plugin
 * Description:         ph_PLUGIN-DESCRIPTION.
 * Author:              Marco Di Bella
 * Author URI:          https://www.marcodibella.de
 * License:             MIT License
 * Requires at least:   6.6.0
 * Tested up to:        6.8.3
 * Requires PHP:        8
 * Version:             0.0.1
 * Text Domain:         tw-safelist-generator
 * Domain Path:         /languages
 *
 * @author  Marco Di Bella
 * @package tailwind-safelist-generator-plugin
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
