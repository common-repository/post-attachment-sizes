<?php

/**
* The plugin bootstrap file
*
* This file is read by WordPress to generate the plugin information in the plugin
* admin area. This file also includes all of the dependencies used by the plugin,
* registers the activation and deactivation functions, and defines a function
* that starts the plugin.
*
* @link              	  http://tylerb.me
* @since             	 1.0.0
* @package            pas
*
* @wordpress-plugin
* Plugin Name:        Post Attachment Sizes
* Plugin URI:        	http://tylerb.me/plugins/post-attachment-sizes.zip
* Description:       	Allows the organization/filtering of posts by total size of all attachments
* Version:           	 1.0.0
* Author:            	 Tyler Bailey
* Author URI:          http://tylerb.me
* License:           	 GPL-2.0+
* License URI:         http://www.gnu.org/licenses/gpl-2.0.txt
* Text Domain:       pas
*/



// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die("Sneaky sneaky...");
}

// Define constants
define('PAS_VERSION', '1.0.0');
define('PAS_SLUG', 'pas');
define('PAS_GLOBAL_DIR', plugin_dir_path( __FILE__ ));
define('PAS_GLOBAL_URL', plugin_dir_url( __FILE__ ));
define('PAS_REQUIRED_PHP_VERSION', '5.3');
define('PAS_REQUIRED_WP_VERSION',  '3.1');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-pas-activator.php
 */
function activate_pas() {
	require_once PAS_GLOBAL_DIR . 'inc/class-pas-activator.php';
	PAS_Activator::activate();
}
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-pas-deactivator.php
 */
function deactivate_pas() {
	require_once PAS_GLOBAL_DIR . 'inc/class-pas-deactivator.php';
	PAS_Deactivator::deactivate();
}
register_activation_hook( __FILE__, 'activate_pas' );
register_deactivation_hook( __FILE__, 'deactivate_pas' );


/**
 * The core plugin classes that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require PAS_GLOBAL_DIR .  'inc/class-pas.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
if(!function_exists('pas_init')) {
	function pas_init() {
		new PAS();
	}
}
add_action('init', 'pas_init');
