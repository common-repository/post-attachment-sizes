<?php
/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to decativate the plugin
 *
 * @author 	Tyler Bailey
 * @version 1.0.0
 * @package post-attachment-sizes
 * @subpackage post-attachment-sizes/inc
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if(!class_exists('PAS_Deactivator')) :

	class PAS_Deactivator {

		/**
		 * Fired on plugin deactivation
		 *
		 * @since    1.0.0
		 */
		public static function deactivate() {
			// nothing here yet
		}
	}

endif;
