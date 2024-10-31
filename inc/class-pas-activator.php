<?php
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @author 	Tyler Bailey
 * @version 1.0.0
 * @package post-attachment-sizes
 * @subpackage post-attachment-sizes/inc
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!class_exists('PAS_Activator')) :

	class PAS_Activator {

		/**
		 * Fired upon plugin activation
		 *
		 * Checks system requirements
		 *
		 * @since    1.0.0
		 */
		public static function activate() {
			self::pas_system_requirements_met();
		}

		/**
		 * Checks if the system requirements are met
		 *
		 * @since	1.0.0
		 * @return 	bool True if system requirements are met, die() message if not
		 */
		private static function pas_system_requirements_met() {
			global $wp_version;

			if ( version_compare( PHP_VERSION, PAS_REQUIRED_PHP_VERSION, '<' ) ) {
				wp_die(__("PHP 5.3 is required to run this plugin.", PAS_SLUG), __('Incompatible PHP Version', PAS_SLUG));
			}
			if ( version_compare( $wp_version, PAS_REQUIRED_WP_VERSION, '<' ) ) {
				wp_die(__("You must be running at least WordPress 3.5 for this plugin to function properly.", PAS_SLUG), __('Incompatible WordPress Version.', PAS_SLUG));
			}

			return true;
		}
	}

endif;
